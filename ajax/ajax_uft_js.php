<?php


/** Set up WordPress environment */
require_once( dirname(dirname(dirname(dirname(dirname( __FILE__ ))))) . '/wp-load.php' );
//C:\Users\KingKong\Nextcloud\Projekte\htdocs\dsvp\
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

global $wpdb;
$UserFeedbackClass = new userFeedback\UserFeedbackClass;

// Fetching Values From URL
$array['field002'] = $UserFeedbackClass->getPost('name');
$array['field003'] = $UserFeedbackClass->getPost('url');
$array['field004'] = $UserFeedbackClass->getPost('comment');
$array['field005'] = $UserFeedbackClass->getPost('kind_of_message');
$array['field006'] = $UserFeedbackClass->getPost('prio_of_message');
$array['field007'] = 'open';

//print_r($array);

//die;

if ( !is_user_logged_in() ) die('Not logged in.');
//write information to DB
    $UserFeedbackClass->writeUserFeedbackToDB($array);
?>