<?php
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=1;

include"auth_check_header.php";
//require_once "login_config.php";

if (!$debugmode) {
  include "email_smtp.php";
}



$t_id = strip_tags(substr($_GET['t_id'],0,20));
$proposed_id = strip_tags(substr($_GET['id'],0,10));
$action = "";
if (isset ($_GET['action'])) {
  $action = strip_tags(substr($_GET['action'],0,20));
}


$query = "SELECT * FROM users WHERE username='$username_from_cookie' AND id='$proposed_id'";
$result = mysql_query($query);
$count=mysql_num_rows($result);
if($count>0){} else {
header("Location: index.php?msg=cant_validate_user");
}

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$id= $row["id"];
$username= $row["username"];
$firstname= $row["firstname"];
$phone= $row["phone"];
$email= $row["email"];
$email_sub = substr($email, 0, 50);
$joined= $row["joined"];
$joined_sub = substr($joined, 0, 10);
$privacy= $row["privacy"];
$device= $row["device"];
$url= $row["url"];
$timezone= $row["timezone"];
$comment= $row["comment"];
$skill= $row["skill"];

}

// Get Info From Control Table

$query = "SELECT * FROM T_CONTROL WHERE t_id='$t_id'";

$result = mysql_query($query) or die("Couldn't execute query1");
while ($row= mysql_fetch_array($result)) {
$t_id= $row["t_id"];
$t_start= $row["t_start"];
$t_desc= $row["t_desc"];
$t_round= $row["t_round"];
$t_status= $row["t_status"];
$t_champ= $row["t_champ"];
}

if ($t_status != "Register") {
} else {
header("Location: index.php?msg=registration_period_no_player");
die();
}

// Retrieve Player's existing data for this tournament ID
$query = "SELECT * FROM $t_id WHERE id='$id'";

$result = mysql_query($query) or die("Couldn't execute query2");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$record= $row["record"];
$player= $row["player"];
$onround= $row["onround"];
$status= $row["status"];
$admcmt= $row["admcmt"];
$opponent= $row["r" . $onround . "_vs"];
$p_msg= $row["r" . $onround . "_pmsg"];
$p1_rslt= $row["r" . $onround . "_rslt"];
$p1_cmt= $row["r" . $onround . "_cmt"];

}

// See if an opponent has already been assigned for this round. If YES, move back to index
if ($opponent == ""){
} else {
header("Location: index.php");
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

<body topmargin="0">

<div id="wrap">
<div id="mymenu"><a href="index.php">Main Menu</a> | <a href="edit_profile.php">Edit Profile</a> | <a href="logout.php">Log Out</a></div>
<h3><?php echo $t_desc ?></h3>
<p><strong>Start Date</strong>: <?php echo $t_start ?><br />
<strong>Status</strong>: <?php echo $t_status ?><br />
<strong>Round</strong>: <?php echo $t_round ?></p>

<div id="searching" style="text-align:center; font-weight:bold;">SEARCHING FOR YOUR NEXT OPPONENT<br /><img src="images/please_wait.gif" /></div>
<div id="confirmations" style='background-color:lightyellow; padding:5px; font-weight:bold; visibility:hidden;'>&nbsp;</div>
<?php
$opponent_found= "no";

$query = "SELECT * FROM " . $t_id . " WHERE onround = '" . $onround . "' AND r" . $onround . "_vs IS NULL AND status <> 'Eliminated' AND id <> " . $id . " ORDER BY RAND()";
$result = mysql_query($query) or die("Couldn't execute query");
$count=mysql_num_rows($result);
//echo "There are " . $count . " players awaiting an opponent for this round.<br />";

while ($row= mysql_fetch_array($result)) {
$o_id= $row["id"];
$opponent= $row["player"];

$query1 = "SELECT * FROM users WHERE id='$o_id'";
$result1 = mysql_query($query1) or die("Couldn't execute query");
while ($row= mysql_fetch_array($result1)) {
	$o_zone= $row["timezone"];
	  }
/*

// This script enables time zone matching

if ($o_zone == $timezone) {
$homezone_msg = "We found a player for you in the <span style=\'color:green;\'>" . $o_zone . " Time Zone</span>. Player screen name is: <span style=\'color:green;\'>" . $opponent . "</span>";
$opponent_found= "yes";
}
if ($opponent_found == "yes") {break 1;}

*/
}

// Do this if no opponents found in control player's time zone
if ($opponent_found == "no") {
$query = "SELECT * FROM " . $t_id . " WHERE onround = '" . $onround . "' AND r" . $onround . "_vs IS NULL AND status <> 'Eliminated' AND id <> " . $id . " ORDER BY RAND() LIMIT 1;";
$result = mysql_query($query) or die("Couldn't execute query");
$count=mysql_num_rows($result);
echo "<script type='text/javascript'>alert(\'im here\');</script>";
if ($count == NULL) {
$homezone_msg = "<span style=\'color:red; font-weight:bold;\'>We cannot assign you an opponent for this Round because there are no other players awaiting assignment at this time. Please check back later or email us at admin@wordswithfriends.net.</span><br />";
echo "<script type='text/javascript'>document.getElementById('searching').style.display = 'none';</script>";
echo "<script type='text/javascript'>document.getElementById('confirmations').innerHTML = '" . $homezone_msg . "';
document.getElementById('confirmations').style.visibility = 'visible';</script>";
echo "<br /><form method=GET action='index.php'><input type=hidden name='t_id' value='" . $t_id . "'><input type=hidden name='id' value='" . $id . "'><input type='Submit' value='Click to Continue'></form></center>";
die( "" . mysql_error() );
}

while ($row= mysql_fetch_array($result)) {
$o_id= $row["id"];
$opponent= $row["player"];
$query1 = "SELECT * FROM users WHERE id='$o_id'";
$result1 = mysql_query($query1) or die("Couldn't execute query");
    while ($row= mysql_fetch_array($result1)) {
	$o_zone= $row["timezone"];
    $homezone_msg = "Your next opponent\'s time zone is <span style=\'color:green;\'>" . $o_zone . "</span>. Player screen name is: <span style=\'color:green;\'>" . $opponent . "</span>";
    }
  }
}

// Set player and opponent variables for database update
$query1 = "SELECT * FROM users WHERE id='$id'";
$result1 = mysql_query($query1) or die("Couldn't execute query1");
while ($row= mysql_fetch_array($result1)) {
    $p_fname= $row["firstname"];
	$p_email= $row["email"];
	$p_hint= $row["password_hint"];
	  }

$query = "SELECT player FROM $t_id WHERE id='$o_id'";
$result = mysql_query($query) or die("Couldn't execute query2");
while ($row= mysql_fetch_array($result)) {
$opponent= $row["player"];
$query1 = "SELECT * FROM users WHERE id='$o_id'";
$result1 = mysql_query($query1) or die("Couldn't execute query3");
while ($row= mysql_fetch_array($result1)) {
    $o_fname= $row["firstname"];
	$o_email= $row["email"];
	$o_hint= $row["password_hint"];
	  }
}


//populate database and send email
function assignPlayers($person0,$op_id,$fname0,$versus0,$hint0,$email0,$onround,$poptable,$ctrl,$t_desc){

$query = "UPDATE $poptable SET `r" . $onround . "_vs` = '" . $versus0 . "', `onround` = '" . $onround . "', `r" . $onround . "_ctrl` = '" . $ctrl . "' WHERE `id` = '" . $op_id . "'; ";
$results = mysql_query( $query );

$from = "Admin <admin@wordswithfriends.net>";
$reply_to = "no-reply@wordswithfriends.net";
$return_path = "no-reply@wordswithfriends.net";

$to = $email0;

$subject = "[WordsWithFriends.net] Your Round " . $onround . " Opponent";


$mailbody = "Dear " . $fname0 . " (" . $person0 . "): <br><br>Round " . $onround . " of the " . $t_desc . " is underway! Your next tournament opponent is '" . $versus0 . ".' You can contact your opponent and take other actions at your MyTourney page. Log in at your <a href=\"http://wordswithfriends.net/?page_id=386\">MyTourney</a> page . Here's your password hint: '" . $hint0 . "'. Please do not reply to this message. <br><br>Thanks for playing!";


if (email($to, $subject, $mailbody)){
$msg_conf = "<p style='background-color:lightyellow; padding:5px;'><span style='font-weight:bold;'>Email message was sent to " . $person0 . ".</span></p>";
}

else {$msg_conf = "<p style='background-color:lightyellow; padding:5px; font-weight:bold; color:red;'>Email not sent. Please check to see if you have been assigned an opponent for this round. If not, then contact admin@wordswithfriends.net for assistance. Otherwise, you can ignore this message.</span>";}

echo $msg_conf;
}

assignPlayers("$player","$id","$p_fname","$opponent","$p_hint","$p_email","$t_round","$t_id","yes","$t_desc"); // Function call for this Player
assignPlayers("$opponent","$o_id","$o_fname","$player","$o_hint","$o_email","$t_round","$t_id","no","$t_desc"); // Function call for the Opponent
//$homezone_msg = "A player cannot be assigned to you at this time.";

echo "<script type='text/javascript'>document.getElementById('searching').style.display = 'none';</script>";
echo "<script type='text/javascript'>document.getElementById('confirmations').innerHTML = '" . $homezone_msg . "';
document.getElementById('confirmations').style.visibility = 'visible';</script>";
echo "<br /><form method=GET action='index.php'><input type=hidden name='t_id' value='" . $t_id . "'><input type=hidden name='id' value='" . $id . "'><input type='Submit' value='Click to Continue'></form></center>";
?>

</div>
</body>
</html>