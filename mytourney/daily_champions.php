<?php include"master_inc.php";


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
<tr><th colspan='3' class='td_tourney'>Daily Champions</th></tr>
<th width="" style="text-align:left;">Tournament Title</th>
<th width="">Champion</th>
<th width="">All Time Record</th>

</tr>
<?php // Get Info From Tournament Table

$query = "SELECT record, t_id, t_desc, t_champ,username FROM T_CONTROL, users WHERE `t_status` = 'Complete' and t_id like 'D2%' and users.id=T_CONTROL.t_champ ORDER BY `record` DESC";

// get results
$result = mysql_query($query) or die("Couldn't execute query 2");
$row_count = 0;

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $t_id = $row['t_id'];
    $user_id = $row['t_champ'];
    $tournament_id = $row['record'];
    $t_desc = $row['t_desc'];
    $t_desc= "<a href='results_daily_final.php?tournament_id=$tournament_id'>$t_desc</a>";
    $champion = $row['username'];

    $color1 = "#ffffff";
    $color2 = "#ebebeb";
    $row_color = ($row_count % 2) ? $color1 : $color2;
    $i = 1;

    $alltime_query = "SELECT sum(case when result='Won' then 1 Else 0 End) as wins,
        sum(case when result<>'' then 1 Else 0 End) as total from daily where user_id=$user_id";
    $alltime_result = mysql_query($alltime_query);
    $wins = 0;
    $total = 0;
    while ($alltime_row = mysql_fetch_array($alltime_result)) {
        $wins = $alltime_row['wins'];
        $total = $alltime_row['total'];
    }
    $losses = $total - $wins;
    $percent = round($wins/$total*100,1);

echo "<tr valign='top'><td bgcolor='$row_color' style='text-align:left;'><strong>$t_desc</strong></td>";
echo "<td bgcolor='$row_color' style='text-align:center;'>$champion</td>";

echo "<td bgcolor='$row_color' style='text-align:center;'>$wins-$losses ($percent%)</td>";



$row_count++;
}

?>

</table>

</div>
</body>
</html>