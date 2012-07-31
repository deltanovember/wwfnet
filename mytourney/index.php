<?php

require_once 'functions.php';

//Set permission level threshold for this page remove if page is good for all levels
$permission_level=1;

include"auth_check_header.php";
require_once "login_config.php";


$msg = "";
$row_count = 0;
if (isset ($_GET['msg'])) {
   $msg = strip_tags(substr($_GET['msg'],0,30));
}

header("Pragma: no-cache");
header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>

<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>My Tournament</title>

<link href="mytourney.css" rel="stylesheet" type="text/css">

</head>

<body topmargin="0">
<div id="wrap" style="width:500px;">

<?php // GET PLAYER'S USER TABLE VALUES AND ASSIGN SESSION VARIABLES
$username_from_cookie = "";
if (isset($_COOKIE[NAME_COOKIE])) {
  $username_from_cookie = $_COOKIE[NAME_COOKIE]; //retrieve contents of cookie
}


$query = "SELECT * FROM users WHERE username='$username_from_cookie'";

// get results
$result = mysql_query($query) or die("Couldn't execute query $query");

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
    $daily_status = $row['daily_status'];
}

// Set appropriate messages based on available profile info
if ($privacy == 'on') {
$privacy_ind = "<span style='color:red; font-weight:bold;'>*</span>";
$privacy_msg = "<p style='color:red';>* - You have chosen to keep this contact information private.</p>";
}

else {
$privacy_ind = "<span style='color:green; font-weight:bold;';>*</span>";
$privacy_msg = "<p style='color:green;';>* - You currently share contact information with your opponent.</p>";
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

    <div id="mymenu"><a href="edit_profile.php">Edit Profile</a> | <a href="logout.php">Log Out</a></div>
<p><span style="color:green; font-size:larger; font-weight:bold;">Hello, <?php echo "$firstname" ?>!</span></p>

<?php echo "
<p>
<strong>Screen Name</strong>: " . $username . "<strong><br />
Email</strong>: $email_sub $privacy_ind<br />" . $has_phone . $has_url .
"<strong>Member Since</strong>: $joined_sub<br />
<strong>Time Zone</strong>: $timezone | <strong>Skill Level</strong>: $skill | <strong>Device</strong>: $device<br />" . $has_hint . $has_comment . $privacy_msg.
"</p>";

// Get Info From Tournament Table

$query = "SELECT * FROM T_CONTROL WHERE `t_id` LIKE 'M20%' AND `t_status` <> 'Archive' AND `t_status` <> 'Complete' ORDER BY `t_start` DESC";
$result=mysql_query($query) or die("Couldn't execute query");
$count=mysql_num_rows($result);

if ($count<1){
}else{
echo "
<table class='mt_table'>
<tr valign='top'>
<th colspan='4' class='td_tourney'>Monthly Tournament</th>
</tr>
<th width='12%'>Opt In</th>
<th width='' style='text-align:left;'>Tournament Title</th>
<th width='18%'>Start Date</th>
<th width=''>Actions</th>
</tr>
";

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $t_id= $row["t_id"];
    $t_start= $row["t_start"];
    $t_desc= $row["t_desc"];
    $t_longdesc= $row["t_longdesc"];
    $t_round= $row["t_round"];
    $t_status= $row["t_status"];
    $tournament_id = $row["record"];

    $color1 = "#ffffff";
    $color2 = "#ebebeb";
    $row_color = ($row_count % 2) ? $color1 : $color2;

    if (strrpos($t_id,"D") > -1){
        $reg_link="<a href='manage_daily.php?tournament_id=" . $tournament_id . "&id=" . $id . "&action=join'>Join Now</a>";
        $rejoin_link="<a href='change_status_public.php?tournament_id=" . $tournament_id . "&action=reinstate'>Re-Join</a>";
    }

    else { $reg_link="<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "&action=join'>Register</a>"; }

    if (strrpos($t_id,"D") > -1){
        $play_link="<a href='manage_daily.php?t_id=" . $t_id . "&id=" . $id . "'>Play</a>";
        $withdraw_link="<a href='change_status_public.php?t_id=" . $t_id . "&action=eliminate'>Withdraw</a>";
    }
    else {
    $play_link="<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "'>Play</a>";
    }

    $outmessage = "";
    $query2 = "SELECT * FROM $t_id WHERE id=$id";

    // get results
    $result2=mysql_query($query2) or die("Could not execute $query2");
    $count=mysql_num_rows($result2);
    if($count<1){
        $p_status= "";
    }

    while ($row= mysql_fetch_array($result2)) {
        $p_onround= $row["onround"];
        $p_status = $row["status"];
        $dispute_link= "<form name='dispute_" . $t_id . "' action='player_dispute.php' method='post'><input type='hidden' name='t_id' value='$t_id' />
        <input type='hidden' name='id' value='$id' />
        <input type='hidden' name='action' value='propose_dispute' />
        <input type='hidden' name='t_desc' value='$t_desc' />
        <a href='javascript:document.dispute_" . $t_id . ".submit();'>Click To<br />Dispute</a>
        </form>";


        if ($p_status == "Eliminated") {
            $outmessage = "<span style='font-size:85%; color:#333333; font-style:italic;'> - you reached Round " . $p_onround . "</span>";
        }
            else {
                $outmessage = "";
            }
        }

    if ($p_status == "Eliminated" && strrpos($t_id,"M") > -1){
        $action = $dispute_link;
    }
    else if ($p_status == "Eliminated" && strrpos($t_id,"D") > -1){
        $action = $rejoin_link;
    }

    else if (strrpos($t_id,"D") > -1 && $p_status != "Active") {
        $action = $reg_link;
    }
else if (strrpos($t_id,"D") > -1 && $p_status == "Active") { $action = $play_link; }
else if ($t_status == "Register" && $p_status == "Active") { $action = $play_link; }
else if ($t_status == "Register" && $p_status != "Active") { $action = $reg_link; }
else if ($p_status == "Active" && $t_status == "In Progress") { 
    $action = $play_link;

    }
else {$action = "CLOSED"; }

$count=mysql_num_rows($result2);
if($count>0) {$joined_image = "<img src='images/checked.png' border='0px' />";}

else if (strrpos($t_id,"D") > -1 && $t_status != "Completed") {  
    $joined_image = "<a href='manage_daily.php?t_id=" . $tournament_id . "&id=" . $id . "&action=join'><img src='images/unchecked.png' border='0px' /></a>"; }

else if ($t_status=="Register") { 
    $joined_image = "<a href='manage.php?t_id=" . $tournament_id . "&id=" . $id . "&action=join'><img src='images/unchecked.png' border='0px' /></a>";}
else { $joined_image = "&mdash;";}

echo "<tr valign='top'>
	<td bgcolor='$row_color' style='text-align:left; padding-left:20px;'>" . $joined_image . "</td>
    <td bgcolor='$row_color'><strong>" . $t_desc . "</strong> (R" . $t_round . ")<br /><span style='font-size:11px;color:green;'>" . $t_longdesc . "</span>";
if ($t_longdesc != "") { echo "<br />"; }
echo "Status: ";
	if ($t_status == 'Open'){echo "<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "&action=join'>Register</a>";}
else {	echo $t_status; }
	echo " " . $outmessage . "</td>
    <td bgcolor='$row_color' style='text-align:center;'>$t_start</td>
    <td bgcolor='$row_color' style='text-align:center;'>" . $action . "</td>
    </tr>";

$row_count++;

$outmessage='';

}

echo "</table><br /><br />";
}
?>

<table class='mt_table'>
<tr valign="top">
<th colspan='4' class="td_tourney">Daily Challenge</th>
</tr>
<th width="12%">Opt In</th>
<th width="" style="text-align:left;">Tournament Title</th>
<th width="18%">Start Date</th>
<th width="">Actions</th>
</tr>

<?php // Get Info From Tournament Table

$query = "SELECT * FROM T_CONTROL WHERE `t_id` LIKE 'D20%' AND `t_status` <> 'Archive' AND `t_status` <> 'Complete' ORDER BY `t_start` DESC";

// get results
$result = mysql_query($query) or die("Couldn't execute $query");

   // $round = get_daily_round();
   // $query2 = "SELECT * FROM `daily`
   //         WHERE user_id='$id' and tournament_id=$tournament_id order by round desc limit 1";

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $t_id= $row["t_id"];
    $t_start= $row["t_start"];
    $t_desc= $row["t_desc"];
    $t_longdesc= $row["t_longdesc"];
    $t_round= $row["t_round"];
    $t_status= $row["t_status"];
    $tournament_id = $row["record"];

    $color1 = "#ffffff";
    $color2 = "#ebebeb";
    
    $max_query = "SELECT max(round) as max_round FROM `daily` WHERE tournament_id = $tournament_id";
    $max_result = mysql_query($max_query);
    
    $max_round = 0;

    while ($row= mysql_fetch_array($max_result)) {
        $max_round = $row["max_round"];
    }

    $match_id = 0;

    $match_query = "SELECT * FROM daily WHERE tournament_id = $tournament_id and
            round=$max_round and user_id=$id";
    $match_result = mysql_query($match_query);
    while ($row= mysql_fetch_array($match_result)) {
        $match_id = $row["match_id"];
    }

    if (!isset ($row_count)) {
        $row_count = 0;
    }
    $row_color = ($row_count % 2) ? $color1 : $color2;

    if (strrpos($t_id,"D") > -1){
        $reg_link="<a href='manage_daily.php?tournament_id=" . $tournament_id . "&id=" . $id . "&action=join'>Join Now</a>";
        $rejoin_link="<a href='change_status_public.php?tournament_id=" . $tournament_id . "&action=reinstate'>Re-Join</a>";
    }

    else { 
        $reg_link="<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "&action=join'>Register</a>";

    }

    if (strrpos($t_id,"D") > -1){
        $play_link="<a href='manage_daily.php?tournament_id=" . $tournament_id . "&id=" . $id . "&round=$max_round&action=play'>Play</a>";
    }
    else {
        $play_link="<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "&action=play'>Play</a>";
    }


    $query2 = "SELECT * FROM `daily` WHERE user_id='$id' 
            and tournament_id = $tournament_id order by round desc limit 1";
    // get results
    $result2=mysql_query($query2) or die("Couldn't execute $query2");
    $count=mysql_num_rows($result2);
    if($count<1){
        $p_status= "";
        $p_onround= "";
    }

    while ($row= mysql_fetch_array($result2)) {

        $p_onround= $row["round"];
        if (strrpos($t_id,"D") > -1) {
            $p_status= "Eliminated";
        }
        
        if ($daily_status == "Eliminated" || $daily_status == "Waiting") {
            $p_status= $daily_status;
        }

        else if ($p_onround == $max_round) {
            $p_status = "Active";
        }
        $dispute_link= "<form name='dispute_" . $t_id . "' action='player_dispute.php' method='post'><input type='hidden' name='t_id' value='$t_id' />
        <input type='hidden' name='id' value='$id' />
        <input type='hidden' name='action' value='propose_dispute' />
        <input type='hidden' name='t_desc' value='$t_desc' />
        <a href='javascript:document.dispute_" . $t_id . ".submit();'>Dispute</a>
        </form>";


        if ($p_status == "Eliminated") {
            $outmessage = "<span style='font-size:85%; color:#333333; font-style:italic;'> - you reached Round " . $p_onround . "</span>";
        }
        else {$outmessage = "";}
        }

        if ($p_status == "Eliminated" && strrpos($t_id,"M") > -1){
            $action = $rejoin_link;
        }
        else if (strrpos($t_id,"D") > -1 && $p_status == "Waiting") {
            $action = "Please wait for draw";
        }
        else if ($p_status == "Eliminated" && strrpos($t_id,"D") > -1){
            $action = $rejoin_link;
        }
        else if (strrpos($t_id,"D") > -1 && $p_status != "Active") {
            $action = $reg_link;
        }
        else if (strrpos($t_id,"D") > -1 && $p_status == "Active") {
            $action = $play_link;

        }
        else if ($t_status == "Register" && $p_status == "Active") { 
            $action = $play_link;

        }
        else if ($t_status == "Register" && $p_status != "Active") { 
            $action = $reg_link;

        }
        else if ($p_status == "Active" && $t_status == "In Progress") { $action = $play_link; }
        else {$action = "N/A"; }

        $count=mysql_num_rows($result2);
        if($count>0) {
            $joined_image = "<img src='images/checked.png' border='0px' />";
        }

        else if (strrpos($t_id,"D") > -1 && $t_status != "Completed") {  $joined_image = "<a href='manage_daily.php?tournament_id=" . $tournament_id . "&id=" . $id . "&action=join'><img src='images/unchecked.png' border='0px' /></a>"; }

        else if ($t_status=="Register") { $joined_image = "<a href='manage.php?tournament_id=" . $tournament_id . "&id=" . $id . "&action=join'><img src='images/unchecked.png' border='0px' /></a>";}
        else { $joined_image = "&mdash;";}

        echo "<tr valign='top'>
                <td bgcolor='$row_color' style='text-align:left; padding-left:20px;'>" . $joined_image . "</td>
            <td bgcolor='$row_color'><strong>" . $t_desc . "</strong> (R" . $t_round . ")<br /><span style='font-size:11px;color:green;'>" . $t_longdesc . "</span>";
        if ($t_longdesc != "") { echo "<br />"; }
        echo "Status: ";
                if ($t_status == 'Open'){echo "<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "&action=join'>Register</a>";}
        else {	echo $t_status; }
        if (isset ($outmessage)) {
            echo " " . $outmessage;
        }


        if ($p_status == "Active"){
            echo "<div style='float:right;'><a href='change_status_public.php?tournament_id=" . $tournament_id . "&action=eliminate' onclick=\"return confirm('You will not be able to rejoin for 24 hours')\">Take a Break!</a></div>";
        }
        else {
                echo "<div style='float:right;'><a href='manage_daily.php?tournament_id=" . $tournament_id . "&id=" . $id . "&action=updatescores'>Update scores without rejoining</a></div>";
                }

        echo "</td>
            <td bgcolor='$row_color' style='text-align:center;'>$t_start</td>
            <td bgcolor='$row_color' style='text-align:center;'>" . $action . "</td>
            </tr>";

        if (isset ($row_count)) {
            $row_count++;
        }
        else {
           $row_count = 0;
        }


        $outmessage='';

    }

?>


</table>
<br /><br />











<table class='mt_table'>
<tr valign="top">
<th colspan='3' class="td_tourney" style="background-color:#BBBBBB">Past Tournaments</th>
</tr>
<th width="" style="text-align:left;">Tournament Title</th>
<th width="18%">Start Date</th>
<th width="">Actions</th>
</tr>
<?php // Get Info From Tournament Table

$query = "SELECT * FROM T_CONTROL WHERE `t_status` = 'Complete' ORDER BY `t_start` DESC";

// get results
$result = mysql_query($query) or die("Couldn't execute query 2");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $t_id= $row["t_id"];
    $t_start= $row["t_start"];
    $t_desc= $row["t_desc"];
    $t_longdesc= $row["t_longdesc"];
    $t_round= $row["t_round"];
    $t_status= $row["t_status"];
    $tournament_id = $row["record"];

    $color1 = "#ffffff";
    $color2 = "#ebebeb";
    $row_color = ($row_count % 2) ? $color1 : $color2;

    if (strrpos($t_id,"D") > -1){
        $reg_link="<a href='manage_daily.php?t_id=" . $t_id . "&id=" . $id . "&action=join'>Join Now</a>";
        $rejoin_link="<a href='change_status_public.php?t_id=" . $t_id . "&action=reinstate'>Re-Join</a>";
    }

    else { $reg_link="<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "&action=join'>Register</a>"; }

    if (strrpos($t_id,"D") > -1){
        $play_link="<a href='manage_daily.php?t_id=" . $t_id . "&id=" . $id . "'>Play</a><br /><br />(<a href='change_status_public.php?t_id=" . $t_id . "&action=eliminate'>Withdraw</a>)";
    }
    else {
        $play_link="<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "'>Play</a>";
    }

    $daily = preg_match("/D2/", $t_id);
    $query2 = "";
    if ($daily) {
        $query2 = "SELECT round as onround FROM daily WHERE user_id='$id' and tournament_id=$tournament_id order by onround desc limit 1";
    }
    else {
        $query2 = "SELECT * FROM `$t_id` WHERE id='$id'";
    }
    
    // get results
    $result2=mysql_query($query2) or die("Could not execute past $query2");
    $count=mysql_num_rows($result2);
    if($count<1){
        $p_status= "";
        $p_onround= "";

    }

    while ($row= mysql_fetch_array($result2)) {
        $p_status= "Eliminated";
        if (!$daily) {
            $p_status = $row["status"];
        }

        $p_onround= $row["onround"];
        $dispute_link= "<form name='dispute_" . $t_id . "' action='player_dispute.php' method='post'><input type='hidden' name='t_id' value='$t_id' />
        <input type='hidden' name='id' value='$id' />
        <input type='hidden' name='action' value='propose_dispute' />
        <input type='hidden' name='t_desc' value='$t_desc' />
        <a href='javascript:document.dispute_" . $t_id . ".submit();'>Dispute</a>
        </form>";


        if ($p_status == "Eliminated") {
            $outmessage = "<span style='font-size:85%; color:#333333; font-style:italic;'> - you reached Round " . $p_onround . "</span>"; }
                else {$outmessage = "";}
        }

    $action = "<a href='results_index.php'>Results</a>";

    $count=mysql_num_rows($result2);
    if($count>0) {$joined_image = "<img src='images/checked.png' border='0px' />";}

    else if (strrpos($t_id,"D") > -1 && $t_status != "Completed") {  $joined_image = "<a href='manage_daily.php?t_id=" . $t_id . "&id=" . $id . "&action=join'><img src='images/unchecked.png' border='0px' /></a>"; }

    else if ($t_status=="Register") { $joined_image = "<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "&action=join'><img src='images/unchecked.png' border='0px' /></a>";}
    else { $joined_image = "&mdash;";}

    echo "<tr valign='top'>
        <td bgcolor='$row_color'><strong>" . $t_desc . "</strong> (R" . $t_round . ")<br /><span style='font-size:11px;color:green;'>" . $t_longdesc . "</span>";
    if ($t_longdesc != "") { echo "<br />"; }
    echo "Status: ";
            if ($t_status == 'Open'){echo "<a href='manage.php?t_id=" . $t_id . "&id=" . $id . "&action=join'>Register</a>";}
    else {	echo $t_status; }
            echo " " . $outmessage . "</td>
        <td bgcolor='$row_color' style='text-align:center;'>$t_start</td>
        <td bgcolor='$row_color' style='text-align:center;'>" . $action . "</td>
        </tr>";

    $row_count++;

    $outmessage='';

    }


?>

</table>

<?php
if ($msg == "registered") {
echo "<script type='text/javascript'>alert('Registration Successful!');</script>";
}

else if ($msg == "registration_period_no_player") {
echo "<script type='text/javascript'>alert('You are registered; however, you cannot play until the Tournament Start Date');</script>";
}

else if ($msg == "cant_validate_user") {
echo "<script type='text/javascript'>alert('There was a problem validating you in our database. If you still have trouble, contact us.');</script>";
}

else if ($msg == "disputed") {
echo "<script type='text/javascript'>alert('Your dispute has been recorded. Be sure that you have emailed your dispute details to disputes@wordswithfriends.net.');</script>";
}

else {}


?>


<div style="margin-top:50px; text-align:right;"><a style="text-decoration:hidden; color:#EEEEEE;" href="http://wordswithfriends.net/mytourney/admin_index.php?" target="_self" />Moderator</a></div>
</div>

</body>
</html>