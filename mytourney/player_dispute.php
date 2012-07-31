<? 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=1;

include"auth_check_header.php";

$t_id = strip_tags(substr($_REQUEST['t_id'],0,20));
$proposed_id = strip_tags(substr($_REQUEST['id'],0,10));
$action = strip_tags(substr($_REQUEST['action'],0,20));
$t_desc = strip_tags(substr($_REQUEST['t_desc'],0,50));

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
}

$query = "SELECT * FROM $t_id WHERE id='$id'"; 
$result = mysql_query($query) or die("Couldn't execute query");
while ($row= mysql_fetch_array($result)) {
$onround= $row["onround"];
}

// If not registered and requesting to join, then insert
if ($action == 'confirm_dispute'){

$query = "UPDATE `$t_id` SET `r" . $onround . "_dispute` = 'yes' WHERE id='$id'";

$results = mysql_query( $query );

if( $results )
{
header("Location: index.php?msg=disputed");
}
else
{
die( "Trouble saving information to the database: " . mysql_error() );
}

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
.td_tourney {background-color:lightblue; font-weight:bold; font-size:larger;}
</style>

</head>

<body topmargin="0">
<div id="wrap" style="width:500px;">

<div id="mymenu"><a href="index.php">Main Menu</a> | <a href="edit_profile.php">Edit Profile</a> | <a href="logout.php">Log Out</a></div>      
<h3>Dispute Process</h3>
<div style="padding:10px; background-color:lightyellow; color:red; font-weight:bold;"><p>You are about to make a dispute against your Round <?php echo $onround ?> opponent in the <?php echo $t_desc ?>. Please read the following guidelines and be certain that you have a sound basis for disputing the result.</p></div>

<p>
<b>Reasons to Dispute</b><br />
Valid reasons to dispute a result may include the following:
<ul>
<li>My opponent posted false scores and/or false game results</li>
<li>I claimed the round due to incomplete games, but my opponent maliciously reversed the result</li>
<li>My opponent cheated, and I think I can prove it</li>
</ul>
<b>Invalid Dispute Reasons</b><br />
The following reasons are not valid because all players are responsible for declaring a winner <em>by the deadline</em>, regardless of whether the games are actually played.

<ul>
<li>My opponent played too slowly</li>
<li>We ran out of time and didn't record a winner by the deadline</li>
<li>My opponent showed poor sportsmanship</li>
</ul>

<b>What A Dispute Will Do</b><br />
A dispute places a "Disputed" flag on the scores appearing on Results tab. This allows you to protest against a player in a way that is visible to the community. Repeated protests against a player eventually draws attention and the potential for that player to receive warnings and possible banning by the tournament administrator. Egregious abuse, if substantiated, could result in instant elimination. All corrective action is at the sole discretion of the tournament administrator.<br /><br />
<b>What A Dispute Will Not Do</b><br />
Filing a dispute does not ensure that you will be reinstated into the tournament. In fact, reinstatement is extremely rare and is permitted only when the claim can be clearly substantiated through records (screen shots and/or email communications). Additionally, filing a dispute does not ensure disciplinary action against your opponent, and we tend to give the benefit of the doubt. We look for documentary evidence and patterns of repeated complaints. <em>We are particularly skeptical of complaints of cheating because uncommon words can be reached through well-developed vocabularies and good guessing (which, by the way, is permitted).</em> </p>

<h3>Continue with Dispute</h3>
<p><b>Submit Evidence and Details</b><br />
Please submit details of your dispute to <a href="mailto:disputes@wordswithfriends.net">disputes@wordswithfriends.net</a>. You must include supporting screen shots and/or email correspondence.</p>

<p>By clicking the "Proceed with Dispute" button, you are saying that you deserve to advance to the next round and that your opponent should not advance.</p>

<form name='dispute' action='player_dispute.php' method='post'>
<input type='hidden' name='t_id' value='<?php echo $t_id ?>' />
<input type='hidden' name='id' value='<?php echo $id ?>' />
<input type='hidden' name='action' value='confirm_dispute' />
<input type='hidden' name='t_desc' value='<?php $t_desc ?>' />
<input type='submit' value='Proceed with Dispute' />
</form>

</div> 
</body>
</html>