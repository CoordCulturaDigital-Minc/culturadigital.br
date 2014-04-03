<?php get_header(); ?>

<div class="row">
	<div class="col_8 col_full_mobile">
		<div id="content">
			<?php if( have_posts() ) : ?>
				<div class="section section_index">
					<div class="head">
						<?php if( is_home() ) : ?>
							<h1><?php _e( 'Last Posts', 'painter' ); ?></h1>
						<?php elseif( is_category() ) : ?>
							<h1><?php single_cat_title(); ?></h1>
						<?php elseif( is_tag() ) : ?>
							<h1><?php _e( 'Tag', 'painter' ); ?> <span>&quot;<?php single_tag_title(); ?>&quot;</span></h1>
						<?php elseif( is_tax() ) : ?>
							<h1><?php single_term_title(); ?></h1>
						<?php elseif( is_day() ) : ?>
							<h1><?php _e( 'Day', 'painter' ); ?> <span><?php echo get_the_time(); ?></span></h1>
						<?php elseif( is_month() ) : ?>
							<h1><?php _e( 'Month', 'painter' ); ?> <span><?php echo get_the_time( 'F' ); ?></span></h1>
						<?php elseif( is_year() ) : ?>
							<h1><?php _e( 'Year', 'painter' ); ?> <span><?php echo get_the_time( 'Y' ); ?></span></h1>
						<?php elseif( is_search() ) : ?>
							<h1><?php _e( 'Search For', 'painter' ); ?> <span>&quot;<?php the_search_query(); ?>&quot;</span></h1>
						<?php endif; ?>
					</div>
					<div class="body">
						<ul class="posts">
							<?php while( have_posts() ) : the_post(); ?>
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