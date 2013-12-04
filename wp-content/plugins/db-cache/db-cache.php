<?php
/*
Plugin Name: DB Cache
Plugin URI: http://wordpress.net.ua/db-cache
Description: The fastest cache engine for WordPress, that produces cache of database queries with easy configuration. (Disable and enable caching after update)
Version: 0.6
Author: Dmitry Svarytsevych
Author URI: http://design.lviv.ua
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

define(DBC_DEBUG, false);

if ( !defined('WP_CONTENT_DIR') )	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

// Path to plugin
define(DBC_PATH, WP_CONTENT_DIR."/plugins/db-cache");

$config = unserialize(@file_get_contents(WP_CONTENT_DIR."/db-config.ini"));
$folders = array("/tmp","/tmp/options","/tmp/links","/tmp/terms","/tmp/users","/tmp/posts");

// Create options menu
add_action('admin_menu', 'dbc_admin_menu');
function dbc_admin_menu() 
{
	add_options_page('DB Cache', 'DB Cache', 'manage_options', 'db-cache/db-options.php');
}

// Enable
function dbc_enable() 
{
	global $dbc_labels, $folders;
	$status = false;
	if (@copy(DBC_PATH."/db-module.php",WP_CONTENT_DIR."/db.php")) $status = true;
	
		foreach($folders as $folder) {
			if ($status && @mkdir(WP_CONTENT_DIR.$folder, 0755)) $status = true;
			if (@copy(DBC_PATH."/.htaccess",WP_CONTENT_DIR.$folder."/.htaccess")) $status = true;
		}

	if ($status) {
		echo "<div id=\"message\" class=\"updated fade\"><p>".$dbc_labels['activated']."</p></div>";
		return true;
	}
	else 
	{
		echo "<div id=\"message\" class=\"error\"><p>".$dbc_labels['notactivated']."</p></div>";
		return false;
	}
}

// Disable
function dbc_disable() 
{
	@include(DBC_PATH."/languages/en_US.php");
	if (WPLANG != '') {
		$langfile = DBC_PATH . "/languages/".WPLANG.".php";
		if (file_exists($langfile)) include($langfile);
	}

  dbc_uninstall();
  echo "<div id=\"message\" class=\"updated fade\"><p>".$dbc_labels['deactivated']."</p></div>";
  
  return true;
}

// Uninstall
add_action('deactivate_db-cache/db-cache.php', 'dbc_uninstall');
function dbc_uninstall()
{
	global $folders;

	@unlink(WP_CONTENT_DIR."/db.php");
	@unlink(WP_CONTENT_DIR."/db-config.ini");
	@unlink(WP_CONTENT_DIR."/tmp/.htaccess");
	dbc_clear();
		foreach($folders as $folder) {
			@unlink(WP_CONTENT_DIR.$folder."/.htaccess");
			@rmdir(WP_CONTENT_DIR.$folder."/");
		}
	@rmdir(WP_CONTENT_DIR."/tmp/");
	
	return;
}

// Clears the cache folder
function dbc_clear() 
{

	if (!class_exists('pcache')) include DBC_PATH."/db-functions.php";
	$dbc = new pcache();
	
	$dbc->storage = WP_CONTENT_DIR."/tmp";
	
	$dbc->clean(false);
	
	return;	
}

// Add cleaning on publish and new comment
	// Posts
	add_action('publish_post', 'dbc_clear', 0);
	add_action('edit_post', 'dbc_clear', 0);
	add_action('delete_post', 'dbc_clear', 0);
	// Comments
	add_action('trackback_post', 'dbc_clear', 0);
	add_action('pingback_post', 'dbc_clear', 0);
	add_action('comment_post', 'dbc_clear', 0);
	add_action('edit_comment', 'dbc_clear', 0);
	add_action('wp_set_comment_status', 'dbc_clear', 0);
	// Other
	add_action('delete_comment', 'dbc_clear', 0);
	add_action('switch_theme', 'dbc_clear', 0); 


function get_num_cachequeries() 
{
	global $wpdb;
	return $wpdb->num_cachequeries;
}

/* 
Function to display load statistics
Put in your template <? loadstats(); ?>
*/
function loadstats() 
{
	global $config;

	if (strlen($config['loadstat']) > 7):

	$stats['timer'] = timer_stop();
		$replace['timer'] = "{timer}";
	$stats['normal'] = get_num_queries();
		$replace['normal'] = "{queries}";
	$stats['cached'] = get_num_cachequeries();
		$replace['cached'] = "{cached}";
	if ( function_exists('memory_get_usage'))	$stats['memory'] = round(memory_get_usage()/1024/1024, 2) . 'MB';
	else $stats['memory'] = 'N/A';
		$replace['memory'] = "{memory}";

	$config['loadstat'] = str_replace($replace,$stats,$config['loadstat']);

	echo $config['loadstat'];
	
	endif;
	
	echo "\n<!-- Cached by DB Cache -->\n";
	
	return;	
}

add_action('wp_footer', 'loadstats', 0);

?>