<?php
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=1;

include"../auth_check_header.php";
require_once "../login_config.php";
$msg = "";
if (isset ($_GET['msg'])) {
 $msg = strip_tags(substr($_GET['msg'],0,20));
}

$id = "";
if (isset ($_GET['id'])) {
 $id = strip_tags(substr($_GET['id'],0,10));
}


?>	

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>My Tournament</title>

<link type="text/css" rel="stylesheet" href="<?php print $base_dir."/" ?>mytourney.css" />

</head>

<body topmargin="0">
<div id="wrap">
<div id="mymenu"><a href="admin_index.php">Admin Index</a> | <a href="logout.php">Log Out</a></div>      

<?php // GET PLAYER'S USER TABLE VALUES AND ASSIGN SESSION VARIABLES
$username_from_cookie = $_COOKIE[NAME_COOKIE]; //retrieve contents of cookie
			
$query = "SELECT * FROM users WHERE id='$id'"; 

// get results
$result = mysql_query($query) or die("Couldn't execute query 1");

$privacy = "";
$firstname= "";
$phone = "";
$url = "";
$comment = "";
$password_hint = "";
$lastname = "";
$email_sub = "";
$joined_sub = "";
$timezone = "";
$skill = "";
$device = "";
$outmessage = "";

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$id= $row["id"];
$username= $row["username"];
$password= $row["password"];
$password_hint= $row["password_hint"];
$lastname= $row["lastname"];
$firstname= $row["firstname"];
$phone= $row["phone"];
$email= $row["email"];
$permissions = $row["permissions"];
$email_sub = substr($email, 0, 50);
$joined= $row["joined"];
$joined_sub = substr($joined, 0, 10);
$privacy= $row["privacy"];
$timezone= $row["timezone"];
$comment= $row["comment"];
$skill= $row["skill"];
$device= $row["device"];
$url= $row["url"];
}

// Set appropriate messages based on available profile info
if ($privacy == 'on') { 
$privacy_ind = "<span style='color:red; font-weight:bold;'>*</span>";
$privacy_msg = "<p style='color:red';>* - $firstname has chosen to keep this contact information private.</p>"; 
} 

else {
$privacy_ind = "<span style='color:green; font-weight:bold;';>*</span>";
$privacy_msg = "<p style='color:green;';>* - $firstname currently shares contact information with his opponents.</p>"; 
}

if ($phone != "") {
$has_phone = "<strong>Phone</strong>: $phone $privacy_ind<br />";
} else {
$has_phone = "<strong>Phone</strong>: [not provided] $privacy_ind<br />";
}			

if ($url != "") {
$has_url = "<strong>URL</strong>: <a href='$url' target='_blank'>$url</a><br />";
} else {
$has_url = "<strong>URL</strong>: [not provided]<br />";
}			

if ($password_hint != "") {
$has_hint = "<strong>Password Hint</strong>: \"$password_hint\"<br />";
} else {
$has_hint = "<strong>Password Hint</strong>: [not provided]<br />";
}			

if ($comment != "") {
$has_comment = "<strong>Public Comment</strong>: \"$comment\"<br />";
} else {
$has_comment = "<strong>Public Comment</strong>: [not provided]<br />";
}			


?>


<p><span style="color:green; font-size:larger; font-weight:bold;">Moderating for <?php echo "$username" ?></span></p>

<?php echo "
<p>
<strong>Real Name</strong>: " . $firstname . " " . $lastname . "<strong><br />
Email</strong>: $email_sub $privacy_ind<br />" . $has_phone . $has_url .
"<strong>Member Since</strong>: $joined_sub<br />
<strong>Time Zone</strong>: $timezone | <strong>Skill Level</strong>: $skill | <strong>Device</strong>: $device<br />" . $has_hint . $has_comment . $privacy_msg.
"</p>" 
?>

<table class='mt_table'>
<tr>
<th colspan='4' class="td_tourney">Recent Tournaments</th>
</tr>
<th width="17%">Start Date</th><th width="50%">Tournament Name</th><th width="25%">Status (Round)</th><th width="8%">Play</th>
</tr>
<?php // Get Info From Tournament Table

$query = "SELECT * FROM T_CONTROL WHERE `t_status` <> 'Archive' ORDER BY `t_start` DESC"; 

// get results
$result = mysql_query($query) or die("Couldn't execute query 2");
$row_count = 0;

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $t_id= $row["t_id"];
    $t_start= $row["t_start"];
    $t_desc= $row["t_desc"];
    $t_round= $row["t_round"];
    $t_status= $row["t_status"];
    $tournament_id = $row["record"];

$color1 = "#ffffff";  
$color2 = "#ebebeb"; 	
$row_color = ($row_count % 2) ? $color1 : $color2; 

$daily = strrpos($t_id,"D") > -1;

$query2 = "";

if (!$daily) {
    $query2 = "SELECT * FROM `$t_id` WHERE id='$id'";
}
else {
    $query2 = "SELECT round as onround FROM daily WHERE user_id='$id' and tournament_id=$tournament_id order by round desc limit 1";
}

// get results
$result2=mysql_query($query2) or die("Couldn't execute $query2");
while ($row= mysql_fetch_array($result2)) {
    $p_status= "";
    if (!$daily) {
        $p_status= $row["status"];;
    }

    $p_onround= $row["onround"];

    if (strrpos($t_id,"D") > -1){
        $t_link="<a href='referee_daily.php?tournament_id=" . $tournament_id . "&id=" . $id . "&round=" . $p_onround . "'>" . $t_status . "</a>";
    }

    else {
        $t_link="<a href='referee.php?t_id=" . $t_id . "&id=" . $id . "&round=" . $p_onround . "'>" . $t_status . "</a>";
    }

    if ($p_status == "Eliminated") {
        $outmessage = "<tr><td style='background-color:lightyellow; text-align:center' colspan='4'>
            <strong>Result:</strong> $firstname reached R" . $p_onround . " of the $t_desc.
            </td></tr>";
    }
    else {
        $outmessage = "";
    }

}

$count=mysql_num_rows($result2);
if($count>0){
    $joined_image = "<img src='$base_dir/images/checked.png' border='0px' />";

	if (strrpos($t_id,"D") > -1){
    $desc_link = "<a href='referee_daily.php?tournament_id=" . $tournament_id . "&id=" . $id . "&round=$p_onround'>" . $t_desc . "</a>";
	}
	
	else {
	$desc_link = "<a href='referee.php?t_id=" . $t_id . "&id=" . $id . "&round=$p_onround'>" . $t_desc . "</a>";
	}
	
} else {
    $desc_link = $t_desc;
    if ($t_status=="Register"){
        $joined_image = "<img src='$base_dir/images/unchecked.png' border='0px' />";
    } else {
        $joined_image = "<img src='$base_dir/images/unchecked.png' border='0px' />";
        $t_link=$t_status;
    }
}


echo "<tr>
    <td bgcolor='$row_color'>$t_start</td>
    <td bgcolor='$row_color'>$desc_link</td>
    <td bgcolor='$row_color'>" . $t_status . " (R" . $t_round . ")</td>
	<td bgcolor='$row_color' style='text-align:center;'>" . $joined_image . "</td>
    </tr>" . $outmessage;

$row_count++;  

$outmessage='';

}	

if ($msg == "registered") {
echo "<script type='text/javascript'>alert('Registration Successful!');</script>";
}

else if ($msg == "cant_validate_user") {
echo "<script type='text/javascript'>alert('There was a problem validating you in our database. Try using a different browser (e.g., iPhone, Safari, Firefox). If you still have trouble, contact us.');</script>";
}

else if ($msg == "disputed") {
echo "<script type='text/javascript'>alert('Your dispute has been recorded. Be sure that you have emailed your dispute details to disputes@wordswithfriends.net.');</script>";
}

else {}

?>

</table>

</div>

</body>
</html>