<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="page">
				<h2 class="catheader"><?php the_title(); ?></h2> <?php edit_post_link(' Edit', '<span class="editpost">', '</span>'); ?>
				
				<div class="page-content">
						<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
						<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				</div>
                
                <?php comments_template(); ?>
			</div>
		
	<?php endwhile; endif; ?>
<?php get_footer(); ?>