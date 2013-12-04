<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="alert">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>

<div id="commentList">
	<div class="subTitle">
		<h4><strong>Comments on:</strong> "<?php the_title(); ?>" (<?php comments_number('0', '1', '%') ;?>)</h4>
	</div>
	<ol class="commentlist">
		<?php wp_list_comments('avatar_size=48&callback=spectrum_comments'); ?>
	</ol>
</div>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<div id="commentForm">
	<div class="subTitle">
		<h4><strong>Leave a comment for:</strong> "<?php the_title(); ?>"</h4>
	</div>
	<form method="post" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php">
		<p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
		<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
		<p class="logged-in-as">You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</p>
		<?php else : ?>
		<?php if ( is_user_logged_in() ) : ?>
		<p class="logged-in-as">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>
		<?php else : ?>
		<p>
			<label for="author"><strong>Name</strong></label>
			<input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" />
		</p>
		<p>
			<label for="email"><strong>E-mail</strong></label>
			<input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" />
		</p>
		<p>
			<label for="url"><strong>Website</strong> <em>(optional)</em></label>
			<input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
		</p>
		<?php endif; ?>
		<p>
			<label for="comment"><strong>Message</strong></label>
			<textarea name="comment" id="comment" cols="" rows=""></textarea>
		</p>
		<p>
			<button type="submit">Post Comment</button>
		</p>
		<?php comment_id_fields(); ?>
		<?php do_action('comment_form', $post->ID); ?>
	</form>

	<?php endif; // If registration required and not logged in ?>

</div>

<?php endif; // if you delete this the sky will fall on your head ?>