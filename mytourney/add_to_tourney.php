<? 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include"auth_check_header.php";

$t_id = strip_tags(substr($_GET['t_id'],0,20));
$proposed_id = strip_tags(substr($_GET['id'],0,10));
$action = strip_tags(substr($_GET['action'],0,20));
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

<?

$query = "SELECT * FROM users WHERE id='$proposed_id'"; 
$result = mysql_query($query);
$count=mysql_num_rows($result);
if($count>0){} else {
echo "This ID is not found in the users database";
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
$t_round= $row["t_round"];
$t_status= $row["t_status"];
$t_champ= $row["t_champ"];
}
			
// Find out if player is already registered for this tourney
$query = "SELECT * FROM $t_id WHERE id='$id'"; 

$result = mysql_query($query) or die("Couldn't execute query");
$count=mysql_num_rows($result);
if($count>0){
$registered = "yes";
} else {
$registered = "no";
}
// If not registered and requesting to join, then insert
if ($registered == "no" && $action == "join"){

$query = "INSERT INTO `$t_id` (  `id` ,  `player` ,  `skill` , `r1_admcmt` ) VALUES ('$id',  '$username',  '$skill',  'Welcome to the tournament!');";

$results = mysql_query( $query );

if( $results )
{
echo "<p>Success! Player was added to the Tournament!</p>";
}
else
{
die( "Trouble saving information to the database: " . mysql_error() );
}
}

else {echo "<p>This player is already signed up!</p>";}

$query = "SELECT * FROM $t_id WHERE id='$id'"; 
$results = mysql_query( $query );

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$record= $row["record"];
$player= $row["player"];
$onround= $row["onround"];
$status= $row["status"];
$admcmt= $row["admcmt"];
$opponent= $row["r" . $onround . "_vs"];
$p_msg= $row["r" . $onround . "_pmsg"];
$p1_g1= $row["r" . $onround . "_g1"];
$p1_g2= $row["r" . $onround . "_g2"];
$p1_g3= $row["r" . $onround . "_g3"];
$p1_rslt= $row["r" . $onround . "_rslt"];
$p1_cmt= $row["r" . $onround . "_cmt"];

}

	

?>


<div id="wrap" style="width:500px;">

<h3><? echo $t_desc ?></h3>
<p><strong>Start Date</strong>: <? echo $t_start ?><br />
<strong>Status</strong>: <? echo $t_status ?><br />
<strong>Round</strong>: <? echo $t_round ?></p>

<form name="input" action="add_to_tourney.php" method="get">
<input type="hidden" name="t_id" value="M2010_04" />
<input type="hidden" name="action" value="join" />
Player's ID: <input type="text" name="id" />
<input type="submit" value="Submit" />
</form>

<?
$query = "SELECT * FROM $t_id WHERE id='$id'"; 
$results = mysql_query( $query );

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$record= $row["record"];
$player= $row["player"];
$onround= $row["onround"];
$status= $row["status"];
$admcmt= $row["admcmt"];
$opponent= $row["r" . $onround . "_vs"];
$p_msg= $row["r" . $onround . "_pmsg"];
$p1_g1= $row["r" . $onround . "_g1"];
$p1_g2= $row["r" . $onround . "_g2"];
$p1_g3= $row["r" . $onround . "_g3"];
$p1_rslt= $row["r" . $onround . "_rslt"];
$p1_cmt= $row["r" . $onround . "_cmt"];
}

// See if an opponent has been assigned for this round. If not, move to pick player page
if ($opponent != ""){ echo "<p>This player already has an opponent: " . $opponent . "</p>";
} else {
echo "<h3>" . $player . " has no opponent.</h3>";
}
	
?>

</div>
</body>
</html>