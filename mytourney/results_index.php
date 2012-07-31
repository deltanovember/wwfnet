<?php include"master_inc.php";

// declare variables
$row_count = 0;

$t_id = "";
if (isset ($_GET['t_id'])) {
   $t_id = strip_tags(substr($_GET['t_id'],0,20));
}

$proposed_id = "";
if (isset($_GET['id'])) {
 $proposed_id = strip_tags(substr($_GET['id'],0,10));
}


?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Tournament History</title>

<link href="mytourney.css" rel="stylesheet" type="text/css" />

</head>

<body topmargin="0">
<div id="wrap" style="width:480px;">

<table class='mt_table'>
<tr><th colspan='3' class='td_tourney'>Tournament History</th></tr>
<th width="" style="text-align:left;">Tournament Title</th>
<th width="">Start Date</th>
<th width="">See Round</th>

</tr>
<?php // Get Info From Tournament Table

$query = "SELECT * FROM T_CONTROL WHERE `t_status` = 'In Progress' OR `t_status` = 'Complete' ORDER BY `t_start` DESC"; 

// get results
$result = mysql_query($query) or die("Couldn't execute query 2");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$t_id= $row["t_id"];
$t_start= $row["t_start"];
$t_desc= $row["t_desc"];
$tournament_id = $row['record'];

if (strrpos($t_id,"D") > -1){
$t_desc= "<a href='results_daily_final.php?tournament_id=$tournament_id'>$t_desc</a>";
}
$t_link = 
$t_round= $row["t_round"];
$t_status= $row["t_status"];

$color1 = "#ffffff";  
$color2 = "#ebebeb"; 	
$row_color = ($row_count % 2) ? $color1 : $color2; 
$i = 1;

echo "<tr valign='top'><td bgcolor='$row_color' style='text-align:left;'><strong>$t_desc</strong></td>";
echo "<td bgcolor='$row_color' style='text-align:center;'>$t_start</td>";

if (strrpos($t_id,"D") > -1){
echo "<td bgcolor='$row_color' style='text-align:left;'><form name='hist_" . $t_id . "' action='results_daily.php' method='get'><input type='hidden' name='tournament_id' value='$tournament_id' />";
}

else { echo "<td bgcolor='$row_color' style='text-align:left;'><form name='hist_" . $t_id . "' action='results.php' method='get'><input type='hidden' name='t_id' value='$t_id' />"; }

echo "<select style='float:left;' name='t_round' onchange='javascript:document.hist_" . $t_id . ".submit();'>";
do {
echo "<option ";
if ($t_round == $i){echo "selected=selected ";}
echo "value=" . $i . ">Round " . $i . "</option>";
$i++;
}
while ($i <= $t_round);
echo "</select><input type='submit' value='Go' /></form></td>";
echo "</tr>";

$row_count++;  

}	


?>

</table>

<h3>Find a Player</h3>
<form action="player_lookup.php" method="post" name="form" id="form">
<input name="q" type='text' id='q' />&nbsp;<input type="submit" name="Submit22" value="Search" />
<p style='font-style:italic;color:#888888; font-size:85%;'>Note: Leave blank for all players (but be warned, it's a looong list).</p>

</div>
</body>
</html>