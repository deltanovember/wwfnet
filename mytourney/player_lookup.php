<?php
//Set permission level threshold for this page remove if page is good for all levels

include"master_inc.php";

// Get query string
$var = strip_tags(substr($_REQUEST['q'],0,50));
$alert_msg = "";
if (isset ($_REQUEST['msg'])) {
    $alert_msg = strip_tags(substr($_REQUEST['msg'],0,100));
}


if ($var != ""){
$query = "SELECT * FROM users WHERE (`username` LIKE \"%$var%\") ORDER BY `username` asc"; 
$results=mysql_query($query);
$count=mysql_num_rows($results); 
	if ($count == 1){
		$result = mysql_query($query) or die("Couldn't execute query");
		while ($row= mysql_fetch_array($result)) {
		$id= $row["id"];
		}
		header("Location: player_profile.php?id=$id");
	}
	else if ($count<1){
		$alert_msg = "No exact match found for \'" . $var . ".\' Try typing only part of the screen name.";
	}
}

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Admin Index</title>

<link href="mytourney.css" rel="stylesheet" type="text/css" />

</head>
<body topmargin="0">
<?php
if ($alert_msg != ""){
echo "<script type='text/javascript'>alert('" . $alert_msg . "');</script>"; 
}
?>
<div id="wrap">
<div id="mymenu"><a href="results_index.php">Back to Results Home</a></div>      
<h3>Player Lookup</h3>

<form action="player_lookup.php" method="post" name="form" id="form">
<input name="q" type='text' id='q' />&nbsp;<input type="submit" name="Submit22" value="Search" /></form>
<p style='font-style:italic;color:#888888; font-size:85%;'>Note: Leave blank for all players (but be warned, it's a looong list).</p>
<br />          
<table class='mt_table' width='485px'>
<tr>
<th colspan='4' class="td_tourney">Registered Players
<?php
if ($var != "") { echo "<br /><span style='font-weight:normal; color:blue; font-style:italic;'>Results for: '$var'</span>"; }
?>

</th>
</tr>
<tr><th width="15%">View</th><th>User Name</th></tr> 

<?php
$color1 = "#ffffff";  
$color2 = "#ebebeb"; 	

$query = "SELECT * FROM users WHERE (`username` LIKE \"%$var%\") ORDER BY `username` asc"; 

$numresults=mysql_query($query);
$numrows=mysql_num_rows($numresults); 

// get results
$result = mysql_query($query) or die("Couldn't execute query");
$row_count = 0;

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$id= $row["id"];
$username= $row["username"];
$password= $row["password"];
$lastname= $row["lastname"];
$firstname= $row["firstname"];
$phone= $row["phone"];
$email= $row["email"];
$premium = $row["premium"];
$email_sub = substr($email, 0, 50);

$profile_link="<a href='player_profile.php?id=" . $id . "'><img border='0px' src='images/eye.gif' /></a>";

$row_color = ($row_count % 2) ? $color1 : $color2; 

echo "
<tr valign='top'>
<td align='center' bgcolor='$row_color' >" . $profile_link . "</td>
                <td bgcolor='$row_color' ><span class='screenname'>$username</span><br /></td></tr>";
    $row_count++; 
} 

?>
</table>

</div> <!-- Close Master Div -->
</body>
</html>
