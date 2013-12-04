<?php
/*
Template Name: Archives
*/
?>
<?php get_header(); ?>
	<div class="post archives">
		<div class="entry">
			<?php get_search_form(); ?>
			<hr />
			<h3>Archives by Month:</h3>
			<ul>
				<?php wp_get_archives('type=monthly'); ?>
			</ul>
			<hr />
			<h3>Archives by Subject:</h3>
			<ul>
				 <?php wp_list_categories(); ?>
			</ul>
		</div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>