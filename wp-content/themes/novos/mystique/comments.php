<?php /* mystique/digitalnature */ ?>
<?php if (!empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password): ?>
<p class="error"><?php _e('Enter your password to view comments','mystique'); ?></p>
<?php return; endif; ?>

<?php
 if ($comments || comments_open()):
  /* Count the totals */
  $numPingBacks = 0;
  $numComments  = 0;
  $jquery = get_mystique_option('jquery');

  /* Loop throught comments to count these totals */
  foreach ($comments as $comment)
   if (get_comment_type() != "comment") $numPingBacks++; else $numComments++;

  if(get_mystique_option('post_single_related')):
   // Related posts. Based on http://www.bin-co.com/blog/2009/04/show-related-post-in-wordpress-without-a-plugin/
   $tags = wp_get_post_tags($post->ID);
   if($tags):
    $tag_ids = array();
    foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
    $args=array('tag__in'=>$tag_ids,'post__not_in'=>array($post->ID),'showposts'=>10,'caller_get_posts'=>1);

    $backup = $post;
    $rp_query = new wp_query($args);

    $post = $backup;
    wp_reset_query();
   endif;
  endif;
 endif;
?>

<!-- tabbed content -->
<div class="tabbed-content post-tabs clear-block" id="post-tabs">

 <?php if(($jquery) && (($comments || comments_open()) || (pings_open() && $numPingBacks>0))): // no tabs if jquery is disabled ?>
 <!-- tab navigation (items must be in reverse order because of the tab-design) -->
 <div class="tabs-wrap clear-block">
  <ul class="tabs">
    <?php if(($rp_query) && ($rp_query->have_posts())): ?><li class="related-posts"><a href="#section-relatedPosts"><span><?php _e('Related Posts','mystique'); ?></span></a></li><?php endif; ?>
    <?php if($numPingBacks>0): ?><li class="trackbacks"><a href="#section-trackbacks"><span><?php printf(__('Trackbacks (%s)','mystique'),$numPingBacks); ?></span></a></li><?php endif; ?>
    <?php if($comments || comments_open()): ?><li class="comments"><a href="#section-comments"><span><?php printf(__('Comments (%s)','mystique'),$numComments); ?></span></a></li><?php endif; ?>
  </ul>
 </div>
 <!-- /tab nav -->
 <?php elseif(!$jquery && (($comments || comments_open()))): ?>
 <div class="clear-block">
  <h2 class="alignright"><?php printf(__('Comments (%s)','mystique'),$numComments); ?></h2>
 </div>
 <?php endif; ?>

 <!-- tab sections -->
 <div class="sections">

  <?php if ($comments || comments_open()): ?>
  <!-- comments -->
  <div class="section clear-block" id="section-comments">

    <?php
     if ($numComments>0): ?>
     <div id="comments-wrap">
      <div class="clear-block">
       <ul id="comments" class="comments">
        <?php wp_list_comments('type=comment&callback=mystique_list_comments', $comments); ?>
       </ul>
      </div>
     <?php
      if (get_option('page_comments')):
       $comment_pages = paginate_comments_links('echo=0');
       if ($comment_pages): ?>
       <div class="comment-navigation clear-block">
    	 <?php echo $comment_pages; ?>
       </div>
       <?php
  	   endif;
  	  endif; ?>
     </div>

     <?php else: ?>
  	 <h6 class="title"><?php _e('No comments yet.','mystique'); ?></h6>
  	 <?php endif; ?>

    <?php

    if (comments_open()):
     if (get_option('comment_registration') && !$user_ID ): // If registration required and not logged in. ?>
   	 <div id="comment_login" class="messagebox">
  	  <?php if (function_exists('wp_login_url')) $login_link = wp_login_url(); else $login_link = get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink()); ?>
    	 <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'mystique'), $login_link); ?></p>
  	 </div>

     <?php else: ?>

     <!-- comment form -->
     <div class="comment-form clear-block" id="respond">
      <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
        <?php if ($user_ID): ?>
          <?php if (function_exists('wp_logout_url')) $logout_link = wp_logout_url(); else $logout_link = get_option('siteurl') . '/wp-login.php?action=logout';	?>
      	  <p>
           <?php
            $login_link = get_option('siteurl')."/wp-admin/profile.php";
            printf(__('Logged in as %s.', 'mystique'), '<a href="'.$login_link.'"><strong>'.$user_identity.'</strong></a>');
           ?>
           <a href="<?php echo $logout_link; ?>" title="<?php _e('Log out of this account', 'mystique'); ?>"><?php _e('Logout &raquo;', 'mystique'); ?></a>
          </p>
       	  <?php else: ?>
  	      <?php if ($comment_author != "") : ?>
  		  <p>
            <?php printf(__('Welcome back <strong>%s</strong>.', 'mystique'), $comment_author) ?>
            <?php if($jquery): ?><a class="js-link" id="show-author-info"><?php _e('Change &raquo;','mystique'); ?></a><?php endif; ?>
          </p>
          <?php endif; ?>
          <div id="author-info"<?php if ($comment_author != "" && $jquery): ?> style="display:none;"<?php endif; ?>>
            <div class="row">
              <?php if(!$jquery): ?><label for="author"> <?php _e("Name (required)","mystique"); ?> </label><?php endif; ?>
              <input type="text" name="author" id="field-author" class="validate required textfield clearField" value="<?php if ($comment_author) echo $comment_author; else $jquery ? _e("Name (required)","mystique"):null; ?>" size="40" />
            </div>
            <div class="row">
              <?php if(!$jquery): ?><label for="email"> <?php _e("E-mail (required, will not be published)","mystique"); ?> </label><?php endif; ?>
              <input type="text" name="email" id="field-email" class="validate required textfield clearField" value="<?php if ($comment_author_email) echo $comment_author_email; else $jquery ? _e("E-mail (required, will not be published)","mystique"):null; ?>" size="40" />
            </div>
            <div class="row">
              <?php if(!$jquery): ?><label for="email"> <?php _e("Website","mystique"); ?> </label><?php endif; ?>
              <input type="text" name="url" id="field-url" class="textfield clearField" value="<?php if ($comment_author_url) echo $comment_author_url; else  $jquery ? _e("Website","mystique"):null; ?>" size="40" />
            </div>
  		  </div>
        <?php endif; ?>

        <!-- comment input -->
        <div class="row">
            <?php if(!$jquery): ?><label for="comment"> <?php _e("Type your comment","mystique"); ?> </label><?php endif; ?>
        	<textarea name="comment" id="comment" class="validate required" rows="8" cols="50"></textarea>
        </div>
        <!-- /comment input -->
        <div class="clear-block">
          <?php if (function_exists('highslide_emoticons')): ?><div id="emoticon"><?php highslide_emoticons(); ?></div><?php endif; ?>
          <?php comment_id_fields(); ?>
          <?php if (function_exists('math_comment_spam_protection')):  $mcsp_info = math_comment_spam_protection(); // Math Comment Spam Protection Plugin ?>
          <p><input type="text" name="mcspvalue" id="mcspvalue" value="" size="22" />
            <label for="mcspvalue"><?php  printf(__('Spam protection: Sum of %1$s + %2$s = ?', 'mystique'), $mcsp_info['operand1'],$mcsp_info['operand2']); ?></label>
            <input type="hidden" name="mcspinfo" value="<?php echo $mcsp_info['result']; ?>" />
          </p>
         <?php endif; ?>
         <?php do_action('comment_form', $post->ID); ?>
        </div>
        
        <!-- comment submit and rss -->
        <div id="submitbox">
		<input name="submit" type="submit" id="submit" class="button" value="<?php _e('Submit Comment', 'mystique'); ?>" />
        <?php if (is_singular() && get_option('thread_comments') && $jquery): ?>
		<input name="cancel-reply" type="button" id="cancel-reply" class="button" value="<?php _e('Cancel Reply','mystique'); ?>" />
        <?php endif; ?>
         <input type="hidden" name="formInput" />
        </div>

      </form>

     </div>
     <!-- /comment form -->
     <?php
     endif;
    endif; ?>
  </div>
  <!-- /comments -->
  <?php endif;  ?>

  <?php if(pings_open() && $jquery): ?>
  <!-- trackbacks -->
	<div class="section" id="section-trackbacks">
     <?php if($numPingBacks>0): ?>
     <ul id="trackbacks">
     <?php wp_list_comments('type=pings&callback=mystique_list_pings'); ?>
    </ul>
    <?php else: ?>
    <h6 class="title"><?php _e("No trackbacks yet.","mystique"); ?></h6>
    <?php endif; ?>
  </div>
  <!-- /trackbacks -->
  <?php endif; ?>


  <?php
  if($rp_query && $rp_query->have_posts() && $jquery): ?>
  <!-- related posts -->
  <div class="section" id="section-relatedPosts">
    <?php
        $backup = $post;
        while ($rp_query->have_posts()):
         $rp_query->the_post(); ?>
         <!-- short post -->
         <div id="post-<?php the_ID(); ?>" class="<?php mystique_post_class(); ?>">
           <?php mystique_post_thumb(); ?>
	   	   <h3 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
           <p class="post-short-info"><strong><?php echo mystique_timesince(get_the_time('U')); ?></strong> - <?php comments_popup_link(__('No comments','mystique'), __('1 comment','mystique'), __('% comments','mystique')); ?></p>
           <div class="post-excerpt"><?php the_excerpt(); ?></div>
  	 </div>
         <!-- /short post -->
       <?php
        endwhile;
        $post = $backup;
       ?>
   </div>
  <!-- /related posts -->
  <?php endif; ?>

 </div>
 <!-- /tab sections -->

</div>
<!-- /tabbed content -->

<?php if(!comments_open()): ?>
 <?php if(is_page() && !$comments): // disable "comments are closed" message on pages that have comments closed and no written comments.
   else: ?>
   <p class="error"><?php _e("Comments are closed.","mystique"); ?></p>
 <?php endif; ?>
<?php endif; ?>