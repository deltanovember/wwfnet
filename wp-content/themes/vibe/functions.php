<?php
/**
 * @package WordPress
 * @subpackage Vibe
 */
automatic_feed_links();

if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'before_widget' => '<li id="%1$s" class="widget %2$s"><div class="bottom"><div class="top">',
		'after_widget' => '</div></div></li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}

if (function_exists('add_theme_support')) {
    add_theme_support('menus');
}
?>