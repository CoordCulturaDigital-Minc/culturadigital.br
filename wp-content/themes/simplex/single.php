<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage simpleX
 * @since simpleX 2.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<?php // simplex_content_nav( 'nav-above' ); ?>

				<?php get_template_part( 'content', 'single' ); ?>

				<?php simplex_content_nav( 'nav-below' ); ?>

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>