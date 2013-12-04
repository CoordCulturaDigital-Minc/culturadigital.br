<?php get_header(); ?>
	<div id="main-top">
		<?php if (is_file(STYLESHEETPATH . '/subscribe.php' )) include(STYLESHEETPATH . '/subscribe.php' ); else include(TEMPLATEPATH . '/subscribe.php' ); ?>
	</div>
	<div id="main" class="clear">
		<div id="content">
			<div class="post single">		 
				<h1 class="title"><?php _e( '404: Page Not Found', 'traction' ); ?></h1>
				<div class="entry single">
					<p><?php _e( 'We are terribly sorry, but the URL you typed no longer exists. It might have been moved or deleted, or perhaps you mistyped it. We suggest searching the site:', 'traction' ); ?></p>
					<?php if (is_file(STYLESHEETPATH . '/searchform.php' )) include (STYLESHEETPATH . '/searchform.php' ); else include(TEMPLATEPATH . '/searchform.php' ); ?>
				</div><!--end entry-->
			</div><!--end post-->
		</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>