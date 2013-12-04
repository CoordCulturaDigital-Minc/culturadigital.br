<?php global $traction; ?>
	</div><!--end main-->
	<div id="main-bottom"></div>
</div><!--end wrapper-->
<div id="footer">
	<div class="wrapper clear">
		<div id="footer-about" class="footer-column">
			<?php if ($traction->footerAboutState() == 'true') : ?>
				<ul>
					<?php if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('footer_sidebar_3') ) : ?>
						<li class="widget widget_categories">
							<h2 class="widgettitle"><?php _e('Categories'); ?></h2>
							<ul>
								<?php wp_list_cats('sort_column=name&hierarchical=0'); ?>
							</ul>
						</li>
					<?php endif; ?>
				</ul>
			<?php else : ?>
				<h2><?php _e( 'About', 'traction' ); ?></h2>
				<?php if ($traction->footerAbout() != '' ) : ?>
					<?php echo $traction->footerAbout(); ?>
				<?php else : ?>
					<p><?php _e("Did you know you can write your own about section just like this one? It's really easy. Head into the the <em>Traction Options</em> menu and check out the footer section. Type some stuff in the box, click save, and your new about section shows up in the footer.", "traction"); ?></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<div id="footer-middle" class="footer-column">
			<?php if ( is_active_sidebar( 'footer_sidebar' ) ) echo "<ul>" ?>
				<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'footer_sidebar' ) ) : ?>
					<ul>
						<li class="widget">
							<h2 class="widgettitle"><?php _e( 'Pages' ); ?></h2>
							<ul>
								<?php wp_list_pages( 'depth=0&title_li=' ); ?>
							</ul>
						</li>
					</ul>
				<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer_sidebar' ) ) echo "</ul>" ?>
		</div>
		<div id="footer-search" class="footer-column">
			<?php if ( is_active_sidebar( 'footer_sidebar_2' ) ) echo "<ul>" ?>
				<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'footer_sidebar_2' ) ) : ?>
					<h2><?php _e( 'Search', 'traction' ); ?></h2>
					<?php if (is_file(STYLESHEETPATH . '/searchform.php' )) include (STYLESHEETPATH . '/searchform.php' ); else include(TEMPLATEPATH . '/searchform.php' ); ?>
				<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer_sidebar_2' ) ) echo "</ul>" ?>
		</div>
	</div><!--end wrapper-->
</div><!--end footer-->
<div id="copyright" class="wrapper">
	<p class="credit"><a href="http://thethemefoundry.com/traction/">Traction Theme</a> by <a href="http://thethemefoundry.com">The Theme Foundry</a></p>
	<p><?php _e( 'Copyright', 'traction' ); ?> &copy; <?php echo date( 'Y' ); ?> <?php echo $traction->copyrightName(); ?>. All rights reserved.</p>
</div><!--end copyright-->
<?php wp_footer(); ?>
<?php
	if ($traction->statsCode() != '' ) {
		echo $traction->statsCode();
	}
?>
</body>
</html>