<?php get_header(); ?>

<div class="row">
	<div class="col_8 col_full_mobile">
		<div id="content">
			<?php the_post(); ?>
			<div class="section section_single">
				<div class="head">
					<h1><?php the_title(); ?></h1>
				</div>
				<div class="body">
					<ul class="posts">
						<?php get_template_part( 'loop', 'single' ); ?>
					</ul>
				</div>
				<div class="foot">
					<div class="pagination">
						<?php previous_post_link( '<div class="alignleft">&laquo; %link</div>' ); ?>
						<?php next_post_link( '<div class="alignright">%link &raquo;</div>' ); ?>
					</div>
				</div>
			</div>
			<div class="clear"></div>

			<?php comments_template(); ?>
		</div>
	</div>

	<div class="col_4 col_full_mobile">
		<?php get_sidebar(); ?>
	</div>

	<div class="clear"></div>
</div>

<?php get_footer(); ?>