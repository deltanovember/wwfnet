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
		<?php the_content(); ?>
		<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
	</div>
	<div class="meta">
		<div class="categories">Categories: <?php the_category(',') ?></div>
		<?php if(get_the_tags()): ?><div class="tags"><?php the_tags(__('Tags: '), ', '); ?></div><?php endif; ?>
	</div>
	<div class="date"><?php the_time('M') ?><br /><?php the_time('d') ?></div>
</div></div></div>

<?php comments_template(); ?>

<?php endwhile; endif; ?>

<?php get_footer(); ?>