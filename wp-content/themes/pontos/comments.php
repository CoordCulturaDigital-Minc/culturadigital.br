<?php 

// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) { ?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','arthemia');?></p>
	<?php return;
}

// add a microid to all the comments
function comment_add_microid($classes) {
	$c_email=get_comment_author_email();
	$c_url=get_comment_author_url();
	if (!empty($c_email) && !empty($c_url)) {
		$microid = 'microid-mailto+http:sha1:' . sha1(sha1('mailto:'.$c_email).sha1($c_url));
		$classes[] = $microid;
	}
	return $classes;	
}
add_filter('comment_class','comment_add_microid');

?>

<!-- You can start editing here. -->

<?php if (have_comments()) : ?>

<h3><?php comments_number(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia') );?> &raquo; </h3>

<ul class="commentlist" id="singlecomments">
	<?php wp_list_comments(array('avatar_size'=>45, 'reply_text'=>__('Reply to this comment &raquo;','arthemia'))); ?>
</ul>

<div class="navigation">
<div class="alignleft"><?php previous_comments_link(); ?></div>
<div class="alignright"><?php next_comments_link(); ?></div>
</div>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<p class="nocomments"><?php _e('Comments are closed.','arthemia');?></p>

	
	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

    <div id="respond">
    <h3><?php comment_form_title(__('Leave a comment!','arthemia'), __('Leave a comment to %s','arthemia')); ?></h3>
      
    <div id="cancel-comment-reply">
	<p><?php cancel_comment_reply_link(__('Click here to cancel reply &raquo;','arthemia')); ?></p>
    </div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php _e('You must be','arthemia'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('logged in','arthemia'); ?></a> <?php _e('to post a comment.','arthemia'); ?></p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p><?php _e('Logged in as','arthemia'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account','arthemia'); ?>"><?php _e('Logout','arthemia'); ?> &raquo;</a></p>

<?php else : ?>

<p><?php _e('Add your comment below, or','arthemia'); ?> <a href="<?php trackback_url(true); ?>" rel="trackback"><?php _e('trackback','arthemia'); ?></a> <?php _e('from your own site','arthemia'); ?>. <?php _e('You can also','arthemia'); ?> <?php comments_rss_link(__('subscribe to these comments','arthemia')); ?> <?php _e('via RSS.','arthemia');?></p>

<p><?php _e('Be nice. Keep it clean. Stay on topic. No spam.','arthemia'); ?></p>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" class="field" />
<label for="author"><small><?php _e('Name','arthemia'); ?> <?php if ($req) echo __('(required)','arthemia'); ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" class="field" />
<label for="email"><small><?php _e('Mail','arthemia'); ?> <?php _e('(will not be published)','arthemia'); ?> <?php if ($req) echo __('(required)','arthemia'); ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" class="field" />
<label for="url"><small><?php _e('Website','arthemia'); ?> <?php _e('(optional)','arthemia'); ?></small></label></p>

<?php endif; ?>

<div>
<?php comment_id_fields(); ?>
<input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" /></div>

<p><textarea name="comment" id="comment" cols="100%" rows="15" tabindex="4" class="field"></textarea></p>
<p><?php _e('You can use these tags:','arthemia'); ?><br/><code><?php echo allowed_tags(); ?></code></p>
<p><?php _e('This is a Gravatar-enabled weblog. To get your own globally-recognized-avatar, please register at','arthemia'); ?> <a href="http://www.gravatar.com">Gravatar</a>.</p>

<p><input name="submit" class="submitbutton" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment','arthemia'); ?>" />
<?php do_action('comment_form', $post->ID); ?>

<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>

</form>


<?php endif; // If registration required and not logged in ?>

</div>

<?php endif; ?>


