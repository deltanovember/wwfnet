<? 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include "../auth_check_header.php";

$id = $_REQUEST['id'];
$t_id = $_REQUEST['t_id'];
$raw_action = $_REQUEST['action'];

if ($id == NULL || $t_id == NULL || $raw_action == NULL) {
die('A required GET variable is missing');
}

if ($raw_action == 'eliminate') {$new_status = 'Eliminated';}
else if ($raw_action == 'reinstate') {$new_status = 'Active';}
else {die('Action not recognized.');}

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

?>


<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
</head>

<body>
<p>
<?
$query = "UPDATE `$t_id` SET status = '$new_status' , onround = '$t_round' WHERE `id` = '$id';";

$result = mysql_query( $query );

// print out the results
if( $result )
{
echo( "Successfully changed player status." );
}
else
{
die( "Error: Could not change status: " . mysql_error() );
}
?>	
</p>
<script type="text/javascript">
alert("This player's status has been changed to '<?php echo $new_status; ?>'. Click OK to continue.'");
window.location = "referee.php?<?php echo 't_id=' . $t_id . '&id=' . $id . '&round=' . $t_round ?>";
</script>
</body>
</html>
