<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','arthemia');?></p>

			<?php
			return;
		}
	}

?>

<!-- You can start editing here. -->


<?php if ($comments) : ?>

<style type="text/css">
/* Comments*/

#comments {
	line-height:1.5em;
	}

.commentlist cite {
	font-style:normal;
	margin-bottom:0px;
	display:block;
	font-size:11px;
	}	

.commentlist blockquote {
	background:#ededed;
	}
			
.commentlist li {
	color:#4d4d4d;
	padding: 10px 14px 10px 14px;
	background:#f2f2f2;
	border-bottom: 1px solid #dcdbd7;
	list-style:none;
	margin-bottom:3px;
	line-height:1.25em;
	}

.commentlist li li {
	background:none;
	border:none;
	list-style:square;
	margin:3px 0 3px 20px;
	padding:3px 0;
	}

.commenttext {
	width:482px;
	float:right;
	line-height:1.5em;
    font-size:11px;
	}
	
li.my_comment {
	background: #fff;
	}

li cite strong {
	font-size: 14px;
	color:#313228;
	}
	
#commentform small {
	background:#FFF;
	font-weight:bold;
	padding:0; 
	}
	
.commentmetadata {
	color:#4d4d4d;
	display: block;
	margin-top:3px;
	text-align:right;
	font-size:10px;
	}

.commentmetadata a, .commentmetadata a:visited {
	color:#959382;
	}
	
.commentlist small {
	background:#e9e9e9;
	}

.commentlist li .avatar {
	border:1px solid #ccc;
	margin:15px 8px 6px 0;
	float:left;
	padding:2px;
	width:45px;
	height:45px;
	}	

#comment {
	width:590px;
	background:#fff;
	}
</style>

	<h3 id="comments"><?php comments_number(__('No Comment','arthemia'), __('One Comment','arthemia'), __('% Comments','arthemia') );?> <a href="#respond" title="<?php _e('Leave a comment','arthemia'); ?>">&raquo;</a></h3>
<ul class="commentlist">

	<?php foreach ($comments as $comment) : ?>
	
<?php
$isByAuthor = false;
if($comment->comment_author_email == get_the_author_email()) {
$isByAuthor = true;
}?>
		<div class="commentlist">

		<li id="comment-<?php comment_ID() ?>" <?php if($isByAuthor ) { echo 'class="my_comment"';} ?>>

		<cite><strong><?php comment_author_link() ?> <?php if($isByAuthor ) { echo '(author)';} ?> </strong> <?php _e('said:','arthemia');?> </cite>
	
		<div class="clearfloat">
		<?php echo get_avatar( $comment, $size = '45' ); ?>

		<div class="commenttext">
			<?php if ($comment->comment_approved == '0') : ?>
			<p><em><?php _e('Your comment is awaiting moderation.');?></em></p>
			<?php endif; ?>
			<?php comment_text() ?>
		</div>	
		
		</div>
		
		<div class="commentmetadata">- <?php comment_date('j F Y') ?> <?php _e('at','arthemia');?> <?php comment_time() ?> </div>
		</li>


	<?php endforeach; /* end for each comment */ ?>
</ul>
	

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.','arthemia');?></p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>


<h3 id="respond"><?php _e('Leave a comment!','arthemia'); ?></h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php _e('You must be','arthemia'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('logged in','arthemia'); ?></a> <?php _e('to post a comment.','arthemia'); ?></p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p><?php _e('Logged in as','arthemia'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account','arthemia'); ?>"><?php _e('Logout','arthemia'); ?> &raquo;</a></p>

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

<p><textarea name="comment" id="comment" cols="100%" rows="15" tabindex="4"></textarea></p>
<p><?php _e('You can use these tags:','arthemia'); ?><br/><code><?php echo allowed_tags(); ?></code></p>
<p><?php _e('This is a Gravatar-enabled we<a href="http://amirariff.com">blog</a>. To get your own globally-recognized-avatar, please register at','arthemia'); ?> <a href="http://www.gravatar.com">Gravatar</a>.</p>

<p><input name="submit" class="submitbutton" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment','arthemia'); ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; ?>


