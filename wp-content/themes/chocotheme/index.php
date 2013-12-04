<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div <?php post_class() ?>>
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="date">
					<div class="bg">
						<span class="day"><?php the_time('d') ?></span>
						<span><?php the_time('M') ?></span>
					</div>
				</div>

				<div class="entry">
					<?php the_content('Read the rest of this entry &raquo;'); ?>
					<div class="cl">&nbsp;</div>
				</div>

				<div class="meta">
					<div class="bg">
						<span class="comments-num"><?php comments_popup_link('No Comments', '1 Comment', '% Comments') ?></span>
						<p>Posted <!-- by <?php the_author_link() ?> --> in <?php the_category(', ') ?></p>
					</div>
					<div class="bot">&nbsp;</div>
				</div>
			</div>

		<?php endwhile; ?>
		<?php if(function_exists('wp_pagenavi')) : wp_pagenavi();  else :?>
			<div class="navigation">
				<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
				<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
			</div>
		<?php endif ?>
		
	<?php else : ?>
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>
	<?php endif; ?>
	
	<?php get_sidebar(); ?>
<?php get_footer(); ?>