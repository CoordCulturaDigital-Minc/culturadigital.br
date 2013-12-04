<?php
/*
Template Name:No sidebar
*/
?>
<?php get_header(); ?>
  <div id="page_noside_header"></div>
  <div id="page_noside_contents" class="clearfix">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="post page" id="page_noside_post">
      <h2 class="post_title"><span><?php the_title(); ?></span></h2>
      <?php edit_post_link(__('[ EDIT ]', 'monochrome'), '<p>', '</p>' ); ?>
      <div class="post_content">
       <?php the_content(__('Read more', 'monochrome')); ?>
       <?php wp_link_pages(); ?>
      </div>
    </div>

<?php endwhile; else: ?>
    <div class="post">
     <p><?php _e("Sorry, but you are looking for something that isn't here.","monochrome"); ?></p>
    </div>
<?php endif; ?>

<?php if (function_exists('wp_list_comments')) { comments_template('', true); } else { comments_template(); } ?>

  </div><!-- #page_noside_contents end -->

  <div id="footer_noside">
<?php get_footer(); ?>