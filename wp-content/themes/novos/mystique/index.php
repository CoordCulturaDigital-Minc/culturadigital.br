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
       if (have_posts()):
        while (have_posts()):
         the_post();
         mystique_post();
        endwhile;

        mystique_pagenavi();
       else: ?>
       <h1 class="title error"><?php _e("No posts found","mystique"); ?></h1>
       <p><?php _e("Sorry, but you are looking for something that isn't here.","mystique"); ?></p>

      <?php endif; ?>
      <?php do_action('mystique_after_primary'); ?>
     </div>
    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>

