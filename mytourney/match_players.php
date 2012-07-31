<? 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include"auth_check_header.php";

$t_id = strip_tags(substr($_GET['t_id'],0,20));
$id = strip_tags(substr($_GET['id'],0,10));
$onround = strip_tags(substr($_GET['round'],0,2)); 

if ($id == NULL || $t_id == NULL || $onround == NULL) {
die('A required GET variable is missing');
}

$query = "SELECT * FROM users WHERE id='$id'"; 
$result = mysql_query($query);
$count=mysql_num_rows($result);
if($count>0){} else {
header("Location: admin_index.php?msg=cant_validate_user");
}

while ($row= mysql_fetch_array($result)) {

$id= $row["id"];
$player= $row["username"];
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
?>


<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Match Players</title>

<style type="text/css">
#wrap {font-family:Arial, Helvetica, sans-serif; font-size:12px;}
</style>

</head>

<body topmargin="0">

<div id="wrap" style="width:500px;">

<form action="match_players.php?<? echo "id=" . $id . "&t_id=" . $t_id . "&round=" . $onround; ?>" method="post" name="form" id="form"> 

<table border="0" cellpadding="2" cellspacing="1" style="border-collapse:collapse;">

<tr><th>Player 1</th><th>Player 2</th></tr>
<tr>
<td><input type="hidden" value="<? echo $id; ?>" name="odd1" /><strong><? echo $player; ?></strong><br />Time Zone: <? echo $timezone; ?></td><td>

<select name="even1">
  <option value="">--Make Selection--</option>
<? 

$query = "SELECT * FROM `$t_id` WHERE onround = $onround AND r" . $onround . "_vs IS NULL AND status = 'Active' AND player <> '$player' ORDER BY player";
$result = mysql_query($query);
$count=mysql_num_rows($result);
if($count>0){} else {
echo "<script type='text/javascript'>alert('No Other Opponents Available in Round " . $onround . "');</script>";
}

while($row = mysql_fetch_array($result))
  {
$opponent= $row['player'];
$o_id= $row['id'];

echo "<option value=" . $o_id . ">" . $opponent . "</option>";
}

?>

</select>
</td>
</tr>
</table>
<input type="hidden" value="true" name="assign" />
<input type="submit" value="Update" name="update" />

</form>

<? 
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
function assignPlayers($person0,$op_id,$fname0,$versus0,$hint0,$email0,$onround,$t_id,$ctrl,$t_desc){

$query = "UPDATE $t_id SET `r" . $onround . "_vs` = '" . $versus0 . "', `onround` = '" . $onround . "', `r" . $onround . "_ctrl` = '" . $ctrl . "' WHERE `id` = '" . $op_id . "'; ";
$results = mysql_query( $query );

$from = "Admin <admin@wordswithfriends.net>";
$reply_to = "no-reply@wordswithfriends.net";
$return_path = "no-reply@wordswithfriends.net";

$to = $email0;

$subject = "[WordsWithFriends.net] Your Round " . $onround . " Opponent";


$mailbody = "Dear " . $fname0 . " (" . $person0 . "): Round " . $onround . " of the " . $t_desc . " is underway! Your next tournament opponent is '" . $versus0 . ".' You can contact your opponent and take other actions at your MyTourney page. Log in at http://wordswithfriends.net/?page_id=386 . Here's your password hint: '" . $hint0 . "'. Please do not reply to this message. Thanks for playing!";

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

If (mail("$to", "$subject", "", $headers)){
$msg_conf = "<p style='background-color:lightyellow; padding:5px;'><span style='font-weight:bold;'>Email message was sent to " . $person0 . ".</span></p>";
}

else {$msg_conf = "<p style='background-color:lightyellow; padding:5px; font-weight:bold; color:red;'>Email not sent. Please check to see if you have been assigned an opponent for this round. If not, then contact admin@wordswithfriends.net for assistance. Otherwise, you can ignore this message.</span>";}

echo $msg_conf;
}

if (($_REQUEST['assign']) == "true") {
assignPlayers("$player","$id","$p_fname","$opponent","$p_hint","$p_email","$onround","$t_id","yes","$t_desc"); // Function call for this Player
assignPlayers("$opponent","$o_id","$o_fname","$player","$o_hint","$o_email","$onround","$t_id","no","$t_desc"); // Function call for the Opponent

echo "<script type='text/javascript'>document.getElementById('confirmations').innerHTML = '" . $homezone_msg . "';</script>";
echo "<br /><form method=GET action='index.php'><input type=hidden name='t_id' value='" . $t_id . "'><input type=hidden name='id' value='" . $id . "'><input type='Submit' value='Click to Continue'></form></center>";
}

?>

</div>
</body>
</html>