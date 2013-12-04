<?php
 /* Mystique/digitalnature */
 get_header();
?>

  <!-- main content: primary + sidebar(s) -->
  <div id="main">
   <div id="main-inside" class="clear-block">

    <!-- primary content -->
    <div id="primary-content">
     <div class="blocks">
      <?php do_action('mystique_before_primary'); ?>
      <?php
       if (have_posts()) :
        $post = $posts[0]; // Hack. Set $post so that the_date() works.
        $desc = category_description();

        if (is_category()): ?>
         <h1 class="title archive-category"><?php echo single_cat_title(); ?></h1>
         <?php if($desc): ?><div class="category-description"><?php echo $desc; ?></div><?php endif; ?>
       <?php elseif(is_tag()): ?>
         <h1 class="title archive-tag"><?php printf( __('Posts tagged %s', 'mystique'), '<span class="altText">'.single_cat_title('', false).'</span>'); ?></h1>
       <?php elseif (is_day()): ?>
         <h1 class="title archive-day"><?php printf(__('Archive for %s', 'mystique'), '<span class="altText">'.get_the_time(get_option('date_format')).'</span>');  ?></h1>
       <?php elseif (is_month()): ?>
         <h1 class="title archive-month"><?php printf(__('Archive for %s', 'mystique'), '<span class="altText">'.get_the_time(__('F, Y','mystique')).'</span>');  ?></h1>
       <?php elseif (is_year()): ?>
         <h1 class="title archive-year"><?php printf(__('Archive for year %s', 'mystique'), '<span class="altText">'.get_the_time(__('Y','mystique')).'</span>');  ?></h1>
       <?php elseif (is_author()): ?>
         <h1 class="title archive-author"><?php _e('Author archive','mystique'); ?></h1>
       <?php elseif (isset($_GET['paged']) && !empty($_GET['paged'])): ?>
         <h1 class="title"><?php _e('Blog Archives','mystique'); ?></h1>
       <?php endif; ?>
       <div class="divider"></div>

       <?php
        while (have_posts()):
         the_post();
         mystique_post();
        endwhile;

        mystique_pagenavi();

       else:

         if (is_category()): ?> <h1 class="title"> <?php printf(__("Sorry, but there aren't any posts in the %s category yet.", "mystique"),single_cat_title('',false)); ?> </h1> <?php
         elseif (is_date()): ?> <h1 class="title"> <?php _e("Sorry, but there aren't any posts within this date."); ?> </h1> <?php
         elseif (is_author()):
          $userdata = get_userdatabylogin(get_query_var('author_name')); ?>
          <h1 class="title"> <?php printf(__("Sorry, but there aren't any posts by %s yet.", "mystique"),$userdata->display_name); ?> </h1> <?php
         else: ?> <h1 class="title"> <?php _e('No posts found.'); ?> </h1> <?php  endif;

       endif;
       ?>
       <?php do_action('mystique_after_primary'); ?>
     </div>
    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>