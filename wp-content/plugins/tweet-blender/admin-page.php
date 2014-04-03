<?php

// Version 4.0.2

// include TweetBlender library
include_once(dirname(__FILE__).'/lib/lib.php');

function tb_admin_load_scripts() {

	global $js_labels;
	
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('jquery-fancybox', '/' . PLUGINDIR . '/tweet-blender/js/fancybox/jquery.fancybox-1.3.4.pack.js',array('jquery'));
    wp_enqueue_script('tb-lib', '/' . PLUGINDIR . '/tweet-blender/js/lib.js',array('jquery'));
    wp_localize_script('tb-lib', 'TB_labels', $js_labels);
    wp_enqueue_script('tb-admin', '/' . PLUGINDIR . '/tweet-blender/js/admin.js',array('jquery','jquery-ui-core','jquery-ui-tabs','jquery-fancybox','tb-lib'));
    
}

function tb_admin_load_styles() {

    wp_enqueue_style('tweet-blender-css', '/' . PLUGINDIR .'/tweet-blender/css/admin.css');
    wp_enqueue_style('jquery-ui-css', '/' . PLUGINDIR . '/tweet-blender/css/jquery-ui/jquery-ui.css');
    wp_enqueue_style('jquery-tabs-css', '/' . PLUGINDIR . '/tweet-blender/css/jquery-ui/ui.tabs.css');
    wp_enqueue_style('jquery-fancybox-css', '/' . PLUGINDIR . '/tweet-blender/js/fancybox/jquery.fancybox-1.3.4.css');
}

// register admin page
add_action('admin_menu', 'tb_admin_menu');
function tb_admin_menu() {

	global $tb_installed_addons, $tb_active_addons, $tb_addons;

	// add hooks for Tweet Blender admin
    $pagehook = add_options_page(__('Tweet Blender Settings'), __('Tweet Blender', 'tweetblender'), 'manage_options', __FILE__, 'tb_admin_page');
    add_action( 'admin_print_scripts-' . $pagehook, 'tb_admin_load_scripts' );
    add_action( 'admin_print_styles-' . $pagehook, 'tb_admin_load_styles' );

	// add hooks for addons
	tb_check_addons();
	foreach($tb_addons as $addon_id => $addon) {
		$addon_file = $addon['slug'] . '/' . $addon['slug'] . '.php';
		
		if ($tb_installed_addons[$addon_id] && $tb_active_addons[$addon_id]) {
			include_once(WP_PLUGIN_DIR . '/' . $addon_file);
		    
			add_action( 'admin_print_scripts-' . $pagehook, 'tb_admin_load_scripts_addon' . $addon_id );
		    add_action( 'admin_print_styles-' . $pagehook, 'tb_admin_load_styles_addon' . $addon_id );
		}
	}
}

function tb_admin_page() {

    global $wp_json, $tb_option_names, $tb_option_names_system, $tb_keep_tweets_options, $tb_languages, $cache_clear_results, $tb_throttle_time_options, $tb_installed_addons, $tb_active_addons, $tb_package_names;
            
	$upgrade_message = '';

	// if add-on installation is requested, perform it
	if (isset($_GET['install_addon'])) {
		
		// get item number
		if (isset($_POST['item_number'])) {
			$item_number = $_POST['item_number'];
		}
		elseif (isset($_GET['item_number'])) {
			$item_number = $_GET['item_number'];
		}
		else {
			echo __('Error','tweetblender') . ': ' . __('addon ID was not specified', 'tweetblender');
		}

		// store transaction ID to prefs for future auto updates
		if (isset($_POST['txn_id']) && $item_number) {
			tb_save_txn_id($item_number, $_POST['txn_id']);
		}

		// perform installation
		tb_download_package($item_number);		
		
		return;
	}
	// check for new versions of addons if we haven't checked recently
	elseif (!get_transient('tb_addon_checked_upgrade')) {

		foreach($tb_package_names as $item_number => $name) {
			
			// if user purchased the addon
			if ($txn_id = tb_get_txn_id($item_number)) {

				$version_check_url = 'http://tweetblender.com/check_upgrade.php?item_number=' . $item_number . '&blog_url=' . urlencode(get_bloginfo('url')) . '&txn_id=' . $txn_id;
				$version_download_url = 'http://tweetblender.com/download.php?item_number=' . $item_number . '&blog_url=' . urlencode(get_bloginfo('url')) . '&txn_id=' . $txn_id;
				$response = wp_remote_get($version_check_url);
				
				if(!is_wp_error($response)) {
					if (isset($response['headers']['have-newer-version']) && $response['headers']['have-newer-version'] == 1) {
						$upgrade_message .= sprintf(__('Newer version of %s is available','tweetblender') . ' - <a href="%s&install_addon=1&item_number=%d">' . __('upgrade automatically','tweetblender') . '</a> ' . __('or','tweetblender') . '<a href="%s"> ' . __('download to install manually','tweetblender') . '</a>', $name, tb_get_current_page_url(), $item_number, $version_download_url);
					}
				}
			}
		}

		// don't check again for 24 hours
		set_transient('tb_addon_checked_upgrade', true, 60*60*24);
	}

    // Read in existing option values from database
	$tb_o = get_option('tweet-blender');
		
	// set defaults
	if (empty($tb_o) || (isset($tb_o['archive_tweets_num']) && $tb_o['archive_tweets_num'] < 1)) {
		$tb_o['archive_tweets_num'] = 20;
	}

	// get API limit info
	if ($have_api_limit_data = tb_get_server_rate_limit_data($tb_o)) {
		// refresh options so we get the new limit info
		$tb_o = get_option('tweet-blender');	
	}
	
	// perform maintenance
	if (isset($tb_o['archive_keep_tweets']) && $tb_o['archive_keep_tweets'] > 0) {
		tb_db_cache_clear('WHERE DATEDIFF(now(),created_at) > ' . $tb_o['archive_keep_tweets']);
	}
					
    // See if the user has posted us some information
	if( isset($_POST['tb_new_data']) && $_POST['tb_new_data'] == 'Y' ) {

		// check nonce
		check_admin_referer('tweet_blender_settings_save','tb_nonce');

		// if we are disabling cache (wasn't disabled before but now is) - clear it
		if (isset($tb_o['advanced_disable_cache']) && (!$tb_o['advanced_disable_cache'] && isset($_POST['advanced_disable_cache']))) {
			tb_db_cache_clear();
		}
		// if we are clearing individual cached sources
		if (isset($_POST['delete_cache_src']) && $_POST['delete_cache_src']) {
			$cleared_sources = array();
			foreach ($_POST['delete_cache_src'] as $del_src) {
				tb_db_cache_clear("WHERE source='$del_src'");
				$cleared_sources[] = $del_src;
			}
			if (sizeof($cleared_sources) > 0 ) {
				$cache_clear_results = __('Cleared cached tweets for', 'tweetblender') . ' ' . implode(', ',$cleared_sources);
			}
		}
		
		// check if we are rerouting with oAuth
		if ((isset($_POST['advanced_reroute_on']) && $_POST['advanced_reroute_on']) && (isset($_POST['advanced_reroute_type']) && $_POST['advanced_reroute_type'] == 'oauth')) {
			
			if(!isset($tb_o['oauth_access_token'])) {
				// Create TwitterOAuth object and get request token
				$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
				 
				// Get request token
				$request_token = $connection->getRequestToken(get_bloginfo('url') . '/' . PLUGINDIR . "/tweet-blender/lib/twitteroauth/callback.php");
				 
				if ($connection->http_code == 200) {
					// Save request token to session
					$tb_o['oauth_token'] = $token = $request_token['oauth_token'];
					$tb_o['oauth_token_secret'] = $request_token['oauth_token_secret'];
					update_option('tweet-blender',$tb_o);
					
					$errors[] = __('To take advantage of a whitelisted account with oAuth ' , 'tweetblender') . "<a href='javascript:tAuth(\"" . $connection->getAuthorizeURL($token) . "\")' title=__('Authorize Twitter Access', 'tweetb)>__('please use your Twitter account to authorize access','tweetblender')</a>.";
				}
				else {
					$errors[] =  __("Not able to form oAuth authorization request URL. HTTP status code: ", 'tweetblender') . $connection->http_code;
				}					
			}
		}

		if (isset($errors) && sizeof($errors) > 0) {
			$message = '<div class="error"><strong><ul><li>' . join('</li><li>',$errors) . '</li></ul>' . $cache_clear_results . '</strong></div>';
			$tb_o = $_POST;
		}
		else {
			// reset oAuth tokens
			if (isset($_POST['reset_oauth']) && $_POST['reset_oauth']) {
				unset($tb_o['oauth_access_token']);
			}

			// unset archive page ID if archive is disabled
			if(isset($_POST['archive_is_disabled']) && $_POST['archive_is_disabled']) {
				unset($tb_o['archive_page_id']);
				unset($tb_o['archive_is_disabled']);
			}
			
			// cycle through all possible options
			foreach($tb_option_names as $opt) {
				// if option was set by user - store it
				if(isset($_POST[$opt])) {
					$tb_o[$opt] = $_POST[$opt];
				}
				// else if option was not one that user controls - unset it
				elseif (!array_key_exists($opt,$tb_option_names_system)) {
					$tb_o[$opt] = '';
				}
			}

			// process filter keywords and remove empty elements
			if (isset($tb_o['filter_bad_strings'])) {
				$filtered_words = explode(',', $tb_o['filter_bad_strings']);
				
				$clean = array();
				foreach ($filtered_words as $badw) {
					$badw = trim($badw);
					if (strlen($badw) > 0) {
						$clean[] = $badw;
					}
				}
				
				$tb_o['filter_bad_strings'] = implode(',',$clean);
			}

			update_option('tweet-blender',$tb_o);
	        // Put an options updated message on the screen
			$message = '<div class="updated"><p><strong>' . __('Settings saved', 'tweetblender')  . '. ' . $cache_clear_results . '</strong></p></div>';
		}	

    }
	
	// if addon installation was cancelled by user, show message
	if (isset($_GET['install_addon']) && $_GET['install_addon'] == 0) {
		$message = '<div class="updated"><p><strong>'. __('Addon installation was cancelled','tweetblender') . '</strong> ' . __('If you have any questions, please use one of the links under the Help tab', 'tweetblender') . '.</p></div>';
	}

?>

<script type="text/javascript">
	var lastUsedTabId = <?php if (isset($_POST['tb_tab_index'])) { echo intval($_POST['tb_tab_index']); } else { echo 0; } ?>,
	TB_pluginPath = '<?php echo plugins_url("tweet-blender") ?>',
	TB_CM_pluginPath = '<?php echo plugins_url('tweet-blender-cache-manager'); ?>',
	TB_cacheManagerAvailable = <?php if ($tb_installed_addons[1] && $tb_active_addons[1]) { echo 'true'; } else { echo 'false'; } ?>,
	TB_NS_pluginPath = '<?php echo plugins_url('tweet-blender-nstyle'); ?>',
	TB_nStyleAvailable = <?php if ($tb_installed_addons[2] && $tb_active_addons[2]) { echo 'true'; } else { echo 'false'; } ?>;
	TB_C_pluginPath = '<?php echo plugins_url('tweet-blender-charts'); ?>',
	TB_chartsAvailable = <?php if ($tb_installed_addons[3] && $tb_active_addons[3]) { echo 'true'; } else { echo 'false'; } ?>;
</script>

<div class="wrap">
	<div id="icon-tweetblender" class="icon32"><br/></div><h2><?php _e('Tweet Blender', 'tweetblender' ); ?></h2>

	<?php 
		if (!empty($upgrade_message)) { echo '<div class="updated"><p>' . $upgrade_message . '</p></div>'; }
		if (!empty($message)) { echo $message; }  if (!empty($log_msg)) { echo "<!-- $log_msg -->"; } 
	?>
	 
	<div id="tabs">
    <ul>
        <li><a href="#tab-1"><span><?php _e('Widgets', 'tweetblender'); ?></span></a></li>
        <li><a href="#tab-2"><span><?php _e('Archive', 'tweetblender'); ?></span></a></li>
        <li><a href="#tab-3"><span><?php _e('Filters', 'tweetblender'); ?></span></a></li>
        <li><a href="#tab-4"><span><?php _e('SEO', 'tweetblender'); ?></span></a></li>
        <li><a href="#tab-5"><span><?php _e('Advanced', 'tweetblender'); ?></span></a></li>
        <li id="statustab"><a href="#tab-6"><span><?php _e('Status', 'tweetblender'); ?></span></a></li>
        <li id="cache-manager-tab"><a href="#tab-7"><span><?php _e('Cache [+]', 'tweetblender'); ?></span></a></li>
    <!--    <li id="charts-tab"><a href="#tab-8"><span><?php _e('Charts [+]', 'tweetblender'); ?></span></a></li> -->
        <li><a href="#tab-8"><span><?php _e('Help', 'tweetblender'); ?></span></a></li>
    </ul>

	<form name="settings_form" id="settings_form" method="post" action="<?php echo str_replace( '%7E', '~', esc_attr($_SERVER['REQUEST_URI'])); ?>">
	<input type="hidden" id="tb_new_data" name="tb_new_data" value="Y" />
	<input type="hidden" id="tb_tab_index" name="tb_tab_index" value="" />
	<?php
	if ( function_exists('wp_nonce_field') )
		wp_nonce_field('tweet_blender_settings_save','tb_nonce');
	?>


    <div id="tab-1">
    <!-- !Widgets Settings -->
		<table class="form-table">
		<tr valign="top">
			<th class="th-full" colspan="2" scope="row">
			<label for="widget_check_sources">
			<input type="checkbox" name="widget_check_sources" <?php checked('on', $tb_o['widget_check_sources']); ?>/>
			<?php _e("Check and verify sources when widget settings are saved", 'tweetblender' ); ?>
			</label>
			</th>
		</tr>
		<tr valign="top">
			<th class="th-full" colspan="2" scope="row">
			<label for="widget_show_header">
			<input type="checkbox" name="widget_show_refresh" <?php checked('on', $tb_o['widget_show_refresh']); ?>/>
			<?php _e("Show refresh button for each widget", 'tweetblender' ); ?>
			</label>
			</th>
		</tr>
		<tr valign="top">
			<th class="th-full" colspan="2" scope="row">
			<label for="widget_show_source">
			<input type="checkbox" name="widget_show_source" <?php checked('on', $tb_o['widget_show_source']); ?>/>
			<?php _e("Show tweet source for each tweet", 'tweetblender' ); ?>
			</label>
			</th>
		</tr>
		</table>
	</div>
	
    <div id="tab-2">
	<!-- !Archive Page Settings -->
		<table class="form-table" id="archivesettings">
		<tr valign="top">
			<th class="th-full" colspan="2" scope="row">
			<label for="archive_is_disabled">
			<input type="checkbox" id="archive_is_disabled" name="archive_is_disabled" <?php checked('on', $tb_o['archive_is_disabled']); ?>/>
			<?php _e('Disable archive page', 'tweetblender' ); ?> 
			</label>
			</th>
		</tr>
		<tr valign="top" <?php if (isset($tb_o['archive_is_disabled']) && $tb_o['archive_is_disabled']) echo 'style="display:none;"'; ?>>
			<th class="th-full" colspan="2" scope="row">
			<label for="archive_auto_page">
			<input type="checkbox" id="archive_auto_page" name="archive_auto_page" <?php checked('on', $tb_o['archive_auto_page']); ?>/>
			<?php _e('Create archive page automatically', 'tweetblender' ); ?> 
			</label>
			</th>
		</tr>
		<tr valign="top" <?php if (isset($tb_o['archive_is_disabled']) && $tb_o['archive_is_disabled']) echo 'style="display:none;"'; ?>>
			<th scope="row"><label for="archive_tweets_num"><?php _e('Maximum number of tweets to show', 'tweetblender' ); ?>:
			</label></th>
			<td>
			<select name="archive_tweets_num">
				<?php
				for ($i = 10; $i <= 90; $i+=10) {
					echo '<option value="' . $i . '"';
					if (isset($tb_o['archive_tweets_num']) && $i == $tb_o['archive_tweets_num']) {
						echo ' selected';
					}
					echo '>' . $i . '</option>';
				}
				for ($i = 100; $i <= 500; $i+=100) {
					echo '<option value="' . $i . '"';
					if ($i == $tb_o['archive_tweets_num']) {
						echo ' selected';
					}
					echo '>' . $i . '</option>';
				}
			?></select></td>
		</tr>
		<tr valign="top" <?php if (isset($tb_o['archive_is_disabled']) && $tb_o['archive_is_disabled']) echo 'style="display:none;"'; ?>>
			<th scope="row"><label for="archive_keep_tweets"><?php _e('Automatically remove tweets that are older than', 'tweetblender' ); ?>:
			</label></th>
			<td>
			<select name="archive_keep_tweets">
			<?php
				foreach ($tb_keep_tweets_options as $name => $days) {
					echo '<option value="' . $days . '"';
					if (isset($tb_o['archive_keep_tweets']) && $days == $tb_o['archive_keep_tweets']) {
						echo ' selected';
					}
					echo '>' . $name . '</option>';
				}
			?></select></td>
		</tr>
		<tr valign="top" <?php if (isset($tb_o['archive_is_disabled']) && $tb_o['archive_is_disabled']) echo 'style="display:none;"'; ?>>
			<th class="th-full" colspan="2" scope="row">
			<label for="archive_show_source_selector">
			<input type="checkbox" name="archive_show_source_selector" <?php checked('on', $tb_o['archive_show_source_selector']); ?>/>
			<?php _e("Show source selector in the header", 'tweetblender' ); ?>
			</label>
			</th>
		</tr>
		<tr valign="top" <?php if (isset($tb_o['archive_is_disabled']) && $tb_o['archive_is_disabled']) echo ' style="display:none;"'; ?>>
			<th class="th-full" colspan="2" scope="row">
			<label for="archive_show_source">
			<input type="checkbox" name="archive_show_source" <?php checked('on', $tb_o['archive_show_source']); ?>/>
			<?php _e("Show tweet source for each tweet", 'tweetblender' ); ?>
			</label>
			</th>
		</tr>
		</table>
	</div>
	
	<div id="tab-3">
	<!-- !Filtering -->
		<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="filter_limit_per_source"><?php _e('Throttling', 'tweetblender' ); ?>:</label></th>
			<td>
			<?php 
				$throttle_select1 = '<select name="filter_limit_per_source"><option value="">' . __('All', 'tweetblender') . '</option>';
				foreach (range(1,10) as $lim) {
					$throttle_select1 .= '<option value="' . $lim . '"';
					if (isset($tb_o['filter_limit_per_source']) && $lim == $tb_o['filter_limit_per_source']) {
						$throttle_select1 .= ' selected';
					}
					$throttle_select1 .= '>' . $lim . '</option>';
				}
				$throttle_select1 .= '</select>';
				
				echo sprintf(__('For each user show a maximum of %s tweet(s)', 'tweetblender'), $throttle_select1);
			?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="filter_lang"><?php _e('Show only tweets in', 'tweetblender' ); ?>:</label></th>
			<td>
			<select name="filter_lang">
				<?php
				foreach ($tb_languages as $code => $lang) {
					echo '<option value="' . $code . '"';
					if (isset($tb_o['filter_lang']) && $code == $tb_o['filter_lang']) {
						echo ' selected';
					}
					echo '>' . $lang . '</option>';
				}
			?></select>
			</td>
		</tr>
		<tr valign="top">
	 		<th class="th-full" colspan="2" scope="row">
			<input type="checkbox" name="filter_hide_same_text" <?php checked('on', $tb_o['filter_hide_same_text']); ?>/>
			<label for="filter_hide_same_text"><?php _e("Hide tweets that come from different users but have exactly the same text", 'tweetblender' ); ?></label>
			</th>
		</tr>
		<tr valign="top">
	 		<th class="th-full" colspan="2" scope="row">
			<input type="checkbox" id="filter_hide_replies" name="filter_hide_replies" <?php checked('on', $tb_o['filter_hide_replies']); ?>/>
			<label for="filter_hide_replies"><?php _e("Hide replies", 'tweetblender' ); ?></label>
			</th>
		</tr>
		<tr valign="top">
	 		<th class="th-full" colspan="2" scope="row">
			<input type="checkbox" id="filter_hide_retweets" name="filter_hide_retweets" <?php checked('on', $tb_o['filter_hide_retweets']); ?>/>
			<label for="filter_hide_retweets"><?php _e("Hide retweets", 'tweetblender' ); ?></label>
			</th>
		</tr>
		<tr valign="top">
	 		<th class="th-full" colspan="2" scope="row">
			<input type="checkbox" id="filter_hide_not_replies" name="filter_hide_not_replies" <?php checked('on', $tb_o['filter_hide_not_replies']); ?>/>
			<label for="filter_hide_not_replies"><?php _e("Show only replies", 'tweetblender' ); ?></label>
			</th>
		</tr>
		<tr valign="top">
	 		<th class="th-full" colspan="2" scope="row">
			<input type="checkbox" name="filter_hide_mentions" <?php checked('on', $tb_o['filter_hide_mentions']); ?>/>
			<label for="filter_hide_mentions"><?php _e("Hide mentions of users, only show tweets from users themselves", 'tweetblender' ); ?></label>
			</th>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="filter_bad_strings"><?php _e('Exclude tweets that contain these users, words or hashtags', 'tweetblender' ); ?>: </label>
			</th>
			<td valign="top">
			<textarea id="filter_bad_strings" name="filter_bad_strings" rows=2 cols=60><?php if (isset($tb_o['filter_bad_strings'])) { echo stripslashes($tb_o['filter_bad_strings']); } ?></textarea> 
				<br/>
				<span class="setting-description"><?php _e('You can use single keywords, usernames, or phrases. Do not use @ for screen names. Separate with commas. Example: #spam,badword,entire bad phrase,badUser,anotherBadUser,#badHashTag', 'tweetblender' ); ?></span>
			</td>
		</tr>
		</table>
	</div>

    <div id="tab-4">
    <!-- !SEO -->
		<table class="form-table">
		<tr valign="top">
			<th class="th-full" colspan="2" scope="row">
			<label for="general_seo_tweets_googleoff">
			<input type="checkbox" name="general_seo_tweets_googleoff" <?php checked('on', $tb_o['general_seo_tweets_googleoff']); ?>/>
			<?php _e('Wrap all tweets with googleoff/googleon tags to prevent indexing', 'tweetblender' ); ?>
			</label>
			</th>
		</tr>
		<tr valign="top">
			<th class="th-full" colspan="2" scope="row">
			<label for="general_seo_footer_googleoff">
			<input type="checkbox" name="general_seo_footer_googleoff" <?php checked('on', $tb_o['general_seo_footer_googleoff']); ?>/>
			<?php _e('Wrap footer with date and time in all tweets with googleoff/googleon tags to prevent indexing', 'tweetblender' ); ?>
			</label>
			</th>
		</tr>
		</table>
	</div>
	
	<div id="tab-5">
	<!-- !Advanced Settings -->
		<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="general_timestamp_format"><?php _e('Timestamp Format', 'tweetblender' ); ?>:
			</label></th>
			<td><input type="text" name="general_timestamp_format" value="<?php if (isset($tb_o['general_timestamp_format'])) { echo $tb_o['general_timestamp_format']; } ?>"/> <span class="setting-description"><br/>
				<?php _e('leave blank = verbose from now ("4 minutes ago")', 'tweetblender' ); ?><br/>
				<?php _e('h = 12-hour format of an hour with leading zeros ("08")', 'tweetblender' ); ?><br/>
				<?php _e('i = Minutes with leading zeros ("01")', 'tweetblender' ); ?><br/>
				<?php _e('s = Seconds, with leading zeros ("01")', 'tweetblender' ); ?><br/>
				<a href="http://php.net/manual/en/function.date.php"><?php _e('additional format options', 'tweetblender' ); ?> &raquo;</a>
			</span></td>
		</tr>
		<?php if(isset($tb_o['oauth_access_token'])) { ?>
		<tr valign="top">
			<th class="th-full" colspan="2" scope="row">
			<label for="reset_oauth">
			<input type="checkbox" name="reset_oauth" value="1">
			<?php _e('Clear oAuth Access Tokens', 'tweetblender' ); ?> 
			</label><br/>
			<span class="setting-description">
				<?php _e('Twitter API v1.1 now requires authentication for every request. To get tweets, Tweet Blender needs to login to Twitter using your credentials. Once you authorize access, the special tokens are stored in the configuration settings. This is NOT a username or password. Your username/password is NOT stored.  The tokens are tied to a specific Twitter account so if you changed your account or would like to use another account for authentication check this box to have previously saved tokens cleared.', 'tweetblender' ); ?>
			</span>
			</th>
		</tr>
		<?php } ?>
		<tr valign="top">
			<th class="th-full" colspan="2" scope="row">
			<label for="advanced_no_search_api">
			<input type="checkbox" name="advanced_no_search_api" <?php checked('on', $tb_o['advanced_no_search_api']); ?>/>
			<?php _e('Do not use search API for screen names', 'tweetblender' ); ?> 
			</label><br/>
			<span class="setting-description">
				<?php _e("To get tweets for screen names Tweet Blender relies on Twitter Search API; however, sometimes Twitter's search does not return any tweets for a particular account due to some complex internal relevancy rules. If you see tweets for a user when looking at http://twitter.com/{someusername} but you do not see tweets for that same user when you look at http://search.twitter.com/search?q={@someusername} you can try to check this box and have Tweet Blender switch to a different API to retrieve recent tweets.", 'tweetblender' ); echo '<b>' . __('Important','tweetblender') . ': ' . __('screen names with modifiers (e.g. @user|#topic), keywords, and hastagas will still use Search API','tweetblender') . '</b>.'; ?>
			</span>
			</th>
		</tr>
		</table>
	</div>

	<div id="tab-6">
	<!-- !Status -->
		<table class="form-table">
		<tr>
			<th><?php _e('API requests from blog server', 'tweetblender' ); ?>:</th>
			<td><?php
			
				if ($have_api_limit_data && isset($tb_o['rate_limit_data'])) {
					
					foreach ($tb_o['rate_limit_data'] as $endpoint_name => $endpoint_data) {
					
						if ($endpoint_name == 'last_check') {
							echo '<i>' . __('Last check ', 'tweetblender') . tb_verbal_time($endpoint_data) . '</i>';
							
						}
						else {
					
							echo '<b>' . $endpoint_name . ':</b> ';
							echo sprintf(__('Max is %d per 15 minutes', 'tweetblender') . ' &middot; ', $endpoint_data['limit']);
							if ($endpoint_data['remaining'] > 0) {
								echo sprintf(__('You have %s left', 'tweetblender') . ' &middot; ','<span class="pass">' . $endpoint_data['remaining'] . '</span>') ;
							}
							else {
								echo sprintf(__('You have %s left', 'tweetblender') . ' &middot; ', '<span class="fail">'. 0 . '</span>');
							}
							echo __('Next reset', 'tweetblender') . ' ' . tb_verbal_time($endpoint_data['reset']);
							echo '<br />';
														
						}

					}
				}
				else {
					echo '<span class="fail">' . __('Check failed', 'tweetblender') . '</span>';
				}
				
			?></td>
		</tr>
		<tr>
			<th><?php _e('oAuth Access Tokens', 'tweetblender' ); ?>:</th>
			<td><?php 
			if(isset($tb_o['oauth_access_token'])) {
				echo '<span class="pass">' . __('Present', 'tweetblender') . '</span>';
			}
			else {
				echo '<span class="fail">' . __('Not Present', 'tweetblender') . '</span>';
				if (class_exists('TwitterOAuth')) {
					// Create TwitterOAuth object and get request token
					$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
					 
					// Get request token
					$request_token = $connection->getRequestToken(get_bloginfo('url') . '/' . PLUGINDIR . "/tweet-blender/lib/twitteroauth/callback.php");
					 
					if ($connection->http_code == 200) {
						// Save request token to session
						$tb_o['oauth_token'] = $token = $request_token['oauth_token'];
						$tb_o['oauth_token_secret'] = $request_token['oauth_token_secret'];
						update_option('tweet-blender',$tb_o);
						
						echo " <a href='javascript:tAuth(\"" . $connection->getAuthorizeURL($token) . "\")' title=" . __('Authorize Twitter Access', 'tweetblender') . ">" . __('Use your Twitter account to login', 'tweetblender') . "</a>.";
		
					}
				}
			}
			?></td>
		</tr>
		<tr>
			<th><?php _e('Cache', 'tweetblender' ); ?>:</th>
			<td>
				<?php	
				
				if ($cached_sources = tb_get_cache_stats()) {
  					// Output each opened file and then close
					foreach ((array)$cached_sources as $cache_src) {
						$s = __("tweet",'tweetblender');
						if ($cache_src->tweets_num != 1) {
							$s = __("tweets",'tweetblender');
						}
						echo '<input type="checkbox" name="delete_cache_src[]" value="' . esc_attr($cache_src->source) . '" /> ';					
				       	echo urldecode($cache_src->source) . " - " . $cache_src->tweets_num . " $s - " . __("updated",'tweetblender') . ' ' . tb_verbal_time($cache_src->last_update) . '<br/>';
					}
					echo '<label for="delete_cache_src[]"> &nbsp;&uarr; ' . __('Check the boxes above to clear cached tweets from the database', 'tweetblender') . '</label>';
				}
				elseif (!isset($tb_o['advanced_disable_cache']) || $tb_o['advanced_disable_cache'] == false) {
					echo '<span class="fail">' . __('no cached tweets found and caching is ON', 'tweetblender') . '</span>';
				}
				else {
					echo '<span class="pass">' . __('no cached tweets found and caching is OFF', 'tweetblender') . '</span>';
				}

				?>
			</td>
		</tr>
		</table>
	</div>

	</form>

	<div id="tab-7">
	<!-- !Cache Manager Addon -->
	<?php 
		// if Cache Manager is not installed
		if (!$tb_installed_addons[1]) { 
	?>
	
	<h2><?php _e('Cache Manager Is Not Installed', 'tweetblender' ); ?></h2>
	<div class="box-left">
	<p><?php _e('Install Cache Manager addon for Tweet Blender and instantly take advantage of the following features', 'tweetblender' ); ?>:</p>
	<ol class="feature-set">
		<li><?php _e('See all the tweets stored in your cache database', 'tweetblender' ); ?></li>
		<li><?php _e('Delete individual tweets or groups of tweets', 'tweetblender' ); ?></li>
		<li><?php _e('Backup and restore your cache', 'tweetblender' ); ?></li>
	</ol>
	<p><?php echo sprintf(__('Click the button below to purchase the addon for a <b>one time flat fee of $%s</b>. This will perform a one-click install of a new plugin and you will get FREE upgrades with new features in the future.', 'tweetblender' ),'2.99'); ?></p>
	<div class="centered">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick" />
		<input type="hidden" name="business" value="tweetblender@gmail.com" />
		<input type="hidden" name="lc" value="US" />
		<input type="hidden" name="currency_code" value="USD" />
		<input type="hidden" name="no_note" value="1" />
		<input type="hidden" name="amount" value="2.99" />
		<input type="hidden" name="item_name" value="Cache Manager for Tweet Blender" />
		<input type="hidden" name="item_number" value="1" />
		<input type="hidden" name="no_shipping" value="1" />
		<input type="hidden" name="custom" value="<?php bloginfo('url'); ?>" />
		<input type="hidden" name="notify_url" value="http://tweetblender.com/ipn.php" />
		<input type="hidden" name="image_url" value="http://tweetblender.com/tweet-blender-logo_150x50.png" />
		<input type="hidden" name="return" value="<?php echo tb_get_current_page_url(); ?>&install_addon=1" />
		<input type="hidden" name="cbt" value="Return to your site to complete installation" />
		<input type="hidden" name="cancel_return" value="<?php echo tb_get_current_page_url(); ?>#tab-7" />
		<input type="submit" name="submit" class="button-secondary" value="<?php _e('Get Cache Manager', 'tweetblender' ); ?>" />
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
	</form>
	
	</div>
	</div>
	<div class="box-right centered">
		<a href="<?php echo plugins_url('tweet-blender/screenshot-12.png'); ?>" title="Cache Manager for Tweet Blender"><img class="tb-addon-screenshot" src="<?php echo plugins_url('tweet-blender/img/cache_manager_th.jpg'); ?>" /></a>
	</div>
	<br clear="all" />
	<?php 
		// if Cache Manager is not active
		} else if(!$tb_active_addons[1]) { 
	?>
	<h2><?php _e('Cache Manager Is Not Active', 'tweetblender' ); ?></h2>
	<p><?php _e('You have the Cache Manager plugin installed but not activated. Please use the [Plugins] menu on the left to activate the plugin', 'tweetblender' ); ?></p>

	<?php
		// else Cache Manager is available
		} else {
			echo tb_cm_get_cache_page_html();
		} 
	?>
	
	</div>

	<!-- 
	<div id="tab-8">
	<?php 
		// if Charts addon is not installed
		if (!$tb_installed_addons[3]) { 
	?>
	
	<h2><?php _e('Charts Are Not Installed', 'tweetblender' ); ?></h2>
	<div class="box-left">
	<p><?php _e('Install Tweet Blender Charts and instantly take advantage of the following features', 'tweetblender' ); ?>:</p>
	<ol class="feature-set">
		<li><?php _e('Make your site more visually attractive by adding pie and bar charts of your cached tweets', 'tweetblender' ); ?></li>
		<li><?php _e('Embed charts in the sidebars, posts and pages', 'tweetblender' ); ?></li>
		<li><?php _e('Define width and height of charts or make them fill available space', 'tweetblender' ); ?></li>
		<li><?php _e('Limit charts data to last month, week, day or hour', 'tweetblender' ); ?></li>
		<li><?php _e('Create both vertical and horizontal bar charts', 'tweetblender' ); ?></li>
		<li><?php _e('Automatically link each bar or pie segment to your archive page', 'tweetblender' ); ?></li>
		<li><?php _e('Make charts refresh data every X seconds to see how trends change over time', 'tweetblender' ); ?></li>
		<li><?php _e('Limit charts data to monthly, weekly, or hourly', 'tweetblender' ); ?></li>
	</ol>
	<p><?php echo sprintf(__('Click the button below to purchase the addon for a <b>one time flat fee of $%s</b>. This will perform a one-click install of a new plugin and you will get FREE upgrades with new features in the future.', 'tweetblender' ),'4.99'); ?></p>
	<div class="centered">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick" />
		<input type="hidden" name="business" value="tweetblender@gmail.com" />
		<input type="hidden" name="lc" value="US" />
		<input type="hidden" name="currency_code" value="USD" />
		<input type="hidden" name="no_note" value="1" />
		<input type="hidden" name="amount" value="4.99" />
		<input type="hidden" name="item_name" value="Tweet Blender Charts" />
		<input type="hidden" name="item_number" value="3" />
		<input type="hidden" name="no_shipping" value="1" />
		<input type="hidden" name="custom" value="<?php bloginfo('url'); ?>" />
		<input type="hidden" name="notify_url" value="http://tweetblender.com/ipn.php" />
		<input type="hidden" name="image_url" value="http://tweetblender.com/tweet-blender-logo_150x50.png" />
		<input type="hidden" name="return" value="<?php echo tb_get_current_page_url(); ?>&install_addon=3" />
		<input type="hidden" name="cbt" value="Return to your site to complete installation" />
		<input type="hidden" name="cancel_return" value="<?php echo tb_get_current_page_url(); ?>#tab-7" />
		<input type="submit" name="submit" class="button-secondary" value="<?php _e('Get Charts', 'tweetblender' ); ?>" />
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
	</form>
	
	</div>
	</div>
	<div class="box-right centered">
		<a href="<?php echo plugins_url('tweet-blender/screenshot-10.png'); ?>" title="Horizontal Bar Charts"><img class="tb-addon-screenshot" src="<?php echo plugins_url('tweet-blender/img/cache_manager_th.jpg'); ?>" /></a>
		<a href="<?php echo plugins_url('tweet-blender/screenshot-10.png'); ?>" title="Pie Charts"><img class="tb-addon-screenshot" src="<?php echo plugins_url('tweet-blender/img/cache_manager_th.jpg'); ?>" /></a>
		<a href="<?php echo plugins_url('tweet-blender/screenshot-10.png'); ?>" title="Widget Configuration"><img class="tb-addon-screenshot" src="<?php echo plugins_url('tweet-blender/img/cache_manager_th.jpg'); ?>" /></a>
		<a href="<?php echo plugins_url('tweet-blender/screenshot-10.png'); ?>" title="Admin Section"><img class="tb-addon-screenshot" src="<?php echo plugins_url('tweet-blender/img/cache_manager_th.jpg'); ?>" /></a>
	</div>
	<br clear="all" />
	<?php 
		// if Charts addon is not active
		} else if(!$tb_active_addons[3]) { 
	?>
	<h2><?php _e('Charts Are Not Active', 'tweetblender' ); ?></h2>
	<p><?php _e('You have the Tweet Blender Charts plugin installed but not activated. Please use the [Plugins] menu on the left to activate the plugin', 'tweetblender' ); ?></p>

	<?php
		// else Charts are available
		} else {
			echo tb_c_get_page_html();
		} 
	?>
	
	</div>
	-->
	
	<div id="tab-8">
	<!-- !Support -->
	<?php _e('GetSatisfaction.com Community', 'tweetblender' ); ?>: <a href="http://getsatisfaction.com/tweet_blender">http://getsatisfaction.com/tweet_blender</a><br/>
	<?php _e('Facebook Page', 'tweetblender' ); ?>: <a href="http://www.facebook.com/pages/Tweet-Blender/96201618006">http://www.facebook.com/pages/Tweet-Blender/96201618006</a><br/>
	<?php _e('Twitter', 'tweetblender' ); ?>: <a href="http://twitter.com/tweetblender"> http://twitter.com/tweetblender</a><br/>
	<?php _e('Homepage', 'tweetblender' ); ?>: <a href="http://www.tweetblender.com">http://www.tweetblender.com</a><br/>
	</div>

	</div>
	
	

	<p class="submit">
	<input id="btn_save_settings" type="button" class="button-primary" value="<?php _e('Save Settings', 'tweetblender' ) ?>" />
	</p>
</div>

<?php
 
}

?>