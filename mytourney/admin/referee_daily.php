<?php
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

ini_set('display_errors', 1);
error_reporting(E_ALL);

include"../auth_check_header.php";
require_once 'functions.php';

$tournament_id = strip_tags(substr($_GET['tournament_id'],0,20));
$proposed_id = strip_tags(substr($_GET['id'],0,10));
$round = strip_tags(substr($_GET['round'],0,2));
$rslt_message = "";


$query = "SELECT * FROM users WHERE id='$proposed_id'"; 
$result = mysql_query($query);
$count=mysql_num_rows($result);
if($count>0){} else {
header("Location: admin_index.php?msg=cant_validate_user");
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

$p1_rslt= "";
$p1_cmt = "";
$p1_g1 = "";
$p1_admcmt = "";
$opponent = "";
$status = "";
$lastround = 0;
$player = "";
$admcmt = "";
$p_msg = "";

$p2_msg = "";
$p2_g1 = "";
$p2_rslt= "";
$p2_cmt = "";
$p2_admcmt = "";

// declare variables

$opponent_id = get_daily_opponent($id, $round, $tournament_id);
$opponent_name = "";
if ($opponent_id) {
    $opponent = get_user_from_id($opponent_id);
    $opponent_name = $opponent->get_screen_name();
}
$user = get_user_from_id($id);
$player = $user->get_screen_name();


// Get Info From Control Table

$query = "SELECT * FROM T_CONTROL WHERE record='$tournament_id'";

$result = mysql_query($query) or die("Couldn't execute $query");
while ($row= mysql_fetch_array($result)) {
$t_id= $row["t_id"];
$t_start= $row["t_start"];
$t_short_start= substr($t_start, 5, 10);
$t_desc= $row["t_desc"];
$t_round= $row["t_round"];
$t_status= $row["t_status"];
$t_champ= $row["t_champ"];

}
			
// Find out if player is already registered for this tourney
$query = "SELECT * FROM daily WHERE user_id='$id' and round=$round and tournament_id=$tournament_id";

$result = mysql_query($query) or die("Couldn't execute $query");
$count=mysql_num_rows($result);
if($count>0){
    $registered = "yes";
} else {
    $registered = "no";
}

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $lastround= $row["round"];
    $admcmt= $row["admin_comment"];
    $p_msg= $row["private_message"];
    $p1_g1= $row["score"];
    $p1_rslt= $row["result"];
    $p1_cmt= $row["comment"];
    $p1_admcmt= $row["admin_comment"];

}
 		       // print $query;die();
//Update scores if needed

if (isset ($_POST['updatescore']) &&
        ($_POST['updatescore']) == "true") {

    $p1_g1 = strip_tags(substr($_POST['p1_g1'],0,3));
    $p2_g1 = strip_tags(substr($_POST['p2_g1'],0,3));
    $winner = strip_tags(substr($_POST['winner'],0,50));
    $result = strip_tags(substr($_POST['result'],0,30));
    $rating = strip_tags(substr($_POST['rating'],0,1));
    $new_cmt = mysql_escape_string(strip_tags(substr($_POST['new_cmt'],0,140)));
    $adm_cmt = mysql_escape_string(strip_tags(substr($_POST['adm_cmt'],0,140)));

    $query = "UPDATE daily SET score = $p1_g1
                WHERE tournament_id = $tournament_id and round=$round and user_id = $id";

    // save the info to the database
    $results = mysql_query( $query );

    $query = "UPDATE daily SET score = $p2_g1
                WHERE tournament_id = $tournament_id and round=$round and user_id = $opponent_id";

    // save the info to the database
    $results = mysql_query( $query );


if ($rating > -1) {
    $query = "UPDATE daily SET rating = $rating
        WHERE tournament_id = $tournament_id and round=$round and user_id = $opponent_id";

    // save the info to the database
    $results = mysql_query( $query );
}

if ($new_cmt > -1) {
    $query = "UPDATE daily SET comment='$new_cmt'
        WHERE tournament_id = $tournament_id and round=$round and user_id = $id";

    // save the info to the database
    $results = mysql_query($query) or die(mysql_error());
}

if ($adm_cmt > -1) {
    $query = "UPDATE daily SET admin_comment='$adm_cmt'
        WHERE tournament_id = $tournament_id and round=$round and user_id = $id";

    // save the info to the database
    $results = mysql_query( $query );
}


// No-winner Use Case //


if ($winner == "no_winner") {

    $query = "UPDATE `daily` SET result = ''
    WHERE tournament_id = $tournament_id and round=$round and user_id = $id";

    // save the info to the database
    $results = mysql_query( $query );


    $query = "UPDATE `daily` SET result = ''
    WHERE tournament_id = $tournament_id and round=$round and user_id = $opponent_id";

    // save the info to the database
    $results = mysql_query( $query );


}

// Player Wins Use Case // 

else if ($winner == $player ||
        $winner == $opponent_name) {

        $id1 = $id;
        if ($winner == $opponent_name) {
            $id1 = $opponent_id;
        }
        $id2 = $opponent_id;
        if ($winner == $opponent_name) {
            $id2 = $id;
        }

        $query = "UPDATE `daily` SET result = 'Won'
            WHERE tournament_id = $tournament_id and round=$round and user_id = $id1";

        // save the info to the database
        $results = mysql_query( $query );

        if ($result != "") {
            $query = "UPDATE `daily` SET result='$result'
                WHERE tournament_id = $tournament_id and round=$round and user_id = $id2";
        }

        else {
            $query = "UPDATE `daily` SET result='Lost'
                WHERE tournament_id = $tournament_id and round=$round and user_id = $id2";
        }

        // save the info to the database
        $results = mysql_query( $query );
}


// Default Use Case - meaning nothing but blanks submitted // 

else {
    $query = "UPDATE `daily SET `result = NULL
                WHERE tournament_id = $tournament_id and round=$round and user_id = $id";

    // save the info to the database
    $results = mysql_query( $query );

}


// Retrieve Updated Values
$query = "SELECT * FROM daily WHERE user_id='$id' and round=$round and tournament_id=$tournament_id";
$result = mysql_query($query) or die("Couldn't execute query");




 // now you can display the results returned
while ($row= mysql_fetch_array($result)) {

        $p_msg= $row["private_message"];
        $p1_g1= $row["score"];
        $p1_rslt= $row["result"];
        $p1_cmt= $row["comment"];
        $p1_admcmt= $row["admin_comment"];


    }

}

if ($p1_rslt == 'Won') {
    $rslt_message = "<p style='color:green;'><b>" . $player . " won Round in " . $round . "</b></p>";

}
elseif ($p1_rslt == 'Lost' || $p1_rslt == 'Forfeited' || $p1_rslt == 'No-Show' || $p1_rslt == 'Incomplete') {$rslt_message = "<p><span style='font-weight:bold; color:red;'>" . $player . " lost Round " . $round . "</span></p>";}
else {}

// GET OPPONENT'S USER TABLE VALUES AND ASSIGN SESSION VARIABLES
	
$query = "SELECT * FROM users WHERE username='$opponent_name'";
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

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Manage My Tournament</title>

<style type="text/css">
#wrap {font-family:Arial, Helvetica, sans-serif; font-size:13px;}
#mymenu {float:right;}
#roundbox {text-align:center; background-color:#FFFF00; padding:10px;}
.td_tourney {background-color:lightblue; font-weight:bold; font-size:larger;}
</style>

</head>

<body topmargin="0">
<div id="wrap" style="width:500px;">
<div id="mymenu"><a href="admin_index.php">Admin Index</a> | <a href="logout.php">Log Out</a></div>      
<p><span style="color:green; font-size:larger; font-weight:bold;">Moderating for <?php echo "$username" ?></span></p>
<table style="border-collapse:collapse; border:none; width:100%; margin:0px;"><tr>
<td>
<h3><?php echo $t_desc ?></h3>

<p><strong>Start Date</strong>: <?php echo $t_start ?><br />
<strong>Tourney Status</strong>: <?php echo $t_status ?> | Round <?php echo $t_round ?><br />
<strong>Player Status</strong>: 
<?php
if ($status == "Eliminated") { echo "<span style='color:red; font-weight:bold'>" . $status . "</span> in Round " . $lastround . "<br /><a href='change_status.php?t_id=$t_id&id=$id&action=reinstate'><img src='images/green_arrow_up.png' border='0px' /></a> Resurrect into Round " . $t_round . "?";}
else { echo "<span style='color:green; font-weight:bold;'>" . $status . "</span> in Round " . $lastround . "<br /><a href='change_status.php?t_id=$t_id&id=$id&action=eliminate'><img src='$base_dir/images/red_x.png' border='0px' /></a> Deactivate?";}
if ($rslt_message != "") {echo $rslt_message ;}
?>
</p>
</td><td width="50%" id="roundbox">
<span style="font-size:170%; font-weight:bold;">Round <?php echo $round ?></span><br />
<span style="font-size:larger; color:green; font-weight:bold;"><?php echo $player; ?></span><br />
vs. <br />

<?php
if ($opponent_name != ""){
echo "<span style='font-size:larger;'><a href='referee.php?round=" . $round . "&t_id=" . $t_id . "&id=" . $id1 . "'>" . $opponent_name . "</a></span>";
}

else if ($status == "Active") {
echo "<span style='font-size:larger; font-weight:bold;'>[<a href='match_players.php?id=" . $id . "&t_id=" . $t_id . "&round=" . $round . "'>ASSIGN OPPONENT</a>]</span>";
}

else {
echo "<span style='color:red; font-size:larger; font-weight:bold;'>[NO OPPONENT]</span>";
}

?>
<br /><br />
<?php

$prev_round = $round - 1;
$next_round = $round + 1;
$max_round = get_daily_round();

if ($prev_round > 0 and $round > 0) {
    echo "<a href='referee_daily.php?tournament_id=$tournament_id &id=$id&round=$prev_round'>< Update Previous Round</a>";

}
if ($max_round > $round) {
        echo "<br /><br /><a href='referee_daily.php?tournament_id=$tournament_id &id=$id&round=$next_round'>Go To Next Round ></a>";
}


?>
</td></tr>
</table>



			
<?php

// GET THE OPPONENT'S CURRENT ROUND DATA

$query = "SELECT * FROM daily WHERE user_id='$opponent_id' and round=$round and tournament_id=$tournament_id";

// get results
$result = mysql_query($query) or die("Couldn't execute $query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {
    $p2_g1= $row["score"];
    $p2_rslt= $row["result"];
    $p2_rated= $row["rated"];
    $p2_cmt= $row["comment"];
    $p2_admcmt= $row["admin_comment"];
    $p2_msg= $row["private_message"];
}
			
// Determine whatever?
if ($admcmt != "") {echo "<p><b>Moderator says:</b> $admcmt</p>";}

?>

<h3 style='background-color:#CCCCCC; border-top:1px solid #000000; border-bottom:1px solid #000000; padding:5px;'>Player-to-Player Messages</h3>

<?php
// Private Messages
if (isset ($_POST['send_pmessage']) &&
        ($_POST['send_pmessage']) == "true") {

    $private_msg = strip_tags(substr($_POST['private_msg'],0,140));
    $query = "UPDATE `" . $t_id . "` SET `r" . $round . "_pmsg` = '" . $private_msg . "' WHERE `player` = '" . $opponent . "'; ";
    // save the info to the database
    $results = mysql_query( $query );

}

if ($p2_msg != "") {
    echo "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>$opponent_name</span> said: $p2_msg</p>";
}	
	
if ($p_msg != "") {
    echo "<form action='referee.php?t_id=" . $t_id . "&id=" . $id . "&round=" . $round . "' method='post' name='form' id='form'><p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>" . $player . "</span> said: " . $p_msg . "
    <input type='hidden' name='private_msg'></textarea><input type='hidden' name='send_pmessage' value='true'><input name='submit' type='submit' value='Remove Comment' /></form></p>";
}

if ($p_msg == "" && $p2_msg == "") {
    echo "<p style='background-color:lightyellow; padding:5px;'>There are no player-to-player messages.</p>";
}

?>

<h3 style='background-color:#CCCCCC; border-top:1px solid #000000; border-bottom:1px solid #000000; padding:5px;'>Scores and Public Comments</h3>

<form action="referee_daily.php?tournament_id=<?php echo $tournament_id; ?>&id=<?php echo $id ?>&round=<?php echo $round ?>" method="post" name="form" id="form">

<table cellpadding="3" width="500px" cellspacing="2" style="border-collapse:collapse; border:1px solid #CCC;">

<tr><td>&nbsp;</td><td style="font-weight:bold;"><?php echo $player; ?></td><td style="font-weight:bold;"><?php echo $opponent_name; ?></td></tr>
<tr>
<td><b>Game 1</b></td>
<td><input type = "text" value="<?php echo $p1_g1; ?>" name="p1_g1" width="5" /></td>
<td><input type = "text" value="<?php echo $p2_g1; ?>" name="p2_g1" width="5" /></td>
</tr>
<tr><td colspan='3'>
<?php

if ($p2_cmt != "") {
    echo "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>" . $opponent_name . "</span> wrote: $p2_cmt</p>";
}

if ($p2_admcmt != "" && $p2_admcmt != "Welcome to the tournament!") {
    echo "<p style='background-color:lightyellow; padding:5px;'><span style='color:red; font-weight:bold;'>Moderator</span> to " . $opponent_name . ": $p2_admcmt</p>";
}

if ($p1_cmt != "") {
    echo "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>" . $player . "</span> wrote: $p1_cmt</p>";
}

if ($p1_admcmt != "" && $p1_admcmt != "Welcome to the tournament!") {
       echo "<p style='background-color:lightyellow; padding:5px;'><span style='color:red; font-weight:bold;'>Moderator</span> to " . $player . ": $p1_admcmt</p>";
}

?>
</td></tr>
<tr valign="top"><td colspan="3"><strong>Edit <?php echo $player; ?>'s public comment:</strong><br />
<textarea cols="55" rows="1" name="new_cmt"><?php echo $p1_cmt ?></textarea></td></tr>
<tr valign="top"><td colspan="3"><strong>Update Moderator's public comment to <?php echo $player; ?>:</strong>
<textarea cols="55" rows="1" name="adm_cmt"><?php echo $p1_admcmt ?></textarea><div style='text-align:center; padding-top:10px;'><input type="submit" value="Update Comments" name="update" /></div></td></tr>
</table>
<h3 style='background-color:#CCCCCC; border-top:1px solid #000000; border-bottom:1px solid #000000; padding:5px;'>Final Outcome</h3>


<table cellpadding="3" width="500px" cellspacing="2" style="border-collapse:collapse; border:1px solid #CCC;">

<tr valign="top"><td colspan="2" align="right"><p><b>Winner of Round <?php echo $round; ?>:</b></p></td><td><p>
<input type="radio" id="[NO WINNER]" <?php if ($p1_rslt == NULL) {echo 'checked="checked"';} ?> name="winner" value="no_winner" />[NO WINNER]<br />
<input type="radio" id="<?php echo $player; ?>" <?php if ($p1_rslt == 'Won') {echo 'checked="checked"';} ?> name="winner" value="<?php echo $player; ?>" /><?php echo $player; ?><br />
<input type="radio" id="<?php echo $opponent_name; ?>" <?php if ($p2_rslt == 'Won') {echo 'checked="checked"';} ?> name="winner" value="<?php echo $opponent_name; ?>" /><?php echo $opponent_name; ?>
</p>
</td></tr> 
<tr valign="top"><td colspan="2" align="right"><p><b>Round <?php echo $round; ?> outcome:</b></p></td><td><p><select name="result" >
  <option value="">--Make Selection--</option>
  <option <?php if ($p1_rslt == 'Lost' || $p2_rslt == 'Lost') {echo 'selected';} ?> value="Lost">Round Completed</option>
  <option <?php if ($p1_rslt == 'No-Show' || $p2_rslt == 'No-Show') {echo 'selected';} ?> value="No-Show">Player No-Show</option>
  <option <?php if ($p1_rslt == 'Forfeited' || $p2_rslt == 'Forfeited') {echo 'selected';} ?> value="Forfeited">Player Forfeited</option>
  <option <?php if ($p1_rslt == 'Incomplete' || $p2_rslt == 'Incomplete') {echo 'selected';} ?> value="Incomplete">Time Ran Out</option>
</select></p></td></tr>
<tr valign="top"><td colspan="2" align="right"><p><b><?php echo $player . " rated " . $opponent_name ?> as:<br /></b></p></td><td><p><select name="rating" >
  <option value="">--Make Selection--</option>
  <option <?php if ($p2_rated == '1') {echo 'selected';} ?> value="1">1 - Terrible</option>
  <option <?php if ($p2_rated == '2') {echo 'selected';} ?> value="2">2 - Poor</option>
  <option <?php if ($p2_rated == '3') {echo 'selected';} ?> value="3">3 - Just OK</option>
  <option <?php if ($p2_rated == '4') {echo 'selected';} ?> value="4">4 - Very Good</option>
  <option <?php if ($p2_rated == '5') {echo 'selected';} ?> value="5">5 - Excellent</option>
</select><br />

</td></tr>

</table>
<input type="hidden" value="true" name="updatescore" />
<div style="text-align:center; margin-top:10px;"><input type="submit" value="Update" name="update" /></div>
</form>
</div> 
</div>
</body>
</html>