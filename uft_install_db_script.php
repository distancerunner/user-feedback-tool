<?php
/*
This script will initialize the Databases
*/

/** Set up WordPress environment */
require_once( dirname(dirname(dirname(dirname( __FILE__ )))) . '/wp-load.php' );

//C:\Users\KingKong\Nextcloud\Projekte\htdocs\dsvp\
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

if ( !is_user_logged_in() ) die('Not logged in.');

global $UserFeedbackClass;

echo 'Try to create MySQL all Tables';
echo '</br>';
// creates new_table in database if not exists
$UserFeedbackClass->setUserFeedbackDB();
