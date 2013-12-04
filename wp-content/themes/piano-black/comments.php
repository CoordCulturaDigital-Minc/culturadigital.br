<div id="comments">

<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
if (function_exists('post_password_required')) 
	{
	if ( post_password_required() ) 
		{
		echo '<div class="nocomments"><p>';_e('This post is password protected. Enter the password to view comments.','piano-black'); echo '</p></div></div>';
		return;
		}
	} else 
	{
	if (!empty($post->post_password)) 
		{ // if there's a password
			if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) 
			{  // and it doesn't match the cookie  ?>
				<div class="nocomments"><p><?php _e('This post is password protected. Enter the password to view comments.','piano-black'); ?></p></div></div>
				<?php return;
			}
		}
	}
?>

<?php  //custom comments function by mg12 - http://www.neoease.com/  ?>

<?php
       if (function_exists('wp_list_comments')) { $trackbacks = $comments_by_type['pings']; }
       else { $trackbacks = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1' AND (comment_type = 'pingback' OR comment_type = 'trackback') ORDER BY comment_date", $post->ID)); }
?>

<?php if ($comments || comments_open()) ://if there is comment and comment is open ?>

 <div id="comment-header">
  <ul id="comment-header-top" class="clearfix">
   <li id="comment-feed"><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('Comments RSS','piano-black'); ?>"></a></li>
   <li id="comment-title"><?php the_title(); ?></li>
  </ul>
  <div id="comment-header-bottom" class="clearfix">
   <ul class="switch">
<?php if(pings_open()) ://if trackback is open ?>
    <li id="comment-switch" class="active"><a href="javascript:void(0);" onclick="MGJS.switchTab('comment-list', 'trackback-list', 'comment-switch', 'active', 'trackback-switch', 'non-active');"><?php _e('Comments','piano-black'); ?><?php echo (' ( ' . (count($comments)-count($trackbacks)) . ' )'); ?></a></li>
    <li id="trackback-switch" class="non-active"><a href="javascript:void(0);" onclick="MGJS.switchTab('trackback-list', 'comment-list', 'trackback-switch', 'active', 'comment-switch', 'non-active');"><?php _e('Trackbacks','piano-black'); ?><?php echo (' ( ' . count($trackbacks) . ' )'); ?></a></li>
<?php else ://if comment is closed,show onky number ?>
    <li><?php _e('Comments', 'piano-black'); echo (' (' . (count($comments)-count($trackbacks)) . ')'); ?></li>
    <li id="trackback-closed"><?php _e('Trackbacks are closed.','piano-black'); ?></li>
<?php endif; ?>
   </ul>
<?php if(comments_open()) ://if comment is open ?>
   <a href="#respond" id="add-comment"><?php _e('Write comment','piano-black'); ?></a>
<?php endif; ?>

<?php if(pings_open()) ://if trackback is open ?>

<?php endif; ?>
  </div><!-- comment-header-bottom END -->
 </div><!-- comment-header END -->


<div id="comment-list">
<!-- start commnet -->
<ol class="commentlist">
	<?php
		if ($comments && count($comments) - count($trackbacks) > 0) {
			// for WordPress 2.7 or higher
			if (function_exists('wp_list_comments')) {
				wp_list_comments('type=comment&callback=custom_comments');
			// for WordPress 2.6.3 or lower
			} else {
				foreach ($comments as $comment) {
					if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') {
						custom_comments($comment, null, null);
					}
				}
			}
		} else {
	?>
<li class="comment"><p><?php _e('No comments yet.','piano-black'); ?></p></li>
	<?php
		}
	?>
</ol>
<!-- comments END -->

<?php //if you select comment pager from comment option
	if (get_option('page_comments')) {
		$comment_pages = paginate_comments_links('echo=0');
		if ($comment_pages) {
?>

<div class="page-navi clearfix" id="comment-pager">
 <?php echo $comment_pages; ?>
</div>

<?php } } ?>

</div><!-- #comment-list END -->


<div id="trackback-list">
<!-- start trackback -->
<?php if (pings_open()) ://id trackback is open ?>

<div id="trackback-url">
<label for="trackback_url"><?php _e('TrackBack URL' , 'piano-black'); ?></label>
<input type="text" name="trackback_url" id="trackback_url" size="60" value="<?php trackback_url() ?>" readonly="readonly" onfocus="this.select()" />
</div>

<ol class="commentlist">

<?php if ($trackbacks) : $trackbackcount = 0; ?>

<?php foreach ($trackbacks as $comment) : ?>
<li class="comment">
 <div class="trackback-time">
  <?php echo get_comment_time(__('F jS, Y', 'piano-black')) ?>
  <?php edit_comment_link(__('[ EDIT ]', 'piano-black'), '', ''); ?>
 </div>
 <div class="trackback-title">
  <?php _e('Trackback from : ' , 'piano-black'); ?><a href="<?php comment_author_url() ?>"><?php comment_author(); ?></a>
 </div>
</li>
<?php endforeach; ?>

<?php else : ?>
<li class="comment"><p><?php _e('No trackbacks yet.','piano-black'); ?></p></li>
<?php endif; ?>
</ol>
<?php endif; ?>
<!-- trackback end -->
</div><!-- #trackbacklist END -->

<?php endif;//comment is open ?>




<?php if (!comments_open()) : // if comment are closed ?>

<div class="comment-closed" id="respond">
<?php _e('Comments are closed.','piano-black'); ?>
</div>

<a href="#pngfix-right" id="back-top"><?php _e('Return top','piano-black'); ?></a>




<?php elseif ( get_option('comment_registration') && !$user_ID ) : // If registration required and not logged in. ?>

<div class="comment-form-area" id="respond">
 <?php if (function_exists('wp_login_url')) 
        { $login_link = wp_login_url();  }
       else 
        { $login_link = get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink()); }
 ?>
<?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'piano-black'), $login_link); ?>
</div>




<?php else ://if comment is open ?>

<div class="comment-form-area" id="respond">

<?php if (function_exists('comment_reply_link')) { ?>
<div id="cancel-comment-reply"><?php cancel_comment_reply_link() ?></div>
<?php } ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>
<div id="comment-user-login">
<?php if (function_exists('wp_logout_url')) { ?>
<p><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.', 'piano-black'), get_option('siteurl') . '/wp-admin/profile.php', $user_identity); ?><span><a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account', 'piano-black'); ?>"><?php _e('Log out', 'piano-black'); ?></a></span></p>
<?php } else { ?>
<p><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.', 'piano-black'), get_option('siteurl') . '/wp-admin/profile.php', $user_identity); ?><span><a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account', 'piano-black'); ?>"><?php _e('Log out', 'piano-black'); ?></a></span></p>
<?php } ?>
</div><!-- #comment-user-login END -->
<?php else : ?>
<div id="guest-info">
 <div id="guest-name"><label for="author"><span><?php _e('NAME','piano-black'); ?></span><?php if ($req) _e('( required )', 'piano-black'); ?></label><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> /></div>
 <div id="guest-email"><label for="email"><span><?php _e('E-MAIL','piano-black'); ?></span><?php if ($req) _e('( required )', 'piano-black'); ?> <?php _e('- will not be published -','piano-black'); ?></label><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> /></div>
 <div id="guest-url"><label for="url"><span><?php _e('URL','piano-black'); ?></span></label><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" /></div>
</div>
<?php endif; ?>

<div id="comment-textarea">
 <textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea>
</div>

<div id="comment-submit-area">
 <input name="submit" type="submit" id="comment-submit" class="button" tabindex="5" value="<?php _e('Submit Comment', 'piano-black'); ?>" title="<?php _e('Submit Comment', 'piano-black'); ?>" alt="<?php _e('Submit Comment', 'piano-black'); ?>" />
</div>

<div id="input_hidden_field">
<?php if (function_exists('comment_id_fields')) { ?>
<?php comment_id_fields(); ?> 
<?php } else { ?>
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
<?php } ?>

<?php do_action('comment_form', $post->ID); ?>
</div>

</form>
</div><!-- #comment-form-area END -->

<?php endif; ?>
</div><!-- #comment END-->