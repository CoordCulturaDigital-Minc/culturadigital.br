<?php

// version 4.0.2

// include WP functions
require_once("../../../wp-config.php");

// if on PHP5, include oAuth library and config
if(!version_compare(PHP_VERSION, '5.0.0', '<'))
{
    class_exists('TwitterOAuth') || include_once dirname(__FILE__).'/lib/twitteroauth/twitteroauth.php';
	include_once dirname(__FILE__).'/lib/twitteroauth/config.php';
}

// include TweetBlender library
include_once(dirname(__FILE__).'/lib/lib.php');

// fix GoDaddy's 404 status
status_header(200);

// get options from WP
$tb_o = get_option('tweet-blender');

// if we don't have json class, get the library
if (!isset($wp_json) || !is_a($wp_json, 'Services_JSON') ) {
	if (file_exists(ABSPATH . WPINC . '/class-json.php')) {
		require_once( ABSPATH . WPINC . '/class-json.php' );
	}
	else {
		require(dirname(__FILE__).'/lib/JSON.php');
	}
	$wp_json = new Services_JSON();
}


// if request is for favorites, search results, user timeline, or list timeline
if (in_array($_GET['action'],array('search','list_timeline','user_timeline','favorites'))) {

	// check to make sure we have the class
	if (!class_exists('TwitterOAuth')) {
		echo $wp_json->encode(array('error' => __('Twitter oAuth is not available', 'tweetblender')));
		exit;
	}

	$params = array();
	parse_str($_SERVER['QUERY_STRING'],$params);
	$use_cache = false;
	
	$sources = array();

	// search
	if ($_GET['action'] == 'search') {
		// if its for screen names
		if (isset($params['from'])) {
			$sources = split(' OR ',$params['from']);
			// add the @ sign
			array_walk($sources, create_function('&$src','$src = "@" . $src;'));
			
			if (isset($params['tag'])) {
				$api_params = array(
					'q' => '#' . $params['tag'] . ' from:' . $params['from']
				);
			}
			elseif (isset($params['ors'])) {
				$api_params = array(
					'q' => $params['ors'] . ' from:' . $params['from']
				);
			}
			
		}
		elseif (isset($_GET['ors'])) {
			$sources = split(' ',$params['ors']);

			$api_params = array(
				'q' => $params['q']
			);
		}
		else {

			$tmp_sources = split(' OR ',$params['q']);

			// pull out screen names and keywords		
			foreach ($tmp_sources as $src) {
				if (substr($src, 0, 5) != 'from:') {
					$sources[] = $src;
				}
			}

			$api_params = array(
				'q' => $params['q']
			);
		}
		
		$api_endpoint = '/search/tweets';
	}
	
	// list
	elseif($_GET['action'] == 'list_timeline') {
		$sources = array('@'.$params['user'].'/'.$params['list']);
		
		$api_endpoint = '/lists/statuses';
		$api_params = array(
			'owner_screen_name' => $params['user'],
			'slug' => $params['list']
		);
	}
	
	// favorites
	elseif($_GET['action'] == 'favorites') {
		$sources = array('@'.$params['screen_name']);
		
		$api_endpoint = '/favorites/list';
		$api_params = array(
			'screen_name' => $params['screen_name']
		);
	}
	
	// user timeline
	elseif($_GET['action'] == 'user_timeline') {
		$sources[] = '@'.$params['screen_name'];
		
		$api_endpoint = '/statuses/user_timeline';
		$api_params = array(
			'screen_name' => $params['screen_name'],
			'contributor_details' => 'true'
		);
		
		// check if we want to exclude replies
		if(isset($tb_o['filter_hide_replies']) && $tb_o['filter_hide_replies']) {
			$api_params['exclude_replies'] = 'true';
		}
		
		// check if we want to exclude retweets
		if(isset($tb_o['filter_hide_retweets']) && $tb_o['filter_hide_retweets']) {
			$api_params['include_rts'] = 'false';
		}
	}

	// make sure we have oAuth info
	if (!isset($tb_o['oauth_access_token'])){
		echo $wp_json->encode(array('error' => __("Do not have oAuth login info", 'tweetblender')));
		exit;
	}

	// make sure we have limit info
	if (!isset($tb_o['rate_limit_data'][$api_endpoint])) {
		$have_api_limit_data = tb_get_server_rate_limit_data($tb_o);
	}

	// figure out optimal delay = 15min in seconds divided by max requests
	if (isset($tb_o['rate_limit_data'][$api_endpoint]['limit'])) {
		$optimal_seconds_between_requests = 15 * 60 / $tb_o['rate_limit_data'][$api_endpoint]['limit'];
	}
	else {
		$optimal_seconds_between_requests = 15 * 60 / 100;
	}
	
	// time since last request
	if (isset($tb_o['rate_limit_data'][$api_endpoint]['last_used'])) {
		$seconds_since_last_request = time() - $tb_o['rate_limit_data'][$api_endpoint]['last_used'];
	}
	else {
		$seconds_since_last_request = $optimal_seconds_between_requests;
	}

//error_log('endpoint=' . $api_endpoint . ' optimal delay=' . $optimal_seconds_between_requests . ' sec since last request=' . $seconds_since_last_request . ' limit remaining=' . $tb_o['rate_limit_data'][$api_endpoint]['remaining']);
		
	// check the limit 
	if ($tb_o['rate_limit_data'][$api_endpoint]['remaining'] > 0 && $seconds_since_last_request >= $optimal_seconds_between_requests) {

//error_log('making live request');
		
		// try to get it directly
		$oAuth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $tb_o['oauth_access_token']['oauth_token'],$tb_o['oauth_access_token']['oauth_token_secret']);
		$json_data = $oAuth->OAuthRequest($tb_api_base_url . $api_endpoint . '.json', 'GET', $api_params);
		
//error_log('***** url = ' . $tb_api_base_url . $api_endpoint . '.json');

//error_log('***** sources = ' . print_r($sources,true));

//error_log('**** params = ' . print_r($api_params,true));

		if ($oAuth->http_code == 200) {
			echo $json_data;

//error_log('**** response=' . $json_data);
			
			// update rate limit info
			$headers = $oAuth->http_header;
			$tb_o['rate_limit_data'][$api_endpoint] = array(
				'limit' => $headers['x_rate_limit_limit'],
				'remaining' => $headers['x_rate_limit_remaining'],
				'reset' => $headers['x_rate_limit_reset'],
				'last_used' => time()
			);

			// save rate limit data to options
			update_option('tweet-blender',$tb_o);
			
//error_log('**** json data: ' . $json_data);
			
			// cache response
			tb_save_cache($wp_json->decode($json_data),$sources);
			
			exit;
		}
		// else, try to get it from cache and if that fails report an error
		else {
			if ($json_data = tb_get_cached_tweets_json($sources)) {
				echo $json_data;
			}
			else {
				echo $json->encode(array('error' => __('No cache. Connection status code', 'tweetblender') . ' ' . $oAuth->http_code));
			}
			exit;
		}
	}
	// if we've reached the limit, just get data from cache
	else {
	
//error_log('getting it from cache');

		if ($json_data = tb_get_cached_tweets_json($sources)) {
			echo $json_data;
		}
		else {
			echo $json->encode(array('error' => __('Reached Twitter API limit and there is no cache.', 'tweetblender')));
		}
		exit;
	}
	
}
else {
	echo $wp_json->encode(array('error' => __('Do not know what you want.', 'tweetblender')));	
}

?>