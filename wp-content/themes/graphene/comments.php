<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to graphene_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Graphene
 * @since Graphene 1.0
 */
?>

<?php if ( post_password_required() ) : ?>
			<div id="comments">
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'graphene' ); ?></p>
                
                <?php do_action('graphene_protected_comment'); ?>
			</div><!-- #comments -->
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php /* Lists all the comments for the current post */ ?>
<?php if ( have_comments() ) : ?>

<div id="comments" class="clearfix">
    <h4><?php comments_number(__('No comment yet','graphene'), __('1 comment','graphene'), __("% comments", 'graphene') );?></h4>

	<?php do_action('graphene_before_comments'); ?>

    <ol class="clearfix">
        <?php
        /* Loop through and list the comments. Tell wp_list_comments()
         * to use graphene_comment() to format the comments.
         * If you want to overload this in a child theme then you can
         * define graphene_comment() and that will be used instead.
         * See graphene_comment() in functions.php for more.
         */
		 wp_list_comments(array('callback' => 'graphene_comment', 'style' => 'ol')); ?>
    </ol>
                    
		<?php // Are there comments to navigate through? ?>
        <?php if (get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
        <div class="comment-nav clearfix">
            <p><?php paginate_comments_links(); ?>&nbsp;</p>
            <?php do_action('graphene_comments_pagination'); ?>
        </div>
        <?php endif; // Ends the comment navigation ?>
    
    <?php do_action('graphene_after_comments'); ?>
</div>
<?php endif; // Ends the comment listing ?>

<?php if ('open' != $post->comment_status && have_comments()) : ?>
	<div id="respond">
		<h3 id="reply-title"><?php _e('Comments have been disabled.', 'graphene'); ?></h3>
        <?php do_action('graphene_comments_disabled'); ?>
    </div>
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>
	<?php do_action('graphene_before_commentform'); ?>
<?php 
	/**
	 * Get the comment form.
	*/ 
	
	comment_form(array(
				'comment_notes_before' => '<p class="comment-notes">'.__('Your email address will not be published.', 'graphene').'</p>',
				'comment_notes_after'  => '<p class="form-allowed-tags">'.sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'graphene'),'<code>'.allowed_tags().'</code>').'</p>',
				'id_form'              => 'commentform',
				'label_submit'         => __('Submit Comment', 'graphene'),
					   )); 

?>
	<?php do_action('graphene_after_commentform'); ?>
<?php endif; // Ends the comment status ?>