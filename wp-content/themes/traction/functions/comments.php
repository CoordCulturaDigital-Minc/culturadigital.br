<?php
	function custom_comment ( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	// Get admin users
	$users = ( get_users_of_blog() );

	foreach ($users as $user) :
		$admin_comment = false;
		$raw = unserialize( $user->meta_value );

		if ( isset( $raw['administrator'] ) ) :
			if ( $comment->comment_author_email == $user->user_email) :
				$admin_comment = true;

				break;
			endif;
		endif;
	endforeach;
?>

	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>" >
		<div class="comment-box clear">
			<div class="c-grav">
				<?php echo get_avatar( get_comment_author_email(), '39' ); ?>
				<div class="c-author"><?php comment_author_link(); ?></div>
				<div class="c-date"><?php comment_date( 'M j Y' ); ?></span></div>
			</div>
			<div class="c-body">
				<?php if ($comment->comment_approved == '0' ) : ?>
					<p><?php _e( '<em><strong>Please Note:</strong> Your comment is awaiting moderation.</em>', 'punchcut' ); ?></p>
				<?php endif; ?>
				<?php comment_text(); ?>
				<?php comment_type(( '' ),( 'Trackback' ),( 'Pingback' )); ?>
				<?php echo comment_reply_link(array( 'depth' => $depth, 'max_depth' => $args['max_depth']));	 ?>
				<?php edit_comment_link( 'edit','<p>','</p>' ); ?>
			</div><!--end c-body-->
		</div><!--end comment-box-->
<?php } ?>
<?php
// Template for pingbacks/trackbacks
	function list_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php } ?>