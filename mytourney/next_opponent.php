<?php

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

header("Pragma: no-cache");
header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//Set permission level threshold for this page remove if page is good for all levels
$permission_level=1;
require_once "login_config.php";
require_once "functions.php";
if (!mysql_ping()) {
    require_once 'master_inc.php';
}


if (!is_logged_in()) {
    
?>

<table style="border:none; border-collapse:collapse;"><tr><td width="40%"><a href="http://wordswithfriends.net/?page_id=386" target="_self"><img border="0px" src="http://wordswithfriends.net/wp-content/themes/vibe/images/sign_up.jpg" /></a></td><td>
<!--span style="color: #0586ff;">Registered Players = <strong><script type="text/javascript">document.write(playerCount);</script></strong><br />Last Joined: <strong><script type="text/javascript">document.write(lastJoined);</script></strong></span--><br /><br / >Click to opt into the <span style='color:#0586ff;'><a href="http://wordswithfriends.net/?page_id=386" target="_self">
    <?php
        
        $open_monthly = get_open_monthly();
        if (NULL == get_open_monthly()) {
            print get_tournament(get_daily_id())->get_description();
        }
        else {
            print $open_monthly->get_description();
        }
    ?> </a></span>.
</td></tr></table>


<?php

}
else {
?>


<div id='wrap'>
    Welcome <span style='color:black; font-weight:bold;'><?php print get_username_from_cookie() ?></span><br />
    You are registered in <span style='color:black; font-weight:bold;'><?php print get_tournament(get_daily_id())->get_description() ?></span><br />
    
<?php 
$record = get_record(get_daily_id(), get_userid_from_cookie());
if ($record == NULL || $record->is_eliminated()) {
    print "You are not currently active";
}
else {

    if (strlen($record->get_opponent()) > 1) {
        $opponent = $record->get_opponent();
        print "Your next opponent is: <span style='color:green; font-weight:bold;'>".$opponent."</span>";
    }
    else {
        print "You are unmatched";
    }

    
}
?>
</div>

<?php
}
?>
