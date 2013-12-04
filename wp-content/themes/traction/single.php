<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<div id="main-top">
			<div class="main-top-left">
				<h4><?php the_time(__( 'F j, Y' )); ?></h4>
				<div class="single-comments">
					<a href="#comments"><?php comments_number( '', '1', '%' ); ?></a>
				</div>
			</div>
			<?php if (is_file(STYLESHEETPATH . '/subscribe.php' )) include(STYLESHEETPATH . '/subscribe.php' ); else include(TEMPLATEPATH . '/subscribe.php' ); ?>
		</div>
		<div id="main" class="clear">
			<div id="content">
				<?php while (have_posts()) : the_post(); ?>
					<div id="post-<?php the_ID(); ?>" <?php post_class( 'clear single' ); ?>>
						<h1 class="title"><?php the_title(); ?></h1>
						<div class="entry single">
							<?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail( 'index-thumb', array( 'class' => 'single-post-thm alignright border' ) ); ?>
							<?php the_content(); ?>
							<?php edit_post_link(__( 'Edit', 'traction' )); ?>
							<?php wp_link_pages(); ?>
						</div><!--end entry-->
						<div class="meta clear">
							<div class="cats"><?php _e( '<em>Read more from</em>', 'traction' ); ?> <?php the_category( ', ' ); ?></div>
							<div class="tags"><?php the_tags( '' ); ?></div>
						</div><!--end meta-->
					</div><!--end post-->
				<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
					<?php comments_template( '', true); ?>
				<?php else : ?>
				<?php endif; ?>
			</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>