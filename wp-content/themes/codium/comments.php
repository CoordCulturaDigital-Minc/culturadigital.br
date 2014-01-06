<?php
	if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
		die ( 'Please do not load this page directly. Thanks.' );
?>
			<div id="comments">
<?php
	if ( post_password_required() ) :
?>
				<div class="nopassword"><?php _e( 'This post is protected. Enter the password to view any comments.', 'codium' ) ?></div>
			</div><!-- .comments -->
<?php
		return;
	endif;
?>
<?php if ( have_comments() ) : ?>
				<div id="comments-list" class="comments">
					<h3><?php comments_number('', __('<span>One</span> comment', 'codium'), __('<span>%</span> Comments', 'codium') ); ?></h3>
					<div id="comments-nav-above" class="comments-navigation">
<?php paginate_comments_links(); ?>
					</div>
					
<?php wp_list_comments('callback=codium_comment'); ?> 
					
					<div id="comments-nav-below" class="comments-navigation">
<?php paginate_comments_links(); ?>
					</div>
				</div><!-- #comments-list .comments -->
<?php endif; // check for comment navigation ?>
		
<?php if ( comments_open() ) : // If comments are open, but there are no comments ?>

		<?php else : // or, if we don't have comments:

			/* If there are no comments and comments are closed,
			 * let's leave a little note, shall we?
			 * But only on posts! We don't want the note on pages.
			 */
			if ( ! comments_open() && ! is_page() ) :
			?>
			<p class="nocomments"><?php _e( 'Comments are closed.', 'commune' ); ?></p>
			<?php endif; // end ! comments_open() && ! is_page() ?>

<?php endif // REFERENCE: if ( have_comments() ) ?>

<?php comment_form(); ?>



			</div><!-- #comments -->