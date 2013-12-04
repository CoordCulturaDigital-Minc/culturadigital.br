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
       <h1 class="title"><span class="error">404.</span> <?php _e('The requested page was not found','mystique'); ?></h1>
       <?php do_action('mystique_after_primary'); ?>
      </div>
    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>