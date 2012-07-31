<?php
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=1;

include"auth_check_header.php";
require_once 'functions.php';
if (!$debugmode) {
 require_once 'email_smtp.php';
}

$proposed_id = strip_tags(substr($_GET['id'],0,10));

$action = "";
if (isset ($_GET['action'])) {
  $action = strip_tags(substr($_GET['action'],0,20));
}
$round = 0;
$max_round = 0;
if (isset ($_GET['round'])) {
  $round = strip_tags(substr($_GET['round'],0,20));
}

$tournament_id = 0;
if (isset ($_GET['tournament_id'])) {
  $tournament_id = strip_tags(substr($_GET['tournament_id'],0,20));
}


$query = "SELECT * FROM users WHERE username='$username_from_cookie' AND id='$proposed_id'"; 
$count=mysql_num_rows($result);
if($count>0){} else {
    header("Location: index.php?msg=cant_validate_user");
}

$user = get_user_from_id($proposed_id);
$id = $user->get_id();
$email = $user->get_email();
$player = $user->get_screen_name();



// Get Info From Control Table
$tournament = get_tournament_from_record($tournament_id);

$t_id= $tournament->get_id();
$t_start= $tournament->get_start();
$t_short_start= $tournament->get_short_start();
$t_desc= $tournament->get_description();
if (!$round) {
    $round= $tournament->get_round();
}
$max_round = $tournament->get_round();
$t_status= $tournament->get_status();
$t_champ= $tournament->get_champion();
$tournament_id = $tournament->get_record();
		
// Find out if player is already registered for this tourney
$query = "SELECT * FROM daily WHERE user_id='$id' and tournament_id=$tournament_id";

$result = mysql_query($query) or die("Couldn't execute $query");
$count=mysql_num_rows($result);
if($count>0){
    $registered = "yes";
} else {
    $registered = "no";
}
// If not registered and requesting to join, then insert
if ($registered == "no" && $action == "join"){
    
    join_daily($id);
    die();

}

// if tourney is not in progress, send back to index
if ($t_status != "In Progress" && $t_status != "Register"){ 
    header("Location: index.php?");

}
 
// declare variables

$opponent_id = get_daily_opponent($proposed_id, $round, $tournament_id);
$opponent_name = "";

if ($opponent_id) {
    $opponent = get_user_from_id($opponent_id);
    $opponent_name = $opponent->get_screen_name();
    
}

$opponent_link = "<a href=\"player_hist_daily.php?id=$opponent_id&tournament_id=$tournament_id\">$opponent_name</a>";

$query = "SELECT * FROM daily WHERE tournament_id='$tournament_id' and user_id=$id and round=$round";
$result = mysql_query($query) or die("Couldn't execute $query");

$admcmt = "";
$p_msg = "";
$p1_g1 = "";
$p1_cmt = "";
$p2_g1 = "";
$p2_cmt = "";
$p2_msg = "";
$p2_rslt = "";
$p2_rated = "";
$p2_cheated = "";

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $admcmt= $row["admin_comment"];
    $p_msg= $row["private_message"];
    $p1_g1= $row["score"];
    $p1_rslt= $row["result"];
    $p1_cmt= $row["comment"];


}

// Find out if player is already registered for this round
$round_query = "SELECT * FROM daily WHERE user_id='$id' and tournament_id=$tournament_id and
        round=$round";

$result = mysql_query($round_query) or die("Couldn't execute $round_query");
$count=mysql_num_rows($result);
$joined_round = 0;
if($count>0){
    $joined_round = 1;
}

// See if an opponent has been assigned for this round. If not, move to pick player page

if ($opponent_name != ""){
}
else if ($action == "play" &&
        !$joined_round) {
    header("Location: pick_daily_player.php?t_id=" . $t_id . "&id=" . $id);
}

//Update scores if needed
$time = time();
if (isset($_POST['updatescore']) && ($_POST['updatescore']) == "true") {
    
    $p1_g1 = strip_tags(substr($_POST['p1_g1'],0,3));
    $p2_g1 = strip_tags(substr($_POST['p2_g1'],0,3));
    $winner = strip_tags(substr($_POST['winner'],0,50));
    $result = strip_tags(substr($_POST['result'],0,30));
    $rating = strip_tags(substr($_POST['rating'],0,1));
    $honesty = strip_tags(substr($_POST['honesty'],0,1));
    $new_cmt = mysql_escape_string(strip_tags(substr($_POST['new_cmt'],0,140)));

    $honesty_sql = " ";
    if ($honesty > 0) {
        //print $honesty; die();
        $honesty_sql = ", cheated=$honesty ";
    }

    $query = "UPDATE daily set score=$p1_g1
            WHERE tournament_id = $tournament_id and round=$round and user_id = $id";


    // save the info to the database
    $results = mysql_query( $query );

    $query = "UPDATE daily set score=$p2_g1 $honesty_sql
            WHERE tournament_id = $tournament_id and round=$round and user_id = $opponent_id";

    // save the info to the database
    $results = mysql_query( $query ) or die(mysql_error().$query);


    if ($rating > -1) {
        $query = "UPDATE `daily` SET rated=$rating
            WHERE tournament_id = $tournament_id and round=$round and user_id = $opponent_id";

        // save the info to the database
        $results = mysql_query( $query );
    }

    if ($new_cmt > -1) {
        $query = "UPDATE `daily` SET comment = '$new_cmt'
            WHERE tournament_id = $tournament_id and round=$round and user_id = $id";

        // save the info to the database
        $results = mysql_query( $query );
    }


    if ($winner == $player ||
            $winner == $opponent_name) {

        $id1 = $id;
        if ($winner == $opponent_name) {
            $id1 = $opponent_id;
        }
        $id2 = $opponent_id;
        if ($winner == $opponent_name) {
            $id2 = $id;
        }

        $query = "UPDATE `daily` SET result = 'Won' , modified = $time
            WHERE tournament_id = $tournament_id and round=$round and user_id = $id1";

        // update ratings for winner
        //update_daily_rating($tournament_id, $round, $user_id);

        // save the info to the database
        $results = mysql_query( $query );

        if ($result != "") {
            $query = "UPDATE `daily` SET result='$result' , modified = $time
                WHERE tournament_id = $tournament_id and round=$round and user_id = $id2";
        }

        else {
            $query = "UPDATE `daily` SET result='Lost' , modified = $time
                WHERE tournament_id = $tournament_id and round=$round and user_id = $id2";
        }

        // save the info to the database
        $results = mysql_query( $query );

    }
    
    // Default Use Case - meaning nothing but blanks submitted //

    else {

        // in progress to stay daily draw
        if ('In Progress' == $result) {
            $result = "'$result'";
        }
        else {
            $result = "NULL";
        }
        
        $query = "UPDATE daily SET result = $result , modified = $time
                        WHERE tournament_id = $tournament_id and round=$round and user_id = $id";

      //  print $query;die();

        // save the info to the database
        $results = mysql_query( $query );

    }

// Retrieve Updated Values
$query = "SELECT * FROM daily WHERE user_id='$id' and tournament_id = $tournament_id and round=$round ";

$result = mysql_query($query) or die("Couldn't execute $query");

    // now you can display the results returned
    while ($row= mysql_fetch_array($result)) {

       // $record= $row["record"];
       // $player= $row["player"];
       // $status= $row["status"];
        $admcmt= $row["admin_comment"];
       // $opponent_name= $row["r" . $round . "_vs"];
        $p_msg= $row["private_message"];
        $p1_g1= $row["score"];
        $p1_rslt= $row["result"];
        $p1_cmt= $row["comment"];

    }

}

if (!isset ($p1_rslt)) {
  $p1_rslt = "";
}


if ($p1_rslt == 'Won') {
    $rslt_message = "<p style='color:green;'><b>YOU ARE THE WINNER FOR ROUND " . $round . "! Please wait for the next round to begin.</b></p>";}
elseif ($p1_rslt == 'Lost' || $p1_rslt == 'Forfeited' || $p1_rslt == 'No-Show' || $p1_rslt == 'Incomplete') {
    $rslt_message = "<p><span style='font-weight:bold; color:red;'>You lost in Round " . $round . "</span></p>";}
else {

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

<script type="text/javascript">
    function updateWinners() {

        var score1 = parseInt(document.dailyresults.p1_g1.value);
        var score2 = parseInt(document.dailyresults.p2_g1.value);
        
        if (score1 > score2) {
            //alert(score1 + ' 1 ' + score2);
            document.dailyresults.winner[1].checked = true;
        }
        else if (score2 > score1) {
           // alert(score1 + ' 2 ' + score2);
            document.dailyresults.winner[2].checked = true;
        }

        document.dailyresults.result[2].selected = true;

       // dailyresults.nowinner.checked = false;
       // dailyresults.winner1.checked = true;
       // dailyresults.winner2.checked = false;
    }
</script>

</head>

<body topmargin="0">
<div id="wrap" style="width:500px;">

<div id="mymenu"><a href="index.php">Main Menu</a> | <a href="edit_profile.php">Edit Profile</a> | <a href="logout.php">Log Out</a></div>      
<h3><?php echo $t_desc ?></h3>
<table style="border-collapse:collapse; border:none; width:100%; margin:0px;"><tr valign="top">
<td>
<p><strong>Start Date</strong>: <?php echo $t_start ?><br />
<strong>Status</strong>: <?php echo $t_status ?><br />
<?php
if ($opponent_name &&
        !$p1_rslt) {


?>
<strong>Instructions</strong>:<br /><span style="color:red;margin:0px">If your opponent has initiated a game, commence that game. Otherwise, from
within Words With Friends tap "Create", tap "User Name", then type <span style="color:green;"><strong><?php echo $opponent_name ?></strong></span> to initiate the game</span><br />
<?php
}
?>
<?php
if (isset ($rslt_message) && $rslt_message != "") {echo $rslt_message ;}
?>

</p>
</td><td width="50%" id="roundbox">
<span style="font-size:170%; font-weight:bold;">Round <?php echo $round ?></span><br />
<span style="font-size:larger;"><?php echo $player; ?></span><br />
vs. <br />

<?php
if ($opponent_name != ""){
    echo "<span  style='font-size:larger; color:green; font-weight:bold;'>" . $opponent_link . "</span>";
}

else {
    echo "<span style='color:red; font-size:larger; font-weight:bold;'>[NO OPPONENT]</span>";
}

?>
<br /><br />
<?php
$prev_round = $round - 1;
$next_round = $round + 1;
if ($prev_round > 0 and $round > $max_round - 2) {
    echo "<a href='manage_daily.php?tournament_id=$tournament_id &id=$id&round=$prev_round'>< Update Previous Round</a>";

}
if ($max_round > $round) {
        echo "<br /><br /><a href='manage_daily.php?tournament_id=$tournament_id &id=$id&round=$next_round'>Go To Next Round ></a>";
}

if ($opponent_name == "" || $round < $max_round - 2){
    die();
}
?>
</td></tr>
</table>

<?php
// GET OPPONENT'S USER TABLE VALUES AND ASSIGN SESSION VARIABLES
	
$query = "SELECT * FROM users WHERE username='$opponent_name'";
// get results
$result2 = mysql_query($query) or die("Couldn't execute $query");

// declare variables
$url1 = "";
$phone1 = "";
$comment1= "";
$username1 = "";
$firstname1 = "";
$has_url1 = "";
$joined_sub1 = "";
$timezone1 = "";
$device1 = "";
$privacy1 = "";
$email_sub1 = "";

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
$query = "SELECT * FROM daily WHERE match_id in
    (select distinct match_id from daily
    where tournament_id=$tournament_id and user_id=$id and round=$round)";

// get results
$result = mysql_query($query) or die("Couldn't execute $query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {
    
    if ($row['user_id'] != $id) {
        $p2_g1= $row["score"];
        $p2_rslt= $row["result"];
        $p2_cmt= $row["comment"];
        $p2_msg= $row["private_message"];
        $p2_rated= $row["rated"];
        $p2_cheated = $row["cheated"];
    }
    else {
        
    }

}



// Determine whatever?
if ($admcmt != "") {echo "<p><b>Moderator says:</b> $admcmt</p>";}

if ($url1 != "") {
$has_url1 = "<strong>URL</strong>: <a href='$url1' target='_blank'>$url1</a><br />";
} 		

if ($phone1 != "") {
$has_phone1 = "<strong>Phone</strong>: $phone1<br />";
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

<div style="padding:10px; background-color:lightyellow; font-weight:bold; display:<?php
if ($t_status == "Register") {echo 'none';} 
else {echo 'none';} 
?>;">

<span style="color:red;">NOTE: The <?php echo $t_desc ?> is still in the Registration Period. Please do not engage your opponent at this time. Instead, return on the start date of <?php echo $t_short_start ?>, at which time you will be provided with further instructions.</span> <br /><br />
Thank you for playing in this WordsWithFriends.net tournament!
</div>

<div style="display:<?php
if ($t_status == "In Progress" || $t_status == "Register") {echo 'block';} 
else {echo 'none';} 
?>;">

<?php

if ((isset ($_POST['send_pmessage']) && $_POST['send_pmessage']) == "true") {

    $private_msg = strip_tags(substr($_POST['private_msg'],0,140));
    $query = "UPDATE daily SET private_message = '$private_msg'
        WHERE tournament_id=$tournament_id and user_id=$id and round=$round";

    // save the info to the database
    $results = mysql_query( $query );
		
    $mailbody = "The following private message was sent by your tournament opponent, " . $player . ": \n\n" . $private_msg . "\n\nIf you wish to respond by private message, then please log in at http://wordswithfriends.net.";

    $from = "admin@wordswithfriends.net";
    $reply_to = "no-reply@wordswithfriends.net";
    $return_path = "no-reply@wordswithfriends.net";

    $to = $email1;

    $subject = "[WordsWithFriends.net] Player-to-Player Message";

    //send message
    $msg_conf = "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>The following new message was just sent to $opponent_name</span>: $private_msg</p>";
    if (!$debugmode) {
        if (text_mail($to, $subject, $mailbody)) {
            
        }

        else {
            $msg_conf = "";
        }
    }



}

?>


<?php
if ($p_msg != "") {
    echo "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>[Private]</span><i><b> You said:</b></i> $p_msg</p>";
}
?>

<?php
if ($p2_msg != "") {
    echo "<p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>[Private]</span><i><b> $opponent_name said:</b></i> $p2_msg</p>";
}
?>

<table width="500px" border="0" cellspacing="0" cellpadding="0" style="padding: 10px; border: 1px solid #CCC;">
    <form action="manage_daily.php?tournament_id=<?php echo $tournament_id; ?>&round=<?php echo $round?>&id=<?php echo $id ?>" method="post" name="form" id="form">
<input type="hidden" name="private_email" value = "<?php echo $email; ?>" />
<tr valign="top"><td colspan="2"><b>Send Private Message</b><br />
This short message (140 chars max) is sent via email to your opponent. It also
      appears on their 'MyTourney' page when they log in. It does <em>not</em> appear publicly.
    </td><td><textarea cols="25" rows="4" name="private_msg"></textarea><div align="right"><input type="hidden" name="send_pmessage" value="true"><input name="submit" <?php if (isset ($mailbody) && $mailbody != "") {echo "disabled";} ?> type="submit" value="Send" /></div></td></tr>
         
  </form></table>
  
<?php 
if (isset ($msg_conf)) {
  echo $msg_conf;   
}

?>


<h3 style='background-color:#CCCCCC; border-top:1px solid #000000; border-bottom:1px solid #000000; padding:10px;'>Scores, Results & Comments</h3>

<form action="manage_daily.php?tournament_id=<?php echo $tournament_id; ?>&round=<?php echo $round ?>&id=<?php echo $id ?>" method="post" name="dailyresults" id="form">

<table cellpadding="3" width="500px" cellspacing="2" style="border-collapse:collapse; border:1px solid #CCC;">
<tr><td colspan="3"> 
<?php

if ($p1_rslt == "Won") {
    echo "<p style='background-color:lightyellow; padding:5px;'><strong style='color:green;'>FINAL OUTCOME</strong>: Congrats! You won Round " . $round . "; "; 
    if ($p2_rslt == 'Lost') {echo $opponent_name . ' lost.</p>';}
    else if ($p2_rslt == 'No-Show') {echo $opponent_name . ' lost due to no-show.</p>';}
	else if ($p2_rslt == 'Forfeited') {echo $opponent_name . ' forfeited.</p>';}
	else if ($p2_rslt == 'Incomplete') {echo $opponent_name . ' lost due to an incomplete round.</p>';}
	else {echo "</p>";}
}

else if ($p2_rslt == "Won") {
    echo "<p style='background-color:lightyellow; padding:5px;'><strong style='color:red;'>FINAL OUTCOME</strong>: You lost Round " . $round;
	if ($p1_rslt == 'Lost') {echo "; " . $opponent_name . ' won.</p>';}
    else if ($p1_rslt == 'No-Show') {echo "; " . $opponent_name . ' won due to no-show.</p>';}
	else if ($p1_rslt == 'Forfeited') {echo "; " . $opponent_name . ' won because you forfeited.</p>';}
	else if ($p1_rslt == 'Incomplete') {echo "; " . $opponent_name . ' won because you had an incomplete round.</p>';}
	else {echo "; " . $opponent_name . " won.</p>";}
}

else {echo "<div style='color:red; padding:5px; font-weight:bold;'>Complete this section only after your game is final.</div>";} 
?>

</td></tr>
<tr><td colspan="2" style="font-weight:bold;"><?php echo $player; ?></td><td style="font-weight:bold;"><?php echo $opponent_name; ?></td></tr>
<tr>
<td colspan="2"><input type = "text" value="<?php echo $p1_g1; ?>" name="p1_g1" size="3" onKeyUp="updateWinners()" /></td>
<td><input type = "text" value="<?php echo $p2_g1; ?>" name="p2_g1" size="3" onKeyUp="updateWinners()" /></td>
</tr>

<?php if ($p2_cmt != "") {
echo "<tr><td colspan='3'><p style='background-color:lightyellow; padding:5px;'><span style='color:green; font-weight:bold;'>$opponent_name says:</span> $p2_cmt</p></td></tr>";
}
?>
<tr valign="top"><td colspan="2"><p><b>Comment on this Round:</b><br /><small>(140 max)</small><br /><br />
<span style="color:red;">NOTE: This will be immediately visible to everyone publicly on the Results tab.</span></p></td><td><br /><textarea cols="25" rows="4" name="new_cmt"><?php echo $p1_cmt ?></textarea></td></tr>

<tr valign="top"><td colspan="2" align="right"><p><b>Who won Round <?php echo $round; ?>?</b></p></td><td width="65%"><p>
<input type="radio" id="[NO WINNER]" <?php if ($p1_rslt == NULL) {echo 'checked="checked"';} ?> name="winner" value="no_winner" />[NO WINNER]<br />
<input type="radio" id="<?php echo $player; ?>" <?php if ($p1_rslt == 'Won') {echo 'checked="checked"';} ?> name="winner" value="<?php echo $player; ?>" /><?php echo $player; ?><br />
<input type="radio" id="<?php echo $opponent_name; ?>" <?php if ($p2_rslt == 'Won') {echo 'checked="checked"';} ?> name="winner" value="<?php echo $opponent_name; ?>" /><?php echo $opponent_name; ?>
</p>
</td></tr> 
<tr valign="top"><td colspan="2" align="right"><p><b>How Did Round <?php echo $round; ?> End?<br /></b></p></td><td><p><select name="result" >
  <option value="">--Make Selection--</option>
  <option <?php if ($p1_rslt == 'In Progress') {echo 'selected';} ?> value="In Progress">In Progress</option>
  <option <?php if ($p1_rslt == 'Lost' || $p2_rslt == 'Lost') {echo 'selected';} ?> value="Lost">Round Completed</option>
  <option <?php if ($p1_rslt == 'No-Show' || $p2_rslt == 'No-Show') {echo 'selected';} ?> value="No-Show">Player No-Show</option>
  <option <?php if ($p1_rslt == 'Forfeited' || $p2_rslt == 'Forfeited') {echo 'selected';} ?> value="Forfeited">Player Forfeited</option>
  <option <?php if ($p1_rslt == 'Incomplete' || $p2_rslt == 'Incomplete') {echo 'selected';} ?> value="Incomplete">Time Ran Out</option>
</select></p></td></tr>
<tr valign="top"><td colspan="2" align="right"><p><b>Rate Your Experience* <br />with this Player:<br /></b></p></td><td><p><select name="rating" >
  <option value="">--Make Selection--</option>
  <option <?php if ($p2_rated == '1') {echo 'selected';} ?> value="1">1 - Terrible</option>
  <option <?php if ($p2_rated == '2') {echo 'selected';} ?> value="2">2 - Poor</option>
  <option <?php if ($p2_rated == '3') {echo 'selected';} ?> value="3">3 - Just OK</option>
  <option <?php if ($p2_rated == '4') {echo 'selected';} ?> value="4">4 - Very Good</option>
  <option <?php if ($p2_rated == '5') {echo 'selected';} ?> value="5">5 - Excellent</option>
</select></p></td></tr>
<tr><td colspan="3">
<p>* Base your rating on quality of communication, sportsmanship, speed of play, and overall pleasantness of this interaction. <span style="color:red;">Do not rate based on player's skill or ability.</span></p>
</td></tr>
<tr valign="top"><td colspan="2" align="right"><p><b>Honesty Rating:<br /></b></p></td><td><p><select name="honesty" >
  <option value="">--Make Selection--</option>
  <option <?php if ($p2_cheated == '1') {echo 'selected';} ?> value="1">1 - Very Suspicious</option>
  <option <?php if ($p2_cheated == '2') {echo 'selected';} ?> value="2">2 - Mildy Suspicious</option>
  <option <?php if ($p2_cheated == '3') {echo 'selected';} ?> value="3">3 - Neutral</option>
  <option <?php if ($p2_cheated == '4') {echo 'selected';} ?> value="4">4 - Quite Honest</option>
  <option <?php if ($p2_cheated == '5') {echo 'selected';} ?> value="5">5 - Very Honest</option>
</select></p></td></tr>
</table>
<input type="hidden" value="true" name="updatescore" />
<div style="text-align:center; margin-top:20px;"><input type="submit" value="Update" name="update" /></div>
</form>
</div> 
</div>
</body>
</html>