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
					
<?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
					
					<div id="comments-nav-below" class="comments-navigation">
<?php paginate_comments_links(); ?>
					</div>
				</div><!-- #comments-list .comments -->
<?php endif // REFERENCE: if ( have_comments() ) ?>

<?php comment_form(); ?>



			</div><!-- #comments -->