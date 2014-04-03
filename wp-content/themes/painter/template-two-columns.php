<?php
	/**
	 * Template Name: Two Columns
	 */

	get_header();

	$useds = array();
?>

<div class="row">
	<div class="col_8 col_full_mobile">
		<div id="content-split">
			<div class="col_4 col_alpha col_full_mobile">
				<div id="content-primary">
					<?php if( !dynamic_sidebar( 'Home Primary' ) ) _e( 'no widgets', 'painter' ); ?>
				</div>
			</div>
			<div class="col_4 col_omega col_full_mobile">
				<div id="content-secondary">
					<?php if( !dynamic_sidebar( 'Home Secondary' ) ) _e( 'no widgets', 'painter' ); ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>

	<div class="col_4 col_full_mobile">
		<?php get_sidebar(); ?>
	</div>

	<div class="clear"></div>
</div>

<?php get_footer(); ?>