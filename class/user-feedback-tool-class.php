<?php

namespace userFeedback;

class UserFeedbackClass{
	
	public $uft_table;
	public $url_with_parameter;

	public function __construct(){
		global $wpdb;
		//include DOC_ROOT_PATH . "include/local_sys_vars.include.php";
		$this->uft_table = $wpdb->prefix . 'user_feedback_tool_table';

		$this->url_with_parameter = ('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	}
	
	public function getUftContent($id=null){
		global $wpdb;
		$uft_table=$this->uft_table;
		

		if(empty($id))
		$this->table_content = $wpdb->get_results( "SELECT * FROM $uft_table");

		if($id>0){
		$array_element = $wpdb->get_results( "SELECT * FROM $uft_table WHERE uid=$id");
		
		if(!empty($array_element))
			$this->table_content=$array_element[0];
		else
			$this->table_content=false;

			//set entry opened flag
			$user_feedback_array=(array)$this->table_content;
			$user_feedback_array['field010']='1';
			
			$write_result=$wpdb->replace( 
				$uft_table, 
				$user_feedback_array
			);
		}

      //echo '<pre>'; print_r($this->table_content); echo '</pre>';
                
		return $this->table_content;
		
	}


	public function getKind_of_message(){
		return array(
			"Feature" => "Feature",
			"Bug" => "Bug",
		);
	}
	
	public function getPrio_of_message(){
		return array(
			"0" => "Gering",
			"1" => "Niedrig",
			"2" => "Mittel",
			"3" => "Hoch",
		);
	}
	
	public function getStatus_of_message(){
		return array(
			"open" => "Offen",
			"work" => "In Arbeit",
			"closed" => "Erledigt",

		);
	}
	
	public function getParameter($parameter){
		
		if(isset($_GET[$parameter])) 
			$output = clean_statement($_GET[$parameter]);
		else
			$output=0;
		
		if($output=='')
			$output=0;
		
		return $output;
	}
	
	public function getPost($parameter){
		
		if(isset($_POST[$parameter]))
			$output=esc_html(strip_tags($_POST[$parameter], ""));
		else
			$output='';
			
		return $output;
	}
	
	public function setUserFeedbackForm($parameter){

		$current_user = wp_get_current_user();
		$user_login=esc_html( $current_user->user_login );
		
		if ( is_user_logged_in() ) {
		echo'<div class="user_feedback_button_uft">';
		echo '</div>' ;


		echo'<div class="help-form-uft">';
		//<!-- Required Div Starts Here -->
		echo'<form id="form" name="form">';
		echo'<div class="close_btn_uft">';
		echo 'X';
		echo '</div>' ;
		echo'<h3>Nutzerfeeback senden</h3>';
		echo'<div class="help-form-uft-container">';
		echo'<label>Nutzername:</label>';
		echo'<input id="name_uft" type="text" value="'.$user_login.'">';
		//echo'<label>URL:</label>';
		echo'<input hidden id="url_uft" type="text" value="'.$this->url_with_parameter.'">';
		echo'<label>Art der Nachricht:</label>';
		echo'<select name="kind_of_message" id="kind_of_message">';
		echo'	<option value="Feature">Verbesserung</option>';
		echo'	<option value="Bug">Fehler</option>';
		echo'</select> </br>';
		echo'<label>Priorität:</label></br>';
		echo'<select name="prio_of_message" id="prio_of_message">';
		echo'	<option value="0">Gering</option>';
		echo'	<option value="1">Niedrig</option>';
		echo'	<option value="2">Mittel</option>';
		echo'	<option value="3">Hoch</option>';
		echo'</select> </br>';
		echo'<label>Kommentar:</label>';
		echo '<textarea id="comment_uft" rows="4"></textarea>';
		echo'<input id="submit" onclick="" class="helperform_submit_uft" type="button" value="Absenden">';
		echo'</div>';
		echo'<div>';
			echo'<span class="success_span_uft">Erfolgreich gesendet.</span>';
		echo'</div>';

		echo'</form>';
		echo'</div>';
		}
	}


	public function setUserFeedbackDB(){
		global $charset_collate;
		global $wpdb;
		$uft_table=$this->uft_table;

		$sql = "CREATE TABLE IF NOT EXISTS $uft_table (
			`uid` mediumint(9) NOT NULL AUTO_INCREMENT,
			`field001` text NOT NULL,
			`field002` text NOT NULL,
			`field003` text NOT NULL,
			`field004` text NOT NULL,
			`field005` text NOT NULL,
			`field006` text NOT NULL,
			`field007` text NOT NULL,
			`field008` text NOT NULL,
			`field009` text NOT NULL,
			`field010` text NOT NULL,
			`field011` text NOT NULL,
			`field012` text NOT NULL,
			`field013` text NOT NULL,
			`field014` text NOT NULL,
			`field015` text NOT NULL,
			UNIQUE (`uid`)
		) $charset_collate;";

		dbDelta( $sql );
		echo 'Check MySQL Table: table_uploaded_files';
		echo '</br>';

		$user_case_lock_table_array = $wpdb->get_results(( "SHOW COLUMNS FROM $uft_table"));
		$user_case_lock_table_array=(array)$user_case_lock_table_array[0];
		if($user_case_lock_table_array['Field']=='uid'){
			echo 'Table exist';
		}
		echo '</br>';
		echo '</br>';
	}

	/*
		Safe Data to DB, create Version Infos and check for succesful DB writing
	*/
	public function writeUserFeedbackToDB($user_feedback_array){
		global $wpdb;
		$uft_table=$this->uft_table;

		//add a uid NULL to create a new row
		$user_feedback_array['uid']='';
		//set the datetime info
		$user_feedback_array['field001']=get_time_internalHis_uft();

		$write_result=$wpdb->replace( 
			$uft_table, 
			$user_feedback_array
		);

	}

	/*
		Safe Data to DB, from Adminpanel
	*/
	public function editUserFeedbackinDB($user_feedback_array){
		global $wpdb;
		$uft_table=$this->uft_table;
		
		$user_feedback_array_in=$this->getUftContent($user_feedback_array['uid']);
		// echo '<pre>'; print_r($user_feedback_array_in); echo '</pre>';
		
		//set the datetime info for last data Edit
		$user_feedback_array['field001']=$user_feedback_array_in->field001;
		$user_feedback_array['field002']=$user_feedback_array_in->field002;
		$user_feedback_array['field003']=$user_feedback_array_in->field003;
		$user_feedback_array['field004']=$user_feedback_array_in->field004;
		$user_feedback_array['field009']=get_time_internalHis_uft();

		$write_result=$wpdb->replace( 
			$uft_table, 
			$user_feedback_array
		);
	}

	/*
		Safe Data to DB, from Adminpanel
	*/
	public function deleteUserFeedbackinDB($user_feedback_array){
		global $wpdb;
		$uft_table=$this->uft_table;
		
		// echo '<pre>'; print_r($user_feedback_array); echo '</pre>';
		
		//delete the row of the entry
		$write_result=$wpdb->delete( 
			$uft_table, 
			$user_feedback_array
		);
	}

}