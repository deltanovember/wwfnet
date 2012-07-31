<? 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include"auth_check_header.php";

?>	

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Manage My Tournament</title>

<style type="text/css">
#wrap {font-family:Arial, Helvetica, sans-serif; font-size:12px;}
</style>

</head>

<body topmargin="0">
<div id="wrap" style="width:500px;">

<form action="assignplayers.php" method="post" name="form" id="form"> 

<table border="0" cellpadding="2" cellspacing="1" style="border-collapse:collapse;">

<tr><th>#</th><th>PL 1</th><th>PL 2</th></tr>
<tr>
<td><b>1</b></td>
<td><input type = "text" value="" name="odd1" width="5" /></td>
<td><input type = "text" value="" name="even1" width="5" /></td>
</tr>

<!--
<tr>
<td><b>2</b></td>
<td><input type = "text" value="" name="odd2" width="5" /></td>
<td><input type = "text" value="" name="even2" width="5" /></td>
</tr>

<tr>
<td><b>3</b></td>
<td><input type = "text" value="" name="odd3" width="5" /></td>
<td><input type = "text" value="" name="even3" width="5" /></td>
</tr>

<tr>
<td><b>4</b></td>
<td><input type = "text" value="" name="odd4" width="5" /></td>
<td><input type = "text" value="" name="even4" width="5" /></td>
</tr>

<tr>
<td><b>5</b></td>
<td><input type = "text" value="" name="odd5" width="5" /></td>
<td><input type = "text" value="" name="even5" width="5" /></td>
</tr>

<tr>
<td><b>6</b></td>
<td><input type = "text" value="" name="odd6" width="5" /></td>
<td><input type = "text" value="" name="even6" width="5" /></td>
</tr>

<tr>
<td><b>7</b></td>
<td><input type = "text" value="" name="odd7" width="5" /></td>
<td><input type = "text" value="" name="even7" width="5" /></td>
</tr>

<tr>
<td><b>8</b></td>
<td><input type = "text" value="" name="odd8" width="5" /></td>
<td><input type = "text" value="" name="even8" width="5" /></td>
</tr>

<tr>
<td><b>9</b></td>
<td><input type = "text" value="" name="odd9" width="5" /></td>
<td><input type = "text" value="" name="even9" width="5" /></td>
</tr>

<tr>
<td><b>10</b></td>
<td><input type = "text" value="" name="odd10" width="5" /></td>
<td><input type = "text" value="" name="even10" width="5" /></td>
</tr>
-->

</table>
<input type="hidden" value="true" name="assign" />
<input type="submit" value="Update" name="update" />

</form>


<? //Define Variables and Functions

function getVars($odd,$even){
$poptable = 'M2010_06';
$onround = '7';

$query = "SELECT player FROM $poptable WHERE id='$odd'"; 
$result = mysql_query($query) or die("Couldn't execute query");
while ($row= mysql_fetch_array($result)) {
$player= $row["player"];
$query1 = "SELECT * FROM users WHERE id='$odd'";
$result1 = mysql_query($query1) or die("Couldn't execute query1");
while ($row= mysql_fetch_array($result1)) {
    $p_fname= $row["firstname"];
	$p_email= $row["email"];
	$p_hint= $row["password_hint"];
	  }
   }

$query = "SELECT player FROM $poptable WHERE id='$even'"; 
$result = mysql_query($query) or die("Couldn't execute query2");
while ($row= mysql_fetch_array($result)) {
$opponent= $row["player"];
$query1 = "SELECT * FROM users WHERE id='$even'";
$result1 = mysql_query($query1) or die("Couldn't execute query3");
while ($row= mysql_fetch_array($result1)) {
    $o_fname= $row["firstname"];
	$o_email= $row["email"];
	$o_hint= $row["password_hint"];
	  }
   }

assignPlayers("$player","$odd","$p_fname","$opponent","$p_hint","$p_email","$onround","$poptable");
assignPlayers("$opponent","$even","$o_fname","$player","$o_hint","$o_email","$onround","$poptable");
}


//populate database and send email
function assignPlayers($person0,$op_id,$fname0,$versus0,$hint0,$email0,$onround,$poptable){

$query = "UPDATE $poptable SET `r" . $onround . "_vs` = '" . $versus0 . "', `onround` = '" . $onround . "' WHERE `id` = '" . $op_id . "'; ";
$results = mysql_query( $query );

$from = "Admin <admin@wordswithfriends.net>";
$reply_to = "no-reply@wordswithfriends.net";
$return_path = "no-reply@wordswithfriends.net";

$to = $email0;

$subject = "[WordsWithFriends.net] Your Round " . $onround . " Opponent";


$mailbody = "Dear " . $fname0 . " (" . $person0 . "):" . "\n" . "Round " . $onround . " has begun! Your next tournament opponent is '" . $versus0 . ".' Please begin play as soon as possible. You can contact your opponent and take other actions at your MyTourney page. Log in at http://wordswithfriends.net/?page_id=386 . Here's your password hint: '" . $hint0 . "'. Please do not reply to this message. Thanks for playing!";


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
$msg_conf = "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>Email Message</span>: $mailbody</p>";
}

else {$msg_conf = "";}

echo $msg_conf;
}


//Get posted values 
if (($_POST['assign']) == "true") {
 
$odd1 = strip_tags(substr($_POST['odd1'],0,5));
$even1 = strip_tags(substr($_POST['even1'],0,5));
/*

$odd2 = strip_tags(substr($_POST['odd2'],0,5));
$even2 = strip_tags(substr($_POST['even2'],0,5));
$odd3 = strip_tags(substr($_POST['odd3'],0,5));
$even3 = strip_tags(substr($_POST['even3'],0,5));
$odd4 = strip_tags(substr($_POST['odd4'],0,5));
$even4 = strip_tags(substr($_POST['even4'],0,5));
$odd5 = strip_tags(substr($_POST['odd5'],0,5));
$even5 = strip_tags(substr($_POST['even5'],0,5));
$odd6 = strip_tags(substr($_POST['odd6'],0,5));
$even6 = strip_tags(substr($_POST['even6'],0,5));
$odd7 = strip_tags(substr($_POST['odd7'],0,5));
$even7 = strip_tags(substr($_POST['even7'],0,5));
$odd8 = strip_tags(substr($_POST['odd8'],0,5));
$even8 = strip_tags(substr($_POST['even8'],0,5));
$odd9 = strip_tags(substr($_POST['odd9'],0,5));
$even9 = strip_tags(substr($_POST['even9'],0,5));
$odd10 = strip_tags(substr($_POST['odd10'],0,5));
$even10 = strip_tags(substr($_POST['even10'],0,5));
*/

getVars($odd1,$even1);
/*

getVars($odd2,$even2);
getVars($odd3,$even3);
getVars($odd4,$even4);
getVars($odd5,$even5);
getVars($odd6,$even6);
getVars($odd7,$even7);
getVars($odd8,$even8);
getVars($odd9,$even9);
getVars($odd10,$even10);
*/

}

?>

</div>
</body>
</html>