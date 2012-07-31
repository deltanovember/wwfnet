<?php
/**
 * @package WordPress
 * @subpackage Vibe
 */

// Script to calculate time-ago
require_once '../login_config.php';
$db = $GLOBALS['db_name'];
mysql_connect( $db_host, $db_username, $db_password );
function time_ago( $difference )
{
   // Seconds
   if($difference < 60)
   {
      $time_ago   = $difference . ' sec' . ( $difference > 1 ? 's' : '' ).' ago';
   }

   // Minutes
   else if( $difference < 60*60 )
   {
//         $ago_seconds   = $difference % 60;
//        $ago_seconds   = ( ( $ago_seconds AND $ago_seconds > 1 ) ? ', '.$ago_seconds.' secs' : ( $ago_seconds == 1 ? ', '.$ago_seconds.' second' : '' ) );
        $ago_minutes   = floor( $difference / 60 );
		$time_ago   = $ago_minutes . ' min' . ( $ago_minutes > 1 ? 's' : '' ).' ago';
//        $ago_minutes   = $ago_minutes . ' min' . ( $ago_minutes > 1 ? 's' : '' ).' ago';
//        $time_ago      = $ago_minutes.$ago_seconds.' ago';
   }

   // Hours
   else if ( $difference < 60*60*24 )
   {
//         $ago_minutes   = round( $difference / 60 ) % 60 ;
//       $ago_minutes   = ( ( $ago_minutes AND $ago_minutes > 1 ) ? ', ' . $ago_minutes . ' mins' : ( $ago_minutes == 1 ? ', ' . $ago_minutes .' minute' : '' ));
       $ago_hours      = floor( $difference / ( 60 * 60 ) );
       $ago_hours      = $ago_hours . ' hr'. ( $ago_hours > 1 ? 's' : '' );
       $time_ago      = $ago_hours.$ago_minutes.' ago';
   }

   // Days
   else if ( $difference < 60*60*24*7 )
   {
//      $ago_hours      = round( $difference / 3600 ) % 24 ;
//      $ago_hours      = ( ( $ago_hours AND $ago_hours > 1 ) ? ', ' . $ago_hours . ' hrs' : ( $ago_hours == 1 ? ' and ' . $ago_hours . ' hour' : '' ));
      $ago_days      = floor( $difference / ( 3600 * 24 ) );
      $ago_days      = $ago_days . ' day' . ($ago_days > 1 ? 's' : '' );
      $time_ago      = $ago_days.$ago_hours.' ago';
   }

   // Weeks
   else if ( $difference < 60*60*24*30 )
   {
//      $ago_days      = round( $difference / ( 3600 * 24 ) ) % 7;
//      $ago_days      = ( ( $ago_days AND $ago_days > 1 ) ? ', '.$ago_days.' days' : ( $ago_days == 1 ? ' and '.$ago_days.' day' : '' ));
      $ago_weeks      = floor( $difference / ( 3600 * 24 * 7) );
      $ago_weeks      = $ago_weeks . ' week'. ($ago_weeks > 1 ? 's' : '' );
      $time_ago      = $ago_weeks.$ago_days.' ago';
   }

   // Months
   else if ( $difference < 60*60*24*365 )
   {
      $days_diff   = round( $difference / ( 60 * 60 * 24 ) );
      $ago_days   = $days_diff %  30 ;
      $ago_weeks   = round( $ago_days / 7 ) ;
      $ago_weeks   = ( ( $ago_weeks AND $ago_weeks > 1 ) ? ', '.$ago_weeks.' weeks' : ( $ago_weeks == 1 ? ' and '.$ago_weeks.' week' : '' ) );
      $ago_months   = floor( $days_diff / 30 );
      $ago_months   = $ago_months .' month'. ( $ago_months > 1 ? 's' : '' );
      $time_ago   = $ago_months.$ago_weeks.' ago';
   }

   // Years
   else if ( $difference >= 60*60*24*365 )
   {
      $ago_months   = round( $difference / ( 60 * 60 * 24 * 30.5 ) ) % 12;
      $ago_months   = ( ( $ago_months AND $ago_months > 1 ) ? ' and ' . $ago_months . ' months' : ( $ago_months == 1 ? ' and '.$ago_months.' month' : '' ) );
      $ago_years   = floor( $difference / ( 60 * 60 * 24 * 365 ) );#30 * 12
      $ago_years   = $ago_years . ' year'. ($ago_years > 1 ? 's' : '' ) ;
      $time_ago   = $ago_years.$ago_months.' ago';
   }

   return $time_ago;
}

$row_count = 0;

// Display Recent Winners
$query = "SELECT *  FROM $db.T_CONTROL WHERE `t_id` LIKE 'M2011_02' AND `t_status` <> 'Archive' ORDER BY `t_id` desc LIMIT 1";

$result = mysql_query($query) or die("Couldn't execute $query");
while ($row= mysql_fetch_array($result)) {
$t_id= $row["t_id"];
$t_start= $row["t_start"];
$t_short_start= substr($t_start, 5, 10);
$t_desc= $row["t_desc"];
$t_round= $row["t_round"];
$t_status= $row["t_status"];
$t_champ= $row["t_champ"];
}

$result = mysql_query("SELECT * FROM `$t_id` WHERE onround = '$t_round' AND `r" . $t_round . "_rslt` = 'won' AND lastwin <> '0000-00-00 00:00:00' ORDER BY lastwin DESC LIMIT 5");
while($row = mysql_fetch_array($result))
  {
$p1_id= $row['id'];
$p1_player= $row['player'];
$p1_lastwin= $row['lastwin'];
$onround = $row['onround'];
$p2_player= $row['r' . $onround . '_vs'];
$p1_cmt= $row['r' . $onround . '_cmt'];
$p1_g1= $row['r' . $onround . '_g1'];
$p1_g2= $row['r' . $onround . '_g2'];
$p1_g3= $row['r' . $onround . '_g3'];
$p1_rslt= $row['r' . $onround . '_rslt'];
$p1_dispute= $row['r' . $onround . '_dispute'];
if ($p1_rslt == "Won"){
    $p1_color = "green";
	$p2_color = "red";
	}
else if ($p1_rslt == "Lost" || $p1_rslt == "Forfeited" || $p1_rslt == "No-Show" || $p1_rslt == "Incomplete" ) {
	$p1_color = "red";
	$p2_color = "green";
    }
else {}

$result2 = mysql_query("SELECT * FROM $db." . $t_id . " WHERE player = '" . $p2_player ."'");
while ($row= mysql_fetch_array($result2)) {
$p2_id = $row['id'];
$onround = $row['onround'];
$p2_g1= $row["r" . $onround . "_g1"];
$p2_g2= $row["r" . $onround . "_g2"];
$p2_g3= $row["r" . $onround . "_g3"];
$p2_rslt= $row["r" . $onround . "_rslt"];
 }

$row_count++;

if ($row_count == 1) {
    $win_link1 = "http://wordswithfriends.net/?page_id=212&t_id=" . $t_id . "&player=" . $p1_id;
	$win_time1 = time_ago(time()-strtotime($p1_lastwin));
    $win_str1 = "<strong>" . $p1_player . "</strong> > " . $p2_player . " <span style=\'font-size:85%; font-style:italic; color:#888888;\'> - " . $win_time1 . "</span>";
    if (is_numeric ($p1_g1)) {
	    $win_str1 .= "<br /><strong>" . $p1_g1 . "</strong>-" . $p2_g1;
	    if (is_numeric ($p1_g2)) {$win_str1 .= " | <strong>" . $p1_g2 . "</strong>-" . $p2_g2;}
	    if (is_numeric ($p1_g3)) {$win_str1 .= " | <strong>" . $p1_g3 . "</strong>-" . $p2_g3;}
	}
	else {$win_str1 .= "<br />Result: " . $p2_player . " = " . $p2_rslt;}
}

if ($row_count == 2) {
    $win_link2 = "http://wordswithfriends.net/?page_id=212&t_id=" . $t_id . "&player=" . $p1_id;
	$win_time2 = time_ago(time()-strtotime($p1_lastwin));
    $win_str2 = "<strong>" . $p1_player . "</strong> > " . $p2_player . " <span style=\'font-size:85%; font-style:italic; color:#888888;\'> - " . $win_time2 . "</span>";
    if (is_numeric ($p1_g1)) {
	    $win_str2 .= "<br /><strong>" . $p1_g1 . "</strong>-" . $p2_g1;
	    if (is_numeric ($p1_g2)) {$win_str2 .= " | <strong>" . $p1_g2 . "</strong>-" . $p2_g2;}
	    if (is_numeric ($p1_g3)) {$win_str2 .= " | <strong>" . $p1_g3 . "</strong>-" . $p2_g3;}
	}
	else {$win_str2 .= "<br />Result: " . $p2_player . " = " . $p2_rslt;}
}

else if ($row_count == 3) {
    $win_link3 = "http://wordswithfriends.net/?page_id=212&t_id=" . $t_id . "&player=" . $p1_id;
	$win_time3 = time_ago(time()-strtotime($p1_lastwin));
    $win_str3 = "<strong>" . $p1_player . "</strong> > " . $p2_player . " <span style=\'font-size:85%; font-style:italic; color:#888888;\'> - " . $win_time3 . "</span>";
    if (is_numeric ($p1_g1)) {
	    $win_str3 .= "<br /><strong>" . $p1_g1 . "</strong>-" . $p2_g1;
	    if (is_numeric ($p1_g2)) {$win_str3 .= " | <strong>" . $p1_g2 . "</strong>-" . $p2_g2;}
	    if (is_numeric ($p1_g3)) {$win_str3 .= " | <strong>" . $p1_g3 . "</strong>-" . $p2_g3;}
	}
	else {$win_str3 .= "<br />Result: " . $p2_player . " = " . $p2_rslt;}
}

else if ($row_count == 4) {
    $win_link4 = "http://wordswithfriends.net/?page_id=212&t_id=" . $t_id . "&player=" . $p1_id;
	$win_time4 = time_ago(time()-strtotime($p1_lastwin));
    $win_str4 = "<strong>" . $p1_player . "</strong> > " . $p2_player . " <span style=\'font-size:85%; font-style:italic; color:#888888;\'> - " . $win_time4 . "</span>";
    if (is_numeric ($p1_g1)) {
	    $win_str4 .= "<br /><strong>" . $p1_g1 . "</strong>-" . $p2_g1;
	    if (is_numeric ($p1_g2)) {$win_str4 .= " | <strong>" . $p1_g2 . "</strong>-" . $p2_g2;}
	    if (is_numeric ($p1_g3)) {$win_str4 .= " | <strong>" . $p1_g3 . "</strong>-" . $p2_g3;}
	}
	else {$win_str4 .= "<br />Result: " . $p2_player . " = " . $p2_rslt;}
}


else if ($row_count == 5) {
    $win_link5 = "http://wordswithfriends.net/?page_id=212&t_id=" . $t_id . "&player=" . $p1_id;
	$win_time5 = time_ago(time()-strtotime($p1_lastwin));
    $win_str5 = "<strong>" . $p1_player . "</strong> > " . $p2_player . " <span style=\'font-size:85%; font-style:italic; color:#888888;\'> - " . $win_time5 . "</span>";
    if (is_numeric ($p1_g1)) {
	    $win_str5 .= "<br /><strong>" . $p1_g1 . "</strong>-" . $p2_g1;
	    if (is_numeric ($p1_g2)) {$win_str5 .= " | <strong>" . $p1_g2 . "</strong>-" . $p2_g2;}
	    if (is_numeric ($p1_g3)) {$win_str5 .= " | <strong>" . $p1_g3 . "</strong>-" . $p2_g3;}
	}
	else {$win_str5 .= "<br />Result: " . $p2_player . " = " . $p2_rslt;}
}

else {}
}

?>
<script type="text/javascript" src="http://www.wordswithfriends.net/mytourney/widgets/min.js"></script>

<script src="http://www.wordswithfriends.net/mytourney/widgets/scroller.js" type="text/javascript" charset="utf-8"></script>


<link rel="stylesheet" href="http://www.wordswithfriends.net/mytourney/widgets/scroller.css" type="text/css" media="screen" />
<script type="text/javascript">
$(function(){
	$("ul#ticker01").liScroll();
	$("ul#ticker02").liScroll({travelocity: 0.15});
//Syntax
//$.syntax({root: 'http://www.gcmingati.net/wordpress/wp-content/themes/giancarlo-mingati/js/jquery-syntax/'});

});
</script>


<ul id="ticker01">
	<li><span>10/10/2007</span><a href="#">The first thing ...</a></li>
	<li><span>10/10/2007</span><a href="#">End up doing is ...</a></li>
	<li><span>10/10/2007</span><a href="#">The code that you ...</a></li>
	<!-- eccetera -->
</ul>
