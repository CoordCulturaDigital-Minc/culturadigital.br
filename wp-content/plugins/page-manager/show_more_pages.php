<?php
/*
========
Show more pages in the pages overview
========
*/

//when you see the pages overview, it should show be able to show more pages.
function show_all_pages($perpage) {
	$perpage = 40;
	if(get_option('cms_show_pages_number')) {
		$perpage = get_option('cms_show_pages_number');
	}
	if($_GET['show_pages']) {
		$perpage = $_GET['show_pages'];
		update_option('cms_show_pages_number',$perpage);
	}
	return $perpage;
}
add_filter('edit_pages_per_page','show_all_pages');

//style the dropdown box
function show_all_pages_dropdown_css() {
	echo '
	<style type="text/css">
		#show_more_pages {
			margin-right: 15px;
		}
	</style>
	';
}
add_action('admin_head-edit-pages.php','show_all_pages_dropdown_css');

//add the dropdown box with javascript (because there is no action for this, apparently)
function add_show_all_pages_dropdown() {
	$pluginPath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
	$show_pages = 40;
	if(get_option('cms_show_pages_number')) {
		$show_pages = get_option('cms_show_pages_number');
	}
	if($_GET['show_pages']) {
		$show_pages = $_GET['show_pages'];
		update_option('cms_show_pages_number',show_pages);
	}
	echo '<script type="text/javascript">var getValue = "'.$_GET['show_pages'].'"; var currentValue = "'.$show_pages.'"; var dropDownText = "'.__('Show more pages','trendwerk').'"</script>';
	echo '<script type="text/javascript" src="'.$pluginPath.'js/add_dropdown_show_pages.js"></script>';
}
add_action('admin_head-edit-pages.php','add_show_all_pages_dropdown');
?>