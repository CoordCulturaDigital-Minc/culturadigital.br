<?php get_header(); ?>
	<div class="list-page">
		<?php if (have_posts()) : ?>
			<h2 class="pagetitle">Search results for &#8216;<?php the_search_query() ?>&#8217;</h2>
			<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class('post') ?>>
					<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					<?php if ($post->post_type=='post'): ?>
						<div class="date">
							<div class="bg">
								<span class="day"><?php the_time('d') ?></span>
								<span><?php the_time('M') ?></span>
							</div>
						</div>	
					<?php endif ?>
					<div class="entry">
						<?php the_content('Read the rest of this entry &raquo;'); ?>
						<div class="cl">&nbsp;</div>
					</div>
					<?php if ($post->post_type=='post'): ?>
						<div class="meta">
							<div class="bg">
								<span class="comments-num"><?php comments_popup_link('No Comments', '1 Comment', '% Comments') ?></span>
								<p>Posted <!-- by <?php the_author_link() ?> --> in <?php the_category(', ') ?></p>
							</div>
							<div class="bot">&nbsp;</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endwhile; ?>
			<div class="navigation">
				<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
				<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
			</div>
		<?php else : ?>
			<h2 class="center">No posts found. Try a different search?</h2>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>