<?php get_header(); ?>
	<div id="main">
	<div id="content" class="narrowcolumn">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle">Search Results</h2>


		<?php while (have_posts()) : the_post(); ?>

			<div class="post">
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<small>Posted in <?php the_category(', ') ?> on <?php the_time('F jS, Y') ?>  by <?php the_author() ?> &ndash; <?php comments_popup_link('Be the first to comment', '1 Comment', '% Comments'); ?> <?php edit_post_link('Edit', ' | ', ''); ?> </small>

				<?php if(is_single()) {?><p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?></p><?php } ?>
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="center">No posts found. Try a different search?</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
	</div>
<?php get_footer(); ?>