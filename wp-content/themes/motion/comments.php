<?php // Do not delete these lines
// thanks to Jeremy at http://clarktech.no-ip.com for the tips

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ( 'Please do not load this page directly. Thanks!' );

if ( function_exists( 'post_password_required' ) ) {
	if ( post_password_required() ) {
		echo '<p class="nocomments">This post is password protected. Enter the password to view comments.</p>';
		return;
	}
} else {
	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { // and it doesn't match the cookie  ?>
			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
			<?php return;
		}
	}
}

if ( function_exists( 'wp_list_comments' ) ):

//WP 2.7 Comment Loop
if ( have_comments() ) : ?>

	<?php if ( ! empty($comments_by_type['comment']) ) :
	$count = count($comments_by_type['comment']);
	($count !== 1) ? $txt = "Comments: " : $txt = "Comment:"; ?>
	<h3><?php echo $count . " " . $txt; ?></h3>
	<ul class="commentlist">
		<?php wp_list_comments( 'type=comment&callback=motiontheme_comment' ); ?>
	</ul>
	<?php endif; ?>

	<div id="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div><!-- /navigation -->

	<?php if ( ! empty($comments_by_type['pings']) ) :
	$countp = count($comments_by_type['pings']);
	($countp !== 1) ? $txtp = "Trackbacks / Pingbacks for this entry:" : $txtp = "Trackback or Pingback for this entry:"; ?>
	<h3 id="trackbacktitle"><?php echo $countp . " " . $txtp; ?></h3>
	<ul class="trackback">
	<?php wp_list_comments( 'type=pings&callback=motiontheme_ping' ); ?>
	</ul>
	<?php endif; ?>

<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( 'open' == $post->comment_status ) :
		// If comments are open, but there are no comments.
	else : ?>
	<?php endif;

endif;

else :
//WP 2.6 and older Comment Loop
/* This variable is for alternating comment background */
		$oddcomment = 'alt';
?>

<!-- You can start editing here. -->
<?php if ($comments) : ?>
	<h3 id="comments"><?php comments_number( 'No comments filed', 'One comment', '% comments' );?> to &#8220;<?php the_title(); ?>&#8221;</h3>
	<ul class="commentlist">
	<?php foreach ( $comments as $comment ) : ?>
		<li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID(); ?>">

<?php
if ( function_exists( 'get_avatar' ) ) {
      echo get_avatar( $comment, 50);
   } else {
      //alternate gravatar code for < 2.5
      $grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=
         " . md5($email) . "&default=" . urlencode($default) . "&size=" . $size;
      echo "<img src='$grav_url' height='50px' width='50px' />";
   }
?>
			<div class="commentbody">
				<div class="author"><?php comment_author_link(); ?></div>
				<?php if ($comment->comment_approved == '0' ) : ?>
				<em>(Your comment is awaiting moderation...)</em>
				<?php endif; ?>

				<div class="commentmetadata"><a href="#comment-<?php comment_ID(); ?>" title=""><?php comment_date( 'F jS, Y' ); ?> at <?php comment_time(); ?></a> <?php edit_comment_link( 'edit', '&nbsp;&nbsp;' , '' ); ?></div>

				<?php comment_text(); ?>
			</div><!-- /commentbody -->
		</li>

	<?php /* Changes every other comment to a different class */
		if ( 'alt' == $oddcomment ) $oddcomment = '';
		else $oddcomment = 'alt';
	?>
	<?php endforeach; /* end for each comment */ ?>
	</ol>
 <?php else : // this is displayed if there are no comments so far ?>

  <?php if ( 'open' == $post->comment_status ) : ?>
		<!-- If comments are open, but there are no comments. -->
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
	<p class="nocomments">Comments are closed.</p>

<?php endif; ?>

<?php endif; ?>

<?php endif; // 2.6 and older Comment Loop end ?>

<?php if ( 'open' == $post->comment_status ) : ?>
<div id="respond">
<h3>Leave a Comment:</h3>
<?php if ( function_exists( 'cancel_comment_reply_link' ) ) {
//2.7 comment loop code ?>
<div id="cancel-comment-reply">
	<small><?php cancel_comment_reply_link( '(... Or click here to cancel reply.)' );?></small>
</div>
<?php } ?>

<?php if ( get_option( 'comment_registration' ) && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option( 'siteurl' ); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p></div>
<?php else : ?>
<form action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>
	<?php
		if ( function_exists( 'wp_logout_url' ) ) {
			$logout_link = wp_logout_url();
		} else {
			$logout_link = get_option( 'siteurl' ) . '/wp-login.php?action=logout';
		}
	?>
<p>Logged in as <a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>.
<a href="<?php echo $logout_link; ?>" title="Log out"><?php _e( 'Logout &raquo;' ); ?></a></p>
<?php else : ?>

<p><label>Name<?php if ($req) { ?> (required)<?php } ?></label>
<input type="text" name="author" id="author" size="22" tabindex="1" /></p>
<p><label>Email<?php if ($req) { ?> (required)<?php } ?></label>
<input type="text" name="email" id="email" size="22" tabindex="2" /></p>
<p><label>Website</label>
<input type="text" name="url" id="url" size="22" tabindex="3" /></p>

<?php endif; ?>

<?php if ( function_exists( 'cancel_comment_reply_link' ) ) {
//2.7 comment loop code ?>
 <p><?php comment_id_fields(); ?></p>
<?php } ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: &lt;a href=&quot;&quot; title=&quot;&quot;&gt; &lt;abbr title=&quot;&quot;&gt; &lt;acronym title=&quot;&quot;&gt; &lt;b&gt; &lt;blockquote cite=&quot;&quot;&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=&quot;&quot;&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=&quot;&quot;&gt; &lt;strike&gt; &lt;strong&gt; </small></p>-->
<p>
	<textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4" onfocus="if (this.value == 'Enter your comments here...' ) { this.value = ''; }" onblur="if (this.value == '' ) { this.value = 'Enter your comments here...'; }">Enter your comments here...</textarea>
</p>
<p>
	<input name="submit" type="submit" id="submit" class="submitbutton" tabindex="5" value="Submit Comment" />
	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>

<div>
	<?php do_action( 'comment_form', $post->ID ); ?>
</div>

</form>
</div><!-- /respond -->

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>