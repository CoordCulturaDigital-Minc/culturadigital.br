<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */

/*
Template Name: Links
*/
?>

<?php get_header(); ?>

<td id="content" class="column round-left">
	<div class="wrapper">
		<div class="section">

<h2>Links:</h2>
<ul>
<?php wp_list_bookmarks(); ?>
</ul>

		</div>
	</div>
</td>

<?php get_footer(); ?>
