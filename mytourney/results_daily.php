<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
include "master_inc.php";
require_once 'classes.php';
require_once 'functions.php';

$tournament_id = strip_tags(substr($_GET['tournament_id'],0,20));
$onround = strip_tags(substr($_GET['t_round'],0,20));

$query = "SELECT * FROM T_CONTROL WHERE record='$tournament_id'";

$tournament_id = 0;
$result = mysql_query($query) or die("Couldn't execute query");
while ($row= mysql_fetch_array($result)) {
    $t_id= $row["t_id"];
    $t_start= $row["t_start"];
    $t_short_start= substr($t_start, 5, 10);
    $t_desc= $row["t_desc"];
    $t_round= $row["t_round"];
    $t_status= $row["t_status"];
    $t_champ= $row["t_champ"];
    $tournament_id = $row["record"];

}

function print_row($daily, $win_array, $total_array, $tournament_id) {

    $p1_id= $daily->get_user_id();
    $p1_player = $daily->get_user_name();
    $p1_cmt= $daily->get_comment();
    $p1_g1= $daily->get_score();
    $p1_rslt= $daily->get_result();
    $p1_rated= $daily->get_rated();
    $adm_cmt= $daily->get_admin_comment();


    if ($p1_rated > 0){
        $p1_rateimage="<a title='1 = Terrible, 5 = Excellent'>&nbsp;<img src='images/star" . $p1_rated . ".gif' alt='star' /></a>";
    }
    else {
        $p1_rateimage="<span style='font-weight:normal; font-size:85%;'>&nbsp;(not rated)</span>";
    }

    $p1_color = "";

    if ($p1_rslt == "Won" ||
            $p1_rslt == 'In Progress'){
        $p1_color = "green";
    }
    else if ($p1_rslt == "Lost" ||
            $p1_rslt == "Forfeited" ||
            $p1_rslt == "No-Show" ||
            $p1_rslt == "Incomplete" ) {
            $p1_color = "red";
    }

    //start win ratio calc
    $wins = 0;
    $validgames = 0;
    $winratio = 0;
    if (array_key_exists($p1_id, $win_array)) {
        $wins = $win_array[$p1_id];
    }
    if (array_key_exists($p1_id, $total_array)) {
        $validgames = $total_array[$p1_id];
        
    }

    if ($validgames > 0) {
        $winratio = ($wins / $validgames) * 100;
    }

    if ($validgames > 3) {
        $win_display = "<div style='float:left; margin-right:5px; text-align:center; color:#FFF; border:1px solid black; font-weight:bold; background-color:green;padding:1px;font-size:80%; width:60px;'>" . round($winratio, 1) . "% " . $wins . "/" . $validgames . "</div>";
    }

    else{
        $win_display = "<div style='float:left; margin-right:5px; text-align:center; color:#333; border:1px solid black; font-weight:bold; background-color:#CCC;padding:1px;font-size:80%; width:60px;'>NR</div>";
    }

      echo "<tr>";
      echo "<td style='font-weight:bold;'>" . $win_display . "<a href='player_hist_daily.php?id=" . $p1_id . "&amp;tournament_id=" . $tournament_id . "'>" . $p1_player . "</a>" . $p1_rateimage . "</td>";
      echo "<td style='text-align:center;'>" . $p1_g1 . "</td>";
      echo "<td style='background-color:" . $p1_color . "; color:white; font-weight:bold; text-align:center;'>" . $p1_rslt . "</td>";
      echo "</tr>";




}

function print_comments($daily1, $daily2) {


    $p1_player = $daily1->get_user_name();
    $p2_player = "";
    $p2_cmt = "";
    $adm_cmt2 = "";
    if ($daily2) {
       $p2_player = $daily2->get_user_name();
       $p2_cmt = $daily2->get_comment();
       $adm_cmt2 = $daily2->get_admin_comment();

    }


    $p1_cmt = $daily1->get_comment();
    $adm_cmt = $daily1->get_admin_comment();
    

     echo "<tr>";
      echo "<td colspan='3' style='font-style:italic; background-color:lightyellow;'>";


    if ($p1_cmt != "") {
        echo $p1_player . " says: <span style='color:#444;'>" . $p1_cmt . "</span><br />";
    }

    if ($p2_cmt != "") {
        echo $p2_player . " says: <span style='color:#444;'>" . $p2_cmt . "</span><br />";
    }
/**
if ($p1_dispute == "yes") {
  echo "<span style='color:red;'>Note: The initial result of this round was disputed by " . $p1_player . ".</span><br />";
  }

if ($p2_dispute == "yes") {
  echo "<span style='color:red;'>Note: The initial result of this round was disputed by " . $p2_player . ".</span><br />";
  }
*/
    if ($adm_cmt != "" && $adm_cmt != "Welcome to the tournament!") {
        echo "<span style='color:green; font-weight:bold;'>Moderator to " . $p1_player . ":</span> <span style='color:#444;'>" . $adm_cmt . "</span><br />";
    }

    if ($adm_cmt2 != "" && $adm_cmt2 != "Welcome to the tournament!") {
        echo "<span style='color:green; font-weight:bold;'>Moderator to " . $p2_player . ":</span> <span style='color:#444;'>" . $adm_cmt2 . "</span><br />";
    }
    echo " ";

    echo "</td>";
    echo "</tr>";


}

function print_matches($sql_result) {

}


?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Words With Friends | Tournament Results</title>

<link href="mytourney.css" rel="stylesheet" type="text/css" />

</head>

<body topmargin="0">
<div id="wrap">


<div id="mymenu"><a href="results_index.php">Results Home</a></div>

<h3><?php echo $t_desc ?></h3>
<p><strong>Start Date</strong>: <?php echo $t_start ?><br />
<strong>Status</strong>: <?php echo $t_status ?></p>

<p><em>NOTE: There is only one game per round in the Daily Challenge format. If a result is not posted (or "no Show") for two consecutive days, you will be deactivated; however, you can rejoin the daily challenge at any time.</em></p>

<div style='float:left; margin-right:5px; text-align:center; color:#FFF; border:1px solid black; font-weight:bold; background-color:green;padding:1px;font-size:80%; width:40px;'>%</div><p> = Win Percentage (current tournament)</p>

<?php

// get wins

$win_query = "SELECT user_id, count(result) FROM `daily` WHERE result = 'Won' and tournament_id=$tournament_id group by user_id";
$win_result = mysql_query($win_query);
$win_array = array();
while($row = mysql_fetch_array($win_result)) {
    $win_array[$row[0]] = $row[1];
}

// get total matches
$total_query = "SELECT user_id, count(distinct round) FROM `daily` where result <> '' and result<>'In Progress' and tournament_id=$tournament_id group by user_id";
$total_result = mysql_query($total_query);
$total_array = array();
while($row = mysql_fetch_array($total_result)) {
    $total_array[$row[0]] = $row[1];
}

// completed matches
$completed_matches_query = "SELECT match_id, daily.user_id, username, round, score, result, private_message, rated, cheated, daily.comment, admin_comment, tournament_id, modified FROM `daily`,users where daily.user_id = users.id and tournament_id=$tournament_id and round=$onround and match_id in (select distinct match_id from daily where result <> '' and result <> 'In Progress') order by match_id";
$completed_matches_result = mysql_query($completed_matches_query);


// incomplete
$incomplete_matches_query = "SELECT match_id, daily.user_id, username, round, score, result, private_message, rated, cheated, daily.comment, admin_comment, tournament_id, modified FROM `daily`,users where daily.user_id = users.id and tournament_id=$tournament_id and round=$onround and match_id in (select distinct match_id from daily where result = '' or result = 'In Progress') order by match_id";
$incomplete_matches_result = mysql_query($incomplete_matches_query);

$result = mysql_query("SELECT * FROM daily WHERE round=$onround");
$count=mysql_num_rows($result);
if($count>0) {
    echo "<table class='mt_table'>
    <tr><th class='td_tourney'>Round $onround Results</th><th class='td_tourney' colspan='2' style='text-align:left;'>";
    $i = 1;
    echo "<form name='hist_" . $t_id . "' action='results_daily.php' method='get'><input type='hidden' name='tournament_id' value='$tournament_id' />";

    echo "<select style='float:left;' name='t_round' onchange='javascript:document.hist_" . $t_id . ".submit();'>";
    do {
        echo "<option ";
        if ($onround == $i) {
            echo "selected=\"selected \"";
        }
        echo "value=\"" . $i . "\">Round " . $i . "</option>";
        $i++;
    }
    while ($i <= $t_round);
    echo "</select>&nbsp;<input type='submit' value='Refresh' /></form>";
echo "</th></tr>
<tr>
<th width='65%'>Player (Feedback Rating)</th><th>Score</th><th style='text-align:center;'>Round " . $onround . " Result</th>
</tr>";


}


$dailies = array();
$matches = array();

// process line by line
while($row = mysql_fetch_array($completed_matches_result)) {

    array_push($dailies, new Daily($row));
}

for ($j=0; $j<count($dailies); $j+=2) {

    $daily_match = new DailyMatch($dailies[$j]);
    if ($j < count($dailies) - 1) {
        $daily_match->add($dailies[$j+1]);

        if ($dailies[$j]->get_match_id() != $dailies[$j+1]->get_match_id()) {
            print "mismatch".$dailies[$j-1]->get_match_id()."<br />";
            print "mismatch".$dailies[$j]->get_match_id()."<br />";
            print $dailies[$j+1]->get_match_id();
            die();
        }
    }
    array_push($matches, $daily_match);

}

usort($matches, array($daily_match, "sort_by_modified"));

foreach ($matches as $match) {

    $daily1 = $match->get_daily1();
    $daily2 = $match->get_daily2();

    print_row($daily1, $win_array, $total_array, $tournament_id);
    if ($daily2) {
        print_row($daily2, $win_array, $total_array, $tournament_id);
    }
    
    print_comments($daily1, $daily2);

}


echo "</table><br /><br />";

$count=mysql_num_rows($incomplete_matches_result);
if($count>0) {
echo "<table class='mt_table'>
<tr><th colspan='3' style='background-color:lightblue; font-weight:bold; font-size:larger;'>Awaiting Results</th></tr>
<tr>
<th width='65%'>Players</th><th>Score</th><th style='text-align:center;'>Round " . $onround . " Result</th>
</tr>";

$dailies = array();
$matches = array();

// process line by line
while($row = mysql_fetch_array($incomplete_matches_result)) {
    array_push($dailies, new Daily($row));
}

for ($j=0; $j<count($dailies); $j+=2) {

    $daily_match = new DailyMatch($dailies[$j]);
    if ($j < count($dailies) - 1) {
        $daily_match->add($dailies[$j+1]);
    }
    array_push($matches, $daily_match);
}

usort($matches, array($daily_match, "sort_by_modified"));

foreach ($matches as $match) {

    $daily1 = $match->get_daily1();
    $daily2 = $match->get_daily2();

    $daily1 = $match->get_daily1();
    $daily2 = $match->get_daily2();

    print_row($daily1, $win_array, $total_array, $tournament_id);
    if ($daily2) {
        print_row($daily2, $win_array, $total_array, $tournament_id);
    }
    
    print_comments($daily1, $daily2);


}
//echo "</tr>";

echo "</table>";
}



$unmatched_result = get_unmatched_daily_players($tournament_id, $onround);
$count=mysql_num_rows($unmatched_result);
if($count>0) {
    echo "
    <H3>Missing in Action</H3>
    The following player(s) could not be matched:<br /><br />";
}
while ($unmatched_row = mysql_fetch_array($unmatched_result))  {
    $missing_player= $unmatched_row['username'];
    $miss_id= $unmatched_row['user_id'];

    echo " | " . "<a href='player_hist_daily.php?id=" . $miss_id . "&amp;tournament_id=" . $tournament_id . "'>" . $missing_player . "</a>";
}


?>

</div>
</body>
</html>