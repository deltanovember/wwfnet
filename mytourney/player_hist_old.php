<? 

include"master_inc.php"; 

$t_id = strip_tags(substr($_GET['t_id'],0,20));
$id = strip_tags(substr($_GET['id'],0,50));

$query = "SELECT * FROM `users` WHERE id ='$id'"; 

$result = mysql_query($query);
$count=mysql_num_rows($result);

if($count>0){} else {
header("Location: player_lookup.php?msg=This%20player%20was%20not%20found.");
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

$query = "SELECT * FROM $t_id WHERE id='$id'"; 
$result = mysql_query($query) or die("Couldn't execute query");
$row= mysql_fetch_array($result);
$lastround= $row['onround'];
$status= $row['status'];

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Manage My Tournament</title>
<link type="text/css" href="mytourney.css" rel="stylesheet" />
<style type="text/css">
#stat_box {
width:185px;
float:right;
margin-right:17px;
margin-bottom:20px;
text-align:center;
padding:10px;
background-color:#FFFF66;
}
</style>
</head>

<body topmargin="0">
<div id="wrap">
<div id="mymenu"><a href="player_profile.php?id=<? echo $id; ?>">Player Profile</a> | <a href="results_index.php">Results Home</a></div>      
<h3><? echo $t_desc ?></h3>
<?

if (strrpos($t_id,"D") > -1){ 
$query = "SELECT * FROM $t_id WHERE id='$id'"; 
$result = mysql_query($query) or die("Couldn't execute query");
$row= mysql_fetch_array($result);

$array = array("r1_rslt","r2_rslt","r3_rslt","r4_rslt","r5_rslt","r6_rslt","r7_rslt","r8_rslt","r9_rslt","r10_rslt","r11_rslt","r12_rslt","r13_rslt","r14_rslt","r15_rslt","r16_rslt","r17_rslt","r18_rslt","r19_rslt","r20_rslt","r21_rslt","r22_rslt","r23_rslt","r24_rslt","r25_rslt","r26_rslt","r27_rslt","r28_rslt","r29_rslt","r30_rslt","r31_rslt");

$wins = 0;
$validgames = 0;

foreach($array as $key => $value) {
$currentkey = $row[$value];
if ($currentkey == "Won") { $wins++; }
if ($currentkey != "") { $validgames++; } 
}

$winratio = ($wins / $validgames) * 100;

echo '<div id="stat_box">';

if ($validgames < 4) {
echo "Player must complete at least four active rounds* before a win ratio is posted.";
}

else{
echo "<span style=''>Won " . $wins . " out of " . $validgames . " Active Rounds*, for a Win Percentage of</span><br /><span style='font-size:350%; font-weight:bold;'>" . round($winratio, 2) . "%</span>"; 
}
echo '</div>';
}
?>
<p><strong>History for:<br /></strong><span style="color:green; font-size:larger; font-weight:bold;"> <? echo "$username" ?></span></p>
<p><strong>Start Date</strong>: <? echo $t_start ?><br />
<strong>Tourney Status</strong>: <? echo $t_status ?> | Round <? echo $t_round ?><br />
<strong>Player Status</strong>: 
<? 
if ($status == "Eliminated") { echo "<span style='color:red; font-weight:bold'>" . $status . "</span> in Round " . $lastround;}
else { echo "<span style='color:green; font-weight:bold;'>" . $status . "</span> in Round " . $lastround;}
if ($rslt_message != "") {echo "<br />" . $rslt_message ;}
?>
</p>

<table class='mt_table'>			
<tr><th colspan='5' class='td_tourney'><? echo $t_desc ?></th></tr> 
<tr>
<th width='50%'>[R#] Player <span style='font-size:85%;'>(Feedback Rating)</span> </th><th>Gm 1</th><th>Gm 2</th><th>Gm 3</th><th style='text-align:center;'>Result</th>
</tr>

<?php


// Find out if player is already registered for this tourney

$i = 1;

do {
$query = "SELECT * FROM $t_id WHERE id='$id'"; 
$result = mysql_query($query) or die("Couldn't execute query");
$row= mysql_fetch_array($result);
$p1_player= $row['player'];
$p2_player= $row['r' . $i . '_vs'];
$p1_cmt= $row['r' . $i . '_cmt'];
$p1_g1= $row['r' . $i . '_g1'];
$p1_g2= $row['r' . $i . '_g2'];
$p1_g3= $row['r' . $i . '_g3'];
$p1_rslt= $row['r' . $i . '_rslt'];
$p1_dispute= $row['r' . $i . '_dispute'];
$p1_rated= $row['r' . $i . '_rated'];

if ($p2_player==""){
echo "<tr><td colspan='5' style='font-weight:bold; background-color:pink;'>[" . $i . "]<span style='color:#333;'> *** Not Active This Round *** </span></td></tr>";
echo "<td colspan='5' style='font-style:italic; background-color:lightyellow;'>";
}
else {
if ($p1_rated > 0){
$p1_rateimage="<a title='1 = Terrible, 5 = Excellent'>&nbsp;<img src='images/star" . $p1_rated . ".gif' /></a>";
} else {$p1_rateimage="<span style='font-weight:normal; font-size:85%;'>&nbsp;(not rated)</span>";}
$adm_cmt="";
$adm_cmt= $row['r' . $i . '_admcmt'];
$p1_color = "#FFF";
$p2_color = "#FFF";
if ($p1_rslt == "Won"){
    $p1_color = "green";
	$p2_color = "red";
	}
else if ($p1_rslt == "Lost" || $p1_rslt == "Forfeited" || $p1_rslt == "No-Show" || $p1_rslt == "Incomplete" ) {
	$p1_color = "red";
	$p2_color = "green";
    }	
else if ($p1_rslt == "") {
	$p1_rslt = "Result Not";
	$p1_color = "grey";
	$p2_color = "grey";
	}
else {
	$p1_color = "grey";
	$p2_color = "grey";
	}
  echo "<tr>";
  echo "<td style='font-weight:bold;'>[" . $i . "] " . $p1_player . $p1_rateimage . "</td>";
  echo "<td style='text-align:center;'>" . $p1_g1 . "</td>";
  echo "<td style='text-align:center;'>" . $p1_g2 . "</td>";
  echo "<td style='text-align:center;'>" . $p1_g3 . "</td>";
  echo "<td style='background-color:" . $p1_color . "; color:white; font-weight:bold; text-align:center;'>" . $p1_rslt . "</td>";
  echo "</tr>";

$result2 = mysql_query("SELECT * FROM " . $t_id . " WHERE player = '" . $p2_player ."'");
while ($row= mysql_fetch_array($result2)) {
$p2_id= $row["id"];
$p2_g1= $row["r" . $i . "_g1"];
$p2_g2= $row["r" . $i . "_g2"];
$p2_g3= $row["r" . $i . "_g3"];
$p2_rslt= $row["r" . $i . "_rslt"];
$p2_cmt= $row["r" . $i . "_cmt"];
$p2_dispute= $row['r' . $i . '_dispute'];
$p2_rated= $row['r' . $i . '_rated'];
if ($p2_rated > 0){
$p2_rateimage="<a title='1 = Terrible, 5 = Excellent'>&nbsp;<img src='images/star" . $p2_rated . ".gif' /></a>";
} else {$p2_rateimage="<span style='font-weight:normal; font-size:85%;'>&nbsp;(not rated)</span>";}
$adm_cmt2= $row["r" . $i . "_admcmt"];
 }  
if ($p2_rslt != "") {}
else {
$p2_rslt = "Reported";
}
  echo "<tr>";  
  echo "<td style='font-weight:bold;'><a href=http://wordswithfriends.net/mytourney/player_hist.php?t_id=" . $t_id . "&id=" . $p2_id . ">" . $p2_player . "</a>" . $p2_rateimage . "</td>";
  echo "<td style='text-align:center;'>" . $p2_g1 . "</td>";
  echo "<td style='text-align:center;'>" . $p2_g2 . "</td>";
  echo "<td style='text-align:center;'>" . $p2_g3 . "</td>";   
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

echo "</tr>";  
}
$i++;
}
while ($i <= $lastround);
echo "</table>";

if (strrpos($t_id,"D") > -1){ 

echo '<div style="clear:both; margin-bottom:10px; margin-top:20px; font-size:small; font-style:italic;">* For the purpose of determining win ratio, an "active round" is one in which a winner was declared. Thus, the calculation includes rounds that concluded with "Won/Lost," "Incomplete," "Forfeiture," or "No Show."</div>';
}
?>
</div>
</body>
</html>