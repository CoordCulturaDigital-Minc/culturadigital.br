<?php
$current_user_id = get_the_author_ID( );
get_header();
?>
<div class="sleeve_main">
<div id="main">
	<h2><?php printf( _c('Updates from %s|month', 'p2'), get_the_time('F, Y') ); ?></h2>
	<ul id="postlist">
<?php
if ( have_posts() ):
	while( have_posts( ) ):
	    the_post( );
        require dirname(__FILE__) . '/entry.php';
	endwhile; // have_posts

endif; // have_posts
?>
	</ul>
<?php
    prologue_navigation();
?>
</div> <!-- // main -->
</div> <!--sleeve_main" -->
<?php get_footer( );