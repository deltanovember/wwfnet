<?php

require_once 'functions.php';

$rejection_message = "";

 function rel_time($from, $to = null) {
     $output = "";
     /**
  $to = (($to === null) ? (time()) : ($to));
  $to = ((is_int($to)) ? ($to) : (strtotime($to)));
  $from = ((is_int($from)) ? ($from) : (strtotime($from)));
*/
  $units = array
  (
   "year"   => 29030400, // seconds in a year   (12 months)
   "month"  => 2419200,  // seconds in a month  (4 weeks)
   "week"   => 604800,   // seconds in a week   (7 days)
   "day"    => 86400,    // seconds in a day    (24 hours)
   "hour"   => 3600,     // seconds in an hour  (60 minutes)
   "minute" => 60,       // seconds in a minute (60 seconds)
   "second" => 1         // 1 second
  );

  $diff = abs($from - $to);
  $suffix = (($from > $to) ? ("from now") : ("ago"));

  foreach($units as $unit => $mult)
   if($diff >= $mult)
   {
    $and = (($mult != 1) ? ("") : ("and "));
    $output .= ", ".$and.intval($diff / $mult)." ".$unit.((intval($diff / $mult) == 1) ? ("") : ("s"));
    $diff -= intval($diff / $mult) * $mult;
   }
  $output .= " ".$suffix;
  $output = substr($output, strlen(", "));

  return $output;
 }

 // send email
function assignPlayers($person0,$fname0,$versus0,$hint0,$email0,$onround,$t_desc){


    $from = "Admin <admin@wordswithfriends.net>";
    $to = $email0;
    $subject = "[WordsWithFriends.net] Your Round " . $onround . " Opponent";


    $mailbody = "Dear " . $fname0 . " (" . $person0 . "): <br><br>Round " . $onround . " of the " . $t_desc . " is underway! Your next tournament opponent is '" . $versus0 . ".' You can contact your opponent and take other actions at your MyTourney page. Log in at your <a href=\"http://wordswithfriends.net/?page_id=386\">MyTourney</a> page . Here's your password hint: '" . $hint0 . "'. Please do not reply to this message. <br><br>Thanks for playing!";

    $email_success = true;
    if (!$debugmode) {
        $email_success = email($to, $subject, $mailbody);
    }

    $msg_conf = "<p style='background-color:lightyellow; padding:5px;'><span style='font-weight:bold;'>Email message was sent to " . $person0 . ".</span></p>";

    if (!$email_success) {
        $msg_conf = "<p style='background-color:lightyellow; padding:5px; font-weight:bold; color:red;'>Email not sent. Please check to see if you have been assigned an opponent for this round. If not, then contact admin@wordswithfriends.net for assistance. Otherwise, you can ignore this message.</span>";
    }

    echo $msg_conf;
 
}
 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=1;

include"auth_check_header.php";

// GET PLAYER'S USER TABLE VALUES AND ASSIGN SESSION VARIABLES
$username_from_cookie = $_COOKIE[NAME_COOKIE]; //retrieve contents of cookie
$sql="SELECT * FROM users WHERE username='$username_from_cookie'";
			
$query = "SELECT * FROM users WHERE username='$username_from_cookie'"; 
$result = mysql_query($query) or die("Couldn't execute query 1");

while ($row= mysql_fetch_array($result)) {
    $id= $row["id"];
}

$tournament_id = $_REQUEST['tournament_id'];
$raw_action = $_REQUEST['action'];
$can_rejoin = true;

if ($id == NULL || $tournament_id == NULL || $raw_action == NULL) {
    die('A required GET variable is missing');
}

if ($raw_action == 'eliminate') {
    $new_status = 'Eliminated';
    $public_status = "Inactive";
}
else if ($raw_action == 'reinstate') {

    //$user = get_user_from_id($id);
    // get elapsed time
    $query = "SELECT daily_status, lastbreaktime FROM users WHERE id=$id";
    $result = mysql_query($query) or die("Couldn't execute query");
    while ($row = mysql_fetch_array($result)) {

        $timestamp = $row["lastbreaktime"];
        $num_matches = count_daily_matches($id, $tournament_id);
        if (time() - $timestamp < 24 * 60 * 60) {
            $can_rejoin = false;
            $rejection_message = "You are currently not able to rejoin. ".rel_time($timestamp, time() - 24 * 60 * 60)." before possible rejoin";
        }
        else if ($row['daily_status'] == 'Eliminated' ||
                $row['daily_status'] == 'Waiting' ||
                $num_matches >= 5) {
            if ($row['daily_status'] != 'Waiting') {
                $update_query = "UPDATE users set daily_status='Waiting' where id=$id";
                mysql_query($update_query) or die("Couldn't execute query");
            }
            $can_rejoin = false;
            $rejection_message = "Please wait for daily draw to commence";
        }
    }
    $new_status = 'Active';
    $public_status = "Active";

}
else {
    die('Action not recognized.');

}

// Get Info From Control Table

$query = "SELECT * FROM T_CONTROL WHERE record='$tournament_id'";

$result = mysql_query($query) or die("Couldn't execute query");
while ($row= mysql_fetch_array($result)) {
    $t_id= $row["t_id"];
    $t_start= $row["t_start"];
    $t_short_start= substr($t_start, 5, 10);
    $t_desc= $row["t_desc"];
    $t_round= $row["t_round"];
    $t_status= $row["t_status"];
    $t_champ= $row["t_champ"];

}

// make sure does not refresh
$refresh_query = "SELECT * FROM daily WHERE tournament_id='$tournament_id' and round=$t_round and user_id=$id";
$refresh_result = mysql_query($refresh_query) or die("Couldn't execute query");
if (mysql_num_rows($refresh_result) != 0 &&
        $can_rejoin) {
    $can_rejoin = false;
    $rejection_message = "You have already joined";
   // print $refresh_query;

}


?>


<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Manage My Tournament</title>
<style type="text/css">
#wrap {font-family:Arial, Helvetica, sans-serif; font-size:13px; width:500px;}
#mymenu {float:right;}
.td_tourney {background-color:lightblue; font-weight:bold; font-size:larger;}
</style>

</head>

<body>
<div id="wrap">
<div id="mymenu"><a href="index.php">Main Menu</a> | <a href="edit_profile.php">Edit Profile</a> | <a href="logout.php">Log Out</a></div>
<h3><?php echo $t_desc ?></h3>
<p><strong>Start Date</strong>: <?php echo $t_start ?><br />
<strong>Status</strong>: <?php echo $t_status ?><br />
<strong>Round</strong>: <?php echo $t_round ?></p>

<?php

// insert timestamp
if ($raw_action == 'eliminate') {
    $query = "UPDATE users SET lastbreaktime = ".time() .", daily_status='Eliminated' WHERE id=$id";
    $result = mysql_query( $query ) or die(mysql_error());
    print "<strong>You are taking a break.</strong><br /><br /><form method=GET action='index.php'>
            <input type='Submit' value='Click to Continue'></form>";
}
else if (!$can_rejoin) {
        print "<strong>$rejection_message.</strong><br /><br /><form method=GET action='index.php'>
            <input type='Submit' value='Click to Continue'></form>";
}
else {
?>

<div id="searching" style="text-align:center; font-weight:bold;">SEARCHING FOR YOUR NEXT OPPONENT<br /><img src="images/please_wait.gif" /></div>
<div id="confirmations" style='background-color:lightyellow; padding:5px; font-weight:bold; visibility:hidden;'>&nbsp;</div>
<p>

<?php

    if ($can_rejoin &&
            $raw_action != 'eliminate') {

        // print out the results
        join_daily($id);

    }

}


?>	
</p>

</div>
</body>
</html>
