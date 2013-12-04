<?php /* Fusion/digitalnature */ ?>
<?php if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
<p class="error"><?php _e('Enter your password to view comments','fusion'); ?></p>
<?php return; endif; ?>

<?php

 // Related posts. Based on http://www.bin-co.com/blog/2009/04/show-related-post-in-wordpress-without-a-plugin/
 $tags = wp_get_post_tags($post->ID);
 if ($tags) {
  $tag_ids = array();
  foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;

  $args=array(
   'tag__in' => $tag_ids,
   'post__not_in' => array($post->ID),
   'showposts'=> 10, // Number of related posts that will be shown.
   'caller_get_posts'=> 1
  );
  $rp_query = new wp_query($args);
  }
?>

<?php if ($comments || comments_open()) : ?>
<?php
  /* Count the totals */
  $numPingBacks = 0;
  $numComments  = 0;

  /* Loop throught comments to count these totals */
  foreach ($comments as $comment)
    if (get_comment_type() != "comment") $numPingBacks++; else $numComments++;
?>
<div id="post-extra-content">
  <?php if(get_option('fusion_jquery')<>'no') { ?>
    <!-- comments and trackback tabs -->
    <ul class="secondary-tabs">
      <li><a href="#tab-1"><span><span><?php _e('Comments','fusion'); echo ' ('.$numComments.')'; ?></span></span></a></li>
      <?php if($numPingBacks>0) { ?><li><a href="#tab-2"><span><span><?php _e('Trackbacks','fusion'); echo ' ('.$numPingBacks.')';?></span></span></a></li><?php } ?>
      <?php if($rp_query) if($rp_query->have_posts()) { ?><li><a href="#tab-3"><span><span><?php _e('Related Posts','fusion'); ?></span></span></a></li><?php } ?>
    </ul>
    <!-- /comments and trackback tabs -->
  <?php } ?>
  <!-- comments -->
  <div id="tab-1">
   <ol id="comments">
    <?php
      if ($numComments > 0) {

      // for WordPress 2.7 or higher
  	if (function_exists('wp_list_comments')) { wp_list_comments('type=comment&callback=list_comments');	}
      else
        { // for WordPress 2.6.3 or lower
  	    foreach ($comments as $comment)
    		  if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') list_comments($comment, null, null);
        }
  	}
      else { ?>
  	  <li><?php _e('No comments yet.','fusion'); ?></li>
  	<?php }	?>
    </ol>
    <?php
      if (get_option('page_comments')) {
       $comment_pages = paginate_comments_links('echo=0');
      if ($comment_pages) { ?>
       <div class="commentnavi">
  	    <div class="commentpager">
  	    	<?php echo $comment_pages; ?>
  	    </div>
       </div>
      <?php
  	  }
  	 }
      ?>
    <?php
    if (comments_open()) :
     if (get_option('comment_registration') && !$user_ID ) { // If registration required and not logged in. ?>
  	<div id="comment_login" class="messagebox">
  	  <?php if (function_exists('wp_login_url')) $login_link = wp_login_url(); else $login_link = get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink()); ?>
    	  <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'fusion'), $login_link); ?></p>
  	</div>

     <?php } else { ?>

      <div id="respond">
      <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
        <?php if (function_exists('cancel_comment_reply_link')) { ?><div class="cancel-comment-reply"><?php cancel_comment_reply_link(__('Cancel Reply','fusion')); ?></div><?php } ?>
        <?php if ($user_ID) : ?>
          <?php if (function_exists('wp_logout_url')) $logout_link = wp_logout_url(); else $logout_link = get_option('siteurl') . '/wp-login.php?action=logout';	?>
      	  <p>
           <?php
            $login_link = get_option('siteurl')."/wp-admin/profile.php";
            printf(__('Logged in as %s.', 'fusion'), '<a href="'.$login_link.'"><strong>'.$user_identity.'</strong></a>');
           ?>
           <a href="<?php echo $logout_link; ?>" title="<?php _e('Log out of this account', 'fusion'); ?>"><?php _e('Logout &raquo;', 'fusion'); ?></a>
          </p>
       	  <?php else : ?>
  	      <?php if ($comment_author != "") : ?>
  		  <p><?php printf(__('Welcome back <strong>%s</strong>.', 'fusion'), $comment_author) ?> <span id="show_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','');MGJS.setStyleDisplay('show_author_info','none');MGJS.setStyleDisplay('hide_author_info','');"> <?php _e('Change &raquo;','fusion'); ?></a></span> <span id="hide_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','none');MGJS.setStyleDisplay('show_author_info','');MGJS.setStyleDisplay('hide_author_info','none');"><?php _e('Close &raquo;','fusion'); ?></a></span></p>
          <?php endif; ?>
          <div id="author_info">
            <div class="row">
              <input type="text" name="author" id="author" class="textfield" value="<?php echo $comment_author; ?>" size="24" tabindex="1" />
              <label for="author" class="small"><?php _e("Name","fusion"); ?> <?php if ($req) _e("(required)","fusion"); ?></label>
            </div>
            <div class="row">
              <input type="text" name="email" id="email" class="textfield" value="<?php echo $comment_author_email; ?>" size="24" tabindex="2" />
              <label for="email" class="small"><?php _e("E-Mail","fusion"); ?> <?php if ($req) _e("(required)","fusion"); ?></label> <em><?php _e("(will not be published)","fusion"); ?></em>
            </div>
            <div class="row">
              <input type="text" name="url" id="url" class="textfield" value="<?php echo $comment_author_url; ?>" size="24" tabindex="3" />
              <label for="url" class="small"><?php _e("Website","fusion"); ?></label>
            </div>
  		  </div>
          <?php if ( $comment_author != "" ) : ?>
  	   	  <script type="text/javascript">MGJS.setStyleDisplay('hide_author_info','none');MGJS.setStyleDisplay('author_info','none');</script>
  	  	  <?php endif; ?>
        <?php endif; ?>

        <!-- comment input -->
        <div class="row">
        	<textarea name="comment" id="comment" tabindex="4" rows="8" cols="50"></textarea>
        	<?php if (function_exists('highslide_emoticons')) : ?><div id="emoticon"><?php highslide_emoticons(); ?></div><?php endif; ?>
        	<?php if (function_exists('comment_id_fields')) : comment_id_fields(); endif; do_action('comment_form', $post->ID); ?>

        <?php
          // Math Comment Spam Protection Plugin
          if ( function_exists('math_comment_spam_protection') ) {
       	    $mcsp_info = math_comment_spam_protection();
          ?><p><input type="text" name="mcspvalue" id="mcspvalue" value="" size="22" tabindex="4" />
	        <label for="mcspvalue"><?php  printf(__('Spam protection: Sum of %s + %s = ?', 'fusion'), $mcsp_info['operand1'],$mcsp_info['operand2']); ?></label>
	        <input type="hidden" name="mcspinfo" value="<?php echo $mcsp_info['result']; ?>" />
            </p>
          <?php } ?>
         <br />
        </div>
        <!-- /comment input -->

        <!-- comment submit and rss -->
        <div id="submitbox" class="left">
         <span class="button submit"><a title="<?php _e("Submit Comment","fusion"); ?>" href="javascript:jQuery('#commentform').submit();"><span><span><?php _e("Submit Comment","fusion"); ?></span></span></a></span>
         <input type="hidden" name="formInput" />
        </div>
        <div class="clear"></div>
      </form>
    </div>
    <?php } ?>
    <?php endif;  ?>
  </div>
  <!-- /comments -->

  <!-- trackbacks -->
  <?php if((get_option('fusion_jquery')<>'no') && ($numPingBacks > 0)) { ?>
  <div id="tab-2">
    <ol id="trackbacks">
     <?php wp_list_comments('type=pings&callback=list_pings'); ?>
    </ol>
  </div>
  <?php } ?>
  <!-- /trackbacks -->

  <!-- related posts -->
  <?php
    if((get_option('fusion_jquery')<>'no'))
     if($rp_query)
      if($rp_query->have_posts()) { ?>
       <div id="tab-3">
        <ol id="relatedposts">
         <?php
	      while ($rp_query->have_posts()) {
		  $rp_query->the_post();
		 ?>
  		 <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
         <?php } ?>
        </ol>
       </div>
      <?php } ?>
  <!-- /related posts -->

</div>
<?php endif; ?>

<?php if (!comments_open()) { // If comments are closed. ?>
 <?php if (is_page() && (!$comments));
  else { ?>
 <p><?php _e("Comments are closed.","fusion"); ?></p>
 <?php } ?>
<?php } ?>


