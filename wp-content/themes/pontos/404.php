<?php get_header(); ?>

	<div id="content" class="archive">
        <span id="map"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','arthemia');?></a> &raquo; <?php _e('Error 404','arthemia');?></span>
		<h2 class="title"><?php _e('Error 404 - Not Found','arthemia');?></h2>
		<p><?php _e('Ooops, We cannot find you the page you are looking for. You may try to search our site for another keyword or use the navigational tools in this website.','arthemia'); ?></p>
		
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
