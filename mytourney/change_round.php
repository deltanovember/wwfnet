<? 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include"auth_check_header.php";

if (($_REQUEST['change']) == 'true' && ($_REQUEST['thistourney']) != ''){
$c_tid = strip_tags(substr($_REQUEST['thistourney'],0,20));

$query = "SELECT * FROM T_CONTROL WHERE `t_id` = '" . $c_tid . "'";  

// get results
$result = mysql_query($query) or die("Couldn't execute query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {
$t_desc= $row["t_desc"];
$t_round= $row["t_round"];
$newround = $t_round + 1;
}

$query = "UPDATE `T_CONTROL` SET t_round = " . $newround . " WHERE t_id = '" . $c_tid . "'";
$result = mysql_query($query) or die("Couldn't execute query");

$query = "UPDATE " . $c_tid . " SET onround = " . $newround . " WHERE r" . $t_round . "_rslt = 'Won'"; 
$result = mysql_query($query) or die("Couldn't execute query");

$query = "UPDATE " . $c_tid . " SET status = 'Eliminated' WHERE onround = '" . $t_round . "'";
$result = mysql_query($query) or die("Couldn't execute query");

}


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

<p style="color:red;">IMPORTANT: Be very careful using this form. You are about to close the current round and open the next round. Everyone who has not recorded a win in the current round will be eliminated from that tournament.</p> 

<form action="change_round.php" method="post" name="form" id="form">  
<table width='470px' border='1' cellpadding='2' cellspacing='0'>
<tr>
<th colspan='4' class="td_tourney">Active Tournaments</th>
</tr>
<th width="18%">Start Date</th><th width="50%">Tournament Name</th><th width="25%">Status (Round)</th><th width="7%">Advance</th>
</tr>


<?

$query = "SELECT * FROM T_CONTROL WHERE `t_status` <> 'Archive' AND `t_status` <> 'Complete' AND `t_status` <> 'Register'";

// get results
$result = mysql_query($query) or die("Couldn't execute query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$t_id= $row["t_id"];
$t_start= $row["t_start"];
$t_desc= $row["t_desc"];
$t_round= $row["t_round"];
$t_status= $row["t_status"];
$color1 = "#ffffff";  
$color2 = "#ebebeb"; 	
$row_color = ($row_count % 2) ? $color1 : $color2; 

$query2 = "SELECT * FROM `$t_id` WHERE id='$id'"; 

echo "<tr>
    <td bgcolor='$row_color'>$t_start</td>
    <td bgcolor='$row_color'>$t_desc</td>
    <td bgcolor='$row_color'>$t_status - R$t_round </td>
	<td bgcolor='$row_color' style='text-align:center;'>";

echo '<input type=radio name=thistourney value=' . $t_id . '>';

echo "</td></tr>";

$row_count++;  
}

?>

</table><br />
<input type="hidden" value="true" name="change" />
<input type="submit" value="Update" name="update" />
</form>


<? //Update the Round


?>

</div>
</body>
</html>