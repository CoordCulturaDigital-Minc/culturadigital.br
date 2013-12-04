<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ( 'Please do not load this page directly. Thanks!' );

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'traction' ); ?></p>
	<?php
		return;
	}
?>
<!-- You can start editing here. -->
<div id="comments">
<?php if ( have_comments() ) : ?>
	<div class="comment-number clear">
		<span><?php comments_number( __( 'No Comments Yet', 'traction' ), __( '1 Comment', 'traction' ), __( '% Comments', 'traction' )); ?></span>
		<?php if ( 'open' == $post->comment_status) : ?>
			<a id="leavecomment" href="#respond" title="<?php _e( 'Post a comment', 'traction' ); ?>"> <?php _e( 'Post a comment', 'traction' ); ?></a>
		<?php endif; ?>
	</div><!--end comment-number-->
	<ol class="commentlist">
		<?php wp_list_comments( 'type=comment&callback=custom_comment' ); ?>
	</ol>
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
		<h3 class="pinghead"><?php _e( 'Trackbacks &amp; Pingbacks', 'traction' ); ?></h3>
		<ol class="pinglist">
			<?php wp_list_comments( 'type=pings&callback=list_pings' ); ?>
		</ol>
	<?php endif; ?>
	<?php if ( 'closed' == $post->comment_status ) : ?>
		<p class="note"><?php _e( 'Comments are closed.', 'traction' ); ?></p>
	<?php endif; ?>
	<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( 'closed' == $post->comment_status) : ?>
		<?php if (!is_page()) : ?>
			<p class="note"><?php _e( 'Comments are closed.', 'traction' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
</div><!--end comments-->

<?php if ( 'open' == $post->comment_status) : ?>
	<div id="respond">
		<div class="cancel-comment-reply">
			<small><?php cancel_comment_reply_link(); ?></small>
		</div>
		<h4 id="postcomment"><?php comment_form_title(__( 'Share your thoughts, post a comment.', 'traction' )); ?></h4>
		<?php if ( get_option( 'comment_registration' ) && !$user_ID ) : ?>
			<p><?php _e( 'You must be', 'traction' ); ?> <a href="<?php echo get_option( 'siteurl' ); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e( 'logged in', 'traction' ); ?></a> <?php _e( 'to post a comment.', 'traction' ); ?></p>
			</div><!--end respond-->
		<?php else : ?>
		<form action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post" id="commentform">
			<?php if ( $user_ID ) : ?>
				<p><?php _e( 'Logged in as', 'traction' ); ?> <a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e( 'Log out &raquo;', 'traction' ); ?></a></p>
			<?php else : ?>
				<fieldset>
					<label for="author" class="comment-field"><?php _e( 'Name', 'traction' ); ?></label>
					<input class="text-input" type="text" name="author" id="author" value="<?php echo $comment_author; ?>"	tabindex="1" />
					<?php if ($req) _e( '<span>(required)</span>' ); ?>
				</fieldset>
				<fieldset>
					<label for="email" class="comment-field"><?php _e( 'Email', 'traction' ); ?></label>
					<input class="text-input" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" />
					<?php if ($req) _e( '<span>(required)</span>' ); ?>
				</fieldset>
				<fieldset>
					<label for="url" class="comment-field"><?php _e( 'Website', 'traction' ); ?></label>
					<input class="text-input" type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" />
				</fieldset>
			<?php endif; ?>
			<fieldset>
				<label for="comment" class="comment-field"><?php _e( 'Comment', 'traction' ); ?></label>
				<textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea>
			</fieldset>
			<div class="comments-submit">
				<p class="guidelines"><?php _e( '<strong>Note:</strong> HTML is allowed. Your email address will <strong>never</strong> be published.', 'traction' ); ?></p>
				<p class="comments-rss"><?php comments_rss_link(__( 'Subscribe to comments', 'traction' )); ?></p>
				<?php do_action( 'comment_form', $post->ID); ?>
				<fieldset>
					<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e( 'Submit Comment', 'traction' ); ?>" />
					<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
				</fieldset>
				<div>
					<?php comment_id_fields(); ?>
				</div>
			</div>
		</form><!--end commentform-->
	</div><!--end respond-->
<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>