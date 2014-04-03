				<?php if( is_active_sidebar( 'footer-primary' ) or is_active_sidebar( 'footer-secondary' ) or is_active_sidebar( 'footer-tertiary' ) ) : ?>
					<div id="extras">
						<div class="row border_top">
							<div class="col_4 col_full_mobile">
								<?php dynamic_sidebar( 'footer-primary' ); ?>
							</div>

							<div class="col_4 col_full_mobile">
								<?php dynamic_sidebar( 'footer-secondary' ); ?>
							</div>

							<div class="col_4 col_full_mobile">
								<?php dynamic_sidebar( 'footer-tertiary' ); ?>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div id="foot">
			<div class="container">
				<div class="row">
					<div class="col_half col_full_mobile">
						<div id="institutional">
							<?php wp_nav_menu( 'theme_location=footer&depth=1' ); ?>
						</div>
					</div>

					<div class="col_half col_full_mobile">
						<div id="copyright">
							<p><?php echo get_bloginfo( 'description' ); ?> - <?php echo get_bloginfo( 'name' ); ?></p>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>

		<?php wp_footer(); ?>

		<!-- queries: <?php echo get_num_queries(); ?> -->
		<!-- seconds: <?php echo timer_stop( 0, 3 ); ?> -->
	</body>
</html>