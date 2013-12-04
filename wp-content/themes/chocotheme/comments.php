<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	
	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>
<?php if ( comments_open() ) : ?>
	<div id="respond">
		<h3>Leave a Reply</h3>
		
		<div class="cancel-comment-reply">
			<small><?php cancel_comment_reply_link(); ?></small>
		</div>
		
		<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
			<p>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</p>
		<?php else : ?>
			<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
				<?php if ( is_user_logged_in() ) : ?>
					<p>
						Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. 
						<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a>
					</p>
				<?php else : ?>
					<div class="left">
						<label for="author">Name <?php if ($req) echo "(required)"; ?></label>
						<div class="field"><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> /></div>
						
						<label for="email">Mail (will not be published) <?php if ($req) echo "(required)"; ?></label>
						<div class="field"><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> /></div>
						
						<label for="url">Website</label>
						<div class="field"><input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="3" /></div>
					</div>
				<?php endif; ?>
				<div class="<?php echo is_user_logged_in() ? '' : 'right' ?>">
					<label for="comment">Comment</label>
					<div class="textarea"><textarea name="comment" id="comment" cols="40" rows="10" tabindex="4" class="field"></textarea></div>
				</div>
				<div class="cl">&nbsp;</div>
                <input name="submit" type="submit" id="submit" tabindex="5" class="button <?php echo is_user_logged_in() ? 'userloggedbtn' : '' ?>" value="Submit Comment" />
				<?php comment_id_fields(); ?>
				<?php do_action('comment_form', $post->ID); ?>
				<div class="cl">&nbsp;</div>
			</form>
		<?php endif; // If registration required and not logged in ?>
	</div>
<?php endif; ?>

<?php if ( have_comments() ) : ?>
	<div id="comments">
		<div class="navigation commentsnavigation">
			<div class="cl">&nbsp;</div>
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
			<div class="cl">&nbsp;</div>
		</div>
		<ol class="commentlist">
			<?php wp_list_comments('callback=print_comment'); ?>
		</ol>
		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
	</div>
<?php else : ?>
	<?php if ( comments_open() ) : ?>
        <!-- If comments are open, but there are no comments. -->
	 <?php else : // comments are closed ?>
		<p class="nocomments">Comments are closed.</p>
	<?php endif; ?>
<?php endif; ?>

