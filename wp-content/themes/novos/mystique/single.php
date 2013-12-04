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
      <?php if (have_posts()) : while (have_posts()): the_post(); ?>
        <div class="single-navigation clear-block">
          <div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
          <div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
        </div>

        <!-- post -->
        <div id="post-<?php the_ID(); ?>" class="<?php mystique_post_class('single'); ?>">

          <?php if (!get_post_meta($post->ID, 'hide_title', true)): ?><h1 class="title"><?php the_title(); ?></h1><?php endif; ?>

          <div class="post-content clear-block">
          <?php the_content(__('More &gt;', 'mystique')); ?>
          <?php if(function_exists('wp_print')): ?><div class="alignright"><?php print_link(); ?></div><?php endif; ?>
          </div>
          <?php wp_link_pages(array('before' => '<div class="page-navigation"><p><strong> '.__("Pages:","mystique").' </strong>', 'after' => '</p></div>', 'next_or_number' => 'number')); ?>

          <?php
            $settings = get_option('mystique');
            $post_tags = get_the_tags();
            if ($post_tags && $settings['post_single_tags']): ?>
            <div class="post-tags">
            <?php
              $tags = array();
              $i = 0;
              foreach($post_tags as $tag):
               $tags[$i] .=  '<a href="'.get_tag_link($tag->term_id).'" rel="tag" title="'.sprintf(__('%1$s (%2$s topics)'),$tag->name,$tag->count).'">'.$tag->name.'</a>';
               $i++;
              endforeach;
              echo implode(', ',$tags); ?>
            </div>
            <?php endif; ?>


          <?php if($settings['post_single_author']): ?>
          <div class="about_the_author clear-block">
           <div class="avatar">
             <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="<?php echo get_the_author(); ?>"><?php echo mystique_get_avatar(get_the_author_meta('email'), 80); ?></a>
           </div>
           <h3><?php _e("About the author","mystique"); ?></h3>
           <p><?php the_author_meta('description'); ?></p>
          </div>
          <?php endif; ?>

        </div>
        <!-- /post -->

        <?php if($settings['post_single_meta'] || $settings['post_single_share'] || $settings['post_single_print']): ?>
        <table class="post-meta">
          <tr>
            <?php if($settings['post_single_share'] && $settings['jquery']): ?><td><?php mystique_shareThis(); ?></td><?php endif; ?>
            <?php if($settings['post_single_print'] && $settings['jquery']): ?><td><a class="control print"><?php _e("Print article", "mystique"); ?></a> </td><?php endif; ?>
            <?php if($settings["post_single_meta"]): ?>
            <td class="details">
              <?php
              printf(__('This entry was posted by %1$s on %2$s at %3$s, and is filed under %4$s. Follow any responses to this post through %5$s.', 'mystique'), '<a href="'. get_author_posts_url(get_the_author_meta('ID')) .'" title="'. sprintf(__("Posts by %s","mystique"), attribute_escape(get_the_author())).' ">'. get_the_author() .'</a>', get_the_time(get_option('date_format')),get_the_time(get_option('time_format')), get_the_category_list(', '), '<a href="'.get_post_comments_feed_link($post->ID).'" title="RSS 2.0">RSS 2.0</a>');echo ' ';

              if (('open' == $post-> comment_status) && ('open' == $post->ping_status)): // Both Comments and Pings are open
                printf(__('You can <a%1$s>leave a response</a> or <a%2$s>trackback</a> from your own site.', 'mystique'), ' href="#respond"',' href="'.trackback_url('',false).'" rel="trackback"');
              elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)): // Only Pings are Open
                printf(__('Responses are currently closed, but you can <a%1$s>trackback</a> from your own site.', 'mystique'), ' href="'.trackback_url('',false).'" rel="trackback"');
              elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)): // Comments are open, Pings are not
                _e('You can skip to the end and leave a response. Pinging is currently not allowed.','mystique');
              elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)): // Neither Comments, nor Pings are open
                _e('Both comments and pings are currently closed.','mystique');
              endif;

              edit_post_link(__('Edit this entry', 'mystique')); ?>
            </td>
            <?php endif; ?>
          </tr>
        </table>
        <?php endif; ?>

       <?php endwhile; endif; ?>

       <?php comments_template(); ?>
       <?php do_action('mystique_after_primary'); ?>
     </div>
    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>
