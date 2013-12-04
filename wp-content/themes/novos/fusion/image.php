<?php /* Fusion/digitalnature */ ?>
<?php get_header(); ?>

  <!-- mid content -->
  <div id="mid-content">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <!-- post -->
            <div id="post-<?php the_ID(); ?>" <?php if (function_exists("post_class")) post_class(); else print 'class="post"'; ?>>
                 <div class="clearfix">
                  <h3 class="posttitle"><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <?php the_title(); ?></h3>
                  <p class="attachment-entry"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></p>
  				 <div class="caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?>
              	  <?php the_content(__('Read the rest of this entry &raquo;', 'fusion')); ?></div>
                </div>
  		    </div>
              <!-- /post -->

            <div class="navigation">
		       <div class="alignleft"><?php previous_image_link() ?></div>
			   <div class="alignright"><?php next_image_link() ?></div>
               <div class="clear"></div>
		    </div>

            <p class="postmetadata alt">
  			<small>
                 <?php
                    printf(__('This entry was posted on %s and is filed under %s. You can follow any responses to this entry through %s.', 'fusion'), get_the_time(get_option('date_format').', '.get_option('time_format')), get_the_category_list(', '), '<a href="'.get_post_comments_feed_link($post->ID).'" title="RSS 2.0">RSS 2.0</a>');  ?>

                 <?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
          		  // Both Comments and Pings are open
                    printf(__('You can <a href="#respond">leave a response</a>, or <a href="%s" rel="trackback">trackback</a> from your own site.', 'fusion'), trackback_url('',false));

          		 } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
          		  // Only Pings are Open
                    printf(__('Responses are currently closed, but you can <a href="%s" rel="trackback">trackback</a> from your own site.', 'fusion'), trackback_url('',false));

          		 } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
          		  // Comments are open, Pings are not
          		  _e('You can skip to the end and leave a response. Pinging is currently not allowed.','fusion');

          		 } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
          		  // Neither Comments, nor Pings are open
          		  _e('Both comments and pings are currently closed.','fusion');
          		} ?>
                  <?php edit_post_link(__('Edit this entry', 'fusion')); ?>
              </small>
  		   </p>
  	<?php comments_template(); ?>
  	<?php endwhile; else: ?>
  	<p><?php _e('Sorry, no attachments matched your criteria.','fusion'); ?></p>
  <?php endif; ?>
    </div>
    <!-- mid content -->
   </div>
   <!-- /mid -->

    <?php get_sidebar(); ?>

 <?php get_footer(); ?>
