<?php /* Fusion/digitalnature

Template Name: Archives 
*/ ?>
<?php get_header(); ?>

  <!-- mid content -->
  <div id="mid-content">
 <?php get_search_form(); ?>
 <h2><?php _e('Archives by Month:','fusion'); ?></h2>
 <ul>
  <?php wp_get_archives('type=monthly'); ?>
 </ul>
 <h2><?php _e('Archives by Subject:','fusion'); ?></h2>
  <ul>
   <?php wp_list_categories(); ?>
  </ul>
  </div>
    <!-- mid content -->
   </div>
   <!-- /mid -->

    <?php get_sidebar(); ?>

<?php get_footer(); ?>