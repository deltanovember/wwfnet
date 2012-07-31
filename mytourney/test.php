<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'functions.php';
require_once 'master_inc.php';
        $open_monthly = get_open_monthly();
        if (NULL == get_open_monthly()) {
            print "here";
            print get_tournament(get_daily_id())->get_description();
        }
        else {
            print "here3";
            print $open_monthly->get_id();
            print "blah";
        }
//print get_user("bltate18")->get_id();
//var_dump(is_file('/usr/share/pear/Mail.php'));
 /**
ini_set('display_errors', 1);
error_reporting(E_ALL);
print "start0.1";
require_once 'email_smtp.php';
print "start2";
sleep(2);
if (smtp_mail('wordswithfriends12@yahoo.com', "welcome", "Dear Don ('polymath3'): <br><br>'myrna17' is your Round 30 opponent in the Battle for America [daily]! You can contact 'myrna17' and take other actions at your <a href='http://wordswithfriends.net/?page_id=386'>MyTourney page</a> (Password hint: '').<br /><br /><em>NOTE: This round will close approximately 24 hours from this email.</em><br><br>Please do not reply to this message. Thanks for playing!")) {
  print "success2";
}
else {
    print "fail";
}
*/

?>