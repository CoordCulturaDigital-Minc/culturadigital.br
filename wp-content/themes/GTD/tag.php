<?php 
get_header();
$tag_obj = $wp_query->get_queried_object();
	
?>
<?php 
get_header();
?>
<div class="sleeve_main">
<div id="main">
	<h2><?php printf( __('Latest Updates: %s', 'p2'), single_tag_title('', false) ); ?> <a class="rss" href="<?php echo get_tag_feed_link( $tag_obj->term_id ); ?>">RSS</a></h2>
<?php
if ( have_posts() ):
?>
<ul id="postlist">
<?php
	while( have_posts() ):
	    the_post();
        require dirname(__FILE__) . '/entry.php';
    endwhile; // have_posts
?>
</ul>
<?php else: // have_posts ?>
<div class="no-posts">
    <h3><?php _e('No posts found!', 'p2'); ?></h3>
</div>
<?php
endif; // have posts
    prologue_navigation();
?>
</div> <!-- main -->
</div> <!-- sleeve -->
<?php get_footer( );