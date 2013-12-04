<?php /* Fusion/digitalnature */ ?>
<?php get_header(); ?>
  <!-- mid content -->
  <div id="mid-content">
   <h1><?php _e('Page not found (404)','fusion'); ?></h1>

   <h2><?php _e('Try one of these links:','fusion'); ?></h2>
   <ul>
    <?php wp_list_pages('title_li='); ?>
   </ul>
  </div>
    <!-- mid content -->
</div>
<!-- /mid -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
