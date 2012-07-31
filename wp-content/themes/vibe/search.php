<?php
/**
 * @package WordPress
 * @subpackage Vibe
 */
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="post" id="post-<?php the_ID(); ?>"><div class="bottom"><div class="top">
	<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	<div class="author">Written by <?php the_author() ?></div>
	<div class="entry">
		<?php the_content('Read the rest of this entry &raquo;'); ?>
		<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
	</div>
	<div class="comments"><?php comments_popup_link(__('0 Comments'), __('1 Comments'), __('% Comments')); ?></div>
	<div class="meta">
		<div class="categories">Categories: <?php the_category(',') ?></div>
		<?php if(get_the_tags()): ?><div class="tags"><?php the_tags(__('Tags: '), ', '); ?></div><?php endif; ?>
	</div>
	<div class="date"><?php the_time('M') ?><br /><?php the_time('d') ?></div>
</div></div></div>

<?php endwhile; ?>

<div class="navigation postnavigation">
	<div class="alignleft"><?php next_posts_link('Older Posts') ?></div>
	<div class="alignright"><?php previous_posts_link('Newer Posts') ?></div>
</div>

<?php else: ?>

<p>Sorry but your search did not produce any valid result</p>

<?php endif; ?>

<?php get_footer(); ?>