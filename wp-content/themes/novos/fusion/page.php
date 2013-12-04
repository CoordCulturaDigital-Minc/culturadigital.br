<?php /* Fusion/digitalnature */ ?>
<?php get_header(); ?>

<!-- mid content -->
<div id="mid-content">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php if (function_exists("post_class")) post_class(); else print 'class="post"'; ?>>
     <?php if (!get_post_meta($post->ID, 'hide_title', true)): ?><h2 class="title left"><?php the_title(); ?></h2><?php endif; ?>
     <p class="right edit-post"><?php edit_post_link(__('Edit','fusion')); ?></p>
     <div class="clear"></div>
     <div class="entry clearfix">
      <?php the_content(__('Read the rest of this page &raquo;', 'fusion')); ?>
      <?php wp_link_pages(array('before' => '<p class="postpages"><strong>'.__("Pages:","fusion").'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
     </div>
    </div>
   <?php endwhile; endif; ?>
   <?php comments_template(); ?>
</div>
<!-- mid content -->
</div>
<!-- /mid -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>