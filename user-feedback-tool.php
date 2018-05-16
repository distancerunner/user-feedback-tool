<?php
/**
* Plugin Name: User Feedback Tool	
* Plugin URI: http://megabyte-programmierung.de/
* Description: A small tool, to get feedback from the users directly to your wordpress backend.
* Version: 0.01
* Author: Gregor Schulze
* Author URI: http://megabyte-programmierung.de/
* License: GPLv3
*/
global $UserFeedbackClass;

define('DOC_ROOT_PATH_UFT', (dirname(__FILE__)).'/');
define('PLUGIN_URL_PATH_UFT', plugin_dir_url( __FILE__ ));

require_once(DOC_ROOT_PATH_UFT.'/class/user-feedback-tool-class.php');

$UserFeedbackClass = new userFeedback\UserFeedbackClass;

function plugin_css_styles_uft() {
    
	wp_enqueue_style( 'userfeedbackstyle_uft', plugins_url( '/css/style.css', __FILE__ ),array(), '0.0.1'  );
}
add_action( 'wp_enqueue_scripts', 'plugin_css_styles_uft' );
add_action('admin_enqueue_scripts', 'plugin_css_styles_uft');

function enable_scripts_uft() {
	
    wp_enqueue_script('user-feedback-tool', plugins_url( '/js/user-feedback-tool.js', __FILE__ ),array('jquery'),'0.7',true);

	
	//give this var to the JS code
	wp_localize_script( 'user-feedback-tool', 'locationvar_uft', array(
		'pluginurl' => PLUGIN_URL_PATH_UFT,
		'mainurl' => "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']
	));
}
add_action('wp_enqueue_scripts','enable_scripts_uft');
add_action('admin_enqueue_scripts', 'enable_scripts_uft');



function add_formular_footer_uft() {
	global $UserFeedbackClass;

	$UserFeedbackClass->setUserFeedbackForm(1);
}
add_action( 'wp_footer', 'add_formular_footer_uft' );

/*Return from a String the first Input. Inputs seperated by spaces will be shreddered*/
function clean_statement_uft($datainput) {
	return esc_html(strip_tags(preg_split('/ +/', $datainput)[0]));
}

/*Return a string without spaces*/
function remove_spaces_from_string_uft($datainput) {
	//echo $datainput.'+'.(preg_replace('/\s+/', '', $datainput)).'</br>';
	return (preg_replace('/\s+/', '', $datainput));
}

/*Remove everything whats not alpha-numeric in string */
function keep_alpha_nummeric_uft($datainput) {
	return preg_replace("/[^A-Za-z0-9 ]/", '', $datainput);
}


function compare_arrays_uft($array_old,$array_new){
	$return_array = [];
	$counter=1;

	foreach($array_old as $key => $element){
		
		/*
		htmlspecialchars_decode -> Manche Werte werden in der DB als HTML Char gespeichert. Wenn das der Fall ist,
		wird beim Abgleich zB & mit &amp; verglichen. Es kommt zu keiner Übereinstimmung. Mit htmlspecialchars_decode
		werden die Werte angeglichen und es kommt beim Vergleich zu keinen missmatches.
		*/
		$string1 = bin2hex(remove_spaces_from_string(htmlspecialchars_decode($element)));
		$string2 = bin2hex(remove_spaces_from_string(htmlspecialchars_decode($array_new[$counter])));
		
		$compare_number=strcasecmp($string1,$string2);
		if($compare_number!=0){
			$return_array[remove_spaces_from_string($key)] = array($string1,$string2);
		}
			//$return_array[$key] = array($element,$compare_number);
		$counter++;
	}
	
	return $return_array;
}

function smtp_wp_mail_uft($phpmailer) {
	$phpmailer->IsSMTP(); // telling the class to use SMTP
	//$phpmailer->Host       = "192.168.1.190";      // set the SMTP server host
	//$phpmailer->Username   = "manager@dsvp.de"; // set the SMTP account username
	
	$phpmailer->Host       = "smtp.gmail.com";      // set the SMTP server host
	$phpmailer->Port       = 465;                     // set the SMTP server port
	$phpmailer->SMTPSecure = "ssl";                   // enable SMTP via SSL
	$phpmailer->SMTPAuth   = true;                    // enable SMTP authentication
	$phpmailer->Password   = "BuT4ffRiowGn**";        // set the SMTP account password
	$phpmailer->Username   = "schulze.gregor@gmail.com"; // set the SMTP account username

	$phpmailer->From = "manager@dsvp.de";
	$phpmailer->FromName = "DSVP Manager";
}
add_action("phpmailer_init", "smtp_wp_mail_uft");



function remove_url_parameter_uft($url_in, $param_to_remove){
	$pos_of_needle=strpos($url_in,$param_to_remove);
	
	if($pos_of_needle==0)
		return $url_in;
	
	$pos_of_param_end=strpos($url_in,'&',$pos_of_needle);
	
	if($pos_of_param_end==0)
		$pos_of_param_end=strlen($url_in);
	
	$url_in_startpart = substr($url_in,0,$pos_of_needle-1);
	
	$url_in_endpart = substr($url_in,$pos_of_param_end);
	
	return $url_in_startpart.$url_in_endpart;
}

/*
	Creates a timestamp with no offset in Timezone Berlin.
*/
function get_time_internal_uft($bool=false){

	if($bool==false)
	$myTimestamp = date( 'd-m-Y H:i', current_time( 'timestamp', 0 ) );
	else
	$myTimestamp = date( 'Y-m-d H:i', current_time( 'timestamp', 0 ) );
    /*$a = "abc";
    $b = "def";
    $c = "ghi";

    return compact('a','b','c');*/
	return $myTimestamp;

}

/*
	Creates a timestamp with no offset in Timezone Berlin.
*/
function get_time_internalHis_uft($bool=false){

	if($bool==false)
	$myTimestamp = date( 'd-m-Y H:i:s', current_time( 'timestamp', 0 ) );
	else
	$myTimestamp = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );

    /*$a = "abc";
    $b = "def";
    $c = "ghi";

    return compact('a','b','c');*/
	return $myTimestamp;

}

function get_time_internal_filename_uft(){
	date_default_timezone_set('Europe/Berlin');
	$myTimestamp = date( 'd-m-Y_H-i-s', current_time( 'timestamp', 0 ) );
	$myTimestamp = date( 'd-m-Y_H-i-s');
    /*$a = "abc";
    $b = "def";
    $c = "ghi";

    return compact('a','b','c');*/
	return $myTimestamp;

}

function get_current_username_uft(){
			
	$current_user = wp_get_current_user();
	$username = $current_user->user_firstname. ' ' . $current_user->user_lastname;
	
	return $username;
}

/*
	Creates a timestamp with no offset in Timezone Berlin.
*/
function get_time_internal_Ymd_uft(){

	$myTimestamp = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
    /*$a = "abc";
    $b = "def";
    $c = "ghi";

    return compact('a','b','c');*/
	return $myTimestamp;

}

/*
	Creates a timestamp with no offset in Timezone Berlin.
*/
function get_time_internal_Ym_uft(){

	$myTimestamp = date( 'Y-m', current_time( 'timestamp', 0 ) );
    /*$a = "abc";
    $b = "def";
    $c = "ghi";

    return compact('a','b','c');*/
	return $myTimestamp;

}

/*
	Creates a timestamp with no offset in Timezone Berlin.
*/
function get_year_internal_uft($offset=null){

	$myTimestamp = date( 'Y', current_time( 'timestamp', 0 ) );
    /*$a = "abc";
    $b = "def";
    $c = "ghi";

    return compact('a','b','c');*/
	$myTimestamp=$myTimestamp+$offset;
	
	return $myTimestamp;

}

/*
	Creates a timestamp with no offset in Timezone Berlin.
*/
function get_month_internal_uft($offset=null){

	$myTimestamp = date( 'm', current_time( 'timestamp', 0 ) );
    /*$a = "abc";
    $b = "def";
    $c = "ghi";

    return compact('a','b','c');*/
	$myTimestamp=$myTimestamp+$offset;
	
	return $myTimestamp;

}

/*
	Edit Admin Page
*/
add_action('admin_menu', 'uft_plugin_setup_menu');
 
function uft_plugin_setup_menu(){
        add_menu_page( 'UFT Plugin Page', 'User Feedback', 'install_plugins', 'uft-plugin', 'uft_init' );
}

function uft_init(){
	global $UserFeedbackClass;
	global $pagenow, $plugin_page;
	$this_page = add_query_arg( 'page', $plugin_page, admin_url( $pagenow ) );


	//get parameter for List amount
	$showuftitems=(string)$UserFeedbackClass->getParameter('showuftitems');
	$itemID=$UserFeedbackClass->getParameter('id');

	switch ($showuftitems) {
		case 'edititem':
			//get List depending on parameter
			$uft_array = ($UserFeedbackClass->getUftContent($itemID));
			
			if($uft_array!=false){
				//echo '<form id="add-data-form" action="#edit_data" method="post" id="contact">';
				echo '<table id="" class="entry_in_table" cellspacing="" width="100%">';
				echo '<thead>';
				echo '<tr>';
					echo '<th width="100px"></th>';
					echo '<th></th>'; 
				echo '</tr>';
			echo '</thead>';
				echo '<tbody>';
				echo '<tr>';
					echo '<td><label for="datafield">Datum</label></td>';
					echo '<td><input type="text" name="field001" id="field001" class="" readonly value="'.$uft_array->field001.'"></td>';
					echo '<td><input type="text" hidden name="uid" id="uid" class="" readonly value="'.$uft_array->uid.'"></td>';
				echo '<tr>';
				echo '<tr>';
					echo '<td><label for="datafield">Nutzer</label></td>';
					echo '<td><input type="text" name="field002" id="field002" class="" readonly value="'.$uft_array->field002.'"></td>';
				echo '<tr>';
				echo '<tr>';
					echo '<td><label for="datafield">URL</label></td>';
					echo '<td><a href="'.$uft_array->field003.'" target=_blank>'.$uft_array->field003.'</a></td>';
				echo '<tr>';
				echo '<tr>';
					echo '<td><label>Kommentar vom Nutzer:</label></td>';
					echo '<td><textarea name="field004" id="field004" rows="4" readonly>'.$uft_array->field004.'</textarea></td>';
				echo '<tr>';
				echo '<tr>';
					echo '<td><label for="datafield">Art</label></td>';
					echo '<td>';
					echo '<select name="field005" id="field005" class="dropdown">';
					
					foreach($UserFeedbackClass->getKind_of_message() as $key => $element )
					{
					$selectit='';
					if ($uft_array->field005 == $key){$selectit = 'selected ';	}
						echo '<option '.$selectit.'value="'.$key.'">'.$element.'</option>';
					}
					
					echo '</select>';
					echo '</td>';
				echo '<tr>';
				echo '<tr>';
					echo '<td><label for="datafield">Prio</label></td>';
					echo '<td>';
					echo '<select name="field006" id="field006" class="dropdown">';
					
					foreach($UserFeedbackClass->getPrio_of_message() as $key => $element )
					{
					$selectit='';
					if ($uft_array->field006 == $key){$selectit = 'selected ';	}
						echo '<option '.$selectit.'value="'.$key.'">'.$element.'</option>';
					}
					
					echo '</select>';
					echo '</td>';
				echo '<tr>';
				echo '<tr>';
					echo '<td><label for="datafield">Status</label></td>';
					echo '<td>';
					echo '<select name="field007" id="field007" class="dropdown">';
					
					foreach($UserFeedbackClass->getStatus_of_message() as $key => $element )
					{
					$selectit='';
					if ($uft_array->field007 == $key){$selectit = 'selected ';	}
						echo '<option '.$selectit.'value="'.$key.'">'.$element.'</option>';
					}
					
					echo '</select>';
					echo '</td>';
				echo '<tr>';
				echo '<tr>';
					echo '<td><label>Kommentar vom Bearbeiter:</label></td>';
					echo '<td><textarea name="field008" id="field008" rows="4" >'.$uft_array->field008.'</textarea></td>';
				echo '<tr>';
				echo '<tr>';
					echo '<td><input type="submit" id="SubmitDataBtn_uft" name="add_data" value="Speichern" class="" /></td>';
					echo '<td><input type="submit" id="DeleteDataBtn_uft" name="delete_data" value="Löschen" class="" />';
					echo '<input type="submit" id="OkDeleteDataBtn_uft" name="delete_data" value="Eintrag wirklich löschen?" class="" /></td>';
				echo '<tr>';
				echo '<tr>';
					echo '<td colspan=2>
								<span class="data_safed_uft">Daten gespeichert</span>
								<span class="data_deleted_uft">Der Eintrag wurde gelöscht</span>
							</td>';
					
				echo '<tr>';
				echo '</tbody>';
    			//echo '</form>';
			}

			break;
		
		default:
	
			//get List depending on parameter
			$uft_array = array_reverse ($UserFeedbackClass->getUftContent());

			echo "<h1>User Feedback Tool</h1>";
			echo "<p>Manage your user feedbacks</p>";
		
			echo '<h3>Ereignis-Verlauf</h3></br>';
			
			echo '<table id="" class="" cellspacing="" width="">';
			echo '<thead>';
				echo '<tr>';
					echo '<th width="200px" style="text-align:left">Datum</th>';
					echo '<th width="100px" style="text-align:left">User</th>'; 
					echo '<th style="text-align:left" width="300px">Nachricht</th>'; 
					echo '<th width="500px" style="text-align:left">URL</th>'; 
					echo '<th width="100px">Art</th>'; 
					echo '<th width="100px">Prio</th>'; 
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
		
			foreach ( $uft_array as $row ) 
			{
				if($row->field010 != '1')
					$set_weight = 'style="font-weight:700"';
				else
					$set_weight = 'style="font-weight:100"';

				echo '<tr '.$set_weight.'>';								
					echo '<td ><a title="Ereignis bearbeiten" href="'.$this_page.'&showuftitems=edititem&id='.$row->uid.'">'.$row->field001.' </td>';
					echo '<td ><a title="Ereignis bearbeiten" href="'.$this_page.'&showuftitems=edititem&id='.$row->uid.'">'.$row->field002.' </td>';
					echo '<td ><a title="Ereignis bearbeiten" href="'.$this_page.'&showuftitems=edititem&id='.$row->uid.'">'.substr($row->field004,0,50).'... </td>';
					echo '<td ><a title="Ereignis bearbeiten" href="'.$this_page.'&showuftitems=edititem&id='.$row->uid.'">'.$row->field003.' </td>';
					echo '<td style="text-align:center"><a title="Ereignis bearbeiten" href="'.$this_page.'&showuftitems=edititem&id='.$row->uid.'">'.$row->field005.' </td>';
					echo '<td style="text-align:center"><a title="Ereignis bearbeiten" href="'.$this_page.'&showuftitems=edititem&id='.$row->uid.'">'.$row->field006.' </td>';
				echo '</tr>';
		
			}	
			echo '</tbody>';
			echo '</table>';
		break;
	}
	

}

