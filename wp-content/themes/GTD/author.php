<?php 
get_header();

$author = isset($_GET['author_name'])?  get_userdatabylogin( $author_name ) : get_userdata( intval($author) );

?>
<div class="sleeve_main" id="userpage">
<div id="main">
	<h2>
		<?php echo prologue_get_avatar($author->ID, $author->email, 36 ); ?>
		<?php printf( _c('Updates from %s|name', 'p2'), $author->user_nicename ); ?>
		<a class="rss" href="<?php echo get_author_feed_link($author->ID); ?>">RSS</a>
	</h2>
	<ul id="postlist">
	<?php
	if ( have_posts() ):
		while( have_posts() ):
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
</div> <!-- // authorpage -->
<?php get_footer( );