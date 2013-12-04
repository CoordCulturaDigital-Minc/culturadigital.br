<?php /* Fusion/digitalnature */ ?>
<?php get_header(); ?>

  <!-- mid content -->
  <div id="mid-content">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="navigation">
      <div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
      <div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
      <div class="clear"></div>
    </div>

    <div id="post-<?php the_ID(); ?>" <?php if (function_exists("post_class")) post_class(); else print 'class="post"'; ?>>
       <?php if (!get_post_meta($post->ID, 'hide_title', true)): ?><h2 class="title"><?php the_title(); ?></h2><?php endif; ?>
       <br />
	    <div class="entry">
          <div class="postbody entry clearfix">
	       <?php the_content(__('Read the rest of this entry &raquo;', 'fusion')); ?>
          </div>
          <?php wp_link_pages(array('before' => '<p class="postpages"><strong>Pages: </strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
          <?php
          $posttags = get_the_tags();
          if ($posttags) { ?>
          <p class="tags"><?php the_tags(''); ?></p>
          <div class="clear"></div>
          <?php } ?>
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
	   </div>
    </div>
	<?php comments_template(); ?>
  <?php endwhile; else: ?>
    <p><?php _e("Sorry, no posts matched your criteria.","fusion"); ?></p>
  <?php endif; ?>
 </div>
    <!-- mid content -->
   </div>
   <!-- /mid -->

   <?php get_sidebar(); ?>

<?php get_footer(); ?>
