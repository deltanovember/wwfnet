<?php include"master_inc.php";
$tournament_id = strip_tags(substr($_GET['tournament_id'],0,20));

$query = "SELECT * FROM T_CONTROL WHERE record='$tournament_id'";

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
<title>Words With Friends | Tournament Results</title>

<link href="mytourney.css" rel="stylesheet" type="text/css">

</head>

<body topmargin="0">
<div id="wrap">


<div id="mymenu"><a href="results_index.php">Results Home</a></div>

<h3><?php echo "Words With Friends ".$t_desc ?></h3>
<p><strong>Start Date</strong>: <?php echo $t_start ?><br />
<strong>Status</strong>: <?php echo $t_status ?></p>

<div style='float:left; margin-right:5px; text-align:center; color:#FFF; border:1px solid black; font-weight:bold; background-color:green;padding:1px;font-size:80%; width:40px;'>%</div><p> = Win Percentage (current tournament)

<?php

function print_table($t_id, $min_games, $max_games) {


    $max_query = "";
    if ($max_games > 0) {
        $max_query = " and sum(case when PointsFor.result<>'' Then 1 Else 0 End) < $max_games";
    }
    $score_query = "SELECT PointsFor.user_id,username,sum(case when PointsFor.result='Won' then 1 Else 0 End)/sum(case when PointsFor.result<>'' and PointsFor.result<>'In Progress' Then 1 Else 0 End)*100.0 As winpercent, sum(case when PointsFor.result<>'' Then 1 Else 0 End) as totalgames, sum(PointsFor.score) as pointsfor, sum(PointsAgainst.score) as pointsagainst, daily_status
    FROM users,`daily` PointsFor
    JOIN `daily` PointsAgainst
      on PointsFor.match_id = PointsAgainst.match_id
      and PointsFor.user_id <> PointsAgainst.user_id
    where PointsFor.user_id=users.id and
    PointsFor.tournament_id=$t_id
    group by user_id
    HAVING sum(case when PointsFor.result<>'' Then 1 Else 0 End) >= $min_games $max_query
    order By WinPercent desc, totalgames desc";
    $score_result = mysql_query($score_query) or die(mysql_error());


    if(mysql_num_rows($score_result)>0) {

        $max_text = "";
        $min_text = "";
        if ($min_games > 0) {
            if ($max_games == 0) {
               $min_text = " >= $min_games ";
            }
            else
                $min_text = " and >= $min_games ";
        }
        if ($max_games > 0) {
            $max_text = " < $max_games";
        }

        echo "<table class='mt_table'>
        <tr><th class='td_tourney' colspan='3'>Players$max_text$min_text Completed Games</th></tr>
        <tr>
        <th>#</th><th width='75%'>Player</th><th>MOV</th>
        </tr>";
        $row_counter = 1;

        while($row = mysql_fetch_array($score_result)) {

            $p1_numgames = $row['totalgames'];
            $p1_player = $row['username'];
            $p1_id= $row['user_id'];
            $p1_winratio = "";
            $p1_mov = 0;
            $status = "Active";
            if ($row['daily_status'] == 'Eliminated') {
                $status = "Inactive";
            }
            if ($p1_numgames > 0) {
               $p1_winratio = round($row['winpercent'], 1);
               $p1_mov = round(($row['pointsfor'] - $row['pointsagainst']) / $p1_numgames, 1);
            }




        if ($p1_numgames > 3) {
            $win_display = "<div style='float:left; margin-right:5px; text-align:center; color:#FFF; border:1px solid black; font-weight:bold; background-color:green;padding:1px;font-size:80%; width:70px;'>" . $p1_winratio . "% | " . $p1_numgames . "</div>";
        }

        else{
            $win_display = "<div style='float:left; margin-right:5px; text-align:center; color:#333; border:1px solid black; font-weight:bold; background-color:#CCC;padding:1px;font-size:80%; width:60px;'>Unranked</div>";
        }

        if ($p1_mov != "") {
            $mov_display = $p1_mov;
        }

        else{
            $mov_display = "N/A";
        }

          echo "<tr><td>$row_counter</td>";
          echo "<td><strong>" . $win_display . "<a href='player_hist_daily.php?id=" . $p1_id . "&tournament_id=" . $t_id . "'>" . $p1_player . "</a></strong> ($status)</td>";
          echo "<td style='text-align:center;'>" . $mov_display . "</td>";
          echo "</tr>";

          $row_counter++;

          }
        echo "</table><br /><br />";
    }

}

print_table($tournament_id, 20, 0);
print_table($tournament_id, 4, 20);
print_table($tournament_id, 0, 4);

echo "</table>";




?>

</div>
</body>
</html>