<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>
	<div class="post">
		<h2>Links:</h2>
		<div class="entry">
			<ul>
				<?php wp_list_bookmarks(); ?>
			</ul>
		</div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>