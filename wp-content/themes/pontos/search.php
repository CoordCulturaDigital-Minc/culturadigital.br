<?php get_header(); ?>

	<div id="content" class="archive">

	<span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Search','arthemia');?></span>

	<h2 class="title"><?php _e('Search Results','arthemia'); ?></h2>
	
	<p><?php _e('You have just searched for','arthemia');?> <strong>"<?php the_search_query() ?>"</strong>. <?php _e('Here are the results:','arthemia');?></p>

	<?php if (have_posts()) : ?>

	<div class="clearfloat">

	<?php while (have_posts()) : the_post(); ?>

	<div class="tanbox left">
		<span class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></span>
		<div class="meta"><?php the_time(get_option('date_format')); ?> &#150; <?php the_time(); ?> | <?php comments_popup_link(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia'));?></div>
	
		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_thumb('thumbnail', 'class="left"'); ?></a>
		
		<?php the_excerpt(); ?>
	</div>
	
	
	<?php endwhile; ?>

	</div>

	<div id="navigation">
	<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
	</div>


	<?php else : ?>

	<p><?php _e('No posts found. Try a different search?','arthemia');?></p>

	<?php endif; ?>

	</div>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>
