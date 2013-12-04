<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?>>
			<h2><?php the_title(); ?></h2>
			<div class="date">
				<div class="bg">
					<span class="day"><?php the_time('d') ?></span>
					<span><?php the_time('M') ?></span>
				</div>
			</div>
			
			<div class="entry">
				<?php the_content(); ?>
				<div class="cl">&nbsp;</div>
				<?php wp_link_pages(array('before' => '<div class="page-navigation"><p><strong>'.__("Pages:").' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number')); ?>
			</div>
			
			<div class="meta">
				<div class="bg">
					<span class="comments-num"><?php comments_popup_link('No Comments', '1 Comment', '% Comments') ?></span>
					<p>Posted <!-- by <?php the_author() ?> --> in <?php the_category(', ') ?></p>
				</div>
				<div class="bot">&nbsp;</div>
			</div>
			<?php the_tags( '<p class="tags">Tags: ', ', ', '</p>'); ?>
		</div>
		<?php comments_template(); ?>
	<?php endwhile; else: ?>
		<p>Sorry, no posts matched your criteria.</p>
	<?php endif; ?>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>