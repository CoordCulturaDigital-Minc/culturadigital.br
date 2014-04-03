<?php
/**
 * Template Name: Two columns, sidebar on the left
 *
 * A custom page template with the main content on 
 * the right side and one sidebar on the left side.
 *
 * @package Graphene
 * @since Graphene 1.1.5
 */
 
 /* translators: For RTL languages, translate "left" as "right" */
 __( 'Two columns, sidebar on the left', 'graphene' );
 
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