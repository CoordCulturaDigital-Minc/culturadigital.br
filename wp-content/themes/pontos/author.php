<?php get_header(); ?>

	<div id="content" class="archive">
	
	<?php if (have_posts()) : ?>
	
    <?php 
if(isset($_GET['author_name'])) :
$curauth = get_userdatabylogin($author_name); // NOTE: 2.0 bug requires get_userdatabylogin(get_the_author_login());
else :
$curauth = get_userdata(intval($author));
endif;
?>

 	 	<span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Archive by Author','arthemia');?></span>
		<h2 class="title"><?php _e('Articles by','arthemia');?> <?php echo $curauth->first_name; ?> <?php echo $curauth->last_name; ?></h2>
 	 
	<div class="clearfloat">

    

    <div id="bio" class="clearfloat"><?php $email = $curauth->user_email; ?><?php echo get_avatar( $email, $size = '80'); ?>
    <p><?php echo $curauth->description; ?></p></div>
	

    <?php while (have_posts()) : the_post(); ?>
	
	<div class="tanbox left">
		<span class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></span>
		<div class="meta"><?php the_time(get_option('date_format')); ?> &#150; <?php the_time(); ?> | <?php comments_popup_link(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia'));?></div>
	
		<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_thumb('thumbnail', 'class="left"'); ?></a>
			
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
