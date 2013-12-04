<?php
if( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'] ) )
	die( __('Please do not load this page directly. Thanks!', 'p2') );

if ( post_password_required() ) { ?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'p2'); ?></p>
<?php
	return;
} // if post_password_required
?>

<ul id='comments' class='commentlist'>
<?php if ( have_comments() ) wp_list_comments(array('callback' => 'prologue_comment')); ?>
</ul>
<?php if( 'open' == $post->comment_status ) { ?>
	<div id="respond" class="replying">
   		<?php require dirname( __FILE__ ) . '/comment-form.php'; ?>
	</div>
<?php } ?>
<?php
if ( get_option( 'page_comments' ) && (get_query_var( 'cpage' ) > 1 || get_query_var( 'cpage' ) < get_comment_pages_count() ) ) {
?>
	<div class="navigation">
		<p><?php previous_comments_link(); ?>  <?php next_comments_link(); ?> </p>
	</div>
<?php } ?>