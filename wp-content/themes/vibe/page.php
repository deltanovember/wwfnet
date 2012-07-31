<?php
/**
 * @package WordPress
 * @subpackage Vibe
 */

$t_id = strip_tags(substr($_GET['t_id'],0,20));
$id2 = strip_tags(substr($_GET['player'],0,5));

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="post page" id="post-<?php the_ID(); ?>"><div class="bottom"><div class="top">
	<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	<div class="entry">
    <? if ($t_id != "" && $id2 != ""){
	    echo '<div class="iframe-wrapper">
  <iframe src="http://wordswithfriends.net/mytourney/player_hist.php?t_id=' . $t_id . '&id=' . $id2 . '" frameborder="0" style="height:1100px;width:515px;">Please upgrade your browser</iframe>
</div>';
}

else {
the_content(); 
wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); 
}
?>

	</div>
</div></div></div>

<?php comments_template(); ?>

<?php endwhile; endif; ?>

<?php get_footer(); ?>