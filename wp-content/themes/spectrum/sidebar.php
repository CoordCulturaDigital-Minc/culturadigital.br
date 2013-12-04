<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */
?>


	<div id="sidebar">
		<div class="sidebarBox" id="searchBox">
			<div class="sidebarTitle">
				<h4>Search</h4>
			</div>
			<?php get_search_form(); ?>
		</div>
		<div class="sidebarBox" id="categoryBox">
			<div class="sidebarTitle">
				<h4>Categories</h4>
			</div>
			<ul>
				<?php wp_list_categories('show_count=0&title_li='); ?>
			</ul>
		</div>
		<div class="sidebarBox" id="recentPostsBox">
			<div class="sidebarTitle">
				<h4>Recent Posts</h4>
			</div>
			<ul>
				<?php wp_get_archives('title_li=&type=postbypost&limit=3'); ?>
			</ul>
		</div>
		<div class="sidebarBox" id="recentCommentsBox">
			<div class="sidebarTitle">
				<h4>Recent Comments</h4>
			</div>
			<ul>
				<?php if (function_exists('get_recent_comments')) {
  					get_recent_comments();
				}?>
			</ul>
		</div>	
		<div class="sidebarBox" id="archivesBox">
			<div class="sidebarTitle">
				<h4>Archives</h4>
			</div>
			<ul>
				<?php wp_get_archives('type=monthly'); ?>
			</ul>
		</div>
		<div class="sidebarBox" id="blogrollBox">
			<div class="sidebarTitle">
				<h4>Blogroll</h4>
			</div>
			<ul>
				<?php wp_list_bookmarks('&categorize=0&title_li='); ?>
			</ul>
		</div>
		<div id="widgetList">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
 			<?php endif; ?>
		</div>
	</div>