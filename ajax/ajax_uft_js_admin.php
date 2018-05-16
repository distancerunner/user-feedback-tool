<?php


/** Set up WordPress environment */
require_once( dirname(dirname(dirname(dirname(dirname( __FILE__ ))))) . '/wp-load.php' );
//C:\Users\KingKong\Nextcloud\Projekte\htdocs\dsvp\
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

global $wpdb;
$UserFeedbackClass = new userFeedback\UserFeedbackClass;

$array['uid'] = $UserFeedbackClass->getPost('uid');


if($UserFeedbackClass->getPost('delete')==0){
    // Fetching Values From URL
    $array['field005'] = $UserFeedbackClass->getPost('field005');
    $array['field006'] = $UserFeedbackClass->getPost('field006');
    $array['field007'] = $UserFeedbackClass->getPost('field007');
    $array['field008'] = $UserFeedbackClass->getPost('field008');
}


//print_r($array);

//die;

if ( !is_user_logged_in() || !current_user_can('administrator') ) die('Not logged in.');
//write information to DB
    if($UserFeedbackClass->getPost('delete')==0){
        // Fetching Values From URL
        $UserFeedbackClass->editUserFeedbackinDB($array);
    }
    
    // echo 'try'.$UserFeedbackClass->getPost('delete');
    if($UserFeedbackClass->getPost('delete')=='true'){
        // Fetching Values From URL
        // echo 'delte';
        $UserFeedbackClass->deleteUserFeedbackinDB($array);
    }
?>