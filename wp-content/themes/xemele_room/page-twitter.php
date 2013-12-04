<?php
/*
Template Name: Twitter
*/
?>

<?php get_header(); ?>

<div id="content">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="postWrapper">

			<div class="post" id="post-<?php the_ID(); ?>">
				<h1><?php the_title(); ?></h1>

				<div class="twitter">
    				<?php thread_twitter();?>
				</div>
				
            </div>
        </div>

	<?php endwhile; endif; ?>

</div> <!-- / content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
