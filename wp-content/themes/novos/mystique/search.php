<?php
 /* Mystique/digitalnature */
 get_header();
?>
  <div id="main">
   <div id="main-inside" class="clear-block">
    <!-- primary content -->
    <div id="primary-content">
     <div class="blocks">
      <?php do_action('mystique_before_primary'); ?>
      <?php
       $searchquery = wp_specialchars(get_search_query(),1);
       if(($searchquery) && ($searchquery!=__('Search',"mystique"))):
         if (have_posts()): ?>
         <h1 class="title"><?php printf(__("Search results for %s","mystique"),'<span class="altText">'.$searchquery.'</span>'); ?></h1>
         <?php
          mystique_pagenavi();

          while (have_posts()):
           the_post();
           mystique_post();
          endwhile; ?>

         <!-- page navigation -->
         <div class="page-navigation clear-block">
          <?php mystique_pagenavi('alignright'); ?>
         </div>
         <!-- /page navigation -->

        <?php else: ?>
         <h1 class="title"><span class="error"><?php _e('Nothing found.','mystique'); ?></span> <?php _e('Try a different search?','mystique'); ?></h1>
         <?php mystique_search_form(); ?>
        <?php endif; ?>
      <?php else: ?>
  	    <h1 class="title"><?php _e('What do you wish to search for?','mystique'); ?></h1>
        <?php mystique_search_form(); ?>
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
