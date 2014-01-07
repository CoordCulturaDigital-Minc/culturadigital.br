<?php
/*
Plugin Name: Clean Options
Plugin URI: http://www.mittineague.com/dev/co.php
Description: finds orphaned options and allows for their removal from the wp_options table
Version: 1.3.2
Author: Mittineague
Author URI: http://www.mittineague.com
*/

/*
* Change Log
*
* 1.3.2 26-Jun-2010
* - updated the $known_ok array (for WordPress 3.0)
* - de_DE German translation
* - nl_NL Dutch translation
*
* 1.3.1 27-Mar-2010
* - allow 2.9.1 blogs to remove 2.8 transient options
* - added timestamp to blog date/time format in review
* - updated $known_ok array
* - get_all_yes_autoload_options() optimization
* - expanded string search to core files
* - added 'site' to regex pattern
* - added get_transient regex
* - sr_RS Serbian Cyrillic translation
* - sr_RS Serbian Latinica translation
* - minor typo fixes
* - updated older translation files
*
* 1.3.0 16-Jan-2010
* - updated the $known_ok array (for WordPress 2.9)
* - optimized for WordPress 2.9
* - replaced deprecated user_role
* - pt_BR Portuguese translation
* - zh_CN Chinese translation
* - hr_HR Croatian translation
*
* 1.2.3 10-Sep-2009
* - removed WordPress < ver. 2.3 feature
* - WordPress < ver. 2.7 fix
* - be_BY Belarusian translation
*
* 1.2.2 27-Aug-2009
* - es_ES Spanish translation
* - corrected ru_RU Russian translation
* - uk_UA Ukrainian translation
*
* 1.2.1 14-Aug-2009
* - added more "transient"s
* - allow 2.8+ blogs to remove all obsolete rss_hash rows
* - allow for non-default folder locations/names
* - improved unpaired rss block
* - capability check for added security
* - eliminate 2.8+ false warnings
* - ru_RU translation
* - minor tweaks
* - changed Version History to Changelog in readme
*
* 1.2.0 19-Jun-2009
* - Internationalization
* - updated the $known_ok array (for WordPress 2.8)
* - changed admin CSS hook
* - plugin page Settings link
* - option count in admin menu
* - minor tweaks
*
* 1.1.9 27-Mar-2009
* - $wpdb compatibility with WordPress < 2.5
*
* 1.1.8 27-Mar-2009
* - nonce compatibility with WordPress < 2.5
* - minor tweak
*
* 1.1.7 24-Mar-2009
* - added show/hide Known Core
* - added Search feature
* - exclude non WP folders in searchdir()
* - tweaked nonce calls
* - changed fopen to is_readable
* - changed fread to file_get_contents
*
* 1.1.6 18-Mar-2009
* - reduced # of dt in warnings
* - searchdir tweak
* - added support for MySQL < 4.1
* - $rss_ts_arr sort tweak
* - added option_name Google search links
*
* 1.1.5 09-Mar-2009
* - "delete all" Bug Fix
*
* 1.1.4 08-Mar-2009
* - added link to options.php page
* - some regex refinement
* - added "known Core" wording
* - removed %s from yes in queries
* - added category_children as known core
* - added show/hide AS warnings
*
* 1.1.3 07-Mar-2009
* - added error message info
* - added find non-string option names
* - changed the way limit_query works
* - optimized database queries
* - refined 'yes' regex
* - more minor tweaks yet again
*
* 1.1.2 25-Feb-2009
* - added show_errors to DB objects
* - friendlier CSS selectors
* - added label tags
*
* 1.1.1 21-Feb-2009
* - query syntax change
* - query error catching added
* - error scope changes
*
* 1.1.0 RC 27-Jan-2009
* - limit 'delete all' rss delete to < 100 highest id
* - added rss_hash limited find
* - fixed and updated the $known_ok array
* - improved robustness
* - - fixed searchdir() return type initialization
* - - set explicit return type in $wpdb->get_results queries
* - - ini_get('safe_mode') fixes
* - changed found rss_hash options section
* - added javascript select/deselect all
* - various other minor tweaks
*
* 1.0.0 RC 12-Nov-2008
* - increased memory limit from 32M to 64M
* - added remove all rss_hash section
*
* For complete Change Log, please visit http://www.mittineague.com/dev/co.php
*/

/*
/-----------------------------------------------------------------------\
|																		|
| License: GPL															|
|																		|
| Clean Options Plugin - allows removal of orphaned options				|
| Copyright (C) 2007 - 2010, Mittineague, www.mittineague.com			|
| All rights reserved.													|
|																		|
| This program is free software; you can redistribute it and/or			|
| modify it under the terms of the GNU General Public License			|
| as published by the Free Software Foundation; either version 2		|
| of the License, or (at your option) any later version.				|
|																		|
| This program is distributed in the hope that it will be useful,		|
| but WITHOUT ANY WARRANTY; without even the implied warranty of		|
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the			|
| GNU General Public License for more details.							|
|																		|
| You should have received a copy of the GNU General Public License		|
| along with this program; if not, write to the							|
| Free Software Foundation, Inc.										|
| 51 Franklin Street, Fifth Floor										|
| Boston, MA  02110-1301, USA											|
|																		|
\-----------------------------------------------------------------------/
*/

if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
if ( ! defined( 'PLUGINDIR' ) )
	define( 'PLUGINDIR', 'wp-content/plugins' );

function mitt_co_add_settings_link($links, $file)
{
	if ( $file == plugin_basename(__FILE__) )
	{
		$co_settings_link = "<a href='tools.php?page=cleanoptions.php'>" . __('Tools', 'cleanoptions') . "</a>";
		array_unshift( $links, $co_settings_link );	// before Deactivate | Edit
//		$links[] = $co_settings_link;				// after Deactivate | Edit
	}
	return $links;
}

function mitt_co_admin_init()
{
	$co_plugin_dir = dirname(plugin_basename(__FILE__));

	if (function_exists('load_plugin_textdomain'))
	{
		load_plugin_textdomain('cleanoptions', PLUGINDIR . '/' . $co_plugin_dir . '/languages',
						 $co_plugin_dir . '/languages' );
	}

	add_filter('plugin_action_links', 'mitt_co_add_settings_link', null, 2); 
}

function mitt_co_css()
{
?>
<style type="text/css">
.mitt_co_submit {
  margin: 0.7em 0;
  clear: left;
 }
fieldset#mod_mem {
  margin: 0.5em 0;
 }
#mitt_co_table {
  border: 1px solid #000;
  margin-bottom: 0.7em;
 }
#mitt_co_table th {
  border-bottom: 1px solid #777;
 }
#mitt_co_table td {
  padding: .1em;
  border-right: 1px solid #bbb;
  border-bottom: 1px solid #bbb;
 }
span.co_found_core {
  font-size: 0.8em;
  white-space: pre;
 }
span.co_google_link {
  white-space: pre;
 }
div.mitt_co_errors {
  border: 2px solid #f00;
  margin: 0.7em 0;
  padding: 1em;
 }
div.mitt_co_errors strong {
  color: #f00;
 }
div.mitt_co_errors dt, div#mitt_co_msg dt {
  font: bold 105% monospace;
  color: #046;
 }
div#mitt_co p.wpdberror {
  border: 2px solid #f00;
  padding: 1em;
 }
div#mitt_co p.wpdberror strong {
  color: #f00;
  margin: 0.2em 0.7em 0.2em 0;
 }
ul#mitt_co_help {
  list-style-type: disc;
  list-style-position: inside;
 }
ul#mitt_co_help li ul li {
  list-style-type: none;
  margin-left: 2em;
 }
span.mitt_co_ps {
  border: 1px inset #000;
  margin-left: 0.7em;
  padding: 0 1em 0.2em 1em;
  background-color: #fff;
 }
span#mitt_co_pss {
  font-size: 1.25em;
  font-weight: bold;
 }
div#mitt_co_searchbar {
  float: right;
  margin-top: 0.7em;
  border: 1px solid #777;
 }
div#mitt_co_searchbar input#mitt_co_psi {
  margin: 0.5em -0.4em 0.5em 0.5em;
  width: 25em;
 }
div#mitt_co_searchbar input.mitt_co_submit {
  margin: 0 0.5em 0.6em -0.4em;
 }
#mitt_co_hrsep {
  visibility: hidden;
  clear: right;
 }
</style>
<?php
}

function mitt_get_opt_tbl_len($show = true)
{
	global $wpdb;
		if($show == true)
	{
		$wpdb->show_errors(true);
	}
	else
	{
		$wpdb->hide_errors();
	}
	$get_tbl_len = $wpdb->get_var("SELECT COUNT(option_id) FROM $wpdb->options");
	$get_tbl_len = ( $get_tbl_len ) ? $get_tbl_len : 0;
	return (int) $get_tbl_len;
}

function mitt_add_co_page()
{
	global $cononce;
	$opt_tbl_len = mitt_get_opt_tbl_len(false);
	if ( function_exists('add_management_page') )
	{
		$mitt_co_mp = add_management_page('Clean Options', 'CleanOptions (' . $opt_tbl_len . ')', 'manage_options', basename(__FILE__), 'mitt_co_page');
		add_action("admin_head-$mitt_co_mp", 'mitt_co_css');
	}
	$cononce = md5('cleanoptions');
}

function mitt_co_page()
{
	global $cononce, $co_err_obj, $as_warn_flag, $db_err_flag, $en_warn_flag , $fs_err_flag, $cur_wp_ver, $content_dir_name, $plugins_dir_name, $bye_rss, $rss_gone, $search_paths;

	$as_warn_flag = $db_err_flag = $en_warn_flag  = $fs_err_flag = false;

	if ( function_exists('current_user_can') && !current_user_can('manage_options') ) die;

/* run Bug Fix in case user did an older version's 'delete all' */
	if ( !get_option('rss_excerpt_length') ) add_option('rss_excerpt_length', 50);
	if ( !get_option('rss_use_excerpt') ) add_option('rss_use_excerpt', 0);
	if ( !get_option('rss_language') ) add_option('rss_language', 'en');

/* "delete all" constants */
	if ( ! defined( 'CO_MIN_BEFORE_SHOW' ) )
		define( 'CO_MIN_BEFORE_SHOW', 500 );	// arbitrary threshold - 500
	if ( ! defined( 'CO_KEEP_NEWER' ) )
		define( 'CO_KEEP_NEWER', 100 );		// arbitrary offset - 100

/* "Large Table" constant */
	if ( ! defined( 'CO_BATCH_SIZE' ) )
		define( 'CO_BATCH_SIZE', 350 );		// arbitrary size - 350, even # only

	$co_mem_lim = ini_get('memory_limit');

	function co_return_bytes($val)
	{
		$val = trim($val);
		$val = ( strlen($val) > 0 ) ? $val : ' ';
		$last = strtolower( $val { strlen($val)-1 } );
		switch($last)
		{
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
				break;
			default:
				$val = 0;
		}
		return $val;
	}

	$mem_lim_as_bytes = co_return_bytes($co_mem_lim);

	$opt_tbl_len = mitt_get_opt_tbl_len(false);

	if ( ($opt_tbl_len > CO_BATCH_SIZE)
		 && ($mem_lim_as_bytes < 67108864)
		 && !ini_get('safe_mode') )
	{
		ini_set('memory_limit', '64M');
	}

	if ( ($opt_tbl_len > CO_BATCH_SIZE)
		 && !ini_get('safe_mode') )
	{
		$co_time_lim = ini_get('max_execution_time');
		if ( $co_time_lim < 60 )
		{
			set_time_limit(60);
		}
	}

	$cur_wp_ver = get_bloginfo('version');
	$cur_wp_ver = substr( $cur_wp_ver, 0, 3 );
	$bye_rss = ($cur_wp_ver >= 2.8) ? true : false;
	$rss_gone = ($cur_wp_ver >= 2.9) ? true : false;
	$del_all_warning = '';
	$lf_select_msg = '';	

	$common_form_message = '<p>' . __('Listed Options are those that are found in the wp_options table but are not referenced by "get_option" or "get_settings" by any of the PHP files located within your blog directory. If you have deactivated plugins and/or non-used themes in your directory, the associated options will not be considered orphaned until the files are removed.', 'cleanoptions') . '</p>';

	if ($rss_gone != true )
	{
	$del_all_warning = '<p>' . __('Most likely there are an extreme number of "rss_hash" rows in the wp_options table.', 'cleanoptions') . '</p>
<p><strong>!' . __('WARNING', 'cleanoptions') . '!</strong> ' . sprintf(__('To avoid excessive memory use, using "%s" does not attempt to list all of the "rss_hash" options, nor will you be able to review the contents.', 'cleanoptions'), __('Delete ALL \'rss\' Options', 'cleanoptions') ) . '<br />';
	$del_all_warning .= sprintf(__('"%s" attempts to provide some measure against the deletion of current "rss_hash" options by not deleting any "rss_hash" rows with an option_id newer than (the last row id - %d ). Because plugins and themes also add rows to the wp_options table, depending on your recent installation history, this may remove <strong>ALL</strong> of the "rss_hash" options, both older AND <strong>CURRENT</strong> ones, no questions asked.', 'cleanoptions'), __('Delete ALL \'rss\' Options', 'cleanoptions'), CO_KEEP_NEWER) . '<br />';
	$del_all_warning .= sprintf(__('Although removing current "rss_hash" rows should not "break" your WordPress blog (they should be entered again next time the feed is cached), please <strong>BACKUP</strong> your database <strong>BEFORE</strong> doing this.<br />After using "%1$s", you should "%2$s" to clean the wp_options table further.', 'cleanoptions'), __('Delete ALL \'rss\' Options', 'cleanoptions'), __('Find Orphaned Options', 'cleanoptions') ). '</p>';
	$del_all_warning .= '<p>' . __('It is <strong>highly recommended</strong> that you Limit the "Find" to only a selected number of the most recent "rss_hash" options Instead and repeat the process until the number becomes manageable.', 'cleanoptions') . '</p>';

	$lf_select_msg = '<p>' . __('The lower the number of "rss_hash" option pairs you "Find", the less likely it is that you will experience problems with memory during the "Find". However, depending on the number of feed rows that are current, the "Find" may not include any older ones that can be deleted.<br />The higher the number of "rss_hash" pairs you "Find", the more likely it is that older ones that can be deleted will be included. But there is a greater chance of having memory problems during the "Find".<br />It is suggested that you start off with a lower "Find", and increase the number gradually, if you wish to, on subsequent "Finds". If you get a memory error, use a lower number.<br />Again said, it is recommended that you scan the results on the review page of anything you select prior to it\'s deletion, to ensure that you really want to remove it.', 'cleanoptions') . '</p>';

	$lf_lmu = __('Low Memory usage', 'cleanoptions');
	$lf_mmu = __('Moderate Memory usage', 'cleanoptions');
	$lf_hmu = __('High Memory usage', 'cleanoptions');
	$lf_find = __('Find', 'cleanoptions');
	$lf_10pairs = __('10 pairs', 'cleanoptions');
	$lf_25pairs = __('25 pairs', 'cleanoptions');
	$lf_50pairs = __('50 pairs', 'cleanoptions');
	$lf_75pairs = __('75 pairs', 'cleanoptions');
	$lf_100pairs = __('100 pairs', 'cleanoptions');
	$lf_125pairs = __('125 pairs', 'cleanoptions');
	$lf_150pairs = __('150 pairs', 'cleanoptions');
	$lf_175pairs = __('175 pairs', 'cleanoptions');

$lf_select = <<<EOLS
	$lf_select_msg

<fieldset id='low_mem'>
	<legend>$lf_lmu</legend>
	<span>&nbsp;$lf_find: </span>
	<input id='lf_20' name='limit_query' type='radio' value='20' />
	<label id='l_20' for='lf_20'>$lf_10pairs</label>

	<input id='lf_50' name='limit_query' type='radio' value='50' checked='checked' />
	<label id='l_50' for='lf_50'>$lf_25pairs</label>
</fieldset>

<fieldset id='mod_mem'>
	<legend>$lf_mmu</legend>
	<span>&nbsp;$lf_find: </span>
	<input id='lf_100' name='limit_query' type='radio' value='100' />
	<label id='l_100' for='lf_100'>$lf_50pairs</label>

	<input id='lf_150' name='limit_query' type='radio' value='150' />
	<label id='l_150' for='lf_150'>$lf_75pairs</label>

	<input id='lf_200' name='limit_query' type='radio' value='200' />
	<label id='l_200' for='lf_200'>$lf_100pairs</label>
</fieldset>

<fieldset id='high_mem'>
	<legend>$lf_hmu</legend>
	<span>&nbsp;$lf_find: </span>
	<input id='lf_250' name='limit_query' type='radio' value='250' />
	<label id='l_250' for='lf_250'>$lf_125pairs</label>

	<input id='lf_300' name='limit_query' type='radio' value='300' />
	<label id='l_300' for='lf_300'>$lf_150pairs</label>

	<input id='lf_350' name='limit_query' type='radio' value='350' />
	<label id='l_350' for='lf_350'>$lf_175pairs</label>
</fieldset>
<br />
EOLS;

	}

	$sc_select = "<input name='hide_known_core' id='hide_known_core' type='checkbox' value='1' checked='checked' />
		&nbsp;<label for='hide_known_core'>" . __('Don\'t show the Known WordPress Core options for this "Find"', 'cleanoptions') . "</label><br />";

	$sh_select = "<input name='hide_as_warn' id='hide_as_warn' type='checkbox' value='1' />
		&nbsp;<label for='hide_as_warn'>" . __('Don\'t show the Alternate Syntax Warnings for this "Find"', 'cleanoptions') . "</label><br />";

/* determine location paths */
	$sdr = $_SERVER['DOCUMENT_ROOT'];
	$wad = getcwd();

	$content_under_blog_root = ( str_replace(ABSPATH, '', WP_CONTENT_DIR) == 'wp-content' ) ? true : false;
	$plugins_under_content = ( str_replace(WP_CONTENT_DIR . '/', '', WP_PLUGIN_DIR) == 'plugins' ) ? true : false;
	$plugins_under_blog_root = ( str_replace(ABSPATH . '/', '', WP_PLUGIN_DIR) == 'plugins' ) ? true : false;

	$back_out_of_admin = '..'; // default, blog under top level doc root
	if ( $sdr . '/' != ABSPATH )
	{
		$back_out_of_admin = '';
		$admin_depth = count( explode('/', str_replace($sdr . '/', '', $wad) ) );
		for ($h = 0; $h < $admin_depth; $h++)
		{
			$back_out_of_admin .= '../';
		}
	}

	$content_search = $plugins_search = '..';
	$content_dir_name = 'wp-content'; // default
	$plugins_dir_name = 'plugins'; // default

	if ( ! $content_under_blog_root )
	{
		$content_path = str_replace($sdr . '/', '', WP_CONTENT_DIR);
		$content_dir_name = end( explode('/', $content_path) );
		$content_separator = ($back_out_of_admin == '..') ? '/' : '';
		$content_search = $back_out_of_admin . $content_separator . $content_path;
	}

	if ( (! $plugins_under_content) && (! $plugins_under_blog_root) )
	{
		$plugins_path = str_replace($sdr . '/', '', WP_PLUGIN_DIR);
		$plugins_dir_name = end( explode('/', $plugins_path) );
		$plugins_separator = ($back_out_of_admin == '..') ? '/' : '';
		$plugins_search = $back_out_of_admin . $plugins_separator . $plugins_path;
	}

	$search_paths = array('..', $content_search, $plugins_search);
	$search_paths = array_unique($search_paths);

/* deal with errors */
	function add_wp_error($code, $message)
	{
		global $co_err_obj;

		if ( !is_object($co_err_obj) )
		{
			$co_err_obj = new WP_Error();
		}

		if ( is_object ($co_err_obj) )
		{
			$co_err_obj->add($code, $message);
		}
		else
		{
			echo '<div class="mitt_co_errors"><strong>' . __('WARNING', 'cleanoptions') . ' !!</strong><br />';
			echo $code . ': ' . $message . '</div>';
		}
	}

	function check_for_wp_errors()
	{
		global $co_err_obj;

		if ( !empty($co_err_obj) )
		{
			echo '<div class="mitt_co_errors">';
			echo "<strong>" . __('WARNING', 'cleanoptions') . " !!</strong><dl>";
			foreach ($co_err_obj->get_error_codes() as $err_code)
			{
				echo '<dt>' . $err_code . '</dt>';
				foreach ($co_err_obj->get_error_messages($err_code) as $err_msg)
				{
					echo '<dd>' . $err_msg . '</dd>';
				}
			}
			echo '</dl></div>';
		}
	}

/* search folders */
	function mitt_co_searchdir($dir)
	{
		global $fs_err_flag, $content_dir_name, $plugins_dir_name;
		$file_list = array();
		$stack[] = $dir;

		while ($stack)
		{
			$current_dir = array_pop($stack);

			if ( ($current_dir == '..')
			 || ( substr($current_dir, 0, 6) == '../wp-')
			 || ( strpos($current_dir, $content_dir_name) !== false)
			 || ( strpos($current_dir, $plugins_dir_name) !== false) )
			{
				if ( $dh = opendir($current_dir) )
				{
					while ( ($file = readdir($dh)) !== false )
					{
						if ( ($file !== '.') && ($file !== '..') )
						{
							$current_file = "{$current_dir}/{$file}";
							$get_ext = pathinfo($current_file);
							if ( is_file($current_file) && isset($get_ext['extension']) && ($get_ext['extension'] == 'php') )
							{
								$file_list[] = $current_file;
							}
							elseif ( is_dir($current_file) )
							{
								$stack[] = $current_file;
							}
						}
					}
					closedir($dh);
				}
				else
				{
					add_wp_error(__('File System Error', 'cleanoptions'), sprintf(__('Could not open folder %s', 'cleanoptions'), $current_dir) );
					$fs_err_flag = true;
				}
			}
		}
		return $file_list;
	}

/* Find Orphans Section */

	if ( isset($_POST['find_orphans']) )
	{
		$co_fo_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_fo' : '_wpnonce';
		check_admin_referer('clean-options-find-orphans_' . $cononce, $co_fo_name);
?>
		<div id="mitt_co" class="wrap">
		<h2>Clean Options</h2>
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<?php
		$co_pro_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_pro' : '_wpnonce';
		if ( function_exists('wp_nonce_field') )
			wp_nonce_field('clean-options-pre-remove-orphans_' . $cononce, $co_pro_name);

		$out = array();
		foreach($search_paths as $search_path)
		{
			$search_results = mitt_co_searchdir($search_path);
			$out = array_unique( array_merge($out, $search_results) );
		}
		
		$pattern = "#(?:\(| |\=)(?:get|update)_(?:user_|site_)?(?:option|settings)[ ]?\([ ]?[\'\"]([-\w]+)[\'\"][ ]?\)#";
		$trans_pattern = "#(?:get_transient[ ]?\([ ]?[\'\"])([\w]+)(?:[\'\"][ ]?\))#";

		$alt_1_pattern = "#(?:\(| |\=)get_(?:user_)?(?:option|settings)[ ]?\([ ]?([$][->\w]+)[ ]?\)#";
		$alt_2_pattern = "#(?:\(| |\=)get_(?:user_)?(?:option|settings)[ ]?\([ ]?([\'\"][-\w]+[\'\"][ ]?[\.][ ]?[-\w$]+)[ ]?\)#";
		

		$temp_file_options_arr = array();

/* Array of known files that use alternate "non-string" syntax
* that can not possibly cause any problems because of it.
* Presumed trust of core WordPress files.
* More to be added as they become known?
*/
		$ignore = array(
				 'wp-admin',
				 'wp-includes',
				 'cleanoptions.php',	//cleanoptions
				 'subscribe2.php',	//subscribe2 4.13
				);

		foreach ( $out as $found_file )
		{
			if ( !is_readable($found_file) )
			{
				add_wp_error(__('File System Error', 'cleanoptions'), sprintf(__('Could not open file %s', 'cleanoptions'), $found_file) );
				$fs_err_flag = true;
				clearstatcache();
			}
			else
			{
				$show_alt_opt_name = 'true';
				$file_data = file_get_contents($found_file);
				preg_match_all($pattern, $file_data, $matches_arr);

				preg_match_all($trans_pattern, $file_data, $trans_matches_arr);
				$trans_opts = array();
				if ( is_array($trans_matches_arr[1]) && !empty($trans_matches_arr[1]) )
				{
					foreach ( $trans_matches_arr[1] as $trans_match)
					{
						$trans_opt_name = '_transient_' . $trans_match;
						$trans_opts[] = $trans_opt_name;
					}	
				}				
				
				if ( isset($_POST['hide_as_warn']) && ($_POST['hide_as_warn'] != FALSE) )  $show_alt_opt_name = 'false';

				if ( $show_alt_opt_name == 'true' )
				{
					foreach ( $ignore as $exempt )
					{
						if ( strpos($found_file, $exempt) !== FALSE ) $show_alt_opt_name = 'false';
					}
				}

				if ( $show_alt_opt_name == 'true' )
				{
					preg_match_all($alt_1_pattern, $file_data, $alt_1_matches_arr);
					preg_match_all($alt_2_pattern, $file_data, $alt_2_matches_arr);

					if ( is_array($alt_1_matches_arr[1]) && !empty($alt_1_matches_arr[1]) )
					{
						foreach ( $alt_1_matches_arr[1] as $alt_syntax_match )
						{
							$alt_syntax_match = str_replace('$', '&#36;', wp_specialchars($alt_syntax_match) );
							add_wp_error(__('Alternate Syntax', 'cleanoptions'), sprintf(__(' %1$s has an option line with %2$s', 'cleanoptions'), $found_file, $alt_syntax_match) );
							$as_warn_flag = true;
						}
					}						

					if ( is_array($alt_2_matches_arr[1]) && !empty($alt_2_matches_arr[1]) )
					{
						foreach ($alt_2_matches_arr[1] as $alt_syntax_match)
						{
							$alt_syntax_match = str_replace('$', '&#36;', wp_specialchars($alt_syntax_match) );
							add_wp_error(__('Alternate Syntax', 'cleanoptions'), sprintf(__(' %1$s has an option line with %2$s', 'cleanoptions'), $found_file, $alt_syntax_match) );
							$as_warn_flag = true;
						}
					}
				}
				$temp_file_options_arr = array_unique ( array_merge ($temp_file_options_arr, $trans_opts) );
				$temp_file_options_arr = array_unique ( array_merge ($temp_file_options_arr, $matches_arr[1]) );
			}
		}

/* Search Options Table for Existing option values */
/* autoload = yes block (Plugins) <2.9 */
/* all options 2.9+ */

		function get_all_yes_autoload_options()
		{
			global $wpdb, $db_err_flag, $en_warn_flag, $rss_gone;
			$wpdb->show_errors(true);
			$all_yes_options = array();

			if ($rss_gone == true)
			{
				$yes_query = "
				SELECT option_name
				     , option_value
			          FROM $wpdb->options
				";
			}	
			else
			{
				$yes_query = "
				SELECT option_name
				     , option_value
			          FROM $wpdb->options
				 WHERE autoload LIKE 'yes'
				  OR ( autoload NOT LIKE 'yes'
				   AND option_name NOT LIKE 'rss\_%' )
				";
			}

			$yes_options = $wpdb->get_results($yes_query, ARRAY_A);

			if( !is_array($yes_options) )
			{
				add_wp_error(__('Database Error', 'cleanoptions'), sprintf(__('%s (the Orphaned Options query) did not return an array.', 'cleanoptions'), $yes_query) );
				$db_err_flag = true;
				$yes_options = array();
			}

			foreach ( $yes_options as $yes_option )
			{
				$yes_opt_val = ( !empty($yes_option['option_value']) ) ? $yes_option['option_value'] : 'EMPTY';
				if ( empty($yes_option['option_name']) )
				{
					add_wp_error(__('Empty Name', 'cleanoptions'), sprintf(__('There is an autoload yes Option with No Name with the value: %s', 'cleanoptions'), wp_specialchars($yes_opt_val) ) );
					$en_warn_flag  = true;
				}
				else
				{
					$all_yes_options[] =  $yes_option['option_name'];
				}
			}
			return apply_filters('all_options', $all_yes_options);
		}

		$temp_table_options_arr = get_all_yes_autoload_options();

		echo "<h2>" . __('To double-check options in the Orphaned Options list:', 'cleanoptions') . "</h2>
<ul id='mitt_co_help'>
<li>" . __('Look at the file names in any Warning messages.', 'cleanoptions') . "</li>
<li>" . __('Look at the text in any Alternate Syntax Warning messages.', 'cleanoptions') . "</li>
<li>" . sprintf(__('Some information may be available at your %s page.', 'cleanoptions'), '<a href=\'./options.php\'>wp-admin/options.php</a>') . "</li>
<li>" . __('Try a Google search for the option_name.', 'cleanoptions') . "</li>
<li>" . __('Search files by:', 'cleanoptions') . "
        <ul>
            <li><span>" . __('entering either a single string eg.', 'cleanoptions') . "</span>
		<span class='mitt_co_ps'>" . __('all_or_portion_of_option_name', 'cleanoptions') . "</span></li>
            <li><span>" . sprintf(__('2 strings separated by %s (in uppercase, enclosed with asterisks) eg.', 'cleanoptions'), '*AND*') . "</span>
		<span class='mitt_co_ps'>" . sprintf(__('prefix %s other_words', 'cleanoptions'), '*AND*') . "</span></li>
            <li><span>" . sprintf(__('or a maximum of 3 strings separated by %s eg.', 'cleanoptions'), '*AND*') . "</span>
		<span class='mitt_co_ps'>" . sprintf(__('prefix%1$sword%2$sother_word', 'cleanoptions'), '*AND*', '*AND*') . "</span></li>
            <li><span>*" . __('Note: all spaces are removed, search is case sensitive.', 'cleanoptions') . "</span></li>
        </ul></li>
	<li>" . __('Carefully Review information on the "View Selected Options Information" page <i>before</i> deleting the option.', 'cleanoptions') . "</li>

</ul>
<h3>" . __('Possibly Orphaned Options', 'cleanoptions') . "</h3><p>" . __('The following Options appear to be orphans. When shown, non-selectable Options are known to have been created from files present during upgrade or backup, or are legitimate options that do not "fit" the search for get_option or get_settings. If you wish to remove them by other means, do so at your own risk.', 'cleanoptions') . "</p>";

		$orphans = array_diff($temp_table_options_arr, $temp_file_options_arr);
		unset($temp_table_options_arr, $temp_file_options_arr);
		natcasesort($orphans);
/*
* Options used by files in WordPress "core" should have been found
* and put into the $temp_file_options_arr array. But just in case,
* they're in the $known_ok array too. A bit of code bloat perhaps, but better
* than making it too easy to remove something that may break the blog.
* these options are found in wp-admin/includes/schema.php, and
* wp-includes/cron.php@, wp-includes/rewrite.php!, wp-admin/options-reading.php*,
* wp-login.php%, wp-admin/includes/update.php&, wp-includes/formatting.php+
*/

		$known_ok = array('active_plugins',			//3.0
				'admin_email',						//3.0
				'advanced_edit',					//3.0
				'blog_charset',						//3.0
				'blogdescription',					//3.0
				'blogname',							//3.0
				'category_base',					//3.0
				'comment_max_links',				//3.0
				'comment_moderation',				//3.0
				'comments_notify',					//3.0
				'cron',								//3.0@
				'date_format',						//3.0
				'default_category',					//3.0
				'default_comment_status',			//3.0
				'default_ping_status',				//3.0
				'default_pingback_flag',			//3.0
				'default_post_edit_rows',			//3.0
				'dismissed_update_core',			//3.0&
				'gmt_offset',						//3.0
				'gzipcompression',					//3.0
				'hack_file',						//3.0
				'home',								//3.0
				'links_recently_updated_append',	//3.0
				'links_recently_updated_prepend',	//3.0
				'links_recently_updated_time'	,	//3.0
				'links_updated_date_format',		//3.0
				'mailserver_login',					//3.0
				'mailserver_pass',					//3.0
				'mailserver_port',					//3.0
				'mailserver_url',					//3.0
				'moderation_keys',					//3.0
				'moderation_notify',				//3.0
				'page_for_posts',					//3.0
				'page_on_front',					//3.0
				'permalink_structure',				//3.0
				'ping_sites',						//3.0
				'posts_per_page',					//3.0
				'posts_per_rss',					//3.0
				'require_name_email',				//3.0
				'rewrite_rules',					//3.0!
				'rss_use_excerpt',					//3.0
				'rss_excerpt_length',				//3.0+
				'show_on_front',					//3.0*
				'siteurl',							//3.0
				'start_of_week',					//3.0
				'time_format',						//3.0
				'use_balanceTags',					//3.0
				'use_smilies',						//3.0
				'use_ssl',							//3.0% get_user_option
				'users_can_register',				//3.0
				
// multisite
				'template',				//3.0
				'stylesheet',			//3.0
				'upload_path',			//3.0
				'fileupload_url',		//3.0
				
// akismet ver 2.3.0 with WordPress ver 3.0
				'wordpress_api_key',				//3.0
				'akismet_discard_month',			//3.0
				'akismet_connectivity_time',		//3.0
				'akismet_available_servers',		//3.0
				'akismet_spam_count',				//3.0
				'widget_akismet',					//3.0
				
// old kubrick theme
				'kubrick_header_color',				//2.9
				'kubrick_header_display',			//2.9
				'kubrick_header_image',				//2.9
				
// get_transient()s
				'_site_transient_theme_roots',				//2.9.1
				'_site_transient_timeout_theme_roots',		//2.9.1
				'_transient_doing_cron',					//2.9.1
				'_transient_mailserver_last_checked',		//2.9.1
				'_transient_plugin_slugs',					//2.9.1
				'_transient_timeout_plugin_slugs',			//2.9.1
				'_transient_timeout_dirsize_cache',			//3.0
				'_transient_random_seed',					//2.9.1
				'_transient_rewrite_rules',					//2.9.1
				'_transient_update_core',					//2.9.1
				'_transient_update_plugins',				//2.9.1
				'_transient_update_themes',					//2.9.1
				'_transient_wporg_theme_feature_list',		//2.9.1

// Core default widgets
				'widget_archives',				//2.9.1
				'widget_calendar',				//2.9.1
				'widget_categories',			//2.9.1
				'widget_links',					//2.9.1
				'widget_meta',					//2.9.1
				'widget_nav_menu',				//3.0
				'widget_pages',					//2.9.1
				'widget_recent_comments',		//2.9.1
				'widget_recent_posts',			//2.9.1
				'widget_rss',					//2.9.1
				'widget_search',				//2.9.1
				'widget_tag_cloud',				//2.9.1
				'widget_text',					//2.9.1

// from earlier versions				
				'embed_autourls',				//2.9
				'embed_size_h',					//2.9
				'embed_size_w',					//2.9
				
				'timezone_string',				//2.8 and 3.0+
				'widget_recent-comments',		//2.8
				'widget_recent-posts',			//2.8
				'widget_recent_entries',		//2.8

				'close_comments_days_old',		//2.7
				'close_comments_for_old_posts',	//2.7
				'comment_order',				//2.7
				'comments_per_page',			//2.7
				'default_comments_page',		//2.7
				'image_default_align',			//2.7
				'image_default_link_type',		//2.7
				'image_default_size',			//2.7
				'large_size_h',					//2.7
				'large_size_w',					//2.7
				'page_comments',				//2.7
				'sticky_posts',					//2.7
				'thread_comments',				//2.7
				'thread_comments_depth',		//2.7
				'widget_categories',			//2.7
				'widget_rss',					//2.7
				'widget_text',					//2.7

				'avatar_default',			//2.6
				'enable_app',				//2.6
				'enable_xmlrpc',			//2.6
				'page_attachment_uris',		//2.6!

				'avatar_rating',			//2.5
				'medium_size_w',			//2.5
				'medium_size_h',			//2.5
				'show_avatars',				//2.5
				'thumbnail_crop',			//2.5
				'thumbnail_size_h',			//2.5
				'thumbnail_size_w',			//2.5
				'upload_url_path',			//2.5

				'xvalid_options',			//2.3 during backup

				'tag_base',					//2.2 and 3.0!

				'import-blogger',			//2.1.3
				'blog_public',				//2.1
				'default_link_category',	//2.1
				'show_on_front',			//2.1

				'secret',					//2.0.3
				'upload_path',				//2.0.1
				'uploads_use_yearmonth_folders',	//2.0.1
				'db_version',				//2.0
				'default_role',				//2.0

				'use_trackback',			//1.5.1
				'blacklist_keys',			//1.5
				'comment_registration',		//1.5
				'comment_whitelist',		//1.5
				'default_email_category',	//1.5
				'html_type',				//1.5
				'page_uris',				//1.5
				'recently_edited',			//1.5
				'rss_language',				//1.5
				'stylesheet',				//1.5
				'template',					//1.5
				'use_linksupdate',			//1.5
				);
				
		if ( ! $rss_gone )
		{
			$known_ok += array('doing_cron',
					'update_core',
					'can_compress_scripts',
					);
		}
 				
		if ( ! $bye_rss )
		{
			$known_ok += array('what_to_show',
					);
		}

		$po_unique_id = 1;
		$found_safe = 'false';
		$found_some = 'false';

		if ( empty($orphans) )
		{
			echo "<h4>" . __('No Orphaned Options were found', 'cleanoptions') . "</h4>";
		}
		else
		{
			foreach ( $orphans as $opt_val )
			{
/*
* - the wp_user_roles option constructed from the capabilities class may not have
* the default install prefix.
* - the origin of the category_chidren option is unknown, but it is referenced in
* the wp-admin/import/wp-cat2tag.php file.
* - _transient_*** options from new 2.8 cache API (the end of the rss_hash problem!)
* 2.8 transients use "rss", 2.9 use "feed"
* - delete_result_# various numbers possible
* Test for them here while testing for the "known ok" array.
*/ 
				if ( ( in_array($opt_val, $known_ok) )
					 || ( strpos($opt_val, 'user_roles') !== FALSE )
					 || ( strpos($opt_val, 'category_children') !== FALSE )
					 || ( strpos($opt_val, '_transient_feed_') !== FALSE )
					 || ( strpos($opt_val, '_transient_timeout_feed_') !== FALSE )
					 || ( strpos($opt_val, '_transient_plugins_delete_result_') !== FALSE )
					 || ( strpos($opt_val, '_site_transient_') !== FALSE )
					)
				{
					if ( !isset($_POST['hide_known_core']) || ($_POST['hide_known_core'] != '1') )
					{ 
						echo "-&nbsp;&nbsp;" . $opt_val . "<span class='co_found_core'>\t(" . __('known WordPress Core option', 'cleanoptions') . ")</span><br />";
						$found_safe = 'true';
					}
				}
				else
				{
					if ( ( get_bloginfo('version') < '2.9.1' )
						 && ( ( strpos($opt_val, '_transient_rss_') !== FALSE )
						 || ( strpos($opt_val, '_transient_timeout_rss_') !== FALSE ) ) )
					{
						if ( !isset($_POST['hide_known_core']) || ($_POST['hide_known_core'] != '1') )
						{ 
							echo "-&nbsp;&nbsp;" . $opt_val . "<span class='co_found_core'>\t(" . __('known WordPress Core option', 'cleanoptions') . ")</span><br />";
							$found_safe = 'true';
						}
					}
					else
					{
						echo "<input id='prune_opt_" . $po_unique_id . "' name='prune_opt[]' type='checkbox' value='" . $opt_val . "' />";
						echo "&nbsp;<label for='prune_opt_" . $po_unique_id . "' >" . $opt_val . "</label><span class='co_google_link'>\t * " . __('Google it', 'cleanoptions') . " <a href='http://www.google.com/search?q=wordpress+" . $opt_val . "'>" . $opt_val . "</a></span><br />";
						$po_unique_id++;
						$found_some = 'true';
					}
				}
			}
		}

		if ( !empty($orphans) )
		{
			if ( ($found_safe != 'true') && ($found_some != 'true') )
				echo "<h4>" . __('No Orphaned Options were found', 'cleanoptions') . "</h4>";

			if ( ($found_safe == 'true') && ($found_some != 'true') )
				echo "<h4>" . __('Only WordPress Core Options were found', 'cleanoptions') . "</h4>";

			if ($found_some == 'true')
			{
			echo "<div id='mitt_co_searchbar'>
				<span>" . __('To look for option_name(s):', 'cleanoptions') . "</span><br />
				<input type='text' id='mitt_co_psi' name='mitt_co_psi' value='" . __('Enter Search String here', 'cleanoptions') . "' />";
?>
				<script type="text/javascript">
				//<![CDATA[
					var dp_inp = document.getElementById('mitt_co_psi');

					dp_inp.onfocus = function()
					{
						if (dp_inp.value == '<?php echo __('Enter Search String here', 'cleanoptions'); ?>')
						{
							dp_inp.value = '';
						}
					};
				//]]>
				</script>
				&nbsp;&nbsp;
<?php
				echo "<input class='mitt_co_submit' type='submit' name='look_for_names' value='" . __('Search', 'cleanoptions') . "' />
			</div><hr id='mitt_co_hrsep' />";
			}
		}

/* autoload != yes block (RSS cache) */
/* for <2.9 only */
		if ($rss_gone != true )
		{
		function get_all_no_autoload_options()
		{
			global $wpdb, $db_err_flag, $en_warn_flag, $cur_wp_ver, $bye_rss;
			$no_query_less = '';
			$all_no_options = array();
			$opt_tbl_len = mitt_get_opt_tbl_len();
			$wpdb->show_errors(true);

			if ( isset($_POST['limit_query'])
				 && ( is_numeric($_POST['limit_query']) )
				 &&  ( $_POST['limit_query'] > 0 ) )
			{
				$query_limit = ( $_POST['limit_query'] < $opt_tbl_len ) ? (int)$_POST['limit_query'] : ( ( floor( $opt_tbl_len / 2 ) ) * 2 );
				$no_query_less = " ORDER BY option_id DESC LIMIT " . ($query_limit / 2) . " ";
			}
/* check for subquery support */
			$mysql_db_ver = ( $cur_wp_ver >= '2.7' ) ? $wpdb->db_version() : 'unknown';
 
			if ( ( $mysql_db_ver != 'unknown' ) && ( $mysql_db_ver >= '4.1' ) )
			{
			$no_query = "
			SELECT ts_opts.option_name
			     , ts_opts.option_value
			  FROM ( SELECT option_name
			              , option_value
			           FROM $wpdb->options
			          WHERE autoload NOT LIKE 'yes'
			            AND option_name LIKE 'rss\_%\_ts'
				$no_query_less
			       ) AS ts_opts
			UNION ALL
			SELECT non_ts_rss_opts.option_name
			     , NULL
			  FROM ( SELECT option_name
			           FROM $wpdb->options
			          WHERE autoload NOT LIKE 'yes'
			            AND option_name LIKE 'rss\_%'
			            AND option_name NOT LIKE 'rss\_%\_ts'
				$no_query_less
			       ) AS non_ts_rss_opts
			";
			$no_options = $wpdb->get_results($no_query, ARRAY_A);
			}
			else
			{
				$ts_query = "
				 SELECT option_name
			              , option_value
			           FROM $wpdb->options
			          WHERE autoload NOT LIKE 'yes'
			            AND option_name LIKE 'rss\_%\_ts'
				$no_query_less
				";
				$ts_results = $wpdb->get_results($ts_query, ARRAY_A);

				if( !is_array($ts_results) )
				{
					if ( $bye_rss != true )
					{
						add_wp_error(__('Database Error', 'cleanoptions'), sprintf(__('%s (the "rss_" timestamp Options query) did not return an array', 'cleanoptions'), $ts_query) );
						$db_err_flag = true;
					}
					$ts_results = array();
				}

				$non_ts_query = "
				 SELECT option_name
			           FROM $wpdb->options
			          WHERE autoload NOT LIKE 'yes'
			            AND option_name LIKE 'rss\_%'
			            AND option_name NOT LIKE 'rss\_%\_ts'
				$no_query_less
				";
				$non_ts_results = $wpdb->get_results($non_ts_query, ARRAY_A);

				if( !is_array($non_ts_results) )
				{
					if ( $bye_rss != true )
					{
						add_wp_error(__('Database Error', 'cleanoptions'), sprintf(__('%s (the "rss_" non-timestamp Options query) did not return an array', 'cleanoptions'), $non_ts_query) );
						$db_err_flag = true;
					}
					$non_ts_results = array();
				}

				$no_options = array_merge($ts_results, $non_ts_results);
			}

			if( !is_array($no_options) )
			{
				if ( $bye_rss != true )
				{
					add_wp_error(__('Database Error', 'cleanoptions'), sprintf(__('%s (the "rss_" Options query) did not return an array', 'cleanoptions'), $no_query) );
					$db_err_flag = true;
				}
				$no_options = array();
			}

			foreach ( $no_options as $no_option )
			{
				$no_opt_val = ( !empty($no_option['option_value']) ) ? $no_option['option_value'] : 'EMPTY';
				if ( empty($no_option['option_name']) )
				{
					add_wp_error(__('Empty Name', 'cleanoptions'), sprintf(__('There is an autoload not equal to yes Option with No Name with the value: %s', 'cleanoptions'), wp_specialchars($no_opt_val) ) );
					$en_warn_flag  = true;
				}
				else
				{
					$no_value = maybe_unserialize($no_opt_val);
					$all_no_options[ $no_option['option_name'] ] = apply_filters('pre_option_' . $no_option['option_name'], $no_value);
				}
			}
			return apply_filters('all_options', $all_no_options);
		}

		$temp_rss_opt_arr = get_all_no_autoload_options();

		check_for_wp_errors();

		function read_rss_ts($rss_opt_val)
		{
			$mtime = get_option($rss_opt_val);
			$age = time() - $mtime;
			$days = floor($age/86400);
			return $days;
		}

		$rss_opt_arr = array();
		$rss_ts_arr = array();
		$ts_regex = '/^(?:rss_)[a-f0-9]+(?:_ts)$/i';
		$rss_regex = '/^(?:rss_)[a-f0-9]+$/i';

		foreach ( $temp_rss_opt_arr as $key => $value )
		{
			if ( preg_match($ts_regex, $key) )
			{
				$rss_ts_arr[] = read_rss_ts($key);
				$rss_opt_arr[] =  $key;
			}
			else if ( preg_match($rss_regex, $key) )
			{
				$rss_opt_arr[] =  $key;
			}
		}
		unset($temp_rss_opt_arr);

		sort($rss_ts_arr, SORT_NUMERIC);
		$num_rss_days = count($rss_ts_arr);

/* arbitrary "safe" numbers -
* if less than 15 RSS options exempt
* all rss_ options for the most recent
* 100 days otherwise exempt rss_ options
* with the same date and newer
* than the 14'th rss_ option
*/
		$ok_rss_date = '100';

		if ($num_rss_days > '14')
		{
			$ok_rss_date = $rss_ts_arr['13'];
		}

		$newer_ver_message = ( $bye_rss == true ) ? __('The "rss_" options are obsolete as of WordPress version 2.8 All are selectable and it should be safe to remove any that remain.', 'cleanoptions') : '';

		echo "<h3>" . __('RSS Options', 'cleanoptions') . "</h3><p>" . sprintf(__('The following contains "RSS" Options added to the wp_options table from the blog\'s dashboard page and other files that parse RSS feeds and cache the results.<br />In each pair, the upper option is the cached feed and the lower is the option\'s timestamp.<br />Those listed may include options that are <strong>Currently Active</strong>.<br />When shown, "rss_" option pairs with dates newer or the same as the date of 14\'th newest "rss_" option pair (the ones that are more likely to be current) have no checkbox but begin with "-" and end with "<em># %1$s</em>" in italics.<br />The older "rss_" options can be selected and end with "<strong># %2$s</strong>" in bold.', 'cleanoptions'), __('days old', 'cleanoptions'), __('days old', 'cleanoptions') ) . " " . $newer_ver_message . "</p>";

		natcasesort($rss_opt_arr);
		$rss_opt_arr = array_chunk($rss_opt_arr, 2, FALSE);

		if ( empty($rss_opt_arr) )
		{
			echo __('There were No "rss_" Options found', 'cleanoptions') . "<br />";
		}
		else
		{
			$odd_man_out = array();
			foreach ( $rss_opt_arr as $rss_opt_val )
			{
				$rss_opt_date = ( isset($rss_opt_val[1]) && preg_match($ts_regex, $rss_opt_val[1]) ) ? read_rss_ts($rss_opt_val[1]) : '';

				if ( isset($rss_opt_val[1])
				 && ( strstr($rss_opt_val[1], $rss_opt_val[0])
				 && ( $rss_opt_date > $ok_rss_date )
				 || ($bye_rss == true) ) )
				{
					if ( !empty($rss_opt_date) && preg_match($rss_regex, $rss_opt_val[0]) )
					{
						echo "<input id='prune_opt_" . $po_unique_id . "' name='prune_opt[]' type='checkbox' value='" . $rss_opt_val[0] . "' />";
						echo "&nbsp;<label for='prune_opt_" . $po_unique_id . "' >" . $rss_opt_val[0] . "</label>&nbsp;&nbsp;&nbsp;<strong>" . $rss_opt_date . " " . __('days old', 'cleanoptions') . "</strong><br />";
						$po_unique_id++;
					}
					else
					{
						$odd_man_out[] = $rss_opt_val[0];
					}

					if ( !empty($rss_opt_date) && preg_match($ts_regex, $rss_opt_val[1]) )
					{
						echo "<input id='prune_opt_" . $po_unique_id . "' name='prune_opt[]' type='checkbox' value='" . $rss_opt_val[1] . "' />";
						echo "&nbsp;<label for='prune_opt_" . $po_unique_id . "' >" . $rss_opt_val[1] . "</label>&nbsp;&nbsp;&nbsp;<strong>" . $rss_opt_date . " " . __('days old', 'cleanoptions') . "</strong><br /><br />";
						$po_unique_id++;
					}
					else
					{
						$odd_man_out[] = $rss_opt_val[1];
					}
				}
				else if ( !empty($rss_opt_date)
					 && isset($rss_opt_val[1])
					 && strstr($rss_opt_val[1], $rss_opt_val[0])
					 && ( $rss_opt_date <= $ok_rss_date ) )
				{
					if ( preg_match($rss_regex, $rss_opt_val[0]) )
					{
						echo "-&nbsp;&nbsp;" . $rss_opt_val[0] . "&nbsp;&nbsp;&nbsp;<em>" . $rss_opt_date . " " . __('days old', 'cleanoptions') . "</em><br />";
					}
					else
					{
						$odd_man_out[] = $rss_opt_val[0];
					}

					if ( preg_match($ts_regex, $rss_opt_val[1]) )
					{
						echo "-&nbsp;&nbsp;" . $rss_opt_val[1] . "&nbsp;&nbsp;&nbsp;<em>" . $rss_opt_date . " " . __('days old', 'cleanoptions') . "</em><br /><br />";
					}
					else
					{
						$odd_man_out[] = $rss_opt_val[1];
					}
				}
				else
				{
					$rss_opt_arr_sz = count($rss_opt_val);
					for ($i = 0; $i < $rss_opt_arr_sz; $i++)
					{
						$odd_man_out[] = $rss_opt_val[$i];
					}
				}
				$rss_opt_date = '';
			}
			if ( !empty($odd_man_out) )
			{
				echo "<p><strong>!" . __('WARNING', 'cleanoptions') . "!</strong> " . __('The following options were not paired correctly. Be certain to check their information carefully before you remove them.', 'cleanoptions') . "</p>";
				$omo_arr_sz = count($odd_man_out);
				for ($j = 0; $j < $omo_arr_sz; $j++)
				{
					if ( preg_match($ts_regex, $odd_man_out[$j]) )
					{
						$rss_opt_date = read_rss_ts($odd_man_out[$j]);
						if ( ($rss_opt_date > $ok_rss_date) || ($bye_rss == true) )
						{
							echo "<input name='prune_opt[]' type='checkbox' value='" . $odd_man_out[$j] . "' />";
							echo "&nbsp;<label for='prune_opt_" . $po_unique_id . "' >" . $odd_man_out[$j] . "</label>&nbsp;&nbsp;&nbsp;<strong>" . $rss_opt_date . " " . __('days old', 'cleanoptions') . "</strong><br />";
							$po_unique_id++;
						}
						else if ( $rss_opt_date <= $ok_rss_date )
						{
							
							echo "-&nbsp;&nbsp;" . $odd_man_out[$j] . "&nbsp;&nbsp;&nbsp;<em>" . $rss_opt_date . " " . __('days old', 'cleanoptions') . "</em><br /><br />";
						}
					}
					else
					{
						echo "<input id='prune_opt_" . $po_unique_id . "' name='prune_opt[]' type='checkbox' value='" . $odd_man_out[$j] . "' />";
						echo "&nbsp;<label for='prune_opt_" . $po_unique_id . "' >" . $odd_man_out[$j] . "</label>&nbsp;&nbsp;&nbsp;<strong>!" . __('ALERT', 'cleanoptions') . "! " . __('Age Unknown', 'cleanoptions') . "</strong> " . __('check the age of the corresponding "_ts" option.', 'cleanoptions') . "<br />";
						$po_unique_id++;
					}
				}
			}
		}
		} // end $rss_gone != true if
?>
<!-- javascript select all modified from wp-db-backup 2.1.5 plugin by Austin Matzko http://www.ilfilosofo.com/blog/ -->
			<script type="text/javascript">
			//<![CDATA[
				var selectAllOptions = function() {};
				(function(b){
					var n = function(c) {
						var p = document.getElementsByTagName("input");
						for(var i=0; i<p.length; i++)
							if( 'prune_opt[]' == p[i].getAttribute('name') ) p[i].checked = c;
					}
					b.a = function() { n(true) }
					b.n = function() { n(false) }
					document.write('<p><a href="javascript:void(0)" onclick="selectAllOptions.a()"><?php echo __('Select all', 'cleanoptions'); ?></a> / <a href="javascript:void(0)" onclick="selectAllOptions.n()"><?php echo __('Deselect all', 'cleanoptions'); ?></a> <?php echo __('"all" means BOTH "plugin" AND "rss_" options.', 'cleanoptions'); ?></p>');
				})(selectAllOptions)
			//]]>
			</script>

		<input class="mitt_co_submit" type="submit" name="pre_remove_orphans" value="<?php echo __('View Selected Options Information', 'cleanoptions'); ?>" />
		</form>
		</div>
<?php
	}
/* Look for Search String in Files Section */
	else if ( isset($_POST['look_for_names']) )
	{
		$co_pro_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_pro' : '_wpnonce';
		check_admin_referer('clean-options-pre-remove-orphans_' . $cononce, $co_pro_name);
?>
		<div id="mitt_co" class="wrap">
		<h2>Clean Options</h2>
<?php
		if ( isset($_POST['mitt_co_psi']) && ( trim($_POST['mitt_co_psi']) != '' ) && ( trim($_POST['mitt_co_psi']) != __('Enter Search String here', 'cleanoptions') ) )
		{
			$probe = array();
			
			foreach($search_paths as $search_path)
			{
				$look_dir = mitt_co_searchdir($search_path);
				$probe = array_unique( array_merge($probe, $look_dir) );
			}
			
			$probe_match_arr = array();
			$probe_string = htmlentities($_POST['mitt_co_psi']);
			$probe_string = preg_replace('#[ ]+#', '', $probe_string);
			$ps_arr_len = 0;

			if ( strpos($probe_string, '*AND*') !== false )
			{
				$probe_string = explode('*AND*', $probe_string);
			}

			if ( is_array($probe_string) )
			{
				foreach($probe_string as $ps_key => $ps_val)
				{
					if($ps_val == "")
					{
						unset($probe_string[$ps_key]);
					}
				}
				$probe_string = array_values($probe_string); 
				$ps_arr_len = count($probe_string);

				if ( $ps_arr_len == 1 ) $probe_string = (string)$probe_string[0];
			}

			foreach ( $probe as $found_file )
			{
				if ( !is_readable($found_file) )
				{
					add_wp_error(__('File System Error', 'cleanoptions'), sprintf(__('Could not open file %s', 'cleanoptions'), $found_file) );
					$fs_err_flag = true;
					clearstatcache();
				}
				else if ( strpos($found_file, 'cleanoptions') === false )
				{
					$file_data = file_get_contents($found_file);
					if ( is_string($probe_string) )
					{
						if ( strpos($file_data, $probe_string) !== false )
						{
							$probe_match_arr[] = $found_file;
						}
					}
					else if ( ( is_array($probe_string) ) && ( $ps_arr_len == 2 ) )
					{
						if ( ( strpos($file_data, $probe_string[0] ) !== false ) && ( strpos($file_data, $probe_string[1] ) !== false ) )
						{
							$probe_match_arr[] = $found_file;
						}
					}
					else if ( ( is_array($probe_string) ) && ( $ps_arr_len > 2 ) )
					{
						if ( ( strpos($file_data, $probe_string[0] ) !== false ) && ( strpos($file_data, $probe_string[1] ) !== false ) && ( strpos($file_data, $probe_string[2] ) !== false ) )
						{
							$probe_match_arr[] = $found_file;
						}
					}

				}
			}

			check_for_wp_errors();

			if ( is_array($probe_string) )
			{
				$probe_string = ( $ps_arr_len == 2 ) ? $probe_string[0] . '*AND*' . $probe_string[1] : $probe_string[0] . '*AND*' . $probe_string[1] . '*AND*' . $probe_string[2];
			}

			if ( is_array($probe_match_arr) && !empty($probe_match_arr) )
			{
				echo "<p><span id='mitt_co_pss'>" . $probe_string . "</span> " . __('was found in:', 'cleanoptions') . "</p>\r\n";

				$pma_len = count($probe_match_arr);

				for ($p = 0; $p < $pma_len; $p++)
				{
					echo "&nbsp;&nbsp;" . $probe_match_arr[$p] . "<br />\r\n";
				}
			}
			else
			{
				echo "<p>" . sprintf(__('No files were found containing %s', 'cleanoptions'), '<span id=\'mitt_co_pss\'>' . $probe_string . '</span>') . "</p>\r\n";
			}
		}
		else
		{
			echo "<b>" . __('No Search string was entered.', 'cleanoptions') . "</b><br />";
		}
		unset($_POST);
		echo "<br />" . sprintf(__('Return to the %s', 'cleanoptions'), '<a href=\'' . $_SERVER["REQUEST_URI"] . '\'>' . __('first screen', 'cleanoptions') . '</a>') . "<br />";
	}

/* Pre Remove Orphans Section */

	else if ( isset($_POST['pre_remove_orphans']) )
	{
		$co_pro_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_pro' : '_wpnonce';
		check_admin_referer('clean-options-pre-remove-orphans_' . $cononce, $co_pro_name);
?>
		<div id="mitt_co" class="wrap">
		<h2>Clean Options</h2>
<?php
		if ( ( isset($_POST['prune_opt']) ) && ( is_array($_POST['prune_opt']) ) )
		{

		echo "<p>" . __('*Note* spaces have been added after every 10th character of the option_name and every 20th character of the option_value to preserve page layout.<br />Not all options have values and/or descriptions.', 'cleanoptions') . "</p>"; // "and/or descriptions" is now obsolete
		echo "<p><strong>" . __('Please review this information very carefully and only remove Options that you know for certain have been orphaned or deprecated.', 'cleanoptions') . "</strong></p>";
		echo "<p><strong>" . __('It is strongly suggested that you BACKUP your database before removing any options.', 'cleanoptions') . "</strong></p>";

			global $wpdb, $db_err_flag;
			$wpdb->show_errors(true);

			$prune_opt_arr = $_POST['prune_opt'];
			$prune_query = "SELECT option_name, option_value FROM $wpdb->options WHERE ";
			$prune_wheres = '';

			foreach ( $prune_opt_arr as $pruneopt )
			{
				if ( !empty($pruneopt) )
				{
					$prune_wheres .= "option_name LIKE '" . $pruneopt . "' OR ";
				}
			}
			$prune_wheres = rtrim($prune_wheres, ' OR ');
			$prune_query .= $prune_wheres;
			$q_results = $wpdb->get_results($prune_query, ARRAY_A);
			echo "<table id='mitt_co_table'>";
			echo "<tr><th>option_name</th><th>option_value</th></tr>";

			if( !is_array($q_results) )
			{
				add_wp_error(__('Database Error', 'cleanoptions'), sprintf(__('%s (the review information query) did not return an array', 'cleanoptions'), $prune_query) );
				$db_err_flag = true;
				$q_results = array();
			}

			foreach ( $q_results as $q_val )
			{
				$opt_name = $q_val['option_name'];
				$opt_name = wordwrap($opt_name, 10, ' ', 1);
				echo "<tr><td>" . $opt_name . "</td>";
				$opt_val = htmlentities( print_r($q_val['option_value'], TRUE) );
				$opt_val = wordwrap($opt_val, 20, ' ', 1);
				if ( preg_match("/^[0-9]{10}$/", $opt_val) )
				{
					$blog_date_format = get_option('date_format');
					$blog_time_format = get_option('time_format');
					$both_format = $blog_date_format . ' ' . $blog_time_format;
					$opt_val = $opt_val . "  [*timestamp = " . date($both_format, (int)$opt_val) . "]";
				}
				echo "<td>" . $opt_val . "</td></tr>\r\n";
			}
			echo "</table>";
		}

		check_for_wp_errors();

		$opt_arr_str = ( empty($prune_opt_arr) ) ? '' : implode('#', $prune_opt_arr);
		if ( !empty($opt_arr_str) )
		{
			echo "<form method='post' action='" . $_SERVER["REQUEST_URI"] . "'>";

			$co_dro_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_dro' : '_wpnonce';
			if ( function_exists('wp_nonce_field') )
				wp_nonce_field('clean-options-do-remove-orphans_' . $cononce, $co_dro_name);

			echo "<input type='hidden' name='orphan_opts' value='" . $opt_arr_str . "' />";
			echo "<input type='radio' name='confirm_rem' value='1' /> " . __('Yes, Remove ALL of these options from the wp_options table.', 'cleanoptions') . "<br />";
			echo "<input type='radio' name='confirm_rem' value='0' checked='checked' /> " . __('No, Don\'t remove them, return to the first screen.', 'cleanoptions') . "<br />";
			echo "<input class='mitt_co_submit' type='submit' name='do_remove_orphans' value='" . __('Submit', 'cleanoptions') . "' />
			</form>";
		}
		else
		{
			echo "<b>" . __('No Orphaned Options where selected.', 'cleanoptions') . "</b><br />";
			unset($_POST);
			echo sprintf(__('Return to the %s', 'cleanoptions'), '<a href="' . $_SERVER["REQUEST_URI"] . '">' . __('first screen', 'cleanoptions') . '</a>') . "<br />";
		}
?>
		</div>
<?php
	}

/* Do Remove Orphans Section */

	else if ( isset($_POST['do_remove_orphans']) )
	{
		$co_dro_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_dro' : '_wpnonce';
		check_admin_referer('clean-options-do-remove-orphans_' . $cononce, $co_dro_name);

		if ( ( isset($_POST['confirm_rem']) ) && ($_POST['confirm_rem'] == "1") )
		{
			if ( !empty($_POST['orphan_opts']) )
			{

				echo "<div id='message' class='updated fade'>
				<p><strong>" . __('Removed Options', 'cleanoptions') . ":</strong><br />";

				$orphan_opt_arr = explode("#", $_POST['orphan_opts']);
				foreach ( $orphan_opt_arr as $orphanopt )
				{
					delete_option($orphanopt);
					echo $orphanopt . "<br />";
				}
?>
				</p>
				</div>
<?php
			}
		}
?>
		<div id="mitt_co" class="wrap">
		<h2>Clean Options</h2>
<?php
		echo $common_form_message;
		$opt_tbl_len = mitt_get_opt_tbl_len();

		echo "<p>" . sprintf(__('The Options table currently has %s found rows.', 'cleanoptions'), '<b>' . $opt_tbl_len . '</b>') . "</p>";

		if ( ($opt_tbl_len > CO_MIN_BEFORE_SHOW) && ($rss_gone != true) )
		{
			echo $del_all_warning;

		echo "<form method='post' action='" . $_SERVER["REQUEST_URI"] . "'>";

			$co_dar_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_dar' : '_wpnonce';
			if ( function_exists('wp_nonce_field') )
				wp_nonce_field('clean-options-del-all-rss_' . $cononce, $co_dar_name);

			echo "<input class='mitt_co_submit' type='submit' name='del_all_rss' value='" . __('Delete ALL \'rss\' Options', 'cleanoptions') . "' />
		</form>";
		}

		echo "<form method='post' action='" . $_SERVER["REQUEST_URI"] . "'>";

		$co_fo_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_fo' : '_wpnonce';
		if ( function_exists('wp_nonce_field') )
			wp_nonce_field('clean-options-find-orphans_' . $cononce, $co_fo_name);

		if ( $opt_tbl_len > CO_BATCH_SIZE ) echo $lf_select;
		echo $sc_select;
		echo $sh_select;

		echo "<input class='mitt_co_submit' type='submit' name='find_orphans' value='" . __('Find Orphaned Options', 'cleanoptions') . "' />
		</form>
		</div>";
	}

/* Delete All RSS Section */
	else if ( (isset($_POST['del_all_rss'])) && ($rss_gone != true) )
	{
		$co_dar_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_dar' : '_wpnonce';
		check_admin_referer('clean-options-del-all-rss_' . $cononce, $co_dar_name);

		global $wpdb;
		$wpdb->show_errors(true);

		$last_option_id = $wpdb->get_results("SELECT option_id FROM $wpdb->options ORDER BY option_id DESC LIMIT 1", ARRAY_A);
		$maybe_safe = $last_option_id[0]['option_id'] - CO_KEEP_NEWER;
		if ( $maybe_safe < 0 ) $maybe_safe = 0;

		$del_all_query = "
		DELETE FROM $wpdb->options
		  WHERE autoload NOT LIKE 'yes'
		    AND option_id < $maybe_safe
		    AND option_name LIKE 'rss\_%'
		    AND option_name NOT LIKE 'rss\_excerpt\_length'
		    AND option_name NOT LIKE 'rss\_use\_excerpt'
		    AND option_name NOT LIKE 'rss\_language'
		";

		$wpdb->query($del_all_query);

		$rows_deleted = $wpdb->rows_affected;

		echo "<div id='message' class='updated fade'>
		<p><strong>" . sprintf(__('Removed %d "rss_hash" Options', 'cleanoptions'), $rows_deleted) . "</strong></p>";
?>
		</div>
		<div id="mitt_co" class="wrap">
		<h2>Clean Options</h2>
<?php
		echo $common_form_message;

		echo "<p>" . sprintf(__('The Options table currently has %s found rows.', 'cleanoptions'), '<b>' . mitt_get_opt_tbl_len() . '</b>') . "</p>
		<form method='post' action='" . $_SERVER["REQUEST_URI"] . "'>";
 
		$co_fo_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_fo' : '_wpnonce';
		if ( function_exists('wp_nonce_field') )
			wp_nonce_field('clean-options-find-orphans_' . $cononce, $co_fo_name);

		echo "<p>" . __('Every "rss_hash" option in the wp_options table will be shown, including current ones.', 'cleanoptions') . "</p>
		<input class='mitt_co_submit' type='submit' name='find_orphans' value='" . __('Find Orphaned Options', 'cleanoptions') . "' />
		</form>
		</div>";
	}

/* Intitial View Section */

	else
	{
		if ( function_exists('is_multisite') && is_multisite() )
		{
			echo "<div id='message' class='updated fade'>
			<p><strong>" . __('WARNING', 'cleanoptions') . " !!</strong><br />" . __('The Clean Options plugin has not been thoroughly tested for use with WordPress MULTISITES enabled.<br />Clean Options version 1.3.2 should work with WordPress 3.0 with the exception that if MULTISITES is enabled, the plugin will only find options found in the primary blog\'s wp_options table.<br />Use At Your Own Risk.<br />Clean Options 2.0.0 is currently being developed. It will not be fully compatible with older versions of WordPress, but it will be compatible with WordPress 3.0, including finding options if MULTISITES is enabled. However, portions of the i18n l10n files will not have translations for any of the new text - unless and until they are provided.', 'cleanoptions') . "</p>
			</div>";
		}
?>
		<div id="mitt_co" class="wrap">
		<h2>Clean Options</h2>
<?php
		echo $common_form_message;
		$opt_tbl_len = mitt_get_opt_tbl_len();

		echo "<p>" . sprintf(__('The Options table currently has %s found rows.', 'cleanoptions'), '<b>' . $opt_tbl_len . '</b>') . "</p>";
		if ( ($opt_tbl_len > CO_MIN_BEFORE_SHOW) && ($rss_gone != true) )
		{
			echo $del_all_warning;

		echo "<form method='post' action='" . $_SERVER["REQUEST_URI"] . "'>";

			$co_dar_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_dar' : '_wpnonce';
			if ( function_exists('wp_nonce_field') )
				wp_nonce_field('clean-options-del-all-rss_' . $cononce, $co_dar_name);

			echo "<input class='mitt_co_submit' type='submit' name='del_all_rss' value='" . __('Delete ALL \'rss\' Options', 'cleanoptions') . "' />
		</form>";
		}

		echo "<form method='post' action='" . $_SERVER["REQUEST_URI"] . "'>";

		$co_fo_name = ( $cur_wp_ver >= '2.5' ) ? '_mitt_co_fo' : '_wpnonce';
		if ( function_exists('wp_nonce_field') )
			wp_nonce_field('clean-options-find-orphans_' . $cononce, $co_fo_name);

		if ( $opt_tbl_len > CO_BATCH_SIZE ) echo $lf_select;
		echo $sc_select;
		echo $sh_select;

		echo "<input class='mitt_co_submit' type='submit' name='find_orphans' value='" . __('Find Orphaned Options', 'cleanoptions') . "' />
		</form>
		</div>";
	}
?>
<!-- /* Warning Message explanations and FURTHER INFORMATION SECTION */ -->
	<div id="mitt_co_msg" class="wrap">
<?php
	if ( $as_warn_flag || $db_err_flag || $en_warn_flag  || $fs_err_flag )
	{
		echo "<h2>" . __('Warning Messages', 'cleanoptions') . "</h2>";
		echo "<p>" . __('A Warning message, means that something has happened and options that should not be deleted might be available for deletion, or options that could be safely deleted might not be available for deletion. In any case, if you see a Warning message, use extra-special care and thought before deleting any options.', 'cleanoptions') . "</p>
		<dl>";

		if ( $as_warn_flag == true )
		{
			echo "<dt>" . __('Alternate Syntax', 'cleanoptions') . " - .... " . __('has an option line with', 'cleanoptions') . " ....</dt>
			<dd>" . sprintf(__('The plugin searches PHP files for instances of get_option(\'option_name as a string\') to match against values found in the wp_options table. Some files however use syntax such as get_option(&#36;variable) or get_option(\'prefix_\' . &#36;variable). These option names will not match those found in the wp_option table, and they may be present in the list of Orphaned Options when in fact they are not really orphaned. In many cases, knowing the file, and the prefix if used, should help in identification of options that are not really orphaned.<br />*Note, if you get this warning with a plugin file (from the <u><i>WordPress.com Plugin Directory only</i></u>, please) and you know it\'s not a potential problem (eg. some WordPress core files, the WordPress.com Stats plugin, and this plugin have alternate syntax in them BUT <u><i>there are no options associated with them listed</i></u>), please visit the blog and leave a comment something like "the whatever plugin has alternate syntax but is OK" and I can add it to the "ignore" list for future version releases if it is safe to do so. Many Thanks. %s', 'cleanoptions'), '<a href="http://www.mittineague.com/blog/2009/03/alternate-syntax/">' . __('Alternate Syntax', 'cleanoptions') . '</a>') . "</dd>";
		}
		if ( $db_err_flag == true )
		{
			echo "<dt>" . __('Database Error', 'cleanoptions') . " - .... " . __('query did not return an array', 'cleanoptions') . "</dt>
			<dd>" . __('The plugin queries the wp_options table. It expects an array with at least 1 row. This error message may be the result of fact. i.e. There actually are no wp_option rows with autoload=yes (next to impossible), or there actually are no "rss_hash" rows. Or it could be an actual database problem (eg. connection failure, memory failure). If you get this error message you should look for a WPDB error message as well for more detailed information. An error with either the autoload=yes query (core/plugin/theme options), or the autoload!=yes query (rss_hash options) means that none of the corresponding rows will be available for review or deletion until the database problem is resolved.', 'cleanoptions') . "</dd>
			<dt>" . __('WordPress database error', 'cleanoptions') . ":<dt>
			<dd>" . __('These are the error messages as returned by WordPress.', 'cleanoptions') . "</dd>";
		}
		if ( $en_warn_flag  == true )
		{
			echo "<dt>" . __('Empty Name', 'cleanoptions') . " - .... " . __('Option with No Name with the value', 'cleanoptions') . " ....</dt>
			<dd>" . __('Perhaps some plugins/themes add options that have no name? Or the name becomes removed from the row somehow? Because this plugin finds options based on their names, these "no name" options will not be included in the list, and thus can not be selected for review or deletion. If the row has no option_name but has an option_value, it will be shown to help you identify the source of the problem. At present, if you wish to remove such rows you must do so by other means.', 'cleanoptions') . "</dd>";
		}
		if ( $fs_err_flag == true )
		{
			echo "<dt>" . __('File System Error', 'cleanoptions') . " - " . __('Could not open folder/file', 'cleanoptions') . " ....</dt>
			<dd>" . __('The plugin failed to open a folder/file. This is most often because of inadequate permissions settings. i.e. The "read" permission setting. They do not need to be "world" readable, but scripts must be able to. Options that are in files that can not be read may appear in the "orphaned options" list when in fact they are not orphaned. In many cases, knowing the folder/file should help in identification of options that are not really orphaned.', 'cleanoptions') . "</dd>";
		}
		echo '</dl>';
	}
		echo "<h2>" . __('Further Information', 'cleanoptions') . "</h2>";
		echo "<p>" . __('WANTED - Bug Reports', 'cleanoptions'); // . "<br />";
/*
		echo __('WANTED - Translations', 'cleanoptions') . "</p><p>"
		. __('If you have any questions, problems, comments, or suggestions, please let me know.', 'cleanoptions') . "</p><p>";
		printf(__('If you would like to provide a translation, please leave a comment at %s', 'cleanoptions'), '<a href=\'http://www.mittineague.com/blog/2009/06/clean-options-translations/\'>http://www.mittineague.com/blog/2009/06/clean-options-translations/</a>');
*/
		echo "</p>";
		echo "<p>";
		printf(__('For more information, the latest version, etc. please visit the plugin\'s page %s', 'cleanoptions'), '<a href=\'http://www.mittineague.com/dev/co.php\'>http://www.mittineague.com/dev/co.php</a>');
		echo "</p><p>";
		printf(__('Questions? For support, please visit the forum %s', 'cleanoptions'), '<a href=\'http://www.mittineague.com/forums/viewtopic.php?t=101\'>http://www.mittineague.com/forums/viewtopic.php?t=101</a>');
		echo " (" . __('registration required to post', 'cleanoptions') . ")</p>";
/*
		echo "<p>" . sprintf(__('For comments / suggestions, please visit the blog %s', 'cleanoptions'), '<a href=\'http://www.mittineague.com/blog/2008/11/clean-options-plugin-release-candidate/\'>http://www.mittineague.com/blog/2008/11/clean-options-plugin-release-candidate/</a>') . "</p>";
*/
		echo "<h3>" . __('Translation Acknowledgements', 'cleanoptions') . "</h3>
<!-- /* add to list as translations become available ll_CC (email|URL)?=>Name (URL=>LinkText)? */ -->
		<ul>
			<li>be_BY <a href='mailto://zhr@tut.by'>Fat Cow</a>  .  .  .  .  .  <a href='http://www.fatcow.com'>Fat Cow</a></li>
			<li>de_DE <a href='mailto://quitzlipochtli@gmail.com'>Thomas Knapp</a>  .  .  .  .  .  <a href='http://thomasknapp.at'>Blog für Politik, Medien und Philosophie</a></li>
			<li>es_ES <a href='mailto://correo@samuelaguilera.com'>Samuel Aguilera</a>  .  .  .  .  .  <a href='http://www.samuelaguilera.com'>Desarrollo web con WordPress</a></li>
			<li>hr_HR <a href='mailto://vdjuranic@gmail.com'>Vladimir</a>  .  .  .  .  .  <a href='http://vladowsky.com'>News</a></li>
			<li>nl_NL <a href='mailto://info@wppg.me'>WordPressPluginGuide.com</a>  .  .  .  .  .  <a href='http://wpwebshop.com'>WordPress premium themes &#38; plugins</a></li>
			<li>pt_BR <a href='mailto://cadusilvas@gmail.com'>Cadu Silva</a>  .  .  .  .  .  <a href='http://www.winnext.com.br/'>Winnext</a></li>
			<li>ru_RU <a href='http://onix.name' target='_blank'>Vadim N.</a>  .  .  .  .  .  Visit my <a href='http://onix.name/portfolio/' title='Portfolio'>Portfolio</a></li>
			<li>sr_RS <a href='mailto://vdjuranic@gmail.com'>Vladimir</a>  .  .  .  .  .   <a href='http://paraisrael.com'>paraISRAEL</a></li>
			<li>uk_UA <a href='mailto://onix@onix.name' target='_blank'>Vadim N.</a>  .  .  .  .  .  Visit my <a href='http://onix.name/' title='Blog'>Blog</a></li>
			<li>zh_CN <a href='mailto://francis.tm@gmail.com'>Francis</a>  .  .  .  .  .  <a href='http://www.wopus.org'>Wopus</a></li>
		</ul>
	</div>";
}

if ( function_exists('add_action') )
{
	add_action('admin_init', 'mitt_co_admin_init');
	add_action('admin_menu', 'mitt_add_co_page');
}
?>