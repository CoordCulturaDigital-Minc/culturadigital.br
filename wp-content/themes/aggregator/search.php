<?php get_header(); ?>

 <div id="wrapper" class="clearfix">

<div id="content">
	<!--the loop-->
	<?php if (have_posts()) : ?>

	<h1>
	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	<?php /* If this is a category archive */ if (is_category()) { ?>				
	<?php echo single_cat_title(); ?>
	
	<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
	Archive for <?php the_time('F jS, Y'); ?>
		
	<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
	Archive for <?php the_time('F, Y'); ?>

	<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
	Archive for <?php the_time('Y'); ?>
		
	<?php /* If this is a search */ } elseif (is_search()) { ?>
	Search Results
		
	<?php /* If this is an author archive */ } elseif (is_author()) { ?>
	Author Archive

	<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
	Blog Archives

	<!--do not delete-->
	<?php } ?>
	</h1>
		
	<!--loop article begin-->
	<?php while (have_posts()) : the_post(); ?>
	<!--post title as a link-->
    
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
    
    
    </div>
		
       <!--one post end-->
	<?php endwhile; ?>

    <!-- Previous/Next page navigation -->
    <div class="page-nav">
	    <div class="nav-previous"><?php previous_posts_link('&larr; Previous Page') ?></div>
	    <div class="nav-next"><?php next_posts_link('Next Page &rarr;') ?></div>
    </div>
	
	<!-- do not delete-->
	<?php else : ?>

	 <h2> Not Found </h2>

	<!--do not delete-->
	<?php endif; ?>
		
	
<!--archive.php end-->

</div>
<!--include sidebar-->
<?php get_sidebar();?>
<!--include footer-->
<?php get_footer(); ?>