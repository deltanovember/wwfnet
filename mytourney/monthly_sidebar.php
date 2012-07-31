<div id='wrap'>


<?php

header("Pragma: no-cache");
header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

require_once 'login_config.php';

$db = $GLOBALS['db_name'];
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=1;

/* Last joined count */
$current_month_number = date('m');
$next_month_number = date('m', time() + 60*60*24*10);
$next_month_year = date('Y', time() + 60*60*24*10);
$currentTourney = "$db.M".$next_month_year."_".$next_month_number;

// manually change the above to current active tournament table name or dynamically pull it from the T_CONTROL table based on ORDER BY and/or t_status indicator
$query = "SELECT * FROM " . $currentTourney . "";
$result = mysql_query($query) or die("Couldn't execute $query");
$count=mysql_num_rows($result);
echo "<script type='text/javascript'>var playerCount = '" . $count . "';</script>";
$query = "SELECT player FROM " . $currentTourney . " ORDER BY `record` DESC LIMIT 1";
$result = mysql_query($query) or die("Couldn't execute query");
$row= mysql_fetch_array($result);
$player= $row["player"];
echo "<script type='text/javascript'>var lastJoined = '" . $player . "';</script>";
$query = "SELECT * FROM " . $currentTourney . " WHERE status = 'Active'";
$result = mysql_query($query) or die("Couldn't execute query");
$count=mysql_num_rows($result);
echo "<script type='text/javascript'>var activeCount = '" . $count . "';</script>";
print "There are currently <span style='color:black; font-weight:bold;'>$count</span> registered players. The last player to join was <span style='color:black; font-weight:bold;'>$player</span>."

?>
    
</div>