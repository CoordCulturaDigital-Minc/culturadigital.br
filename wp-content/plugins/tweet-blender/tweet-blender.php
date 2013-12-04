<?php
/*
Plugin Name: Tweet Blender
Plugin URI: http://www.tweetblender.com
Description: Provides several Twitter widgets: show your own tweets, show tweets relevant to post's tags, show tweets for Twitter lists, show tweets for hashtags, show tweets for keyword searches, show favorite tweets. Multiple widgets on the same page are supported. Can combine sources and blend all of them into a single stream.
Version: 4.0.2
Author: Kirill Novitchenko
Author URI: http://kirill-novitchenko.com
*/

/*  Copyright 2009-2013  Kirill Novitchenko  (email : tweetblender@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// load localization file
load_plugin_textdomain('tweetblender', false, dirname(plugin_basename(__FILE__)) . "/lang/");

// if on PHP5, include oAuth library and config
if(!version_compare(PHP_VERSION, '5.0.0', '<'))
{
    class_exists('TwitterOAuth') || include_once dirname(__FILE__).'/lib/twitteroauth/twitteroauth.php';
	include_once dirname(__FILE__).'/lib/twitteroauth/config.php';
}

// include TweetBlender library
include_once(dirname(__FILE__).'/lib/lib.php');

// include Widgets
include_once(dirname(__FILE__).'/widget.php');
include_once(dirname(__FILE__).'/widget-tags.php');
include_once(dirname(__FILE__).'/widget-favorites.php');

// include admin tools
if (is_admin()) {
	include_once(dirname(__FILE__).'/admin-page.php');
}

// add includes for addons
tb_check_addons();
foreach($tb_addons as $addon_id => $addon) {
	$addon_file = $addon['slug'] . '/' . $addon['slug'] . '.php';
	
	if ($tb_installed_addons[$addon_id] && $tb_active_addons[$addon_id]) {
		include_once(WP_PLUGIN_DIR . '/' . $addon_file);
	}
}


// DB initialization
register_activation_hook(__FILE__,'tb_plugin_init');
function tb_plugin_init() {
	// install or upgrade database
	tb_db_install();
		
	// set defaults
	$tb_o = get_option("tweet-blender");
	if (!isset($tb_o['widget_check_sources'])) {
		$tb_o['widget_check_sources'] = true;
	}
	if (!isset($tb_o['rate_limit_data'])) {
		$tb_o['rate_limit_data'] = array();
	}
	update_option('tweet-blender',$tb_o);
}
function tb_db_install() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . "tweetblender";
	$tb_o = get_option("tweet-blender");

	$tb_db_version = "5";	
	$installed_ver = $tb_o["db_version"];

	$sql = "CREATE TABLE " . $table_name . " (
	  div_id VARCHAR(200) NOT NULL PRIMARY KEY,
	  source VARCHAR(255) NOT NULL,
	  tweet_text VARCHAR(255),
	  tweet_json TEXT NOT NULL,
	  created_at TIMESTAMP
	);";
	
	// if table is not already there - create it
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

		error_log('table not found');
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		$tb_o['db_version'] = $tb_db_version;
		update_option('tweet-blender',$tb_o);
	}
	// if table is there but has old structure
	elseif ((int)$installed_ver != (int)$tb_db_version) {
	
		error_log('version mismatch');
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$tb_o['db_version'] = $tb_db_version;
		update_option('tweet-blender',$tb_o);
	}
}
// remove old DB cache entries
function tb_db_cache_clear($where_sql = ''){
	global $wpdb;
	$table_name = $wpdb->prefix . "tweetblender";

	// delete tweets that are older than predefined period
	$wpdb->query("DELETE FROM $table_name $where_sql");
}


// generate config
add_action('wp_head', 'tb_add_header_config', 1);
function tb_add_header_config() {

	$tb_o = get_option('tweet-blender');
	
	$settings = array();
	// if user already saved settings - use them
	if(is_array($tb_o)) {
		
		// remove options not used by widget/archive
		unset($tb_o['archive_page_id']);
		unset($tb_o['db_version']);
		
		// urlencode bad words
		if (isset($tb_o['filter_bad_strings'])) {
			$tb_o['filter_bad_strings'] = base64_encode($tb_o['filter_bad_strings']);
		}
				
		foreach($tb_o as $opt => $val) {
			// skip oAuth
			if (strpos($opt,"oauth_") === 0) {
				continue;
			}
			
			if ($val == 'on') {
				$settings[] = "'$opt':true";
			}
			elseif ($val == '') {
				$settings[] = "'$opt':false";
			}
			else {
				$settings[] = "'$opt':'$val'";
			}
		}
	}
	// else, use defaults
	else {
		$settings = array(
			"'general_timestamp_format':false",
			"'general_seo_tweets_googleoff':false",
			"'general_seo_footer_googleoff':false",
			"'widget_show_reply_link':false",
			"'widget_show_follow_link':false",
			"'archive_tweets_num':'100'",
			"'archive_show_reply_link':false",
			"'archive_show_follow_link':false",
			"'archive_keep_tweets':'0'",
			"'advanced_no_search_api':false",
			"'filter_lang':' '",
			"'filter_hide_mentions':false",
			"'filter_hide_replies':false",
			"'filter_bad_strings':''",
			"'filter_limit_per_source':false",
			"'filter_limit_per_source_time':'0'",
			"'filter_hide_same_text':true",
			"'filter_hide_not_replies':false",
			"'archive_is_disabled':false",
			"'flag_use_charts':true",
			"'archive_show_source_selector':true"
		);
	}
	
	// add default view more link URL
	if (isset($tb_o['archive_is_disabled']) && (!$tb_o['archive_is_disabled'] && ($archive_post_id = tb_get_archive_post_id()) != null)) {
		$settings[] = "'default_view_more_url':'" . get_permalink($archive_post_id) . "'";
	}
	
	// add default ajax url
	$settings[] = "'ajax_url':'" . admin_url('admin-ajax.php') . "'";

	$js = "\nvar TB_pluginPath = '" . plugins_url('/tweet-blender') . "', TB_C_pluginPath = '" . plugins_url('/tweet-blender-charts') . "';\n";
	if (sizeof($settings) > 0) {
		$js .= "var TB_config = {\n" . join(",\n",$settings) . "\n}";
	}
	echo tb_wrap_javascript($js);
}

// register stylesheet
add_action('wp_print_styles', 'tb_add_header_css');
function tb_add_header_css() {
	wp_enqueue_style('tb-css', '/' . PLUGINDIR . '/tweet-blender/css/tweets.css');
}

// add javascript with dependency on jQuery to public pages only
add_action("template_redirect","tb_load_js");
function tb_load_js() {

	global $js_labels, $tb_installed_addons, $tb_active_addons, $tb_addons;
	
	$dependencies = array('jquery');
	
	$tb_o = get_option('tweet-blender');
	// load PHPDate only if have a custom date
	if (isset($tb_o['general_timestamp_format']) && ($tb_o['general_timestamp_format'] != '')) {
		wp_enqueue_script('phpdate', '/' . PLUGINDIR . '/tweet-blender/js/jquery.phpdate.js', array('jquery'), false, true);
		$dependencies[] = 'phpdate';
	}
	
	// load Google Charts if charts are being used
	if (isset($tb_o['flag_use_charts'])) {
		wp_enqueue_script('gchart', 'https://www.google.com/jsapi?autoload={"modules":[{"name":"visualization","version":"1"}]}', array(), false, true);
		$dependencies[] = 'gchart';
	}
		
	// load lib
	wp_enqueue_script('tb-lib', '/' . PLUGINDIR . '/tweet-blender/js/lib.js',array('jquery'), false, true);
    wp_localize_script('tb-lib', 'TB_labels', $js_labels);
	$dependencies[] = 'tb-lib';
	
	// add hooks for addons
	foreach($tb_addons as $addon_id => $addon) {
		$addon_file = $addon['slug'] . '/' . $addon['slug'] . '.php';
		
		if ($tb_installed_addons[$addon_id] && $tb_active_addons[$addon_id] && file_exists(WP_PLUGIN_DIR . '/' . $addon['slug'] . '/js/main.js')) {
			$dependencies[] = $addon['slug'];
		}
	}

	// load main JS code
	wp_enqueue_script('tb-main', '/' . PLUGINDIR . '/tweet-blender/js/main.js', $dependencies, false, true);
	
}

// hookup filter to add tweet list to the content of archive page
add_filter('the_content', 'tb_add_archive_page_content');
function tb_add_archive_page_content($content = '') {
	global $post;
	
	// do nothing if archive page is disabled
	$tb_o = get_option('tweet-blender');
	if (isset($tb_o['archive_is_disabled']) && $tb_o['archive_is_disabled']) {
		return $content;	
	}
	else {
		// work with pages only, ignore blog posts
		if ($post->post_type != 'page') {
			return $content;
		}
		
		// if looking at archive page, apend list of tweets to content
		if ($post->ID == tb_get_archive_post_id()) {
			return $content . tb_make_archive_html($tb_o);
		}
		// if content has the shorttag
		else if (preg_match("[TweetBlender Archive]",$content)) {
			return str_replace("[TweetBlender Archive]",tb_make_archive_html($tb_o),$content);
		} 
		// else, do nothing
		else {
			return $content;
		}
	}
}

function tb_make_archive_html($tb_o) {
	
	$sources = array();	// by default we get tweets for all sources
	$archive_html = '';
	
	if((isset($tb_o['archive_show_source_selector']) && $tb_o['archive_show_source_selector']) || isset($_GET['source'])) {

		if(isset($_GET['source'])) {
			if (is_array($_GET['source'])) {
				$sources = $_GET['source'];
			}
			else {
				$sources = array_values(array_filter(preg_split('/,/m', trim($_GET['source']))));
			}
		}
		
		$archive_html .= '<form id="tb-source-selector">' . __('Show tweets for','tweetblender');
		
		$cached_sources = tb_get_cache_stats();
		foreach ((array)$cached_sources as $cache_src) {
			$archive_html .= '<input type="checkbox" class="tb-source" name="source[]" value="' . esc_attr($cache_src->source) . '"';
			if(in_array($cache_src->source,$sources) || sizeof($sources) == 0) {
				$archive_html .= ' checked';
			}
			$archive_html .= '/> ';					
	       	$archive_html .= urldecode($cache_src->source);
		}
		
		$archive_html .= '<br /><input id="tb-update-sources" type="submit" value="Update" />';
		
		$archive_html .= '</form>';
	}
	
	$archive_html .= '<div id="tweetblender-archive">';
	$archive_html .= tb_get_cached_tweets_html('archive',null,null,$sources);
	$archive_html .= '</div>';

	// JavaScript code for mouseovers
	$archive_html .= tb_wrap_javascript("jQuery('document').ready(function(){
		TB_mode = 'archive';
		jQuery.each(jQuery('#tweetblender-archive').children('div'),function(i,obj){ TB_wireMouseOver(obj.id); });
	});");
	
	return $archive_html;
}

// template tag for general widget
function tweet_blender_widget($options) {

	echo '<div id="'. $options['unique_div_id'] . '" class="widget widget_tweetblender">';
	// if required parameters not provided output HTML comment with usage instructions
	if (!isset($options['unique_div_id']) || !isset($options['sources'])) {
		echo "The 'unique_div_id' and 'sources' are required parameters when using tweet_blender_widget() template tag for Tweet Blender plugin. The code should look as follows:
		
		<pre>tweet_blender_widget(array(
	'unique_div_id' => 'tweetblender-t1',
	'sources' => '@tweetblender,#tweetblender,twitter',
	'refresh_rate'=> 60,
	'tweets_num' => 5,
	'view_more_url' => 'http://twitter.com/tweetblender',
	'view_more_text' => 'follow us!'
));</pre>";	
	}
	// else create widget HTML
	else {
		$tb = new TweetBlender();
		$tb->id = $options['unique_div_id'];
		$tb->widget(array(),array(
			'widget_sources' => $options['sources'],
			'widget_refresh_rate' => $options['refresh_rate'],
			'widget_tweets_num' => $options['tweets_num'],
			'widget_view_more_url' => $options['view_more_url'],
			'widget_view_more_text' => $options['view_more_text']
		));
	}
	echo '</div>';		
}

// template tag for general tags widget
function tweet_blender_widget_for_tags($options) {

	echo '<div id="'. $options['unique_div_id'] . '" class="widget widget_tweetblender">';
	// if required parameters not provided output HTML comment with usage instructions
	if (!isset($options['unique_div_id'])) {
		echo "The 'unique_div_id' is a required parameter when using tweet_blender_widget_for_tags() template tag for Tweet Blender plugin. The code should look as follows:
		
		<pre>tweet_blender_widget_for_tags(array(
	'unique_div_id' => 'tweetblender-t1',
	'refresh_rate'=> 60,
	'tweets_num' => 5
));</pre>";	
	}
	// else create widget HTML
	else {
		$tb = new TweetBlenderForTags();
		$tb->id = $options['unique_div_id'];
		$tb->widget(array(),array(
			'widget_refresh_rate' => $options['refresh_rate'],
			'widget_tweets_num' => $options['tweets_num'],
		));
	}
	echo '</div>';
}

function tb_create_markup($mode = 'widget',$instance,$widget_id,$tb_o) {
	$html = '';
	$html .= tb_create_markup_header($widget_id);

	if ($mode == 'chart') {
		$html .= '<div class="tb_tweetchart" id="' . $widget_id . '-chart"></div>';
		$html .= tb_get_cached_tweets_html($mode,$instance,$widget_id);
	}
	else {
		if (isset($tb_o['general_seo_tweets_googleoff']) && $tb_o['general_seo_tweets_googleoff']) {
			$html .= '<!--googleoff: index--><div class="tb_tweetlist">' . tb_get_cached_tweets_html($mode,$instance,$widget_id) . '</div><!--googleon: index-->';
		}
		else {
			$html .= '<div class="tb_tweetlist">' . tb_get_cached_tweets_html($mode,$instance) . '</div>';
		}
	}
	return $html;
}

function tb_create_markup_header($widget_id) {
	$html = '<div class="tb_header">';
	$html .= '<img class="tb_twitterlogo" src="' . plugins_url('tweet-blender/img/twitter-logo.png') . '" alt="Twitter Logo" />';
	$html .= '<div class="tb_tools" style="background-image:url(' . plugins_url('tweet-blender/img/bg_sm.png') . ')">';
	$html .= '<a class="tb_infolink" href="http://kirill-novitchenko.com" title="Tweet Blender by Kirill Novitchenko" style="background-image:url(' . plugins_url('tweet-blender/img/info-kino.png') . ')"> </a>';
	$html .= '<a class="tb_refreshlink" href="javascript:TB_blend(\'' . $widget_id . '\');" title="Refresh Tweets"><img src="' . plugins_url('tweet-blender/img/ajax-refresh-icon.gif') . '" alt="Refresh" /></a></div></div>';
	return $html;	
}

?>
