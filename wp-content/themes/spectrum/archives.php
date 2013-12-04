<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */
?>

<?php get_header(); ?>

<div class="post">
	<div class="mainTitle"><h3>Archives by Month:</h3></div>
	<div class="entry">
		<ul>
	 		<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</div>
</div>

<div class="post">
	<div class="mainTitle"><h3>Archives by Subject:</h3></div>
	<div class="entry">
		<ul>
	 		<?php wp_list_categories(); ?>
		</ul>
	</div>
</div>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>