<?php get_header(); ?>
	<?php the_post(); ?>
	<div class="post">
		<h2><?php the_title(); ?></h2>
		
		<div class="entry">
			<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
		</div>
	</div>
	<?php comments_template(); ?>
	
	<?php get_sidebar(); ?>
<?php get_footer(); ?>