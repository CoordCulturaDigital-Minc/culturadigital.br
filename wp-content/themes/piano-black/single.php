<?php get_header(); ?>
<?php $options = get_option('pb_options'); ?>

  <div id="middle-contents" class="clearfix">

   <div id="left-col">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="post" id="single">
     <h2><?php the_title(); ?></h2>
     <ul class="post-info">
      <li><?php the_time(__('F jS, Y', 'piano-black')) ?></li>
      <li><?php _e('Posted in ','piano-black'); ?><?php the_category(' . '); ?></li>
      <?php if ($options['author']) : ?><li><?php _e('By ','piano-black'); ?><?php the_author_posts_link(); ?></li><?php endif; ?>
      <li class="write-comment"><a href="#respond"><?php _e('Write comment','piano-black'); ?></a></li>
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

   <?php if ($options['next_preview_post']) : ?>
   <div id="previous_next_post" class="clearfix">
    <p id="previous_post"><?php previous_post_link('%link') ?></p>
    <p id="next_post"><?php next_post_link('%link') ?></p>
   </div>
   <?php endif; ?>

   <a href="#wrapper" id="back-top"><?php _e('Return top','piano-black'); ?></a>

   </div><!-- #left-col end -->

   <?php get_sidebar(); ?>

  </div><!-- #middle-contents end -->

<?php get_footer(); ?>