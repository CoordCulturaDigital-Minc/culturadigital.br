<?php

/**
 * @package WordPress
 * @subpackage Spectrum
 */

if ( function_exists('dynamic_sidebar') )
    register_sidebar(array(
        'before_widget' => '<div class="sidebarBox">',
        'after_widget' => '</div>',
        'before_title' => '<div class="sidebarTitle"><h4>',
        'after_title' => '</h4></div>',
    ));


function spectrum_tag_cloud($tags)
{
	$tags = preg_replace_callback("|(class='tag-link-[0-9]+)('.*?)(style='font-size: )([0-9]+)(px;')|",
		create_function(
			'$match',
			'$low=1; $high=10; $sz=($match[4])/(2); return "{$match[1]} tagSize-{$sz}{$match[2]}";'
		),
		$tags);
	return $tags;
}
add_action('wp_tag_cloud', 'spectrum_tag_cloud');

function spectrum_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	
	<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
		<div class="avatarHolder">
			<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		</div>
		<div <?php comment_class() ?> id="div-comment-<?php comment_ID() ?>">
			<div class="commentAuthorAndDate">
				<div class="commentAuthor">
					<?php printf(__('<strong>%s</strong> <em>said:</em>'), get_comment_author_link()) ?>
				</div>
				<div class="commentDate">
					<a href="<?php comments_link(); ?> "><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a>
				</div>
			</div>
			<div class="commentText">
				<?php comment_text() ?>
				<?php if ($comment->comment_approved == '0') : ?>
				<p class="waiting4Mod"><?php _e('Your comment is awaiting moderation.') ?></p>
				<?php endif; ?>
				<p class="editComment"><?php edit_comment_link(__('(Edit)'),'','') ?></p>
				<p class="replyLink"><?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></p>
			</div>
		</div>

<?php } ?>