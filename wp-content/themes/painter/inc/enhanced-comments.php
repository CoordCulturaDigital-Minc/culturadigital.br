<?php
/*
Function Name: Enhanced Comments
Description: Allow to reply the comments
Version: 0.1
Author: Marcelo Mesquita
Author URI: http://www.marcelomesquita.com/
*/

function enhanced_comments($comment, $args, $depth)
{
  $GLOBALS['comment'] = $comment;
  
  ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
      <?php print get_avatar($comment); ?>
      <div class="options">
        <?php edit_comment_link(__("Edit", "painter")); ?>
        <?php comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'])); ?>
      </div>
      <h3 class="comment-author"><cite><?php comment_author_link(); ?></cite></h3>
      <div class="info">
        <span class="comment-date"><?php _e('in', 'painter'); ?> <?php comment_time(__('F jS, Y @ H:i', 'painter')); ?></span>
      </div>
      <?php if($comment->comment_approved == '0') : ?><p class="comment-wait"><?php _e("Your comment is waiting moderation", "painter") ?></p><?php endif; ?>
      <?php comment_text(); ?>
      <hr class="clear" />
    </li>
  <?php
}
?>
