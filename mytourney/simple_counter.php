<?php
date_default_timezone_set('UTC');

// change these
$target = mktime(9, 0, 0, 3, 14, 2011);
$round = 4;
$tournament = "M2011_03";
//

$difference = ($target - time());

$days = (int)($difference/3600/24);
$hours = (int)($difference/3600 - 24 * $days);
$minutes =  (int)($difference/60 - $days * 1440 - 60 * $hours);
$seconds = $difference - $days - 86400 * $days - 3600 * $hours - 60 * $minutes;
$days_string = "";
$hours_string = "";
$minutes_string = "";
$seconds_string = "";
if ($days > 0) {
    if ($days == 1) {
        $days_string = "$days day, ";
    }
    else {
        $days_string = "$days days, ";
    }

}
if ($hours > 0) {
    if ($hours == 1) {
        $hours_string = "$hours hour, ";
    }
    else {
        $hours_string = "$hours hours, ";
    }

}

if ($minutes > 0) {
    if ($minutes == 1) {
        $minutes_string = "$minutes minute, ";
    }
    else {
        $minutes_string = "$minutes mins, ";
    }

}

$db = $GLOBALS['db_name'];
$query = "select count(*) from $db.$tournament where status = 'Active'";
$result = mysql_query($query) or die("Couldn't execute $query");
$count = 0;
while ($row= mysql_fetch_array($result)) {
    $count = $row[0];
}

if ($seconds > 0) {
    if ($seconds == 1) {
        $seconds_string = "$seconds second";
    }
    else {
        $seconds_string = "$seconds seconds";
    }

    print "Round $round will close in $days_string $hours_string $minutes_string $seconds_string. There are <strong>$count</strong> players in this round.";

}




?>
