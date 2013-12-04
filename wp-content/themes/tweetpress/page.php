<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */

get_header(); ?>

	<td id="content" class="column round-left" role="main">
		<div class="wrapper">
			<div class="section">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="post" id="post-<?php the_ID(); ?>">
				<h2 class="pagetitle"><?php the_title(); ?></h2>
					<div class="entry">
						<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
		
						<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		
					</div>
				</div>
				<?php endwhile; endif; ?>
			<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
			
			<?php comments_template(); ?>
			</div>
		</div>
	</td>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
