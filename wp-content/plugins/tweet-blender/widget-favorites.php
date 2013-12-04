<?php

// version 4.0.0

class TweetBlenderFavorites extends WP_Widget {
	
	// constructor	 
	function TweetBlenderFavorites() {
		parent::WP_Widget('tweetblenderfavorites', __('Tweet Blender Favorites', 'tweetblender'), array('description' => __('Shows favorite tweets for one or multiple @users', 'tweetblender')));	
	}
 
	// display widget	 
	function widget($args, $instance) {

		global $post;
		if (sizeof($args) > 0) {
			extract($args, EXTR_SKIP);			
		}
		$tb_o = get_option('tweet-blender');
		
		// find out id/url of the archive page
		$archive_post_id = tb_get_archive_post_id();
		$archive_page_url = $instance['widget_view_more_url'];
		if (!$archive_page_url && $archive_post_id > 0) {
			$archive_page_url = get_permalink($archive_post_id);
		}
		
		// don't show widget on the archive page
		if ($post == null || ($post->ID != $archive_post_id && $archive_page_url != "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])) {

			echo $before_widget;
			$instance['title'] = trim($instance['title']);
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			
			$sources = preg_split('/[\s+\n\r]/m',trim($instance['widget_sources']));

			// set "view more" text
			if(isset($instance['widget_view_more_text']) && $instance['widget_view_more_text'] != '') {
				$view_more_text = $instance['widget_view_more_text'];
			} 
			else {
				$view_more_text = __("view more", 'tweetblender');
			}
			
			// add configuraiton options
			echo '<form id="' . $this->id . '-f" class="tb-widget-configuration" action="#"><div>';
			echo '<input type="hidden" name="sources" value="' . join(',',$sources) . '" />';
			echo '<input type="hidden" name="refreshRate" value="' . $instance['widget_refresh_rate'] . '" />';
			echo '<input type="hidden" name="tweetsNum" value="' . $instance['widget_tweets_num'] . '" />';
			echo '<input type="hidden" name="viewMoreText" value="' . esc_attr($view_more_text) . '" />';
			echo '<input type="hidden" name="viewMoreUrl" value="' . $archive_page_url . '" />';
			echo '<input type="hidden" name="favoritesOnly" value="true" />';
			echo '</div></form>';
						
			// print out header and list of tweets
			echo '<div id="'. $this->id . '-mc">';
			echo tb_create_markup($mode = 'widget',$instance,$this->id,$tb_o);

			echo '<div class="tb_footer">';
			if(!$tb_o['archive_is_disabled'] || $tb_o['archive_is_disabled'] == false) {
				
				// indicate that using default url
				$default = '';
				if (!$instance['widget_view_more_url']) {
					$default = ' defaultUrl';
				}
				if ($archive_page_url != '') {
					echo '<a class="tb_archivelink' . $default . '" href="' . $archive_page_url . '">' . $view_more_text . ' &raquo;</a>';
				}
				elseif ($archive_post_id > 0) {
					echo '<a class="tb_archivelink' . $default . '" href="' . get_permalink($archive_post_id) . '">' . $view_more_text . ' &raquo;</a>';
				}
			}
			echo '</div>';

			echo '</div>';
			echo $after_widget;
		}
	}

	// update/save function
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$tb_o = get_option('tweet-blender');
		$errors = array();

		// check to make sure we have oAuth token		
		if (!$tb_o['oauth_access_token']) {				
			// Create TwitterOAuth object and get request token
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
					 
			// Get request token
			$request_token = $connection->getRequestToken(get_bloginfo('url') . '/' . PLUGINDIR . "/tweet-blender/lib/twitteroauth/callback.php");
					 
			if ($connection->http_code == 200) {
				// Save request token to session
				$tb_o['oauth_token'] = $token = $request_token['oauth_token'];
				$tb_o['oauth_token_secret'] = $request_token['oauth_token_secret'];
				update_option('tweet-blender',$tb_o);
				
				$errors[] = __("Twitter API v1.1 requires authentication", 'tweetblender') . " <a href='javascript:tAuth(\"" . $connection->getAuthorizeURL($token) . "\")' title=" . __('Authorize Access', 'tweetblender') . ">" . __('Use your Twitter account to login', 'tweetblender') . "</a>.";

			}
			else {
				$errors[] = __("Twitter oAuth is not possible at this time.", 'tweetblender') .  "<!--" . $connection->last_api_call . "-->";
			}
		}
		else {
		
			// process sources
			if (isset($old_instance['widget_sources'])) {
				$old_sources = preg_split('/[\n\r]/m', $old_instance['widget_sources']);
			}
			else {
				$old_sources = array();
			}
			$new_sources = preg_split('/[\n\r]/m', $new_instance['widget_sources']);

			$have_bad_sources = false; $status_msg = array(); $log_msg = '';

			if (isset($tb_o['widget_check_sources']) && $tb_o['widget_check_sources']) {
				foreach($new_sources as $src) {
					$src = trim($src);
					if ($src != '') {
						list($is_ok,$msg,$log) = $this->check_source($src,$tb_o);
						
						if (!$is_ok) {
							$have_bad_sources = true;
						}
						$status_msg[] = $msg;
						$log_msg .= $log;
					}
				}
			}
		}

		if (sizeof($errors) == 0 && !$have_bad_sources) {
			$this->message = __('Settings saved', 'tweetblender') . '<br/><br/>' . join(', ',$status_msg);
			$instance['title'] = trim(strip_tags($new_instance['title']));
			$instance['widget_refresh_rate'] = $new_instance['widget_refresh_rate'];
			$instance['widget_tweets_num'] = $new_instance['widget_tweets_num'];
			$instance['widget_sources'] = $new_instance['widget_sources'];
			$instance['widget_view_more_url'] = $new_instance['widget_view_more_url'];
			$instance['widget_view_more_text'] = trim($new_instance['widget_view_more_text']);
			return $instance;
		}
		else {
			if (sizeof($status_msg) > 0) {
				$this->error = join(', ',$status_msg) . "<!-- $log_msg -->" . '<br/><br/>';
			}
			if (sizeof($errors) > 0) {
				$this->error .= '<br/><br/>' . join(', ', $errors);
			}
			$this->bad_input = $new_instance;
			return false;
		}
	}
 
	// admin control form
	function form($instance) {
		global $tb_refresh_periods;

		$default = 	array( 
			'title' => __('Favorite Tweets', 'tweetblender'),
			'widget_refresh_rate' => 0,
			'widget_tweets_num' => 4,
			'widget_sources' => ''
		);
		$instance = wp_parse_args( (array) $instance, $default );
 
 		// report errors if any
 		if (isset($this->error)) {
 			echo tb_wrap_javascript("function tAuth(url) {var tWin = window.open(url,'tWin','width=800,height=410,toolbar=0,location=1,status=0,menubar=0,resizable=1');}");
 			echo '<div class="error">' . $this->error . '</div>';
			$instance = $this->bad_input;
 		}
		// report messages if an
 		if (isset($this->message)) {
 			echo '<div class="updated">' . $this->message . '</div>';
 		}
		
 		// title		
		$field_id = $this->get_field_id('title');
		$field_name = $this->get_field_name('title');
		echo "\r\n".'<p><label for="'.$field_id.'">'.__('Title', 'tweetblender').': <input type="text" class="widefat" id="'.$field_id.'" name="'.$field_name.'" value="'.esc_attr( $instance['title'] ).'" /></label></p>';

		// sources
		$field_id = $this->get_field_id('widget_sources');
		$field_name = $this->get_field_name('widget_sources');
		echo "\r\n".'<p><label for="'.$field_id.'">'.__('Sources (one per line)', 'tweetblender').': <textarea class="widefat" id="'.$field_id.'" name="'.$field_name.'" rows=4 cols=20 wrap="hard">' . esc_attr( $instance['widget_sources'] ) . '</textarea></label></p>';
		 		
		// specify refresh
		$field_id = $this->get_field_id('widget_refresh_rate');
		$field_name = $this->get_field_name('widget_refresh_rate');
		echo "\r\n".'<label for="'.$field_id.'">'.__('Refresh', 'tweetblender').'</label>';
		echo "\r\n".'<select id="'.$field_id.'" name="'.$field_name.'">';
			
		foreach ($tb_refresh_periods as $name => $sec) {
			echo "\r\n".'<option value="' . $sec . '"';
			if ($sec == $instance['widget_refresh_rate']) {
				echo ' selected';
			}
			echo '>' . $name . '</option>';
		}
		echo "\r\n".'</select><br>';

		// specify number of tweets
		$field_id = $this->get_field_id('widget_tweets_num');
		$field_name = $this->get_field_name('widget_tweets_num');
		echo "\r\n".'<br/><label for="'.$field_id.'">'.__('Show', 'tweetblender').' <select id="'.$field_id.'" name="'.$field_name.'">';
		for ($i = 1; $i <= 15; $i++) {
			echo "\r\n".'<option value="' . $i . '"';
			if ($i == $instance['widget_tweets_num']) {
				echo ' selected';
			}
			echo '>' . $i . '</option>';
		}
		for ($i = 20; $i <= 100; $i+=10) {
			echo "\r\n".'<option value="' . $i . '"';
			if ($i == $instance['widget_tweets_num']) {
				echo ' selected';
			}
			echo '>' . $i . '</option>';
		}
		echo "\r\n".'</select>' .__('tweets', 'tweetblender') . '</label><br>';

		// specify text for "view more" link
		$field_id = $this->get_field_id('widget_view_more_text');
		$field_name = $this->get_field_name('widget_view_more_text');
		if (!isset($instance['widget_view_more_text'])) {
			$instance['widget_view_more_text'] = '';
		} 
		echo "\r\n".'<br/><label for="'.$field_id.'">'. sprintf(__('Text for %s link', 'tweetblender'),'&quot;' . __('view more','tweetblender') . '&quot') . ':</label>';
		echo "\r\n".'<input class="widefat" type="text" id="'.$field_id.'" name="'.$field_name.'" value="' . esc_attr( $instance['widget_view_more_text'] ) . '">';
				
		// specify URL for "view more" link
		$field_id = $this->get_field_id('widget_view_more_url');
		$field_name = $this->get_field_name('widget_view_more_url');
		if (!isset($instance['widget_view_more_url'])) {
			$instance['widget_view_more_url'] = '';
		} 
		echo "\r\n".'<br/><label for="'.$field_id.'">' . sprintf(__('URL for %s link', 'tweetblender'),'&quot;' . __('view more','tweetblender') . '&quot') . ':</label>';
		echo "\r\n".'<input class="widefat" type="text" id="'.$field_id.'" name="'.$field_name.'" value="' . esc_attr( $instance['widget_view_more_url'] ) . '"><br/>';
		if ($archive_post = tb_get_archive_post_id()) {
			echo '<span style="color:#777;font-style:italic;">' . __('Leave blank to use ', 'tweetblender') . '<a href="page.php?action=edit&post=' . $archive_post . '" target="_blank">' . __('existing page', 'tweetblender') . '</a></span>';
		}
	}
	
	function check_source($src,$tb_o) {

		$source_check_result = '';
		$log_msg = '';
		$is_ok = false;
		
		// remove modifiers
		if (stripos($src,'|') > 1) {
			$source_check_result = ' ' . $src . ' - <span class="fail">' . __('FAIL', 'tweetblender') . '</span>';
			$log_msg = "($src)" . __('only screen names work with favorites', 'tweetblender') ."\n";
			return array($is_ok,$source_check_result,$log_msg);
		}
		
		$source_is_screen_name = false;
		
		// if it's a list, report it as bad source
		if (stripos($src,'@') === 0 && stripos($src,'/') > 1) {
			$source_check_result = ' ' . $src . ' - <span class="fail">' . __('FAIL', 'tweetblender') . '</span>';
			$log_msg = "($src)" . __('only screen names work with favorites', 'tweetblender') ."\n";
			return array($is_ok,$source_check_result,$log_msg);
		}
		// if it's a screen name, use timeline API
		elseif (stripos($src,'@') === 0) {
			$api_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
			$api_params = array('screen_name' => substr($src,1));
		}
		// else assume it's a hashtag or keyword, report as bad source
		else {
			$source_check_result = ' ' . $src . ' - <span class="fail">' . __('FAIL', 'tweetblender') . '</span>';
			$log_msg = "($src)" . __('only screen names work with favorites',  'tweetblender') ."\n";
			return array($is_ok,$source_check_result,$log_msg);
		}

		// try to get data from Twitter
		if(isset($tb_o['oauth_access_token'])) {
			$oAuth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $tb_o['oauth_access_token']['oauth_token'],$tb_o['oauth_access_token']['oauth_token_secret']);
			
			$json_data = $oAuth->OAuthRequest($api_url, 'GET', $api_params);
			
			// if we didn't get any data
			if (!isset($json_data)) {
				$source_check_result = ' ' . $src . ' - <span class="fail">' . __('FAIL', 'tweetblender') . '</span>';
				$log_msg = "($src) " . __('json error','tweetblender') . ': ' . __('can not get json', 'tweetblender') . "\n" . $apiUrl;
			}
			// if Twitter reported error
			elseif (isset($json_data->{'errors'})) {
			
				// if it's just limit error we are OK
				if (strpos($json_data->{'errors'}->{'message'},"Rate limit exceeded") === 0) {
					$is_ok = true;
					$source_check_result = ' ' . $src . ' - <span class="pass">' . __('OK', 'tweetblender') . '</span>';
					$log_msg = "($src) " . __('Error','tweetblender') . ': ' . __('limit error', 'tweetblender') . "\n";
				}
				// any other error is an error
				else {
					$source_check_result = ' ' . $src . ' - <span class="fail">' . __('FAIL', 'tweetblender') . '</span>';
					$log_msg = "($src) " . __('json error','tweetblender') . ': ' . $jsonData->{error} . "\n";
				}
			}
			// else we assume OK
			else {
				$is_ok = true;
				$source_check_result = ' ' . $src . ' - <span class="pass">' . __('OK', 'tweetblender') . '</span>';
				$log_msg = "($src) " . __('Got json with no errors', 'tweetblender') . "\n";
			}
		}
		// else we can't check so assume it's a bad source
		else {
			$source_check_result = ' ' . $src . ':<span class="fail">' . __('FAIL', 'tweetblender') . '</span>';
			$log_msg = "($src)" . __('HTTP error:  ',  'tweetblender') . __('no oAuth tokens', 'tweetblender') . "\n";
		}
		
		return array($is_ok,$source_check_result,$log_msg);

	}
}

add_action( 'widgets_init', create_function('', 'return register_widget("TweetBlenderFavorites");') );

?>