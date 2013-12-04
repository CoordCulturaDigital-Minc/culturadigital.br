<?php /* Fusion/digitalnature */ ?>
<?php get_header(); ?>


  <!-- mid content -->
  <div id="mid-content">
	<?php if (have_posts()) : ?>
   	 <h1><?php _e("Search Results","fusion"); ?></h1>
	 <div class="navigation" id="pagenavi">
      <?php if(function_exists('wp_pagenavi')) : ?>
	  <?php wp_pagenavi() ?>
      <?php else : ?>
	   <div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','fusion')) ?></div>
	   <div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','fusion')) ?></div>
       <div class="clear"></div>
      <?php endif; ?>
	 </div>

     <?php while (have_posts()) : the_post(); ?>
       <div class="post-search">
  		<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
      		<small><?php the_time(get_the_time(get_option('date_format')).' - '.get_the_time(get_option('time_format'))) ?></small>
      		<p class="postmetadata"><?php the_tags(__('Tags: ','fusion'), ', ', '<br />'); ?> <?php printf(__('Posted in %s','fusion'), get_the_category_list(', '));?> | <?php edit_post_link(__('Edit','fusion'), '', ' | '); ?>  <?php comments_popup_link(__('No Comments','fusion'), __('1 Comment','fusion'), __('% Comments','fusion')); ?></p>
       </div>
     <?php endwhile; ?>
     <div class="navigation" id="pagenavi">
       <?php if(function_exists('wp_pagenavi')) : ?>
        <?php wp_pagenavi() ?>
       <?php else : ?>
        <div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','fusion')) ?></div>
        <div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','fusion')) ?></div>
        <div class="clear"></div>
       <?php endif; ?>
     </div>
	<?php else : ?>
  	 <h1><?php _e('No posts found. Try a different search?','fusion'); ?></h1>
	 <?php get_search_form(); ?>
	<?php endif; ?>

  </div>
    <!-- mid content -->
   </div>
   <!-- /mid -->

   <?php get_sidebar(); ?>

<?php get_footer(); ?>
