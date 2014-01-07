<?php
/**
 * Template Name: Three columns, sidebar left and right
 *
 * A custom page template with the main content in 
 * the middle and two sidebars (left and right side).
 *
 * @package Graphene
 * @since Graphene 1.1.5
 */
 __( 'Three columns, sidebar left and right', 'graphene' );
 get_header(); ?>
 
    <?php
    /* Run the loop to output the pages.
	 * If you want to overload this in a child theme then include a file
	 * called loop-page.php and that will be used instead.
	*/
	the_post();
    get_template_part( 'loop', 'page' );
    ?>

<?php get_footer(); ?>