<?php
/**
 * The category archive template file
 *
 * @package Graphene
 * @since Graphene 1.1.5
 */
get_header();
?>

    <h1 class="page-title archive-title">
        <?php
            printf( __( 'Category Archive: <span>%s</span>', 'graphene' ), single_cat_title( '', false ) );
        ?>
    </h1>
    
    <?php graphene_tax_description(); ?>
    
    <div class="entries-wrapper">
    <?php
        /**
         * Run the loop for the category page to output the posts.
         * If you want to overload this in a child theme then include a file
         * called loop-category.php and that will be used instead.
        */
        while ( have_posts() ) {
            the_post(); 
            get_template_part( 'loop', 'category' );
        }
    ?>
    </div>
    
    <?php graphene_posts_nav(); ?>

<?php get_footer(); ?>