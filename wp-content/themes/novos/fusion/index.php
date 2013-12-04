<?php
 /* Fusion/digitalnature */
 get_header();
?>
<!-- mid content -->
<div id="mid-content">
<?php if (have_posts()) : ?>
 <?php while (have_posts()) : the_post(); ?>
    <!-- post -->
    <div id="post-<?php the_ID(); ?>" <?php if (function_exists("post_class")) post_class(); else print 'class="post"'; ?>>

        <h3 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link:','fusion'); echo ' '; the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

        <!-- story header -->
        <div class="postheader">
          <div class="postinfo">
            <p><?php printf(__('Posted by %s in %s on %s','fusion'),'<a href="'. get_author_posts_url(get_the_author_ID()) .'" title="'. sprintf(__("Posts by %s","fusion"), attribute_escape(get_the_author())).' ">'. get_the_author() .'</a>',get_the_category_list(', '), get_the_time(get_option('date_format'))); ?> <?php edit_post_link(__('Edit','fusion'),'| ',''); ?></p>
          </div>
        </div>
        <!-- /story header -->

        <div class="postbody entry clearfix">
         <?php if(get_option('fusion_indexposts')=='excerpt') the_excerpt(); else the_content(__('Read the rest of this entry &raquo;', 'fusion')); ?>
        </div>
        <?php
         $posttags = get_the_tags();
         if ($posttags) { ?>
          <p class="tags"><?php the_tags(''); ?></p>
        <?php } ?>
        <p class="postcontrols">
          <?php
           global $id, $comment;
           $number = get_comments_number( $id );
          ?>
          <a class="<?php if($number<1) { echo 'no '; }?>comments" href="<?php comments_link(); ?>"><?php comments_number(__('No Comments','fusion'), __('1 Comment','fusion'), __('% Comments','fusion')); ?></a>
        </p>
        <div class="clear"></div>
    </div>
    <!-- /post -->
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
    <h2><?php _e("Not Found","fusion"); ?></h2>
    <p class="error"><?php _e("Sorry, but you are looking for something that isn't here.","fusion"); ?></p>
    <?php get_search_form(); ?>
 <?php endif; ?>
</div>
<!-- mid content -->
</div>
<!-- /mid -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
