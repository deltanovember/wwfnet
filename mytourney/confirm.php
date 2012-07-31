<?php

require_once 'login_config.php';
require_once 'functions.php';
require_once 'master_inc.php';

//setup some variables
$action = array();
$action['result'] = null;

//quick/simple validation
/**
if(empty($_GET['email']) || empty($_GET['key'])){
	$action['result'] = 'error';
	$action['text'] = 'We are missing variables. Please double check your email.';
}*/

if($action['result'] != 'error'){

	//cleanup the variables
	$email = mysql_real_escape_string($_GET['email']);
	$key = mysql_real_escape_string($_GET['key']);


	//check if the key is in the database
	$check_key = mysql_query("SELECT * FROM `confirm` WHERE `email` = '$email' AND `key` = '$key' LIMIT 1") or die(mysql_error());

	if(mysql_num_rows($check_key) != 0){

		//get the confirm info
		$confirm_info = mysql_fetch_assoc($check_key);

		//confirm the email and update the users database
		$update_users = mysql_query("UPDATE `users` SET `verified` = 1 WHERE `username` = '$confirm_info[userid]' LIMIT 1") or die(mysql_error());
		//delete the confirm row
		$delete = mysql_query("DELETE FROM `confirm` WHERE `id` = '$confirm_info[id]' LIMIT 1") or die(mysql_error());

		if($update_users){
                        header('Refresh: 3; URL=http://wordswithfriends.net/?page_id=386');
                       // join_daily($confirm_info[userid]);
			print 'You have been confirmed. Thank you! You may now login <a href="http://wordswithfriends.net/?page_id=386">here</a> or please wait 3 seconds to be redirected';

		}else{

			$action['result'] = 'error';
			print 'You could not be confirmed. Reason: '.mysql_error(). 'Please notify admin@wordswithfriends.net with this error';

		}

	}else{

		$action['result'] = 'error';
		print 'The key and email is not in our database.';

	}

}

?>
