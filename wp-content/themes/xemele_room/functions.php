<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
include_once(TEMPLATEPATH . '/inc/the_thumb.php');
include_once(TEMPLATEPATH . '/inc/limit_chars.php');
require_once(TEMPLATEPATH . '/controlpanel.php'); 

if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));


/* Custom Comment template */
function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>

		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
            <div class="left-sidebar">
                <?php echo get_avatar($comment,$size='32',$default='<path_to_url>' ); ?>
                <?php printf(__('<p><b>%s</b>'), get_comment_author_link()) ?><br />
                <?php comment_date('j M, Y') ?>
                <?php edit_comment_link('edit','[ ',' ]'); ?></p>
            </div>
			<div class="comment-content">
                <?php comment_text() ?>
                <?php if ($comment->comment_approved == '0') : ?><p><b><em>Your comment is awaiting moderation!</em></b></p><?php endif; ?>
                <div class="reply">
                   <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
            </div>  
<?php }

/* Custom Trackbacks/Pingbacks template */
function mytheme_trackbacks($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>

		<li id="li-comment-<?php comment_ID() ?>">
                <?php printf(__('<b>%s</b>'), get_comment_author_link()) ?>
                <?php comment_date('j M, Y') ?>
                <?php edit_comment_link('edit','[ ',' ]'); ?>
                <!-- Trackbacks/Pingbacks description is disabled by default. Uncomment it if you want to use this feature.
                <br /><small class="grey"><?php comment_text() ?></small>-->
                
<?php }

function is_gallery(){
	if($_GET['post_type'] == 'attachment' ){
		return true;
	}
	return false;
}

?>
