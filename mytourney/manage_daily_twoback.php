<? 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=1;

include"auth_check_header.php";

$t_id = strip_tags(substr($_GET['t_id'],0,20));
$proposed_id = strip_tags(substr($_GET['id'],0,10));
$action = strip_tags(substr($_GET['action'],0,20));

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

$result = mysql_query($query) or die("Couldn't execute query");
while ($row= mysql_fetch_array($result)) {
$t_id= $row["t_id"];
$t_start= $row["t_start"];
$t_short_start= substr($t_start, 5, 10);
$t_desc= $row["t_desc"];
$raw_t_round= $row["t_round"];
$t_round = $raw_t_round-2;
$t_status= $row["t_status"];
$t_champ= $row["t_champ"];
}


// if tourney is not in progress, send back to index
if ($t_status != "In Progress" && $t_status != "Register"){ header("Location: index.php?"); } 

$query = "SELECT * FROM $t_id WHERE id='$id'"; 
$result = mysql_query($query) or die("Couldn't execute query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$record= $row["record"];
$player= $row["player"];
$raw_round= $row["onround"];
$onround = $raw_round-2;
$status= $row["status"];
$admcmt= $row["admcmt"];
$opponent= $row["r" . $onround . "_vs"];
$p_msg= $row["r" . $onround . "_pmsg"];
$p1_g1= $row["r" . $onround . "_g1"];
$p1_rslt= $row["r" . $onround . "_rslt"];
$p1_cmt= $row["r" . $onround . "_cmt"];

}


//Update scores if needed

if (($_POST['updatescore']) == "true") {
$p1_g1 = strip_tags(substr($_POST['p1_g1'],0,3));
$p2_g1 = strip_tags(substr($_POST['p2_g1'],0,3));
$winner = strip_tags(substr($_POST['winner'],0,50));
$result = strip_tags(substr($_POST['result'],0,30));
$rating = strip_tags(substr($_POST['rating'],0,1));
$new_cmt = strip_tags(substr($_POST['new_cmt'],0,140));

$query = "UPDATE `$t_id` SET `r" . $onround . "_g1` = '" . $p1_g1 . "' WHERE `player` = '" . $player . "'; ";

// save the info to the database
$results = mysql_query( $query );

$query = "UPDATE `$t_id` SET `r" . $onround . "_g1` = '" . $p2_g1 . "' WHERE `player` = '" . $opponent . "'; ";

// save the info to the database
$results = mysql_query( $query );


if ($rating > -1) {
$query = "UPDATE `$t_id` SET `r" . $onround . "_rated` = '" . $rating . "' WHERE `player` = '" . $opponent . "'; ";

// save the info to the database
$results = mysql_query( $query );
}

if ($new_cmt > -1) {
$query = "UPDATE `$t_id` SET `r" . $onround . "_cmt` = '" . $new_cmt . "' WHERE `player` = '" . $player . "'; ";

// save the info to the database
$results = mysql_query( $query );
}


// No-winner Use Case //

if ($winner == "no_winner") {
$query = "UPDATE `$t_id` SET `r" . $onround . "_rslt` = NULL , lastwin = '0'  WHERE `player` = '" . $player . "'; ";

// save the info to the database
$results = mysql_query( $query );


$query = "UPDATE `$t_id` SET `r" . $onround . "_rslt` = NULL , lastwin = '0'  WHERE `player` = '" . $opponent . "'; ";

// save the info to the database
$results = mysql_query( $query );


}

// Player Wins Use Case // 

else if ($winner == $player) {
$query = "UPDATE `$t_id` SET `r" . $onround . "_rslt` = 'Won' , lastwin = now() WHERE `player` = '" . $player . "'; ";

// save the info to the database
$results = mysql_query( $query );

if ($result != "") {
$query = "UPDATE `$t_id` SET `r" . $onround . "_rslt` = '" . $result . "' , lastwin = '0' WHERE `player` = '" . $opponent . "'; ";
}

else {
$query = "UPDATE `$t_id` SET `r" . $onround . "_rslt` = 'Lost' , lastwin = '0' WHERE `player` = '" . $opponent . "'; ";
}

// save the info to the database
$results = mysql_query( $query );

}

// Opponent Wins Use Case // 

elseif ($winner == $opponent) {
$query = "UPDATE `$t_id` SET `r" . $onround . "_rslt` = 'Won' , lastwin = now() WHERE `player` = '" . $opponent . "'; ";

// save the info to the database
$results = mysql_query( $query );

if ($result != "") {
$query = "UPDATE `$t_id` SET `r" . $onround . "_rslt` = '" . $result . "' , lastwin = '0' WHERE `player` = '" . $player . "'; ";
}

else {
$query = "UPDATE `$t_id` SET `r" . $onround . "_rslt` = 'Lost' , lastwin = '0' WHERE `player` = '" . $player . "'; ";
}

// save the info to the database
$results = mysql_query( $query );

}

// Default Use Case - meaning nothing but blanks submitted // 

else {
$query = "UPDATE `$t_id` SET `r" . $onround . "_rslt` = NULL , lastwin = '0' WHERE `player` = '" . $player . " OR WHERE `player` = '" . $opponent . "'; ";

// save the info to the database
$results = mysql_query( $query );

}

// Retrieve Updated Values
$query = "SELECT * FROM $t_id WHERE id='$id'"; 

$result = mysql_query($query) or die("Couldn't execute query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$record= $row["record"];
$player= $row["player"];
$raw_round= $row["onround"];
$onround= $raw_round-2;
$status= $row["status"];
$admcmt= $row["admcmt"];
$opponent= $row["r" . $onround . "_vs"];
$p_msg= $row["r" . $onround . "_pmsg"];
$p1_g1= $row["r" . $onround . "_g1"];
$p1_rslt= $row["r" . $onround . "_rslt"];
$p1_cmt= $row["r" . $onround . "_cmt"];

}

}

if ($p1_rslt == 'Won') {$rslt_message = "<p style='color:green;'><b>YOU ARE THE WINNER FOR ROUND " . $onround . "!</b></p>";} 
elseif ($p1_rslt == 'Lost' || $p1_rslt == 'Forfeited' || $p1_rslt == 'No-Show' || $p1_rslt == 'Incomplete') {$rslt_message = "<p><span style='font-weight:bold; color:red;'>You lost in Round " . $onround . "</span></p>";}
else {}

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Manage My Tournament</title>

<style type="text/css">
#wrap {font-family:Arial, Helvetica, sans-serif; font-size:13px;}
#mymenu {float:right;}
#onroundbox {text-align:center; background-color:#FFFF00; padding:10px;}
.td_tourney {background-color:lightblue; font-weight:bold; font-size:larger;}
</style>

</head>

<body topmargin="0">
<div id="wrap" style="width:500px;">

<div id="mymenu"><a href="index.php">Main Menu</a> | <a href="edit_profile.php">Edit Profile</a> | <a href="logout.php">Log Out</a></div>      
<h3><? echo $t_desc ?></h3>
<table style="border-collapse:collapse; border:none; width:100%; margin:0px;"><tr valign="top">
<td>
<p><strong>Start Date</strong>: <? echo $t_start ?><br />
<strong>Status</strong>: <? echo $t_status ?><br />

<? 
if ($rslt_message != "") {echo $rslt_message ;}
?>

</p>
</td><td width="50%" id="onroundbox">
<span style="font-size:170%; font-weight:bold;">Round <? echo $onround ?></span><br />
<span style="font-size:larger;"><? echo $player; ?></span><br />
vs. <br />

<? 
if ($opponent != ""){
echo "<span  style='font-size:larger; color:green; font-weight:bold;'>" . $opponent . "</span>";
}

else {
echo "<span style='color:red; font-size:larger; font-weight:bold;'>[NO OPPONENT]</span>";
}

?>
<br /><br />
<?
echo "<a href='manage_daily_prior.php?t_id=" . $t_id . "&id=" . $id . "'>Go to Next Round ></a>"; 
?>
</td></tr>
</table>

<? 
// GET OPPONENT'S USER TABLE VALUES AND ASSIGN SESSION VARIABLES
	
$query = "SELECT * FROM users WHERE username='$opponent'"; 
// get results
$result2 = mysql_query($query) or die("Couldn't execute query");
// now you can display the results returned
while ($row= mysql_fetch_array($result2)) {


$id1= $row["id"];
$username1= $row["username"];
$password_hint1= $row["password_hint"];
$lastname1= $row["lastname"];
$firstname1= $row["firstname"];
$phone1= $row["phone"];
$email1= $row["email"];
$permissions1 = $row["permissions"];
$email_sub1 = substr($email1, 0, 50);
$joined1= $row["joined"];
$joined_sub1 = substr($joined1, 0, 10);
$privacy1= $row["privacy"];
$timezone1= $row["timezone"];
$comment1= $row["comment"];
$skill1= $row["skill"];
$device1= $row["device"];
$url1= $row["url"];

}
			
// GET THE OPPONENT'S CURRENT ROUND DATA
			
$query = "SELECT * FROM $t_id WHERE player='$opponent'"; 

// get results
$result = mysql_query($query) or die("Couldn't execute query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {
$p2_g1= $row["r" . $onround . "_g1"];
$p2_rslt= $row["r" . $onround . "_rslt"];
$p2_rated= $row["r" . $onround . "_rated"];
$p2_cmt= $row["r" . $onround . "_cmt"];
$p2_msg= $row["r" . $onround . "_pmsg"];
}

// Determine whatever?
if ($admcmt != "") {echo "<p><b>Moderator says:</b> $admcmt</p>";}

if ($url1 != "") {
$has_url1 = "<strong>URL</strong>: <a href='$url1' target='_blank'>$url1</a><br />";
} 		

if ($phone1 != "") {
$has_phone1 = "<strong>Phone</strong>: <a href='$phone1' target='_blank'>$phone1</a><br />";
} else {
$has_phone1 = "<strong>Phone</strong>: [not provided]<br />";
}

if ($comment1 != "") {
$has_comment = "<strong>Public Comment</strong>: \"$comment1\"<br />";
} else {
$has_comment = "<strong>Public Comment</strong>: [not provided]<br />";
}			

echo "
<p>
<strong>WWF Screen Name</strong>: <span style='color:green; font-weight:bold;'>" . $username1 . "</span><br />
<strong>First Name</strong>: $firstname1<br />" . $has_url1 .
"<strong>Member Since</strong>: $joined_sub1<br />
<strong>Time Zone</strong>: $timezone1 | <strong>Device</strong>: $device1<br />"; 

if ($privacy1 != "on") {

echo "<strong>Email</strong>: $email_sub1<br />" . $has_phone1; }

else { echo "Note: Your opponent does not share private contact info.<br />"; }
echo $has_comment . "</p>";

?>

<div style="padding:10px; background-color:lightyellow; font-weight:bold; display:<?
if ($t_status == "Register") {echo 'none';} 
else {echo 'none';} 
?>;">

<span style="color:red;">NOTE: The <? echo $t_desc ?> is still in the Registration Period. Please do not engage your opponent at this time. Instead, return on the start date of <? echo $t_short_start ?>, at which time you will be provided with further instructions.</span> <br /><br />
Thank you for playing in this WordsWithFriends.net tournament!
</div>

<div style="display:<?
if ($t_status == "In Progress" || $t_status == "Register") {echo 'block';} 
else {echo 'none';} 
?>;">


<?

if (($_POST['send_pmessage']) == "true") {
$private_msg = strip_tags(substr($_POST['private_msg'],0,140));


$query = "UPDATE `" . $t_id . "` SET `r" . $onround . "_pmsg` = '" . $private_msg . "' WHERE `player` = '" . $opponent . "'; ";

// save the info to the database
$results = mysql_query( $query );
		
$mailbody = "The following private message was sent by your tournament opponent, " . $player . ": \n\n" . $private_msg . "\n\nIf you wish to respond by private message, then please log in at http://wordswithfriends.net.";

$from = "admin@wordswithfriends.net";
$reply_to = "no-reply@wordswithfriends.net";
$return_path = "no-reply@wordswithfriends.net";

$to = $email1;

$subject = "[WordsWithFriends.net] Player-to-Player Message";

//____________________________Begin Multipart Mail Sender
//add From: header 
$headers = "From:$from\nReply-to:$reply_to\nReturn-path:$return_path\nJobID:$date\n"; 

//specify MIME version 1.0 
$headers .= "MIME-Version: 1.0\n"; 

//unique boundary 
$boundary = uniqid("HTMLDEMO8656856"); 

//tell e-mail client this e-mail contains//alternate versions 
$headers.="X-Priority: 3\n";
$headers.="Content-Type: multipart/alternative; boundary=\"".$boundary."\"\n";
$headers.="Content-Transfer-Encoding: 7bit\n";

//message to people with clients who don't 
//understand MIME 
$headers .= "This is a MIME encoded message.\n\n"; 

//plain text version of message 
$headers .= "--$boundary\n" . 
   "Content-Type: text/plain; charset=ISO-8859-1\r\n" . 
   "Content-Transfer-Encoding: base64\n\n"; 
$headers .= chunk_split(base64_encode("$mailbody")); 

//HTML version of message 
$headers .= "--$boundary\n" . 
   "Content-Type: text/html; charset=ISO-8859-1\n" . 
   "Content-Transfer-Encoding: base64\n\n"; 
$headers .= chunk_split(base64_encode("$mailbody")); 

//send message

If (mail("$to", "$subject", "", $headers))
{

$msg_conf = "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>The following new message was just sent to $opponent</span>: $private_msg</p>";

}

else {$msg_conf = "";}

}

?>


<? if ($p2_msg != "") {
echo "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>[Private]</span><i><b> You said:</b></i> $p2_msg</p>";
}
?>

<? if ($p_msg != "") {
echo "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>[Private]</span><i><b> $opponent said:</b></i> $p_msg</p>";
}
?>

<table width="500px" border="0" cellspacing="0" cellpadding="0" style="padding: 10px; border: 1px solid #CCC;">
    <form action="manage_daily_twoback.php?t_id=<? echo $t_id; ?>&id=<? echo $id ?>" method="post" name="form" id="form">
<input type="hidden" name="private_email" value = "<? echo $email; ?>" />  
<tr valign="top"><td colspan="2"><b>Send Private Message</b><br />
This short message (140 chars max) is sent via email to your opponent. It also
      appears on their 'MyTourney' page when they log in. It does <em>not</em> appear publicly.
    </td><td><textarea cols="25" rows="4" name="private_msg"></textarea><div align="right"><input type="hidden" name="send_pmessage" value="true"><input name="submit" <? if ($mailbody != "") {echo "disabled";} ?> type="submit" value="Send" /></div></td></tr>
         
  </form></table>
  
<? echo $msg_conf ?>


<h3 style='background-color:#CCCCCC; border-top:1px solid #000000; border-bottom:1px solid #000000; padding:10px;'>Scores, Results & Comments</h3>

<form action="manage_daily_twoback.php?t_id=<? echo $t_id; ?>&id=<? echo $id ?>" method="post" name="form" id="form"> 

<table cellpadding="3" width="500px" cellspacing="2" style="border-collapse:collapse; border:1px solid #CCC;">
<tr><td colspan="3"> 
<? 

if ($p1_rslt == "Won") {
    echo "<p style='background-color:lightyellow; padding:5px;'><strong style='color:green;'>FINAL OUTCOME</strong>: Congrats! You won Round " . $onround . "; "; 
    if ($p2_rslt == 'Lost') {echo $opponent . ' lost.</p>';} 
    else if ($p2_rslt == 'No-Show') {echo $opponent . ' lost due to no-show.</p>';} 
	else if ($p2_rslt == 'Forfeited') {echo $opponent . ' forfeited.</p>';}
	else if ($p2_rslt == 'Incomplete') {echo $opponent . ' lost due to an incomplete round.</p>';}
	else {echo "</p>";}
}

else if ($p2_rslt == "Won") {
    echo "<p style='background-color:lightyellow; padding:5px;'><strong style='color:red;'>FINAL OUTCOME</strong>: You lost Round " . $onround;
	if ($p1_rslt == 'Lost') {echo "; " . $opponent . ' won.</p>';} 
    else if ($p1_rslt == 'No-Show') {echo "; " . $opponent . ' won due to no-show.</p>';} 
	else if ($p1_rslt == 'Forfeited') {echo "; " . $opponent . ' won because you forfeited.</p>';}
	else if ($p1_rslt == 'Incomplete') {echo "; " . $opponent . ' won because you had an incomplete round.</p>';}
	else {echo "; " . $opponent . " won.</p>";}
}

else {echo "<div style='color:red; padding:5px; font-weight:bold;'>Complete this section only after your game is final.</div>";} 
?>

</td></tr>
<tr><td colspan="2" style="font-weight:bold;"><? echo $player; ?></td><td style="font-weight:bold;"><? echo $opponent; ?></td></tr>
<tr>
<td colspan="2"><input type = "text" value="<? echo $p1_g1; ?>" name="p1_g1" size="3" /></td>
<td><input type = "text" value="<? echo $p2_g1; ?>" name="p2_g1" size="3" /></td>
</tr>

<? if ($p2_cmt != "") {
echo "<tr><td colspan='3'><p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>$opponent says:</span> $p2_cmt</p></td></tr>";
}
?>
<tr valign="top"><td colspan="2"><p><b>Comment on this Round:</b><br /><small>(140 max)</small><br /><br />
<span style="color:red;">NOTE: This will be immediately visible to everyone publicly on the Results tab.</span></p></td><td><br /><textarea cols="25" rows="4" name="new_cmt"><? echo $p1_cmt ?></textarea></td></tr>

<tr valign="top"><td colspan="2" align="right"><p><b>Who won Round <? echo $onround; ?>?</b></p></td><td width="65%"><p>
<input type="radio" id="[NO WINNER]" <? if ($p1_rslt == NULL) {echo 'checked="checked"';} ?> name="winner" value="no_winner" />[NO WINNER]<br />
<input type="radio" id="<? echo $player; ?>" <? if ($p1_rslt == 'Won') {echo 'checked="checked"';} ?> name="winner" value="<? echo $player; ?>" /><? echo $player; ?><br />
<input type="radio" id="<? echo $opponent; ?>" <? if ($p2_rslt == 'Won') {echo 'checked="checked"';} ?> name="winner" value="<? echo $opponent; ?>" /><? echo $opponent; ?>
</p>
</td></tr> 
<tr valign="top"><td colspan="2" align="right"><p><b>How Did Round <? echo $onround; ?> End?<br /></b></p></td><td><p><select name="result" >
  <option value="">--Make Selection--</option>
  <option <? if ($p1_rslt == 'Lost' || $p2_rslt == 'Lost') {echo 'selected';} ?> value="Lost">Round Completed</option>
  <option <? if ($p1_rslt == 'No-Show' || $p2_rslt == 'No-Show') {echo 'selected';} ?> value="No-Show">Player No-Show</option>
  <option <? if ($p1_rslt == 'Forfeited' || $p2_rslt == 'Forfeited') {echo 'selected';} ?> value="Forfeited">Player Forfeited</option>
  <option <? if ($p1_rslt == 'Incomplete' || $p2_rslt == 'Incomplete') {echo 'selected';} ?> value="Incomplete">Time Ran Out</option>
</select></p></td></tr>
<tr valign="top"><td colspan="2" align="right"><p><b>Rate Your Experience* <br />with this Player:<br /></b></p></td><td><p><select name="rating" >
  <option value="">--Make Selection--</option>
  <option <? if ($p2_rated == '1') {echo 'selected';} ?> value="1">1 - Terrible</option>
  <option <? if ($p2_rated == '2') {echo 'selected';} ?> value="2">2 - Poor</option>
  <option <? if ($p2_rated == '3') {echo 'selected';} ?> value="3">3 - Just OK</option>
  <option <? if ($p2_rated == '4') {echo 'selected';} ?> value="4">4 - Very Good</option>
  <option <? if ($p2_rated == '5') {echo 'selected';} ?> value="5">5 - Excellent</option>
</select></p></td></tr>
<tr><td colspan="3">
<p>* Base your rating on quality of communication, sportsmanship, speed of play, and overall pleasantness of this interaction. <span style="color:red;">Do not rate based on player's skill or ability.</span></p>
</td></tr>

</table>
<input type="hidden" value="true" name="updatescore" />
<div style="text-align:center; margin-top:20px;"><input type="submit" value="Update" name="update" /></div>
</form>
</div> 
</div>
</body>
</html>