<?php get_header(); ?>
<?php $options = get_option('pb_options'); ?>

  <div id="middle-contents" class="clearfix">

   <div id="left-col">

  <?php if (have_posts()) : ?>
<div class="common-navi-wrapper">
  <p><?php _e('Search results for ', 'piano-black'); echo "[ " .$s. " ]"; ?><span id="search-hit"> - <?php $my_query =& new WP_Query("s=$s & showposts=-1"); echo $my_query->post_count; _e(' hit', 'piano-black'); ?></span></p>	
</div>

<?php while ( have_posts() ) : the_post(); ?>

    <div class="post search-page">
     <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
     <ul class="post-info">
      <li><?php the_time(__('F jS, Y', 'piano-black')) ?></li>
      <?php if ($options['categories']) : ?><li><?php _e('Posted in ','piano-black'); ?><?php the_category(' . '); ?></li><?php endif; ?>
      <?php if ($options['author']) : ?><li><?php _e('By ','piano-black'); ?><?php the_author_posts_link(); ?></li><?php endif; ?>
      <?php edit_post_link(__('[ EDIT ]', 'piano-black'), '<li class="post-edit">', '</li>' ); ?>
     </ul>
     <p><a href="<?php the_permalink() ?>"><?php the_excerpt_rss(); ?></a></p>
    </div>

<?php endwhile; ?>

<?php if (function_exists('wp_pagenavi')) { wp_pagenavi(); } else { include('navigation.php'); } ?>

<a href="#wrapper" id="back-top"><?php _e('Return top','piano-black'); ?></a>

<?php else: ?>
    <div class="common-navi-wrapper">
      <p><?php _e("Sorry, but you are looking for something that isn't here.","piano-black"); ?></p>
    </div>
<?php endif; ?>

   </div><!-- #left-col end -->

   <?php get_sidebar(); ?>

  </div><!-- #middle-contents end -->

<?php get_footer(); ?>