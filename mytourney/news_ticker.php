<script type="text/javascript" src="http://www.wordswithfriends.net/mytourney/widgets/min.js"></script>


<link rel="stylesheet" href="http://www.wordswithfriends.net/mytourney/widgets/scroller.css" type="text/css" media="screen" />
<script type="text/javascript">
jQuery.fn.liScroll = function(settings) {
		settings = jQuery.extend({
		travelocity: 0.07
		}, settings);
		return this.each(function(){
				var $strip = jQuery(this);
				$strip.addClass("newsticker")
				var stripWidth = 0;
				var $mask = $strip.wrap("<div class='mask'></div>");
				var $tickercontainer = $strip.parent().wrap("<div class='tickercontainer'></div>");
				var containerWidth = $strip.parent().parent().width();	//a.k.a. 'mask' width
				$strip.find("li").each(function(i){
				stripWidth += jQuery(this, i).outerWidth(true); // thanks to Michael Haszprunar
				});
				$strip.width(stripWidth);
				var totalTravel = stripWidth+containerWidth;
				var defTiming = totalTravel/settings.travelocity;	// thanks to Scott Waye
				function scrollnews(spazio, tempo){
				$strip.animate({left: '-='+ spazio}, tempo, "linear", function(){$strip.css("left", containerWidth); scrollnews(totalTravel, defTiming);});
				}
				scrollnews(totalTravel, defTiming);
				$strip.hover(function(){
				jQuery(this).stop();
				},
				function(){
				var offset = jQuery(this).offset();
				var residualSpace = offset.left + stripWidth;
				var residualTime = residualSpace/settings.travelocity;
				scrollnews(residualSpace, residualTime);
				});
		});
};

$(function(){
        $("ul#ticker01").show();
	$("ul#ticker01").liScroll();
        
	$("ul#ticker02").liScroll({travelocity: 0.15});

});
</script>

<ul id="ticker01">



<?php
print	"<li><span>Real time clickable results</span></li>";

/**
 * @package WordPress
 * @subpackage Vibe
 */

// Script to calculate time-ago
require_once "login_config.php";
$db = $GLOBALS['db_name'];
$ago_hours = 0;
mysql_connect( $db_host, $db_username, $db_password );
date_default_timezone_set('America/Chicago');
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
         $ago_minutes   = round( $difference / 60 ) % 60 ;
       $ago_minutes   = ( ( $ago_minutes AND $ago_minutes > 1 ) ? ', ' . $ago_minutes . ' mins' : ( $ago_minutes == 1 ? ', ' . $ago_minutes .' minute' : '' ));
       $ago_hours      = floor( $difference / ( 60 * 60 ) );
       $ago_hours      = $ago_hours . ' hr'. ( $ago_hours > 1 ? 's' : '' );
       $time_ago      = $ago_hours.$ago_minutes.' ago';
   }

   // Days
   else if ( $difference < 60*60*24*7 )
   {
      $ago_hours      = round( $difference / 3600 ) % 24 ;
      $ago_hours      = ( ( $ago_hours AND $ago_hours > 1 ) ? ', ' . $ago_hours . ' hrs' : ( $ago_hours == 1 ? ' and ' . $ago_hours . ' hour' : '' ));
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
$year = date("Y");
$month = date("m");

// Display Recent Winners
$query = "SELECT *  FROM $db.T_CONTROL WHERE `t_id` LIKE 'M2012_$month' AND `t_status` <> 'Archive' ORDER BY `t_id` desc LIMIT 1";

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
//print "SELECT * FROM $db.$t_id WHERE onround = '$t_round' AND `r" . $t_round . "_rslt` = 'won' AND lastwin <> '0000-00-00 00:00:00' ORDER BY lastwin DESC LIMIT 5";
$result = mysql_query("SELECT * FROM $db.$t_id WHERE onround = '$t_round' AND `r" . $t_round . "_rslt` = 'won' AND lastwin <> '0000-00-00 00:00:00' ORDER BY lastwin DESC LIMIT 10");
while($row = mysql_fetch_array($result))  {
    $row_count++;
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
    else {

    }

    $result2 = mysql_query("SELECT * FROM $db." . $t_id . " WHERE player = '" . $p2_player ."'");
    while ($row= mysql_fetch_array($result2)) {
        $p2_id = $row['id'];
        $onround = $row['onround'];
        $p2_g1= $row["r" . $onround . "_g1"];
        $p2_g2= $row["r" . $onround . "_g2"];
        $p2_g3= $row["r" . $onround . "_g3"];
        $p2_rslt= $row["r" . $onround . "_rslt"];
     }

    $win_link1 = "http://wordswithfriends.net/?page_id=212&t_id=" . $t_id . "&player=" . $p1_id;
    $win_time1 = time_ago(time()-strtotime($p1_lastwin));
    $win_str1 = "<font color='#339900'>".$p1_player. "</font>". " > " . "<font color='#cc0000'>".$p2_player."</font>";
    if (is_numeric ($p1_g1)) {
        $win_str1 .= " | ".$p1_g1 . "-" .$p2_g1;
        if (is_numeric ($p1_g2)) {
            $win_str1 .= " | " . $p1_g2 . "-" . $p2_g2;
         }
        if (is_numeric ($p1_g3)) {
            $win_str1 .= " | " . $p1_g3 . "-" . $p2_g3;}
        }
        else {
            $win_str1 .= " Result: " . $p2_player . " = " . $p2_rslt;
        }
        print	"<li><span>$win_time1</span><a href='$win_link1'>$win_str1</a></li>\n";
    }

    if ($row_count == 0) {
        print	"<li>There are no results yet for this round.</li>\n";

    }

?>




</ul>
