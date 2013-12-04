<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */

get_header(); ?>

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<div class="mainTitle">
				<h3><?php the_title(); ?></h3>
				<div class="postDate">
					<span class="month"><?php the_time('m') ?></span>
					<span class="day"><?php the_time('d') ?></span>
					<span class="year"><?php the_time('y') ?>'</span>
				</div>
			</div>
			<div class="entry">
				<?php the_content('Read the rest of this entry &raquo;'); ?>
			</div>
		</div>
		
		<?php comments_template(); ?>
		
		<?php endwhile; endif; ?>
		
</div>
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>