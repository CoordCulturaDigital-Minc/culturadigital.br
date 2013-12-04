<?php get_header(); ?>

	<div id="content" class="archive">
	
	<?php if (have_posts()) : ?>
	
 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

 	  <?php /* If this is a category archive */ if (is_category()) { ?><span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Archive by Category','arthemia');?></span>
    <h2 class="title"><?php _e('Articles in ','arthemia');?> <strong><?php single_cat_title(); ?></strong></h2>

	<?php /* If this is a tagged archive */ } elseif (is_tag()) { ?><span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Archive by Tags','arthemia');?></span><h2 class="title"><?php _e('Articles tagged with: ','arthemia');?> <?php single_tag_title(); ?></h2>

 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?><span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Archive by Day','arthemia');?></span><h2 class="title"><?php _e('Article Archive for ','arthemia');?> <?php the_time('j F Y'); ?></h2>

 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?><span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Archive by Month','arthemia');?></span><h2 class="title"><?php _e('Article Archive for ','arthemia');?> <?php the_time('F Y'); ?></h2>
 	  
	<?php /* If this is a yearly archive */ } elseif (is_year()) { ?><span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Archive by Year','arthemia');?></span><h2 class="title"><?php _e('Article Archive for ','arthemia');?> <?php _e('Year','arthemia');?> <?php the_time('Y'); ?></h2>

 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Archive','arthemia');?></span>
		<h2 class="title"><?php _e('The Archive','arthemia');?></h2>
 	  <?php } ?>

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
		<span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Archive','arthemia');?></span>
		<h2 class="title"><?php _e('Not Found','arthemia');?></h2>

		<p><?php _e('No posts found. Try a different search?','arthemia');?></p>

	<?php endif; ?>

	</div>



<?php get_sidebar(); ?>
<?php get_footer(); ?>
