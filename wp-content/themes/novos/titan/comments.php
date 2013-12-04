<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ( 'Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'titan' ); ?></p>
	<?php
		return;
	} ?>
<!-- You can start editing here. -->
<div id="comments">
<?php if ( have_comments() ) : ?>
	<div class="comment-number">
		<span><?php comments_number( __( 'No Comments Yet', 'titan'), __( 'One Comment', 'titan'), __( '% Comments', 'titan')); ?></span>
		<?php if ( 'open' == $post->comment_status) : ?>
			<a id="leavecomment" href="#respond" title="<?php _e( 'Leave One', 'titan'); ?>"> &rarr;</a>
		<?php endif; ?>
	</div><!--end comment-number-->
	<ol class="commentlist">
		<?php wp_list_comments( 'type=comment&callback=custom_comment'); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php next_comments_link(__ ( '&laquo; Older Comments', 'titan')); ?></div>
		<div class="alignright"><?php previous_comments_link(__ ( 'Newer Comments &raquo;', 'titan')); ?></div>
	</div>
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
		<h3 class="pinghead"><?php _e( 'Trackbacks &amp; Pingbacks', 'titan' ); ?></h3>
		<ol class="pinglist">
			<?php wp_list_comments( 'type=pings&callback=list_pings'); ?>
		</ol>
	<?php endif; ?>
	<?php if ( 'closed' == $post->comment_status ) : ?>
		<p class="note"><?php _e( 'Comments are closed.', 'titan' ); ?></p>
	<?php endif; ?>
	<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( 'open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->
		<div class="comment-number">
			<span><?php _e( 'No comments yet', 'titan' ); ?></span>
		</div>
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<?php if (!is_page()) : ?>
			<p class="note"><?php _e( 'Comments are closed.', 'titan' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
</div><!--end comments-->

<?php if ( 'open' == $post->comment_status) : ?>

	<div id="respond">
		<div class="cancel-comment-reply">
			<small><?php cancel_comment_reply_link(); ?></small>
		</div>
		<h4 id="postcomment"><?php comment_form_title(__ ( 'Leave a Reply', 'titan' ), __( 'Leave a Reply', 'Leave a Reply to %s', 'titan' )); ?></h4>
		<?php if ( get_option( 'comment_registration' )&& !$user_ID ) : ?>
			<p><?php _e( 'You must be', 'titan' ); ?> <a href="<?php echo get_option( 'siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e( 'logged in', 'titan' ); ?></a> <?php _e( 'to post a comment.', 'titan' ); ?></p>
			</div><!--end respond-->
		<?php else : ?>
		<form action="<?php echo get_option( 'siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
			<?php if ( $user_ID ) : ?>
				<p><?php _e( 'Logged in as', 'titan' ); ?> <a href="<?php echo get_option( 'siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e( 'Log out &raquo;', 'titan' ); ?></a></p>
			<?php else : ?>
				<fieldset>
					<label for="author" class="comment-field"><small><?php _e( 'Name:', 'titan' ); ?> <?php if ($req) _e( '(required)'); ?>:</small></label>
					<input class="text-input" type="text" name="author" id="author" value="<?php echo $comment_author; ?>"	tabindex="1" />
				</fieldset>
				<fieldset>
					<label for="email" class="comment-field"><small><?php _e( 'Email:', 'titan' ); ?> <?php if ($req) _e( '(required)'); ?>:</small></label>
					<input class="text-input" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" />
				</fieldset>
				<fieldset>
					<label for="url" class="comment-field"><small><?php _e( 'Website:', 'titan' ); ?></small></label>
					<input class="text-input" type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" />
				</fieldset>
			<?php endif; ?>
			<fieldset>
				<label for="comment" class="comment-field"><small><?php _e( 'Comment:', 'titan' ); ?></small></label>
				<textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea>
			</fieldset>
			<p class="guidelines"><?php _e( '<strong>Note:</strong> XHTML is allowed. Your email address will <strong>never</strong> be published.', 'titan' ); ?></p>
			<p class="comments-rss"><?php comments_rss_link(__( 'Subscribe to this comment feed via RSS', 'titan')); ?></p>
			<?php do_action( 'comment_form', $post->ID); ?>
			<fieldset>
				<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e( 'Submit Comment', 'titan' ); ?>" />
				<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
			</fieldset>
			<?php comment_id_fields(); ?>
		</form><!--end commentform-->
	</div><!--end respond-->

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>