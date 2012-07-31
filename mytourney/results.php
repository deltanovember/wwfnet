<?php include"master_inc.php";
$t_id = "";
if (isset($_GET['t_id'])) {
  $t_id = strip_tags(substr($_GET['t_id'],0,20));
}

$onround = "";
if (isset ($_GET['t_round'])) {
 $onround = strip_tags(substr($_GET['t_round'],0,20));
}


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
<title>Tournament Results</title>

<link href="mytourney.css" rel="stylesheet" type="text/css">

</head>

<body topmargin="0">
<div id="wrap">


<div id="mymenu"><a href="results_index.php">Results Home</a></div>
<h3><?php echo $t_desc ?></h3>
<p><strong>Start Date</strong>: <?php echo $t_start ?><br />
<strong>Status</strong>: <?php echo $t_status ?></p>


<?php

$result = mysql_query("SELECT * FROM " . $t_id . " WHERE r" . $onround . "_ctrl = 'yes' AND r" . $onround . "_rslt IS NOT NULL ORDER BY player");

echo "<table class='mt_table'>
<tr><th colspan='1' class='td_tourney'>Round $onround Results</th><th style='padding-left:35px;' class='td_tourney' colspan='3'>";
$i = 1;
echo "<form name='hist_" . $t_id . "' action='results.php' method='get'><input type='hidden' name='t_id' value='$t_id' />";

echo "<select style='float:left;' name='t_round' onchange='javascript:document.hist_" . $t_id . ".submit();'>";
do {
echo "<option ";
if ($onround == $i){echo "selected=selected ";}
echo "value=" . $i . ">Round " . $i . "</option>";
$i++;
}
while ($i <= $t_round);
echo "</select></th><th class='td_tourney' colspan='1'style='text-align:center; padding-left:10px;'>";
echo "<input type='submit' value='Refresh' /></form>";
echo "</tr>
<tr>
<th width='42%'>Player (Feedback Rating)</th><th>Game 1</th><th>Game 2</th><th>Game 3</th><th style='text-align:center;'>Round " . $onround . " Result</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
$p1_player= $row['player'];
//$p1_public = mysql_query("SELECT `comment` FROM 'users' WHERE `username` = '" . $p1_player . "' LIMIT 1");
$p1_id= $row['id'];
$p2_player= $row['r' . $onround . '_vs'];
$p1_cmt= $row['r' . $onround . '_cmt'];
$p1_g1= $row['r' . $onround . '_g1'];
$p1_g2= $row['r' . $onround . '_g2'];
$p1_g3= $row['r' . $onround . '_g3'];
$p1_rslt= $row['r' . $onround . '_rslt'];
$p1_dispute= $row['r' . $onround . '_dispute'];
$p1_rated= $row['r' . $onround . '_rated'];
if ($p1_rated > 0){
$p1_rateimage="<a title='1 = Terrible, 5 = Excellent'>&nbsp;<img src='images/star" . $p1_rated . ".gif' /></a>";
} else {$p1_rateimage="<span style='font-weight:normal; font-size:85%;'>&nbsp;(not rated)</span>";}
$adm_cmt= $row['r' . $onround . '_admcmt'];
if ($p1_rslt == "Won"){
    $p1_color = "green";
	$p2_color = "red";
	}
else if ($p1_rslt == "Lost" || $p1_rslt == "Forfeited" || $p1_rslt == "No-Show" || $p1_rslt == "Incomplete" ) {
	$p1_color = "red";
	$p2_color = "green";
    }
else {}
  echo "<tr>";
  echo "<td style='font-weight:bold;'><a href='player_hist.php?id=" . $p1_id . "&t_id=" . $t_id . "'>" . $p1_player . "</a>" . $p1_rateimage . "</td>";
  echo "<td>" . $p1_g1 . "</td>";
  echo "<td>" . $p1_g2 . "</td>";
  echo "<td>" . $p1_g3 . "</td>";
  echo "<td style='background-color:" . $p1_color . "; color:white; font-weight:bold; text-align:center;'>" . $p1_rslt . "</td>";
  echo "</tr>";
$result2 = mysql_query("SELECT * FROM " . $t_id . " WHERE player = '" . $p2_player ."'");
while ($row= mysql_fetch_array($result2)) {
$p2_id= $row["id"];
$p2_g1= $row["r" . $onround . "_g1"];
$p2_g2= $row["r" . $onround . "_g2"];
$p2_g3= $row["r" . $onround . "_g3"];
$p2_rslt= $row["r" . $onround . "_rslt"];
$p2_cmt= $row["r" . $onround . "_cmt"];
$p2_dispute= $row['r' . $onround . '_dispute'];
$p2_rated= $row['r' . $onround . '_rated'];
if ($p2_rated > 0){
$p2_rateimage="<a title='1 = Terrible, 5 = Excellent'>&nbsp;<img src='images/star" . $p2_rated . ".gif' /></a>";
} else {$p2_rateimage="<span style='font-weight:normal; font-size:85%;'>&nbsp;(not rated)</span>";}
$adm_cmt2= $row["r" . $onround . "_admcmt"];
 }  
  echo "<tr>";  
  echo "<td style='font-weight:bold;'><a href='player_hist.php?id=" . $p2_id . "&t_id=" . $t_id . "'>" . $p2_player . "</a>" . $p2_rateimage . "</td>";
  echo "<td>" . $p2_g1 . "</td>";
  echo "<td>" . $p2_g2 . "</td>";
  echo "<td>" . $p2_g3 . "</td>";   
  echo "<td style='background-color:" . $p2_color . "; color:white; font-weight:bold; text-align:center;'>" . $p2_rslt . "</td>"; 
  echo "</tr>";
  echo "<tr>";
  echo "<td colspan='5' style='font-style:italic; background-color:lightyellow;'>";
if ($p1_cmt != "") {
  echo $p1_player . " says: <span style='color:#444;'>" . $p1_cmt . "</span><br />";
  }

if ($p2_cmt != "") {
  echo $p2_player . " says: <span style='color:#444;'>" . $p2_cmt . "</span><br />";
  }  

if ($p1_dispute == "yes") {
  echo "<span style='color:red;'>Note: The initial result of this round was disputed by " . $p1_player . ".</span><br />";
  }  
  
if ($p2_dispute == "yes") {
  echo "<span style='color:red;'>Note: The initial result of this round was disputed by " . $p2_player . ".</span><br />";
  }      

if ($adm_cmt != "" && $adm_cmt != "Welcome to the tournament!") {
  echo "<span style='color:green; font-weight:bold;'>Moderator to " . $p1_player . ":</span> <span style='color:#444;'>" . $adm_cmt . "</span><br />";
  }    
  
if ($adm_cmt2 != "" && $adm_cmt2 != "Welcome to the tournament!") {
  echo "<span style='color:green; font-weight:bold;'>Moderator to " . $p2_player . ":</span> <span style='color:#444;'>" . $adm_cmt2 . "</span><br />";
  }    
  
  echo "</td>";  
  }
echo "</tr>";  

echo "</table>";  
?> 
<br /><br />
<?php

$result = mysql_query("SELECT * FROM " . $t_id . " WHERE r" . $onround . "_ctrl = 'yes' AND r" . $onround . "_rslt IS NULL ORDER BY player");

echo "<table class='mt_table'>
<tr><th colspan='5' style='background-color:lightblue; font-weight:bold; font-size:larger;'>Awaiting Results</th></tr>
<tr>
<th width='42%'>Players</th><th>Game 1</th><th>Game 2</th><th>Game 3</th><th style='text-align:center;'>Round " . $onround . " Result</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
$p1_player= $row['player'];
$p2_player= $row['r' . $onround . '_vs'];
$p1_cmt= $row['r' . $onround . '_cmt'];
$p1_g1= $row['r' . $onround . '_g1'];
$p1_g2= $row['r' . $onround . '_g2'];
$p1_g3= $row['r' . $onround . '_g3'];
$p1_rslt= $row['r' . $onround . '_rslt'];
$adm_cmt= $row['admcmt'];
$p1_color = "#FFFFFF";
$p2_color = "#FFFFFF";
  echo "<tr>";
  echo "<td style='font-weight:bold;'>" . $p1_player . "</td>";
  echo "<td>" . $p1_g1 . "</td>";
  echo "<td>" . $p1_g2 . "</td>";
  echo "<td>" . $p1_g3 . "</td>";
  echo "<td style='background-color:" . $p1_color . "; color:grey; font-weight:bold; text-align:center;'>" . $p1_rslt . "</td>";
  echo "</tr>";
  echo "<tr>";  
  echo "<td style='font-weight:bold;'>" . $p2_player . "</td>";
$result2 = mysql_query("SELECT * FROM " . $t_id . " WHERE player = '" . $p2_player ."'");
while ($row= mysql_fetch_array($result2)) {
$p2_g1= $row["r" . $onround . "_g1"];
$p2_g2= $row["r" . $onround . "_g2"];
$p2_g3= $row["r" . $onround . "_g3"];
$p2_rslt= $row["r" . $onround . "_rslt"];
$p2_cmt= $row["r" . $onround . "_cmt"];
$adm_cmt2= $row["admcmt"];
 }  
  echo "<td>" . $p2_g1 . "</td>";
  echo "<td>" . $p2_g2 . "</td>";
  echo "<td>" . $p2_g3 . "</td>";   
  echo "<td style='background-color:" . $p2_color . "; color:grey; font-weight:bold; text-align:center;'>" . $p2_rslt . "</td>"; 
  echo "</tr>";
  echo "<tr>";
  echo "<td colspan='5' style='font-style:italic; background-color:lightyellow;'>";
if ($p1_cmt != "") {
  echo $p1_player . " says: <span style='color:#444;'>" . $p1_cmt . "</span><br />";
  }

if ($p2_cmt != "") {
  echo $p2_player . " says: <span style='color:#444;'>" . $p2_cmt . "</span><br />";
  }  

if ($adm_cmt != "") {
  echo "<span style='color:red;'>Admin to " . $p1_player . "</span>: <span style='color:#444;'>" . $adm_cmt . "</span><br />";
  }  
  
if ($adm_cmt2 != "") {
  echo "<span style='color:red;'>Admin to " . $p2_player . "</span>: <span style='color:#444;'>" . $adm_cmt2 . "</span>";
  }    
  
  echo "</td>";  
  }
echo "</tr>";  

echo "</table>";  
?> 

</td></tr></table>

<?php

$result = mysql_query("SELECT * FROM " . $t_id . " WHERE onround = " . $onround . " AND r" . $onround . "_vs IS NULL");
$count=mysql_num_rows($result); 
if($count>0) {
echo "
<H3>Missing in Action</H3>
The following player(s) still need to sign in and retrieve a random opponent:<br /><br />";
}

while($row = mysql_fetch_array($result))
  {
$missing_player= $row['player'];
  
echo " | " . $missing_player; 
  }
?>

</div>
</body>
</html>