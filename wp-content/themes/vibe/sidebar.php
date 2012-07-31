<?php
/**

// Last joined count
$currentTourney = "M2010_11";
$query = "SELECT * FROM " . $currentTourney . ""; 
$result = mysql_query($query) or die("Couldn't execute query");
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
*/
/**
 * @package WordPress
 * @subpackage Vibe
 */
?>
<ul id="sidebar">
<?php
/* Widgetized sidebar, if you have the plugin installed. */
if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
	<?php wp_list_pages('title_li=<h3>Pages</h3>'); ?>
	<?php wp_list_categories('title_li=<h3>' . __('Categories') . '</h3>'); ?>
	<li id="archives"><h3><?php _e('Archives'); ?></h3>
		<ul>
		<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</li>
	<?php wp_list_bookmarks('title_before=<h3>&title_after=</h3>'); ?>
<?php endif; ?><center>
</center>
</ul>