<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 * @package WordPress
 * @subpackage Graphene
 * @since Graphene 1.0.5
 */
 get_header(); ?>
 
 	<?php
    /* Run the loop to output the posts.
     * If you want to overload this in a child theme then include a file
     * called loop-single.php and that will be used instead.
     */
     get_template_part('loop', 'single');
    ?>

<?php get_footer(); ?>