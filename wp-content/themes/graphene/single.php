<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Graphene
 * @since Graphene 1.0
 */

get_header(); ?>

	<?php
    /* Run the loop to output the posts.
     * If you want to overload this in a child theme then include a file
     * called loop-single.php and that will be used instead.
     */
     get_template_part('loop', 'single');
    ?>
            
<?php get_sidebar(); ?>
<?php get_footer(); ?>