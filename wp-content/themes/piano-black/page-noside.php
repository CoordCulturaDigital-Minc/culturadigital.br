<?php
/*
Template Name:No sidebar
*/
?>
<?php get_header(); ?>
<?php $options = get_option('pb_options'); ?>
  <div id="no-side" class="clearfix">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="post" id="single">
     <h2><?php the_title(); ?></h2>
     <ul class="post-info">
      <?php if ($options['author']) : ?><li><?php _e('By ','piano-black'); ?><?php the_author_posts_link(); ?></li><?php endif; ?>
      <?php edit_post_link(__('[ EDIT ]', 'piano-black'), '<li class="post-edit">', '</li>' ); ?>
     </ul>
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

<?php if (function_exists('wp_list_comments')) { comments_template('', true); } else { comments_template(); } ?>

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

<?php wp_footer(); ?>
</body>
</html>