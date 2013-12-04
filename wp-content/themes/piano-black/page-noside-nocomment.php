<?php
/*
Template Name:No sidebar, No comment
*/
?>
<?php get_header(); ?>
  <div id="no-side" class="clearfix">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="post" id="single">
     <h2><?php the_title(); ?></h2>
     <div class="post-content">
       <?php the_content(__('Read more', 'piano-black')); ?>
       <?php wp_link_pages(); ?>
     </div>
    </div>

<?php endwhile; else: ?>
    <div class="post-content">
      <p><?php _e("Sorry, but you are looking for something that isn't here.","piano-black"); ?></p>
    </div>
<?php endif; ?>

  </div><!-- #middle-contents end -->

  <div id="footer-noside">
  </div>
 
 </div><!-- #contents end -->
</div><!-- #wrapper end -->

<?php $options = get_option('pb_options'); if ($options['return_top']) : ?>
<div id="page-top">
 <a href="#wrapper">&nbsp;</a>
</div>
<?php endif; ?>

</body>
</html>
