<?php get_header(); ?>
<?php $options = get_option('mc_options'); ?>
  <div id="contents" class="clearfix">

   <div id="left_col">

<?php if (have_posts()) : ?>

    <div id="header_meta">
     <p><?php _e('Search results for ', 'monochrome'); echo '&#8216;<span id="keyword"> ' .$s. ' </span>&#8217;'; ?><span id="search_hit"> - <?php $my_query =& new WP_Query("s=$s & showposts=-1"); echo $my_query->post_count; _e(' hit', 'monochrome'); ?></span></p>
    </div>


<?php $odd_or_even = 'odd'; ?>
<?php while ( have_posts() ) : the_post(); ?>

    <div class="post_<?php echo $odd_or_even; ?>">
     <div class="post clearfix">
      <div class="post_content_wrapper">
       <h2 class="post_title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
       <div class="post_content">
        <?php the_content(__('Read more', 'monochrome')); ?>
       </div>
      </div>
      <dl class="post_meta">
        <dt class="meta_date"><?php the_time('Y') ?></dt>
         <dd class="post_date"><?php the_time('m') ?><span>/<?php the_time('d') ?></span></dd>
        <?php if ($options['author']) : ?>
        <dt><?php _e('POSTED BY','monochrome'); ?></dt>
         <dd><?php the_author_posts_link(); ?></dd>
        <?php endif; ?>
        <dt><?php _e('CATEGORY','monochrome'); ?></dt>
         <dd><?php the_category('<br />'); ?></dd>
        <?php if ($options['tag']) : ?>
         <?php the_tags(__('<dt>TAGS</dt><dd>','monochrome'),'<br />','</dd>'); ?>
        <?php endif; ?>
        <dt class="meta_comment"><?php comments_popup_link(__('Write comment', 'monochrome'), __('1 comment', 'monochrome'), __('% comments', 'monochrome')); ?></dt>
         <?php edit_post_link(__('[ EDIT ]', 'monochrome'), '<dd>', '</dd>' ); ?>
      </dl>
     </div>
    </div>

<?php $odd_or_even = ('odd'==$odd_or_even) ? 'even' : 'odd'; ?>
<?php endwhile; else: ?>
    <div id="header_meta">
     <p><?php _e("Sorry, but you are looking for something that isn't here.","monochrome"); ?></p>
    </div>
<?php endif; ?>

    <div class="content_noside">
     <?php if($options['page_navi_type'] == 'pager'){ 
     if (function_exists('wp_pagenavi')) { wp_pagenavi(); } else { include('navigation.php'); }
     } else { ?>
     <div class="normal_navigation cf">
      <div id="normal_next_post"><?php next_posts_link(__('Older Entries', 'monochrome')) ?></div>
      <div id="normal_previous_post"><?php previous_posts_link(__('Newer Entries', 'monochrome')) ?></div>
     </div>
     <?php }; ?>
    </div>

   </div><!-- #left_col end -->

   <?php get_sidebar(); ?>

  </div><!-- #contents end -->

  <div id="footer">
<?php get_footer(); ?>
