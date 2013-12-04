<?php get_header(); ?>

	<div id="content" class="archive">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
	<div class="post" id="post_<?php the_ID(); ?>">
	
	<span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php the_title(); ?></span>
		
	<h2 class="title"><?php the_title(); ?></h2>
		
		<div class="entry" style="padding-top:15px;">
		<?php the_content(__('<p>Read the rest of this page &raquo;</p>','arthemia')); ?>

		<?php wp_link_pages(array('before' => __('<p><strong>Pages:</strong>','arthemia'), 'after' => '</p>', 'next_or_number' => 'number')); ?>

		</div>

	</div>
		
	<?php endwhile; endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
