<?php get_header(); ?>

<div id="wrapper" class="clearfix">
   	
    <div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    
    
    
    


		<div id="posts" >
	<div class="date"> <span class="d"><?php the_time('j'); ?></span> <br /> <span class="month"><?php the_time('M'); ?></span></div>
	
	<h3 class="h1" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>

				
	<!--Post Meta-->
	<div class="post-meta-top">
	<div class="auth"><span>Posted by <strong><?php the_author_posts_link(); ?></strong> | <?php comments_popup_link('(0) Comment', '(1) Comment', '(%) Comment'); ?></span></div>
 	</div>
	<div class="clearboth"></div>
	<!--optional excerpt or automatic excerpt of the post-->
	<?php the_excerpt(); ?>
 
	<div class="post-meta-bottom">
	  <div class="tags"> <?php the_tags(' '.__('Tags : ','Templatic').'', ', ', ''); ?>  </div>
	<div class="cat"> <strong>Category  : </strong> <?php the_category(', ') ?></div>

	</div>
    
    
    </div> <!-- post #end -->

	<div id="comments">	<?php comments_template(); ?> </div>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

	</div>
    
    
    <?php get_sidebar(); ?>  
    
</div>

<?php get_footer(); ?>
