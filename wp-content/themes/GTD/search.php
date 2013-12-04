<?php 
get_header();
?>
<div class="sleeve_main">
<div id="main">
	<h2><?php printf( __('Search Results for: %s', 'p2'), esc_html( get_search_query() ) ); ?></h2>
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