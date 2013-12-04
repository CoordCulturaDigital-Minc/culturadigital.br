<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */
/*
Template Name: Archives
*/
?>
<?php get_header(); ?>
	<td id="content" class="column round-left">
		<div class="wrapper">
			<div class="section">
	
				<h2>Archives by Month:</h2>
					<ul>
						<?php wp_get_archives('type=monthly'); ?>
					</ul>
				
				<h2>Archives by Subject:</h2>
					<ul>
						 <?php wp_list_categories(); ?>
					</ul>
	
			</div>
		</div>
	</td>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
