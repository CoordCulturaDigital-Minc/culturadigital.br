<?php get_header(); ?>

<div id="wrapper" class="clearfix" >
   	
    <div id="content">
       <!--loop-->
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <!--post title-->
      <h1   id="post-<?php the_ID(); ?>">
        <?php the_title(); ?>
      </h1>
      <!--post with more link -->
      <div class="clear">
        <?php the_content('<p class="serif">continue</p>'); ?>
      </div>
      <!--if you paginate pages-->
      <?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
      <!--end of post and end of loop-->
      <?php endwhile; endif; ?>
      </div>
      
      
<?php get_sidebar(); ?>      
      
    
</div>
<!-- wrapper #end -->

<!-- footer #end -->
<script src="<?php bloginfo('template_directory'); ?>/scripts/newsblocks.js" type="text/javascript" charset="utf-8"></script>
<?php get_footer(); ?>
