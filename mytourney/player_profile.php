<?php
//Set permission level threshold for this page remove if page is good for all levels
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
include"master_inc.php";
$msg = "";
if (isset ($_GET['msg'])) {
  $msg = strip_tags(substr($_GET['msg'],0,20));
}


$id = strip_tags(substr($_GET['id'],0,10));

$row_count = 0;
$tourney_count = 0;

?>	

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Words With Friends | Player Profile</title>

<link type="text/css" rel="stylesheet" href="mytourney.css" />

</head>

<body topmargin="0">
<div id="wrap">
<div id="mymenu"><a href="results_index.php">Results Home</a></div>      

<?php // GET PLAYER'S USER TABLE VALUES AND ASSIGN SESSION VARIABLES
			
$query = "SELECT * FROM users WHERE id='$id' LIMIT 1"; 

// get results
$result = mysql_query($query) or die("Couldn't execute query 1");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $id= $row["id"];
    $username= $row["username"];
    $joined= $row["joined"];
    $joined_sub = substr($joined, 0, 10);
    $timezone= $row["timezone"];
    $comment= $row["comment"];
    $skill= $row["skill"];
    $device= $row["device"];
    $url= $row["url"];
    $alias= $row["alias"];
}

if ($url != "") {
$has_url = "<br /><strong>Web site</strong>: <a href='$url' target='_blank'>$url</a>";
} else {
$has_url = "";
}

if ($comment != "") {
$has_comment = "<br /><br /><strong>'" . $username . "' says</strong><i>: \"$comment\"</i>";
} else {
$has_comment = "";
}			


if ($alias != "") {
$has_alias = "<br /><span style='color:red;font-weight:bold;'>History of Screen Name Changes</span>: $alias</span>";
} else {
$has_alias = "";
}			


?>


<p style="font-size:larger; font-weight:bold;">Words With Friends Profile for <span style="color:green;"><?php echo "$username" ?></span></p>

<?php echo "<p><strong>Member Since</strong>: $joined_sub<br />
<strong>Time Zone</strong>: $timezone | <strong>Skill Level</strong>: $skill | <strong>Device</strong>: $device" . $has_url . $has_alias . $has_comment . "</p>";

echo "
<table class='mt_table'>
<tr valign='top'>
<th colspan='4' class='td_tourney'>" . $username . "'s Tournaments</th></tr>
<th width='12%'>View</th>
<th width='' style='text-align:left;'>Tournament Title</th>
<th width=''>Start Date</th>
<th width=''>Outcome</th>
</tr>";

// Get Info From Tournament Table

$query = "SELECT * FROM T_CONTROL WHERE `t_status` <> 'Archive' AND `t_status` <> 'Register' ORDER BY `t_start` DESC"; 

// get results
$result = mysql_query($query) or die("Couldn't execute query 2");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $t_id= $row["t_id"];
    $t_start= $row["t_start"];
    $t_desc= $row["t_desc"];
    $t_round= $row["t_round"];
    $t_status= $row["t_status"];
    $t_champ= $row["t_champ"];
    $tournament_id = $row["record"];

    $color1 = "#ffffff";
    $color2 = "#ebebeb";
    $row_color = ($row_count % 2) ? $color1 : $color2;

    $hist_lnk = "";
    if (preg_match("/D2/", $t_id)) {
         $hist_link="<a href='player_hist_daily.php?tournament_id=" . $tournament_id . "&id=" . $id . "'><img border='0px' src='images/eye.gif' /></a>";
    }
    else {
        $hist_link="<a href='player_hist.php?t_id=" . $t_id . "&id=" . $id . "'><img border='0px' src='images/eye.gif' /></a>";
    }


    $count = 0;
    $result2 = NULL;

    // See if player is participated in this tournament
    if (preg_match("/D2/", $t_id)) {
       // 
        $daily_query = "SELECT * FROM daily WHERE user_id='$id' and tournament_id = $tournament_id order by round desc limit 1";
        $daily_result=mysql_query($daily_query) or die("Couldn't execute query $daily_query");
        $count=mysql_num_rows($daily_result);

    }
    else if (preg_match("/M2/", $t_id)) {
    // print $t_id.strpos($t_id, "D2")."<br />";
        $query2 = "SELECT * FROM `$t_id` WHERE id='$id'";
        $result2=mysql_query($query2) or die("Couldn't execute $query2");
        $count=mysql_num_rows($result2);
    }


    if($count<=0){
        $in = 'no';
    }
    else if (preg_match("/D2/", $t_id)) {
        
        $tourney_count = $tourney_count +1;
        $in = 'yes';
        while ($row= mysql_fetch_array($daily_result)) {
            $p_status= "Playing";
            if ($t_champ == $username) {
                $p_status = "Champion";
            }
            $p_onround= $row["round"];

            if ($p_status == "Champion") {
                
                $outmessage = "<span style='font-style:italic;font-weight:bold;color:green;'>Champion</span>";
            }
            else if ($t_round > $p_onround ||
                    $t_status == "Complete") {
                $outmessage = "<span style='font-style:italic;'> Out in Round " . $p_onround . "</span>";

            }
            else {$outmessage = "<span style='font-style:italic;'>Playing - Round " . $p_onround . "</span>";}
            }


            echo "<tr valign='top'>
                <td bgcolor='$row_color' style='text-align:center;'>" . $hist_link . "</td>
            <td bgcolor='$row_color'><strong>" . $t_desc . "</strong> (R" . $t_round . ")</td>
            <td bgcolor='$row_color' style='text-align:center;'>$t_start</td>
            <td bgcolor='$row_color' style='text-align:center;'>" . $outmessage . "</td>
            </tr>";

            $row_count++;

            $outmessage='';
    }
    else {
        //print "here";
        $tourney_count = $tourney_count +1;
        $in = 'yes';
        while ($row= mysql_fetch_array($result2)) {
            $p_status= $row["status"];
            $p_onround= $row["onround"];

            if ($p_status == "Eliminated") {
                $outmessage = "<span style='font-style:italic;'> Out in Round " . $p_onround . "</span>";
            }
            else if ($p_status == "Champion") {
                $outmessage = "<span style='font-style:italic;font-weight:bold;color:green;'>Champion</span>";

                }
            else {
                $outmessage = "<span style='font-style:italic;'>Playing - Round " . $p_onround . "</span>";}
            }


            echo "<tr valign='top'>
                <td bgcolor='$row_color' style='text-align:center;'>" . $hist_link . "</td>
            <td bgcolor='$row_color'><strong>" . $t_desc . "</strong> (R" . $t_round . ")</td>
            <td bgcolor='$row_color' style='text-align:center;'>$t_start</td>
            <td bgcolor='$row_color' style='text-align:center;'>" . $outmessage . "</td>
            </tr>";

            $row_count++;

            $outmessage='';
    }
}	

if ($tourney_count <= 0 ) {echo "<tr><td colspan='4' style='background-color:lightyellow; color:#444444; text-align:center; font-weight:bold;'>No tournament history for this player yet.</td></tr>";}

?>
</table>

</div>

</body>
</html>