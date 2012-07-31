
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
   <link rel="image_src" href="http://wordswithfriends.net/wp-content/themes/vibe/images/fullicon.png" />
<meta name="description" content="The unofficial site for enthusiasts of Words with Friends--the wildly addictive iPhone-based crossword game. Tips, stories, tournaments, and fun!">
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

  <meta name="keywords" content="words with friends, scrabble, tournament, iphone, crossword, puzzles, competition" />
	<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php if (is_singular()) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>

<script type="text/javascript">
var onround_m = "<?php echo $t_round; ?>";
var t_desc_m = "<?php echo $t_desc; ?>";
var pausecontent2=new Array();
<? 

if ($win_str1 != ""){echo 'pausecontent2[0]=\'<a href="' . $win_link1 . '">' . $win_str1 . '</a>\';';


}else{
echo 'pausecontent2[0]="There are no results yet for this round.";';
}
if ($win_str2 != ""){echo 'pausecontent2[1]=\'<a href="' . $win_link2 . '">' . $win_str2 . '</a>\';';}else{
echo "pausecontent2[1]='Stay tuned for more results!';";
}
if ($win_str3 != ""){echo 'pausecontent2[2]=\'<a href="' . $win_link3 . '">' . $win_str3 . '</a>\';';}else{}
if ($win_str4 != ""){echo 'pausecontent2[3]=\'<a href="' . $win_link4 . '">' . $win_str4 . '</a>\';';}else{}
if ($win_str5 != ""){echo 'pausecontent2[4]=\'<a href="' . $win_link5 . '">' . $win_str5 . '</a>\';';}else{}

?>

</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20933246-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body <?php body_class(); ?>>

<div id="wrapper">
	<div id="page">
		<div id="header">
			<h1 style="width:500px; position:relative; left:-50px;"><a href="<?php echo get_option('home'); ?>/"><img src="images/spacer.png" border="0" height="40px" width="500px" /></a></h1>
			<p><?php bloginfo('description'); ?></p>
			<div style="float:right; position:relative;bottom:-25px;"><span id="hidden"><a style="text-decoration:none; color:#FFFFFF;" href="http://wordswithfriends.net/wp-admin/" target="_self" />Admin</a>&nbsp;</span><script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=6b568dc8-4810-4ae7-8a66-67fd164efc53&amp;type=wordpress&amp;style=rotate"></script></div><ul class="menu">
				<li<?php if(is_home()) echo ' class="current_page_item"'; ?>><a href="<?php bloginfo('url'); ?>">Home</a></li>
				<?php wp_list_pages('title_li=&depth=1'); ?>
                                <li<?php echo ' class="page_item page-item-124"'; ?>><a href="http://ask.wordswithfriends.net" target="_blank">Ask Us</a></li>
			</ul>
				
			<ul class="rss">
                                <li><a href="<?php bloginfo('rss2_url'); ?>">Posts RSS</a></li>
				<li><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments RSS</a></li>
			</ul>
        <form action="http://www.google.com/cse" id="cse-search-box">
          <div>
            <input type="hidden" name="cx" value="partner-pub-0930621878563048:swttjkxdhmw" />
            <input type="hidden" name="ie" value="ISO-8859-1" />
            <input type="text" name="q" size="31" />
            <input type="submit" name="sa" class="btn" value="Search" />
          </div>
        </form>
        <script type="text/javascript" src="http://www.google.com/cse/brand?form=cse-search-box&amp;lang=en"></script>



<!--
			<form method="get" action="<?php bloginfo('url'); ?>/">
				<input type="text" class="text" name="s" />
				<input type="submit" class="btn" value="Search" />
			</form>
-->
		</div>
           <div id="content">
<br />
<script type="text/javascript"><!--
google_ad_client = "ca-pub-0930621878563048";
/* bigwide */
google_ad_slot = "3234618291";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br />
<br />
<?php include "mytourney/news_ticker.php"; ?>
<br /><br />
           </div>
		<div id="container">
			<div id="content">
                            