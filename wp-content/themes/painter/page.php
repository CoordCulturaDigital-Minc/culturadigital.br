<?php get_header(); ?>

<?php get_sidebar(); ?>

<?php $painter_options = get_option('painter_options'); ?>
<?php if(is_array($painter_options)) extract($painter_options); ?>

<!-- Content -->
<div id="content">
  
  <!-- Posts -->
  <?php if(have_posts()) : ?>
    
    <?php while(have_posts()) : the_post(); ?>
      <div class="post">
        <div class="options">
          <?php edit_post_link(__("Edit", "painter")); ?>
          <span class="post-print"><a href="javascript:print()" title="<?php _e('Print', 'painter'); ?>"><?php _e('Print', 'painter'); ?></a></span>
        </div>
    		<h2 class="post-title"><?php the_title(); ?></h2>
        <div class="entry"><?php the_content(); ?></div>
        <hr class="clear" />
        <div class="info">
          <?php if($show_date == 1) : ?><p class="post-date"><strong><?php _e('Date', 'painter'); ?>:</strong> <?php the_time(get_option('date_format')); ?></p><?php endif; ?>
          <?php if($show_author == 1) : ?><p class="post-author"><strong><?php _e('Author', 'painter'); ?>:</strong> <?php the_author_posts_link(); ?></p><?php endif; ?>
        </div>
    	</div>
    <?php endwhile; ?>
	<?php endif; ?>
	
	<?php comments_template(); ?>
</div>

<?php get_footer(); ?>
