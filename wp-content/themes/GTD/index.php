<?php
if($_GET['page']=='ajax')
{
	global $wpdb;
	$post_id = $_REQUEST['post_id'];
	$meta_key = 'attached_file';
	$meta_value = stripslashes($_REQUEST['afile']);
	if($meta_value)
	{
		$meta_value_arr = explode(',',$meta_value);
		$meta_value_array = array();
		for($i=0;$i<count($meta_value_arr);$i++)
		{
			$meta_value_array[] = get_option( 'siteurl' ) ."/wp-content/themes/".get_option( 'template' )."/attachments/".$meta_value_arr[$i];			
		}
		$meta_value = implode(',',$meta_value_array);
		update_post_meta( $post_id, $meta_key, $meta_value );
	}
	exit;
}
elseif($_GET['page']=='notify')
{
	include_once(TEMPLATEPATH.'/notify.php');
	exit;
}
elseif($_GET['page']=='last_post_meta')
{
	global $wpdb;
	$last_postid = $wpdb->get_var("SELECT max(ID) as ID FROM $wpdb->posts");
	echo $attached_file = get_post_meta($last_postid, 'attached_file', $single = true);
	exit;
}

	global $paged;
	prologue_new_post_noajax();
	get_header();
?>
<div class="sleeve_main">
<?php
	if( current_user_can( 'publish_posts' ) ) require_once dirname( __FILE__ ) . '/post-form.php';
	
?>
<div id="main">
	<h2><?php _e( 'Recent Updates' , 'p2'); ?> <?php if ( $paged > 1 ) printf( __('Page %s', 'p2'), $paged ); ?>
		<a class="rss" href="<?php bloginfo( 'rss2_url' ); ?>">RSS</a>
		<span class="controls">
			<a href="#" id="togglecomments"><?php _e('Hide threads', 'p2'); ?></a> | <a href="#directions" id="directions-keyboard"><?php  _e('Keyboard Shortcuts', 'p2'); ?></a>
		</span>
	</h2>
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
<ul id="postlist">
	<li class="no-posts">
    	<h3><?php _e('No posts yet!', 'p2'); ?></h3>
	</li>
</ul>
<?php
endif; // have posts
    prologue_navigation();
?>
</div> <!-- main -->
</div> <!-- sleeve -->
<?php get_footer( );