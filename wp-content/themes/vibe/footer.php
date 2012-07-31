<?php
/**
 * @package WordPress
 * @subpackage Vibe
 */
?>
			</div>
			<?php get_sidebar(); ?>
		</div>
		<div id="footer">
			Copyright &copy; 2009 <a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a>
			<?php wp_footer(); ?>
			<ul class="rss">
				<li><a href="<?php bloginfo('rss2_url'); ?>">Posts RSS</a></li>
				<li><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments RSS</a></li>
			</ul>
		</div>
	</div>
</div>

</body>
</html>