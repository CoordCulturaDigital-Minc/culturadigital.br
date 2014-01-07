<?php
/*
Plugin Name: Share Buttons by AddToAny
Plugin URI: http://www.addtoany.com/
Description: Share buttons for your pages including AddToAny's universal sharing button, Facebook, Twitter, Google+, Pinterest, StumbleUpon and many more.  [<a href="options-general.php?page=add-to-any.php">Settings</a>]
Version: 1.2.7.8
Author: micropat
Author URI: http://www.addtoany.com/
*/

if( !isset($A2A_locale) )
	$A2A_locale = '';
	
// Pre-2.6 compatibility
if ( ! defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );
	
$A2A_SHARE_SAVE_plugin_basename = plugin_basename(dirname(__FILE__));

// WordPress Must-Use?
if ( basename(dirname(__FILE__)) == "mu-plugins" ) {
	// __FILE__ expected in /wp-content/mu-plugins (parent directory for auto-execution)
	// /wp-content/mu-plugins/add-to-any
	$A2A_SHARE_SAVE_plugin_url_path = WPMU_PLUGIN_URL . '/add-to-any';
	$A2A_SHARE_SAVE_plugin_dir = WPMU_PLUGIN_DIR . '/add-to-any';
} 
else {
	// /wp-content/plugins/add-to-any
	$A2A_SHARE_SAVE_plugin_url_path = WP_PLUGIN_URL . '/' . $A2A_SHARE_SAVE_plugin_basename;
	$A2A_SHARE_SAVE_plugin_dir = WP_PLUGIN_DIR . '/' . $A2A_SHARE_SAVE_plugin_basename;
}
	

// Fix SSL
if (is_ssl())
	$A2A_SHARE_SAVE_plugin_url_path = str_replace('http:', 'https:', $A2A_SHARE_SAVE_plugin_url_path);

$A2A_SHARE_SAVE_options = get_option('addtoany_options');

function A2A_SHARE_SAVE_init() {
	global $A2A_SHARE_SAVE_plugin_url_path,
		$A2A_SHARE_SAVE_plugin_basename, 
		$A2A_SHARE_SAVE_options;
	
	if (get_option('A2A_SHARE_SAVE_button')) {
		A2A_SHARE_SAVE_migrate_options();
		$A2A_SHARE_SAVE_options = get_option('addtoany_options');
	}
	
	load_plugin_textdomain('add-to-any',
		$A2A_SHARE_SAVE_plugin_url_path.'/languages',
		$A2A_SHARE_SAVE_plugin_basename.'/languages');
		
	if ($A2A_SHARE_SAVE_options['display_in_excerpts'] != '-1') {
		// Excerpts use strip_tags() for the_content, so cancel if Excerpt and append to the_excerpt instead
		add_filter('get_the_excerpt', 'A2A_SHARE_SAVE_remove_from_content', 9);
		add_filter('the_excerpt', 'A2A_SHARE_SAVE_add_to_content', 98);
	}
}
add_filter('init', 'A2A_SHARE_SAVE_init');

function A2A_SHARE_SAVE_link_vars($linkname = FALSE, $linkurl = FALSE) {
	global $post;
	
	// Set linkname
	if ( !$linkname ) {
		if ( isset( $post ) )
			$linkname = get_the_title($post->ID);
		else
			$linkname = '';
	}
	
	$linkname_enc	= rawurlencode( html_entity_decode($linkname, ENT_QUOTES, 'UTF-8') );
	
	// Set linkurl
	if ( !$linkurl ) {
		if (isset( $post ))
			$linkurl = get_permalink($post->ID);
		else
			$linkurl = '';
	}
	
	$linkurl_enc	= rawurlencode( $linkurl );
	
	return compact( 'linkname', 'linkname_enc', 'linkurl', 'linkurl_enc' );
}

include_once($A2A_SHARE_SAVE_plugin_dir . '/addtoany.services.php');

// Combine ADDTOANY_SHARE_SAVE_ICONS and ADDTOANY_SHARE_SAVE_BUTTON
function ADDTOANY_SHARE_SAVE_KIT( $args = false ) {
	global $_addtoany_counter;
	
	$_addtoany_counter++;
	
	$options = get_option('addtoany_options');
	
	// If universal button disabled, and not manually disabled through args
	if ( $options['button'] == 'NONE' && ! isset( $args['no_universal_button'] ) ) {
		// Pass this setting on to ADDTOANY_SHARE_SAVE_BUTTON
		// (and only via this ADDTOANY_SHARE_SAVE_KIT function because it is used for automatic placement)
		$args['no_universal_button'] = true;
	}
	
	// Set a2a_kit_size_## class name unless "icon_size" is set to '16'
	if ( !$options['icon_size'] )
		$icon_size = ' a2a_kit_size_32';
	else if ($options['icon_size'] == '16')
		$icon_size = '';
	else
		$icon_size = ' a2a_kit_size_' . $options['icon_size'] . '';
	
	if ( ! isset($args['html_container_open'])) {
		$args['html_container_open'] = '<div class="a2a_kit' . $icon_size . ' a2a_target addtoany_list" id="wpa2a_' . $_addtoany_counter . '">'; // ID is later removed by JS (for AJAX)
		$args['is_kit'] = TRUE;
	}
	if ( ! isset($args['html_container_close']))
		$args['html_container_close'] = "</div>";
	// Close container element in ADDTOANY_SHARE_SAVE_BUTTON, not prematurely in ADDTOANY_SHARE_SAVE_ICONS
	$html_container_close = $args['html_container_close']; // Cache for _BUTTON
	unset($args['html_container_close']); // Avoid passing to ADDTOANY_SHARE_SAVE_ICONS since set in _BUTTON
				
	if ( ! isset($args['html_wrap_open']))
		$args['html_wrap_open'] = "";
	if ( ! isset($args['html_wrap_close']))
		$args['html_wrap_close'] = "";
	
	$kit_html = ADDTOANY_SHARE_SAVE_ICONS($args);
	
	$args['html_container_close'] = $html_container_close; // Re-set because unset above for _ICONS
	unset($args['html_container_open']); // Avoid passing to ADDTOANY_SHARE_SAVE_BUTTON since set in _ICONS
	
	$kit_html .= ADDTOANY_SHARE_SAVE_BUTTON($args);
	
	if (isset($args['output_later']) && $args['output_later'])
		return $kit_html;
	else
		echo $kit_html;
}

function ADDTOANY_SHARE_SAVE_ICONS( $args = array() ) {
	// $args array: output_later, html_container_open, html_container_close, html_wrap_open, html_wrap_close, linkname, linkurl
	
	global $A2A_SHARE_SAVE_plugin_url_path, 
		$A2A_SHARE_SAVE_services;
	
	$linkname = (isset($args['linkname'])) ? $args['linkname'] : FALSE;
	$linkurl = (isset($args['linkurl'])) ? $args['linkurl'] : FALSE;
	
	$args = array_merge($args, A2A_SHARE_SAVE_link_vars($linkname, $linkurl)); // linkname_enc, etc.
	
	$defaults = array(
		'linkname' => '',
		'linkurl' => '',
		'linkname_enc' => '',
		'linkurl_enc' => '',
		'output_later' => FALSE,
		'html_container_open' => '',
		'html_container_close' => '',
		'html_wrap_open' => '',
		'html_wrap_close' => '',
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	// Make available services extensible via plugins, themes (functions.php), etc.
	$A2A_SHARE_SAVE_services = apply_filters('A2A_SHARE_SAVE_services', $A2A_SHARE_SAVE_services);
	
	$service_codes = (is_array($A2A_SHARE_SAVE_services)) ? array_keys($A2A_SHARE_SAVE_services) : Array();
	
	// Include Facebook Like and Twitter Tweet etc.
	array_unshift($service_codes, 'facebook_like', 'twitter_tweet', 'google_plusone', 'google_plus_share', 'pinterest_pin');
	
	$options = get_option('addtoany_options');
	
	// False only if "icon_size" is set to '16'
	$large_icons = ( isset($options['icon_size']) && $options['icon_size'] == '16' ) ? FALSE : TRUE;
	
	$active_services = $options['active_services'];
	
	$ind_html = "" . $html_container_open;
	
	// Use default services if options have not been set yet
	if ( false === $options )
		$active_services = array( 'facebook', 'twitter', 'google_plus' );
	elseif ( empty( $active_services ) )
		$active_services = array();
	
	foreach($active_services as $active_service) {
		
		if ( !in_array($active_service, $service_codes) )
			continue;

		if ($active_service == 'facebook_like' || $active_service == 'twitter_tweet' || $active_service == 'google_plusone' || $active_service == 'google_plus_share' || $active_service == 'pinterest_pin') {
			$special_args = $args;
			$special_args['output_later'] = TRUE;
			$link = ADDTOANY_SHARE_SAVE_SPECIAL($active_service, $special_args);
		}
		else {
			$service = $A2A_SHARE_SAVE_services[$active_service];
			$safe_name = $active_service;
			$name = $service['name'];
			
			if (isset($service['href'])) {
				$custom_service = TRUE;
				$href = $service['href'];
				if (isset($service['href_js_esc'])) {
					$href_linkurl = str_replace("'", "\'", $linkurl);
					$href_linkname = str_replace("'", "\'", $linkname);
				} else {
					$href_linkurl = $linkurl_enc;
					$href_linkname = $linkname_enc;
				}
				$href = str_replace("A2A_LINKURL", $href_linkurl, $href);
				$href = str_replace("A2A_LINKNAME", $href_linkname, $href);
				$href = str_replace(" ", "%20", $href);
			} else {
				$custom_service = FALSE;
			}
	
			$icon_url = (isset($service['icon_url'])) ? $service['icon_url'] : FALSE;
			$icon = (isset($service['icon'])) ? $service['icon'] : 'default'; // Just the icon filename
			$width = (isset($service['icon_width'])) ? $service['icon_width'] : '16';
			$height = (isset($service['icon_height'])) ? $service['icon_height'] : '16';
			
			$url = ($custom_service) ? $href : "http://www.addtoany.com/add_to/" . $safe_name . "?linkurl=" . $linkurl_enc . "&amp;linkname=" . $linkname_enc;
			$src = ($icon_url) ? $icon_url : $A2A_SHARE_SAVE_plugin_url_path."/icons/".$icon.".png";
			$class_attr = ($custom_service) ? "" : " class=\"a2a_button_$safe_name\"";
			
			$link = $html_wrap_open."<a$class_attr href=\"$url\" title=\"$name\" rel=\"nofollow\" target=\"_blank\">";
			$link .= ($large_icons) ? "" : "<img src=\"$src\" width=\"$width\" height=\"$height\" alt=\"$name\"/>";
			$link .= "</a>".$html_wrap_close;
		}
		
		$ind_html .= $link;
	}
	
	$ind_html .= $html_container_close;
	
	if ( $output_later )
		return $ind_html;
	else
		echo $ind_html;
}

function ADDTOANY_SHARE_SAVE_BUTTON( $args = array() ) {
	
	// $args array = output_later, html_container_open, html_container_close, html_wrap_open, html_wrap_close, linkname, linkurl, no_universal_button

	global $A2A_SHARE_SAVE_plugin_url_path, $_addtoany_targets, $_addtoany_counter, $_addtoany_init;
	
	$linkname = (isset($args['linkname'])) ? $args['linkname'] : FALSE;
	$linkurl = (isset($args['linkurl'])) ? $args['linkurl'] : FALSE;
	$_addtoany_targets = ( isset( $_addtoany_targets ) ) ? $_addtoany_targets : array();

	$args = array_merge($args, A2A_SHARE_SAVE_link_vars($linkname, $linkurl)); // linkname_enc, etc.
	
	$defaults = array(
		'linkname' => '',
		'linkurl' => '',
		'linkname_enc' => '',
		'linkurl_enc' => '',
		'use_current_page' => FALSE,
		'output_later' => FALSE,
		'is_kit' => FALSE,
		'html_container_open' => '',
		'html_container_close' => '',
		'html_wrap_open' => '',
		'html_wrap_close' => '',
		'no_universal_button' => FALSE,
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	// If not enclosed in an AddToAny Kit, count & target this button (instead of Kit) for async loading
	if ( ! $args['is_kit']) {
		$_addtoany_counter++;
		$button_class = ' a2a_target';
		$button_id = ' id="wpa2a_' . $_addtoany_counter . '"';  // ID is later removed by JS (for AJAX)
	} else {
		$button_class = '';
		$button_id = '';
	}
	
	/* AddToAny button */
	
	$is_feed = is_feed();
	$button_target = '';
	$button_href_querystring = ($is_feed) ? '#url=' . $linkurl_enc . '&amp;title=' . $linkname_enc : '';
	$options = get_option('addtoany_options');
	
	// If universal button is enabled
	if ( ! $args['no_universal_button'] ) {
	
		if ( ! $options['button'] || $options['button'] == 'A2A_SVG_32' ) {
			// Skip button IMG for A2A icon insertion
			$button_text = '';
		} else if( $options['button'] == 'CUSTOM' ) {
			$button_src		= $options['button_custom'];
			$button_width	= '';
			$button_height	= '';
		} else if( $options['button'] == 'TEXT' ) {
			$button_text	= stripslashes($options['button_text']);
		} else {
			$button_attrs	= explode( '|', $options['button'] );
			$button_fname	= $button_attrs[0];
			$button_width	= ' width="'.$button_attrs[1].'"';
			$button_height	= ' height="'.$button_attrs[2].'"';
			$button_src		= $A2A_SHARE_SAVE_plugin_url_path.'/'.$button_fname;
			$button_text	= stripslashes($options['button_text']);
		}
		
		$style = '';
		
		if ( isset($button_fname) && ($button_fname == 'favicon.png' || $button_fname == 'share_16_16.png') ) {
			if ( ! $is_feed) {
				$style_bg	= 'background:url('.$A2A_SHARE_SAVE_plugin_url_path.'/'.$button_fname.') no-repeat scroll 4px 0px !important;';
				$style		= ' style="'.$style_bg.'padding:0 0 0 25px;display:inline-block;height:16px;vertical-align:middle"'; // padding-left:21+4 (4=other icons padding)
			}
		}
		
		if ( isset($button_text) && ( ! isset($button_fname) || ! $button_fname || $button_fname == 'favicon.png' || $button_fname == 'share_16_16.png') ) {
			$button			= $button_text;
		} else {
			$style = '';
			$button			= '<img src="'.$button_src.'"'.$button_width.$button_height.' alt="Share"/>';
		}
		
		$button_html = $html_container_open . $html_wrap_open . '<a class="a2a_dd' . $button_class . ' addtoany_share_save" href="http://www.addtoany.com/share_save' .$button_href_querystring . '"' . $button_id
			. $style . $button_target
			. '>' . $button . '</a>';
	
	} else {
		// Universal button disabled
		$button_html = '';
	}
	
	// If not a feed
	if( ! $is_feed ) {
		if ($use_current_page) {
			$button_config = "\n{title:document.title,"
				. "url:location.href}";
			$_addtoany_targets[] = $button_config;
		} else {
			$button_config = "\n{title:'". esc_js($linkname) . "',"
				. "url:'" . $linkurl . "'}";
			$_addtoany_targets[] = $button_config;
		}
		
		// If doing AJAX (the DOING_AJAX constant can be unreliable)
		if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
			$javascript_button_config = "<script type=\"text/javascript\"><!--\n"
				. "wpa2a.targets.push("
					. $button_config
				. ");\n";
			
			if ( ! $_addtoany_init) {
				// Catch post-load event to support infinite scroll (and more?)
				$javascript_button_config .= "\nif('function'===typeof(jQuery))"
					. "jQuery(document).ready(function($){"
						. "$('body').on('post-load',function(){"
							. "if(wpa2a.script_ready)wpa2a.init();"
							. "wpa2a.script_load();" // Load external script if not already called
						. "});"
					. "});";
			}
			
			$javascript_button_config .= "\n//--></script>\n";
		}
		else $javascript_button_config = '';
		
		if ( ! $_addtoany_init) {
			$javascript_load_early = "\n<script type=\"text/javascript\"><!--\n"
				. "wpa2a.script_load();"
				. "\n//--></script>\n";
		}
		else $javascript_load_early = "";
		
		$button_html .= $javascript_load_early . $javascript_button_config;
		$_addtoany_init = TRUE;
	}
	
	// Closing tags come after <script> to validate in case the container is a list element
	$button_html .= $html_wrap_close . $html_container_close;
	
	if ( $output_later )
		return $button_html;
	else
		echo $button_html;
}

function ADDTOANY_SHARE_SAVE_SPECIAL($special_service_code, $args = array() ) {
	// $args array = output_later, linkname, linkurl
	
	$options = get_option('addtoany_options');
	
	$linkname = (isset($args['linkname'])) ? $args['linkname'] : FALSE;
	$linkurl = (isset($args['linkurl'])) ? $args['linkurl'] : FALSE;
	
	$args = array_merge($args, A2A_SHARE_SAVE_link_vars($linkname, $linkurl)); // linkname_enc, etc.
	extract( $args );
	
	$special_anchor_template = '<a class="a2a_button_%1$s addtoany_special_service"%2$s></a>';
	$custom_attributes = '';
	
	if ($special_service_code == 'facebook_like') {
		$custom_attributes .= ($options['special_facebook_like_options']['verb'] == 'recommend') ? ' data-action="recommend"' : '';
		$custom_attributes .= ' data-href="' . $linkurl . '"';
		$special_html = sprintf($special_anchor_template, $special_service_code, $custom_attributes);
	}
	
	elseif ($special_service_code == 'twitter_tweet') {
		$custom_attributes .= ($options['special_twitter_tweet_options']['show_count'] == '1') ? ' data-count="horizontal"' : ' data-count="none"';
		$custom_attributes .= ' data-url="' . $linkurl . '"';
		$custom_attributes .= ' data-text="' . $linkname . '"';
		$special_html = sprintf($special_anchor_template, $special_service_code, $custom_attributes);
	}
	
	elseif ($special_service_code == 'google_plusone') {
		$custom_attributes .= ($options['special_google_plusone_options']['show_count'] == '1') ? '' : ' data-annotation="none"';
		$custom_attributes .= ' data-href="' . $linkurl . '"';
		$special_html = sprintf($special_anchor_template, $special_service_code, $custom_attributes);
	}
	
	elseif ($special_service_code == 'google_plus_share') {
		$custom_attributes .= ($options['special_google_plus_share_options']['show_count'] == '1') ? '' : ' data-annotation="none"';
		$custom_attributes .= ' data-href="' . $linkurl . '"';
		$special_html = sprintf($special_anchor_template, $special_service_code, $custom_attributes);
	}
	
	elseif ($special_service_code == 'pinterest_pin') {
		$custom_attributes .= ($options['special_pinterest_pin_options']['show_count'] == '1') ? '' : ' data-pin-config="none"';
		$custom_attributes .= ' data-url="' . $linkurl . '"';
		$special_html = sprintf($special_anchor_template, $special_service_code, $custom_attributes);
	}
	
	if ( $output_later )
		return $special_html;
	else
		echo $special_html;
}

if (!function_exists('A2A_menu_locale')) {
	function A2A_menu_locale() {
		global $A2A_locale;
		$locale = get_locale();
		if($locale == 'en_US' || $locale == 'en' || $A2A_locale != '' )
			return false;
			
		$A2A_locale = 'a2a_localize = {
	Share: "' . __("Share", "add-to-any") . '",
	Save: "' . __("Save", "add-to-any") . '",
	Subscribe: "' . __("Subscribe", "add-to-any") . '",
	Email: "' . __("Email", "add-to-any") . '",
	Bookmark: "' . __("Bookmark", "add-to-any") . '",
	ShowAll: "' . __("Show all", "add-to-any") . '",
	ShowLess: "' . __("Show less", "add-to-any") . '",
	FindServices: "' . __("Find service(s)", "add-to-any") . '",
	FindAnyServiceToAddTo: "' . __("Instantly find any service to add to", "add-to-any") . '",
	PoweredBy: "' . __("Powered by", "add-to-any") . '",
	ShareViaEmail: "' . __("Share via email", "add-to-any") . '",
	SubscribeViaEmail: "' . __("Subscribe via email", "add-to-any") . '",
	BookmarkInYourBrowser: "' . __("Bookmark in your browser", "add-to-any") . '",
	BookmarkInstructions: "' . __("Press Ctrl+D or \u2318+D to bookmark this page", "add-to-any") . '",
	AddToYourFavorites: "' . __("Add to your favorites", "add-to-any") . '",
	SendFromWebOrProgram: "' . __("Send from any email address or email program", "add-to-any") . '",
	EmailProgram: "' . __("Email program", "add-to-any") . '"
};
';
		return $A2A_locale;
	}
}


function A2A_SHARE_SAVE_head_script() {
	if (is_admin())
		return;
		
	$options = get_option('addtoany_options');
	
	$http_or_https = (is_ssl()) ? 'https' : 'http';
	
	global $A2A_SHARE_SAVE_external_script_called;
	if ( ! $A2A_SHARE_SAVE_external_script_called ) {
		// Use local cache?
		$cache = ($options['cache']=='1') ? TRUE : FALSE;
		$upload_dir = wp_upload_dir();
		$static_server = ($cache) ? $upload_dir['baseurl'] . '/addtoany' : $http_or_https . '://static.addtoany.com/menu';
		
		// Enternal script call + initial JS + set-once variables
		$additional_js = $options['additional_js_variables'];
		$script_configs = (($cache) ? "\n" . 'a2a_config.static_server="' . $static_server . '";' : '' )
			. (($options['onclick']=='1') ? "\n" . 'a2a_config.onclick=1;' : '')
			. (($options['show_title']=='1') ? "\n" . 'a2a_config.show_title=1;' : '')
			. (($additional_js) ? "\n" . stripslashes($additional_js) : '');
		$A2A_SHARE_SAVE_external_script_called = true;
	}
	else {
		$script_configs = "";
	}
	
	$javascript_header = "\n" . '<script type="text/javascript">' . "<!--\n"
	
			. "var a2a_config=a2a_config||{},"
			. "wpa2a={done:false,"
			. "html_done:false,"
			. "script_ready:false,"
			. "script_load:function(){"
				. "var a=document.createElement('script'),"
					. "s=document.getElementsByTagName('script')[0];"
				. "a.type='text/javascript';a.async=true;"
				. "a.src='" . $static_server . "/page.js';"
				. "s.parentNode.insertBefore(a,s);"
				. "wpa2a.script_load=function(){};"
			. "},"
			. "script_onready:function(){"
				. "if(a2a.type=='page'){" // Check a2a internal var to ensure script loaded is page.js not feed.js
					. "wpa2a.script_ready=true;"
					. "if(wpa2a.html_done)wpa2a.init();"
				. "}"
			. "},"
			. "init:function(){"
				. "for(var i=0,el,target,targets=wpa2a.targets,length=targets.length;i<length;i++){"
					. "el=document.getElementById('wpa2a_'+(i+1));"
					. "target=targets[i];"
					. "a2a_config.linkname=target.title;"
					. "a2a_config.linkurl=target.url;"
					. "if(el){"
						. "a2a.init('page',{target:el});"
						. "el.id='';" // Remove ID so AJAX can reuse the same ID
					. "}"
					. "wpa2a.done=true;"
				. "}"
				. "wpa2a.targets=[];" // Empty targets array so AJAX can reuse from index 0
			. "}"
		. "};"
		
		. "a2a_config.tracking_callback=['ready',wpa2a.script_onready];"
		. A2A_menu_locale()
		. $script_configs
		
		. "\n//--></script>\n";
	
	 echo $javascript_header;
}

add_action('wp_head', 'A2A_SHARE_SAVE_head_script');

function A2A_SHARE_SAVE_footer_script() {
	global $_addtoany_targets;
	
	if (is_admin())
		return;
		
	$_addtoany_targets = (isset($_addtoany_targets)) ? $_addtoany_targets : array();
	
	$javascript_footer = "\n" . '<script type="text/javascript">' . "<!--\n"
		. "wpa2a.targets=["
			. implode(",", $_addtoany_targets)
		. "];\n"
		. "wpa2a.html_done=true;"
		. "if(wpa2a.script_ready&&!wpa2a.done)wpa2a.init();" // External script may load before html_done=true, but will only init if html_done=true.  So call wpa2a.init() if external script is ready, and if wpa2a.init() hasn't been called already.  Otherwise, wait for callback to call wpa2a.init()
		. "wpa2a.script_load();" // Load external script if not already called with the first AddToAny button.  Fixes issues where first button code is processed internally but without actual code output
		. "\n//--></script>\n";
	
	echo $javascript_footer;
}

add_action('wp_footer', 'A2A_SHARE_SAVE_footer_script');



function A2A_SHARE_SAVE_theme_hooks_check() {
	$template_directory = get_template_directory();
	
	// If footer.php exists in the current theme, scan for "wp_footer"
	$file = $template_directory . '/footer.php';
	if (is_file($file)) {
		$search_string = "wp_footer";
		$file_lines = @file($file);
		
		foreach ($file_lines as $line) {
			$searchCount = substr_count($line, $search_string);
			if ($searchCount > 0) {
				return true;
			}
		}
		
		// wp_footer() not found:
		echo "<div class=\"update-nag\">" . __("Your theme needs to be fixed. To fix your theme, use the <a href=\"theme-editor.php\">Theme Editor</a> to insert <code>&lt;?php wp_footer(); ?&gt;</code> just before the <code>&lt;/body&gt;</code> line of your theme's <code>footer.php</code> file.") . "</div>";
	}
	
	// If header.php exists in the current theme, scan for "wp_head"
	$file = $template_directory . '/header.php';
	if (is_file($file)) {
		$search_string = "wp_head";
		$file_lines = @file($file);
		
		foreach ($file_lines as $line) {
			$searchCount = substr_count($line, $search_string);
			if ($searchCount > 0) {
				return true;
			}
		}
		
		// wp_footer() not found:
		echo "<div class=\"update-nag\">" . __("Your theme needs to be fixed. To fix your theme, use the <a href=\"theme-editor.php\">Theme Editor</a> to insert <code>&lt;?php wp_head(); ?&gt;</code> just before the <code>&lt;/head&gt;</code> line of your theme's <code>header.php</code> file.") . "</div>";
	}
}

function A2A_SHARE_SAVE_auto_placement($title) {
	global $A2A_SHARE_SAVE_auto_placement_ready;
	$A2A_SHARE_SAVE_auto_placement_ready = true;
	
	return $title;
}


/**
 * Remove the_content filter and add it for next time 
 */
function A2A_SHARE_SAVE_remove_from_content($content) {
	remove_filter('the_content', 'A2A_SHARE_SAVE_add_to_content', 98);
	add_filter('the_content', 'A2A_SHARE_SAVE_add_to_content_next_time', 98);
	
	return $content;
}

/**
 * Apply the_content filter "next time"
 */
function A2A_SHARE_SAVE_add_to_content_next_time($content) {
	add_filter('the_content', 'A2A_SHARE_SAVE_add_to_content', 98);
	
	return $content;
}


function A2A_SHARE_SAVE_add_to_content($content) {
	global $A2A_SHARE_SAVE_auto_placement_ready;
	
	$is_feed = is_feed();
	$options = get_option('addtoany_options');
	$sharing_disabled = get_post_meta( get_the_ID(), 'sharing_disabled', true );
	
	if( ! $A2A_SHARE_SAVE_auto_placement_ready)
		return $content;
		
	if (get_post_status(get_the_ID()) == 'private')
		return $content;
		
	// Disabled for this post?
	if ( ! empty( $sharing_disabled ) )
		return $content;
	
	if ( 
		( 
			// Legacy tags
			// <!--sharesave--> tag
			strpos($content, '<!--sharesave-->')===false || 
			// <!--nosharesave--> tag
			strpos($content, '<!--nosharesave-->')!==false
		) &&
		(
			// Posts
			// All posts
			( ! is_page() && $options['display_in_posts']=='-1' ) ||
			// Front page posts		
			( is_home() && $options['display_in_posts_on_front_page']=='-1' ) ||
			// Archive page posts (Category, Tag, Author and Date pages)
			( is_archive() && $options['display_in_posts_on_archive_pages']=='-1' ) ||
			// Search results posts (same as Archive page posts option)
			( is_search() && $options['display_in_posts_on_archive_pages']=='-1' ) || 
			// Posts in feed
			( $is_feed && ($options['display_in_feed']=='-1' ) ||
			
			// Pages
			// Individual pages
			( is_page() && $options['display_in_pages']=='-1' ) ||
			// <!--nosharesave--> legacy tag
			( (strpos($content, '<!--nosharesave-->')!==false) )
		)
		)
	)	
		return $content;
	
	$kit_args = array(
		"output_later" => true,
		"is_kit" => ($is_feed) ? FALSE : TRUE,
	);
	
	if ( ! $is_feed ) {
		$container_wrap_open = '<div class="addtoany_share_save_container">';
		$container_wrap_close = '</div>';
	} else { // Is feed
		$container_wrap_open = '<p>';
		$container_wrap_close = '</p>';
		$kit_args['html_container_open'] = '';
		$kit_args['html_container_close'] = '';
		$kit_args['html_wrap_open'] = '';
		$kit_args['html_wrap_close'] = '';
	}
	
	$options['position'] = isset($options['position']) ? $options['position'] : 'bottom';
	
	if ($options['position'] == 'both' || $options['position'] == 'top') {
		// Prepend to content
		$content = $container_wrap_open.ADDTOANY_SHARE_SAVE_KIT($kit_args) . $container_wrap_close . $content;
	}
	if ( $options['position'] == 'bottom' || $options['position'] == 'both') {
		// Append to content
		$content .= $container_wrap_open.ADDTOANY_SHARE_SAVE_KIT($kit_args) . $container_wrap_close;
	}
	
	return $content;
}

// Only automatically output button code after the_title has been called - to avoid premature calling from misc. the_content filters (especially meta description)
add_filter('the_title', 'A2A_SHARE_SAVE_auto_placement', 9);
add_filter('the_content', 'A2A_SHARE_SAVE_add_to_content', 98);


// [addtoany url="http://example.com/page.html" title="Some Example Page"]
function A2A_SHARE_SAVE_shortcode( $attributes ) {
	extract( shortcode_atts( array(
		'url' => 'something',
		'title' => 'something else',
	), $attributes ) );
	$linkname = (isset($attributes['title'])) ? $attributes['title'] : FALSE;
	$linkurl = (isset($attributes['url'])) ? $attributes['url'] : FALSE;
	$output_later = TRUE;

	return '<div class="addtoany_shortcode">'
		. ADDTOANY_SHARE_SAVE_KIT( compact('linkname', 'linkurl', 'output_later') )
		. '</div>';
}

add_shortcode( 'addtoany', 'A2A_SHARE_SAVE_shortcode' );


function A2A_SHARE_SAVE_stylesheet() {
	global $A2A_SHARE_SAVE_options, $A2A_SHARE_SAVE_plugin_url_path;
	
	// Use stylesheet?
	if ($A2A_SHARE_SAVE_options['inline_css'] != '-1' && ! is_admin()) {
		wp_enqueue_style('A2A_SHARE_SAVE', $A2A_SHARE_SAVE_plugin_url_path . '/addtoany.min.css', false, '1.6');
	}
}

add_action('wp_print_styles', 'A2A_SHARE_SAVE_stylesheet');



/*****************************
		CACHE ADDTOANY
******************************/

function A2A_SHARE_SAVE_refresh_cache() {
	$contents = wp_remote_fopen("http://www.addtoany.com/ext/updater/files_list/");
	$file_urls = explode("\n", $contents, 20);
	$upload_dir = wp_upload_dir();
	
	// Make directory if needed
	if ( ! wp_mkdir_p( dirname( $upload_dir['basedir'] . '/addtoany/foo' ) ) ) {
		$message = sprintf( __( 'Unable to create directory %s. Is its parent directory writable by the server?' ), dirname( $new_file ) );
		return array( 'error' => $message );
	}
	
	if (count($file_urls) > 0) {
		for ($i = 0; $i < count($file_urls); $i++) {
			// Download files
			$file_url = $file_urls[$i];
			$file_name = substr(strrchr($file_url, '/'), 1, 99);
			
			// Place files in uploads/addtoany directory
			wp_get_http($file_url, $upload_dir['basedir'] . '/addtoany/' . $file_name);
		}
	}
}

function A2A_SHARE_SAVE_schedule_cache() {
	// WP "Cron" requires WP version 2.1
	$timestamp = wp_next_scheduled('A2A_SHARE_SAVE_refresh_cache');
	if ( ! $timestamp) {
		// Only schedule if currently unscheduled
		wp_schedule_event(time(), 'daily', 'A2A_SHARE_SAVE_refresh_cache');
	}
}

function A2A_SHARE_SAVE_unschedule_cache() {
	$timestamp = wp_next_scheduled('A2A_SHARE_SAVE_refresh_cache');
	wp_unschedule_event($timestamp, 'A2A_SHARE_SAVE_refresh_cache');
}



/*****************************
		OPTIONS
******************************/

if ( is_admin() ) {
	include_once( $A2A_SHARE_SAVE_plugin_dir . '/addtoany.admin.php' );
}

function A2A_SHARE_SAVE_add_menu_link() {
	if ( current_user_can( 'manage_options' ) ) {
		$page = add_options_page(
			'AddToAny: '. __("Share/Save", "add-to-any"). " " . __("Settings")
			, __("AddToAny", "add-to-any")
			, 'activate_plugins' 
			, basename(__FILE__)
			, 'A2A_SHARE_SAVE_options_page'
		);
		
		/* Using registered $page handle to hook script load, to only load in AddToAny admin */
		add_filter( 'admin_print_scripts-' . $page, 'A2A_SHARE_SAVE_scripts' );
	}
}

add_filter( 'admin_menu', 'A2A_SHARE_SAVE_add_menu_link' );

function A2A_SHARE_SAVE_widget_init() {
	global $A2A_SHARE_SAVE_plugin_dir;
	
	include_once( $A2A_SHARE_SAVE_plugin_dir . '/addtoany.widget.php' );
	register_widget( 'A2A_SHARE_SAVE_Widget' );
}

add_action( 'widgets_init', 'A2A_SHARE_SAVE_widget_init' );

// Place in Option List on Settings > Plugins page 
function A2A_SHARE_SAVE_actlinks( $links, $file ){
	// Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	
	if ( ! $this_plugin ) {
		$this_plugin = plugin_basename(__FILE__);
	}
	
	if ( $file == $this_plugin ) {
		$settings_link = '<a href="options-general.php?page=add-to-any.php">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	
	return $links;
}

add_filter( 'plugin_action_links', 'A2A_SHARE_SAVE_actlinks', 10, 2 );
