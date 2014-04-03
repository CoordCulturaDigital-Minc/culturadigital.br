<?php
	/**
	 * Template Name: Slideshow
	 */

	get_header();

	$useds = array();
?>

<div class="row">
	<div class="col_full">
		<div id="content-full">
			<?php get_template_part( 'loop', 'slideshow' ); ?>
		</div>
	</div>
</div>

<div class="row">
	<div class="col_8 col_full_mobile">
		<div id="content">
			<?php parse_str( $query_string, $query_array ); ?>
			<?php $query_array[ 'post__not_in' ] = $useds; ?>
			<?php $last = new WP_Query( $query_array ); ?>
			<?php if( $last->have_posts() ) : ?>
				<div class="section section_index">
					<div class="body">
						<ul class="posts">
							<?php while( $last->have_posts() ) : $last->the_post(); ?>
								<?php get_template_part( 'loop' ); ?>
							<?php endwhile; ?>
						</ul>
					</div>
					<div class="foot">
						<div class="pagination">
							<?php
								global $wp_query;

								$big = 999999999; // need an unlikely integer

								echo paginate_links( array(
									'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
									'format'    => '?paged=%#%',
									'current'   => max( 1, get_query_var( 'paged' ) ),
									'prev_text' => '&laquo;',
									'next_text' => '&raquo',
									'total'     => $wp_query->max_num_pages
								) );
							?>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			<?php else : ?>
				<?php get_template_part( 'error' ); ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="col_4 col_full_mobile">
		<?php get_sidebar(); ?>
	</div>

	<div class="clear"></div>
</div>

<?php get_footer(); ?>