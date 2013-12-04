<?php

// Version 4.0.1

// aliases for sources
$TB_sourceNames = array();

// options configurable via admin page
$tb_option_names = array(
	// general configuration options
	'general_timestamp_format','general_seo_tweets_googleoff','general_seo_footer_googleoff',
	// options related to widget
	'widget_show_source','widget_show_refresh','widget_check_sources',
	// options related to archive page
	'archive_show_source','archive_tweets_num','archive_is_disabled','archive_auto_page','archive_keep_tweets','archive_show_source_selector',
	// advanced options
	'advanced_no_search_api',
	// filtering
	'filter_lang','filter_hide_mentions','filter_hide_replies','filter_hide_retweets','filter_bad_strings','filter_limit_per_source','filter_hide_same_text','filter_hide_not_replies',

	// database
	'db_version'
);

// Twitter API base URL
$tb_api_base_url = 'https://api.twitter.com/1.1';

// Twitter API endpoints used
$tb_api_endpoints_used = array('/lists/statuses','/statuses/user_timeline','/favorites/list','/search/tweets');

// options used only internally
$tb_option_names_system = array(
	'db_version'
);

// refresh periods in seconds
$tb_refresh_periods = array(
	__('Manual', 'tweetblender') => 0,
	__('Only once (on load)', 'tweetblender') => 1,
	sprintf(__('Every %d seconds', 'tweetblender'),10) => 10,
	sprintf(__('Every %d seconds', 'tweetblender'),15) => 15,
	sprintf(__('Every %d seconds', 'tweetblender'),20) => 20,
	sprintf(__('Every %d seconds', 'tweetblender'),30) => 30,
	__('Every minute', 'tweetblender') => 60,
	sprintf(__('Every %d minutes', 'tweetblender'),2) => 120,
);

$tb_keep_tweets_options = array(
	__('Do not delete them', 'tweetblender') => 0,
	'1 ' . __('day', 'tweetblender')	=> 1,
	'2 ' . __('days', 'tweetblender') => 2,
	'3 ' . __('days', 'tweetblender') => 3,
	'1 ' . __('week', 'tweetblender') => 7,
	'2 ' . __('weeks', 'tweetblender') => 14,
	'1 ' . __('month', 'tweetblender') => 30
);

$tb_languages = array(
' ' => __('any language', 'tweetblender'),
'ab' => 'Abkhazian',
'ae' => 'Avestan',
'af' => 'Afrikaans',
'ak' => 'Akan',
'am' => 'Amharic',
'an' => 'Aragonese',
'ar' => 'Arabic',
'as' => 'Assamese',
'av' => 'Avaric',
'ay' => 'Aymara',
'az' => 'Azerbaijani',
'ba' => 'Bashkir',
'be' => 'Belarusian',
'bg' => 'Bulgarian',
'bh' => 'Bihari',
'bi' => 'Bislama',
'bm' => 'Bambara',
'bn' => 'Bengali',
'bo' => 'Tibetan',
'br' => 'Breton',
'bs' => 'Bosnian',
'ca' => 'Catalan; Valencian',
'ce' => 'Chechen',
'ch' => 'Chamorro',
'co' => 'Corsican',
'cr' => 'Cree',
'cs' => 'Czech',
'cv' => 'Chuvash',
'cy' => 'Welsh',
'da' => 'Danish',
'de' => 'German',
'en' => 'English',
'eo' => 'Esperanto',
'es' => 'Spanish; Castilian',
'et' => 'Estonian',
'eu' => 'Basque',
'fa' => 'Persian',
'ff' => 'Fulah',
'fi' => 'Finnish',
'fj' => 'Fijian',
'fo' => 'Faroese',
'fr' => 'French',
'fy' => 'Western Frisian',
'ga' => 'Irish',
'gl' => 'Galician',
'gn' => 'Guarani',
'gu' => 'Gujarati',
'gv' => 'Manx',
'ha' => 'Hausa',
'he' => 'Hebrew',
'hi' => 'Hindi',
'ho' => 'Hiri Motu',
'hr' => 'Croatian',
'ht' => 'Haitian; Haitian Creole',
'hu' => 'Hungarian',
'hy' => 'Armenian',
'hz' => 'Herero',
'id' => 'Indonesian',
'ie' => 'Interlingue; Occidental',
'ig' => 'Igbo',
'ii' => 'Sichuan Yi; Nuosu',
'ik' => 'Inupiaq',
'io' => 'Ido',
'is' => 'Icelandic',
'it' => 'Italian',
'iu' => 'Inuktitut',
'ja' => 'Japanese',
'jv' => 'Javanese',
'ka' => 'Georgian',
'kg' => 'Kongo',
'ki' => 'Kikuyu; Gikuyu',
'kj' => 'Kuanyama; Kwanyama',
'kk' => 'Kazakh',
'kl' => 'Kalaallisut; Greenlandic',
'km' => 'Central Khmer',
'kn' => 'Kannada',
'ko' => 'Korean',
'kr' => 'Kanuri',
'ks' => 'Kashmiri',
'ku' => 'Kurdish',
'kv' => 'Komi',
'kw' => 'Cornish',
'ky' => 'Kirghiz; Kyrgyz',
'la' => 'Latin',
'lb' => 'Luxembourgish; Letzeburgesch',
'lg' => 'Ganda',
'li' => 'Limburgan; Limburger; Limburgish',
'ln' => 'Lingala',
'lo' => 'Lao',
'lt' => 'Lithuanian',
'lu' => 'Luba-Katanga',
'lv' => 'Latvian',
'mg' => 'Malagasy',
'mh' => 'Marshallese',
'mi' => 'Maori',
'mk' => 'Macedonian',
'ml' => 'Malayalam',
'mn' => 'Mongolian',
'mr' => 'Marathi',
'ms' => 'Malay',
'mt' => 'Maltese',
'my' => 'Burmese',
'na' => 'Nauru',
'ne' => 'Nepali',
'ng' => 'Ndonga',
'nl' => 'Dutch; Flemish',
'no' => 'Norwegian',
'oj' => 'Ojibwa',
'om' => 'Oromo',
'or' => 'Oriya',
'os' => 'Ossetian; Ossetic',
'pa' => 'Panjabi; Punjabi',
'pi' => 'Pali',
'pl' => 'Polish',
'ps' => 'Pushto; Pashto',
'pt' => 'Portuguese',
'qu' => 'Quechua',
'rm' => 'Romansh',
'rn' => 'Rundi',
'ro' => 'Romanian; Moldavian; Moldovan',
'ru' => 'Russian',
'rw' => 'Kinyarwanda',
'sa' => 'Sanskrit',
'sc' => 'Sardinian',
'sd' => 'Sindhi',
'se' => 'Northern Sami',
'sg' => 'Sango',
'si' => 'Sinhala; Sinhalese',
'sk' => 'Slovak',
'sl' => 'Slovenian',
'sm' => 'Samoan',
'sn' => 'Shona',
'so' => 'Somali',
'sq' => 'Albanian',
'sr' => 'Serbian',
'ss' => 'Swati',
'su' => 'Sundanese',
'sv' => 'Swedish',
'sw' => 'Swahili',
'ta' => 'Tamil',
'te' => 'Telugu',
'tg' => 'Tajik',
'th' => 'Thai',
'ti' => 'Tigrinya',
'tk' => 'Turkmen',
'tl' => 'Tagalog',
'tn' => 'Tswana',
'to' => 'Tonga (Tonga Islands)',
'tr' => 'Turkish',
'ts' => 'Tsonga',
'tt' => 'Tatar',
'tw' => 'Twi',
'ty' => 'Tahitian',
'ug' => 'Uighur; Uyghur',
'uk' => 'Ukrainian',
'ur' => 'Urdu',
'uz' => 'Uzbek',
've' => 'Venda',
'vi' => 'Vietnamese',
'yi' => 'Yiddish',
'yo' => 'Yoruba',
'za' => 'Zhuang; Chuang',
'zh' => 'Chinese',
'zu' => 'Zulu'
);

$tb_addons = array(
	'1' => array(
		'name' => __('Cache Manager', 'tweetblender'),
		'slug' => 'tweet-blender-cache-manager'
	),
	'2' => array(
		'name' => __('nStyle', 'tweetblender'),
		'slug' => 'tweet-blender-nstyle'
	),
	'3' => array(
		'name' => __('Charts', 'tweetblender'),
		'slug' => 'tweet-blender-charts'
	)
);

$tb_package_names = array(
	'1' => __('Cache Manager', 'tweetblender'),
	'2' => __('nStyle', 'tweetblender'),
	'3' => __('Charts', 'tweetblender'),
);

$js_labels = array(
	'no_config' => __('No configuration settings found', 'tweetblender'),
	'twitter_logo' => __('Twitter Logo', 'tweetblender'),
	'kino' => __('Development by Kirill Novitchenko', 'tweetblender'),
	'refresh' => __('Refresh', 'tweetblender'),
	'no_sources' => __('Twitter sources to blend are not defined', 'tweetblender'),
	'no_global_config' => __('Cannot retrieve Tweet Blender configuration options', 'tweetblender'),
	'version_msg' => __('Powered by Tweet Blender plugin v{0} blending {1}', 'tweetblender'),
	'limit_msg' => __('You reached Twitter API connection limit', 'tweetblender'),
	'no_tweets_msg' => __('No tweets found for {0}', 'tweetblender'),
	'loading_msg' => __('Loading tweets...', 'tweetblender'),
	'time_past' => __('{0} {1} ago', 'tweetblender'),
	'time_future' => __('in {0} {1}', 'tweetblender'),
	'second' => __('second', 'tweetblender'),
	'seconds' => __('seconds', 'tweetblender'),
	'minute' => __('minute', 'tweetblender'),
	'minutes' => __('minutes', 'tweetblender'),
	'hour' => __('hour', 'tweetblender'),
	'hours' => __('hours', 'tweetblender'),
	'day' => __('day', 'tweetblender'),
	'days' => __('days', 'tweetblender'),
	'week' => __('week', 'tweetblender'),
	'weeks' => __('weeks', 'tweetblender'),
	'month' => __('month', 'tweetblender'),
	'months' => __('months', 'tweetblender'),
	'year' => __('year', 'tweetblender'),
	'years' => __('years', 'tweetblender'),
	'check_fail' => __('Check failed', 'tweetblender'),
	'limit_num' => __('Max is {0}/hour', 'tweetblender'),
	'limit_left' => __('You have {0} left', 'tweetblender'),
	'from' => __('from', 'tweetblender'),
	'reply' => __('reply', 'tweetblender'),
	'follow' => __('follow', 'tweetblender'),
	'limit_reset' => __('Next reset','tweetblender'),
	'view_more' => __('view more','tweetblender') 
);

// set up data structure for addon tracking
$tb_installed_addons = array();
$tb_active_addons = array();

// variables needed for filtering & throttling
$tb_seen_tweets = array();
$tb_tweet_counts_per_source = array();

// if we don't have json class, get own PHP4 compatible library
if (!isset($wp_json) || !is_a($wp_json, 'Services_JSON')) {
	if (!class_exists('Services_JSON')) {
		require_once( dirname(__FILE__) . '/JSON.php' );
	}
	$wp_json = new Services_JSON();
}

function tb_verbal_time($timestamp) {
    $periods = array(__("second",'tweetblender'), __("minute",'tweetblender'), __("hour",'tweetblender'), __("day",'tweetblender'), __("week",'tweetblender'), __("month",'tweetblender'), __("year",'tweetblender'));
    $periods_plural = array(__("seconds",'tweetblender'), __("minutes",'tweetblender'), __("hours",'tweetblender'), __("days",'tweetblender'), __("weeks",'tweetblender'), __("months",'tweetblender'), __("years",'tweetblender'));
    $lengths = array("60","60","24","7","4.35","12");
   
    $now = time();
	$difference = abs($now - $timestamp);
   
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
   
    if($difference != 1) {
    	$units = $periods_plural[$j];
    }
    else {
    	$units = $periods[$j];
    }

    if ($timestamp < $now) {
		return sprintf(__("%d %s ago",'tweetblender'), $difference, $units);
	}
	else {
		return sprintf(__("in %d %s",'tweetblender'), $difference, $units);
	}
}

// old search: Wed, 27 May 2009 15:52:40 +0000
// new search and 	user feed: Thu May 21 00:09:16 +0000 2009
function tb_str2time($date_string) {
	$mnum = array('Jan' => 1,'Feb' => 2, 'Mar' => 3, 'Apr' => 4, 'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Aug' => 8, 'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12);

	if (strpos($date_string, ',') !== false) {
		list($wday,$mday, $mon, $year, $hour,$min,$sec,$offset) = preg_split('/[\s\:]/',$date_string);
	}
	else {
		list($wday,$mon,$mday,$hour,$min,$sec,$offset,$year) = preg_split('/[\s\:]/',$date_string);
	}
	
	if (($timestamp = gmmktime($hour,$min,$sec,$mnum[$mon],$mday,$year)) > 0 ) {
		return $timestamp;
	}
	else {
		return time();
	}
}

function tb_wrap_javascript($script_content) {
	return "\n\r".'<script type="text/javascript">' . $script_content . '</script>'."\n\r";
}

function tb_get_server_rate_limit_data($tb_o) {

	global $tb_api_base_url, $tb_api_endpoints_used, $wp_json;
	
	// TODO: if we checked within last 2 minutes - don't check again
	if (time() - $tb_o['rate_limit_data']['last_check'] <= 60 * 2) {
		return true;
	}
	
	$params = array(
		'rand' => rand(),
		'resources' => 'search,statuses,favorites,lists'
	);
	
	// check to make sure we have the class
	if (!class_exists('TwitterOAuth')) {
		return false;
	}
	// make sure we have oAuth info
	if (!isset($tb_o['oauth_access_token'])){
		return false;
	}
	else {
		// try to get it directly
		$oAuth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $tb_o['oauth_access_token']['oauth_token'],$tb_o['oauth_access_token']['oauth_token_secret']);
		$json_response = $oAuth->OAuthRequest($tb_api_base_url . '/application/rate_limit_status.json', 'GET', $params);
		
		if ($oAuth->http_code == 200) {
			$json_data = $wp_json->decode($json_response);
		
			// iterate over resources
			foreach ($json_data->{'resources'} as $resource_name => $resource_data) {
				// iterate over enpoints available for each resource
				foreach ($resource_data as $endpoint_name => $endpoint_data) {
					// process only endpoints that we are actually using
					if (in_array($endpoint_name, $tb_api_endpoints_used)){
					
						$tb_o['rate_limit_data'][$endpoint_name] = array(
							'limit' => $endpoint_data->limit,
							'remaining' => $endpoint_data->remaining,
							'reset' => $endpoint_data->reset,
							'last_check' => time()
						);
					}
				}
			}
			
			$tb_o['rate_limit_data']['last_check'] = time();
			
			// save rate limit data to options
			update_option('tweet-blender',$tb_o);
			
			return true;
		}
		else {
			return false;
		}
	}
}

function tb_get_cached_tweets_json($sources) {
	global $wp_json;
	
	$tweets = array();
	$tweets = tb_get_cached_tweets($sources, 500);

//error_log('*** cached tweets: ' . print_r($tweets,true));

	foreach ($tweets as $t){
		$tweet = $wp_json->decode($t->tweet_json);
		$tweet->{'div_id'} = $t->div_id;
		$tweets[] = $tweet;
	}
	
	return $wp_json->encode($tweets);
}

function tb_save_cache($tweets,$tweet_sources) {
	global $wpdb, $wp_json;
	$inserted_cache = false;
	$tweets_set = array();

//error_log('*** tweets: ' . print_r($tweets, true));

	$table_name = $wpdb->prefix . "tweetblender";

	// search results
	if (isset($tweets->{'statuses'})) {
		$tweets_set = (array)$tweets->{'statuses'};
	}
	// user timeline or favorites
	else {
		$tweets_set = (array)$tweets;
	}

	// process each tweet
	foreach ($tweets_set as $tweet) {

//error_log('*** tweet = ' . print_r($tweet,true));

		// figure out $div_id
		$div_id = 't-' . tb_str2time($tweet->{'created_at'}) . '000-' . $tweet->{'user'}->{'screen_name'} . '-' . $tweet->{'id_str'};

		// process each source
		foreach($tweet_sources as $src) {

//error_log('*** src = '. $src);

			// only insert if tweet text contains source or tweet author matches the source
			if (strpos($tweet->{'text'}, $src) !== false || $tweet->{'user'}->{'screen_name'} == substr($src, 1)) {
						
//error_log('** inserting for ' . $src);

				$wpdb->query("INSERT IGNORE INTO $table_name (div_id,source,tweet_text,tweet_json) VALUES ('" . 
					$wpdb->escape($div_id) . "','" . 
					$wpdb->escape($src) . "','" . 
					$wpdb->escape($tweet->{'text'}) . "','" .
					$wpdb->escape($wp_json->encode($tweet)) . "')"
				);
				
				$inserted_cache = true;
				
			}
		}
	}
	
	return $inserted_cache;	
}

// creates HTML for the list of tweets using cached tweets
function tb_get_cached_tweets_html($mode,$instance,$widget_id = '',$sources = array()) {

	global $wp_json;
	
	// get options
	$tb_o = get_option('tweet-blender');

	// if no explicit sources requisted, get the ones configured for the widget
	if (sizeof($sources) == 0) {
		$sources = array_values(array_filter(preg_split('/[\n\r]/m', trim($instance['widget_sources']))));
	}
	
	$tweets_html = '';
	
	if ($mode == 'chart') {
		
	 	// get data from DB
		$chart_data = tb_get_chart_data($sources,$instance['widget_chart_period'],$instance['widget_chart_type']);
		
		$tweets_html .= tb_wrap_javascript('TB_chartsData["' . $widget_id . '"] = eval(\'(' . $wp_json->encode($chart_data) . ')\');');
		
	}
	else {
		// figure out how many to get
		if ($mode == 'archive') {
			$tweets_to_show	= $tb_o['archive_tweets_num'];
		}
		else {
			$tweets_to_show	= $instance['widget_tweets_num'];
		}
		
	 	// get data from DB
		$tweets = tb_get_cached_tweets($sources, $tweets_to_show,$widget_id);
		foreach ($tweets as $t){
			$tweet = $wp_json->decode($t->tweet_json);
			$tweet->{'div_id'} = $t->div_id;
			$tweets_html .= tb_tweet_html($tweet,$mode,$tb_o);
		}
	}
				
	return $tweets_html;
}


function tb_get_cached_tweets($sources,$tweets_num,$widget_id = '') {
	global $wpdb;
	$table_name = $wpdb->prefix . "tweetblender";

	// TODO: if widget_id contains "favorites" pull out only favorite tweets for the given sources
	
	$sources_sql = "";
	if (sizeof($sources) > 0) {
		array_walk($sources,'tb_process_sources');
		$sources_sql = "WHERE source IN ('" . join("','",$sources) . "')";
	}
	
	// get data from DB
	return $wpdb->get_results("SELECT DISTINCT div_id, tweet_json FROM $table_name $sources_sql ORDER BY div_id DESC LIMIT $tweets_num");
}

function tb_process_sources(&$src, $key) {
	global $TB_sourceNames;

	$src = trim($src);
	if(strpos($src,':') > 0 ) {
		list($src,$alias) = explode(':',$src);
		$source = substr($src,1);
		if (strpos($src,'|') > 0) {
			$parts = explode('|',$src);
			$source = substr($parts[0],1);
		}
		$TB_sourceNames[$source] = $alias;
	}
}

function tb_tweet_is_ok_to_show($tweet, $tb_o) {
	
	global $tb_seen_tweets;
	
	// if we don't show tweets with same content
	if ($tb_o['filter_hide_same_text']) {
		if (in_array($tweet->text,$tb_seen_tweets)) {
			return false;
		}
	}

	// if this is a reply
	if ($tweet->in_reply_to_user_id || $tweet->in_reply_to_status_id || $tweet->in_reply_to_screen_name) {
		// if we don't show replies
		if ($tb_o['filter_hide_replies']) {
			return false;
		}
	}
	// else, if it's not a reply but we are showing only replies
	else if ($tb_o['filter_hide_not_replies']) {
		return false;
	}
	
	// if it's a retweet and we are hiding retweets
	if ($tweet->retweeted_status && $tb_o['filter_hide_retweets']) {
		return false;
	}

	// if there are filtered words and the tweet text matches any of them - skip this tweet
	if (isset($tb_o['filter_bad_strings'])) {
		$bad_strings = explode(',', $tb_o['filter_bad_strings']);
		
		if (sizeof($bad_strings) > 0) {
			foreach ($bad_strings as $bad_string) {
	
				if ($bad_string != '' && strpos($tweet->text, $bad_string) !== false) {
					return false;
				}
			}
		}
	}
	
	// if throttling is ON
	if ($tb_o['filter_limit_per_source'] > 0) {
		// if we alrady have seent tweets from this sources
		if (isset($tb_tweet_counts_per_source[$tweet->user->screen_name])) {
		
			//  and we are at max for this user, skip it
			if ($tb_tweet_counts_per_source[$tweet->user->screen_name] >= $tb_o['filter_limit_per_source']) {
				return false;
			}
			else {
				$tb_tweet_counts_per_source[$tweet->user->screen_name]++;
			}
		}
		else {
			$tb_tweet_counts_per_source[$tweet->user->screen_name] = 1;
		}
	}
	
	// add this tweet to seen tweets
	$tb_seen_tweets[] = $tweet->text;
	
	return true;
}


// creates all HTML for a tweet using current configuration
function tb_tweet_html($tweet,$mode = 'widget',$tb_o) {

	// first make sure this tweet should be shown considering current filters
	if (!tb_tweet_is_ok_to_show($tweet, $tb_o)) {
		return '';
	}
	
 	// add screen name if from_user is given
	if (!isset($tweet->user)) {
		$user = new stdClass();
		if (isset($tweet->from_user)) {
			
			$user->screen_name = $tweet->from_user;
			$tweet->user = $user;
		}
		else {
			$user->screen_name = '';
			$tweet->user = $user;
		}
	}

	if (isset($TB_sourceNames[strtolower($tweet->user->screen_name)])) {
		$tweet->user->alias = $TB_sourceNames[strtolower($tweet->user->screen_name)];
	}
	else {
		$tweet->user->alias = null;
	}

	$patterns = array(); $replacements = array();
	// link URLs
	$patterns[] = '/(https?:\/\/\S+)/';
	$replacements[] = '<a rel="nofollow" href="$1">$1</a>';
	// link screen names
	$patterns[] = '/\@([\w]+)/';
	$replacements[] = '<a rel="nofollow" href="http://twitter.com/$1">@$1</a>';
	// link hashtags
	$patterns[] = '/\#(\S+)/';
	$replacements[] = '<a rel="nofollow" href="http://twitter.com/search?q=%23$1">#$1</a>';

	if (sizeof($patterns) > 0) {
		$tweet->text = preg_replace($patterns,$replacements,$tweet->text);
	}

	// date
	$tweet_date = tb_str2time($tweet->created_at);
	if ($tb_o['general_timestamp_format']) {
		if(!version_compare(PHP_VERSION, '5.1.0', '<')) {
			date_default_timezone_set(get_option('timezone_string'));
		}
		$date_html = date($tb_o['general_timestamp_format'],$tweet_date);
	}
	else {
		$date_html = tb_verbal_time($tweet_date);
	} 

	// if source is not url encoded -> use as is
	if (isset($tweet->source) && strpos($tweet->source,'&lt;') === false) {
		$source_html = $tweet->source;
	}
	// else decode
	else {
		$source_html = html_entity_decode($tweet->source);
	}


	$tweet_template = '';
	
	$tweet_template .= '<div class="tb_tweet" id="{0}">';

	// photo
	$tweet_template .= '<a class="tb_photo" rel="nofollow" href="http://twitter.com/{1}"><img src="{2}" alt="{1}" /></a>';

	// author
	$tweet_template .= '<span class="tb_author">{7}<br /><a rel="nofollow" href="http://twitter.com/{1}">@{1}</a>: </span> ';

	// tweet text	
	$tweet_template .= '<span class="tb_msg">{3}</span><br/>';

	// start tweet footer with info
	if (empty($tb_o['general_seo_tweets_googleoff']) && $tb_o['general_seo_footer_googleoff']) {
		$tweet_template .= '<!--googleoff: index-->';
	}
	$tweet_template .= ' <span class="tb_tweet-info">';
	
	// show timestamp
	$tweet_template .= '<a rel="nofollow" href="http://twitter.com/{1}/statuses/{4}">{5}</a>';
	
	// show source if requested
	if ($tb_o[$mode . '_show_source'] && isset($tweet->source)) {
		$tweet_template .= ' ' . __('from','tweetblender') . ' {6}';
	}
	
	// end tweet footer
	$tweet_template .= '</span>';
	if (empty($tb_o['general_seo_tweets_googleoff']) && $tb_o['general_seo_footer_googleoff']) {
		$tweet_template .= '<!--googleon: index-->';
	}
	
	// add tweet tools
	if ($tb_o[$mode . '_show_follow_link'] || $tb_o[$mode . '_show_reply_link']) {
		$tweet_template .= '<div class="tweet-tools" style="display:none;">';
		if ($tb_o[$mode . '_show_reply_link']) {
			$tweet_template .= '<a rel="nofollow" href="http://twitter.com/home?status=@{1}%20&in_reply_to_status_id={4}&in_reply_to={1}">' . __('reply','tweetblender') . '</a>';
		}
		if ($tb_o[$mode . '_show_follow_link'] && $tb_o[$mode . '_show_reply_link']) {
			$tweet_template .= ' | ';
		}
		if ($tb_o[$mode . '_show_follow_link']) {
			$tweet_template .= '<a rel="nofollow" href="http://twitter.com/{1}">' . __('follow','tweetblender') . ' {1}</a>';
		}
		$tweet_template .= '</div>'; 
	}

	// end tweet	
	$tweet_template .= "</div>\n";
 
	return str_replace(
		array(
			'{0}','{1}','{2}','{3}','{4}','{5}','{6}','{7}'
		),
		array(
			$tweet->div_id,	// {0}
			$tweet->user->screen_name,	// {1}
			$tweet->user->profile_image_url,	// {2}
			$tweet->text, // {3}
			$tweet->id_str, // {4}
			$date_html, // {5}
			$source_html, // {6}
			$tweet->user->name // {7}
		),
		$tweet_template
	);
}

function tb_page_exists($id) {
	global $wpdb;
	return $wpdb->get_row("SELECT id FROM $wpdb->posts WHERE id = $id && post_status = 'publish' && post_type = 'page'", 'ARRAY_N');
}



function tb_get_archive_post_id() {
	$tb_o = get_option('tweet-blender');
	// if archive is disabled return null
	if (isset($tb_o['archive_is_disabled']) && $tb_o['archive_is_disabled']) {
		return null;
	}

	// if we already have page id saved as option, return it
	if ($tb_o && array_key_exists('archive_page_id',$tb_o) && $tb_o['archive_page_id'] > 0 && tb_page_exists($tb_o['archive_page_id'])) {
		return $tb_o['archive_page_id'];
	}
	// else if we have such a page already, get its id and store as option
	else if ($post = get_page_by_path('tweets-archive')) {
		$tb_o['archive_page_id'] = $post->ID;
		update_option('tweet-blender',$tb_o);
		return $tb_o['archive_page_id'];
	}
	// else create such a page (unless an over-ride by user is provided)
	else if (isset($tb_o['archive_auto_page']) && $tb_o['archive_auto_page']) {
		if ($post_id = wp_insert_post(array(
			  'post_status' => 'publish',
			  'post_type' => 'page',
			  'post_author' => 1,
			  'post_title' => __('Twitter Feed' , 'tweetblender'),
			  'post_content' => __('Our twitter feed', 'tweetblender'),
			  'post_name' => 'tweets-archive'
		))) {
			$tb_o['archive_page_id'] = $post_id;
			update_option('tweet-blender',$tb_o);
			return $tb_o['archive_page_id'];
		}
		else {
			return null;
		}
	}
	else {
		return null;
	}
}

function tb_get_current_page_url() {
	$page_url = 'http';
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$page_url .= "s";
	}
	$page_url .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}
	else {
		$page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $page_url;
}


function tb_download_package($item_number) {

	global $tb_package_names;

	if ($item_number <= 0) {
		echo __('ID of the addon to install not provided', 'tweetblender') . '... ' . __('aborting', 'tweetblender');
		return;
	}
	
	// get user-friendly package name
	$package_name = $tb_package_names[$item_number];	

	// Show status updates on screen
	echo "<h3>" . sprintf(__('Installing %s for Tweet Blender','tweetblender'), $package_name) . "</h3>\n";

	// get purchase transaction id
	$txn_id = tb_get_txn_id($item_number);
	if (!isset($txn_id) || strlen($txn_id) < 10) {
		echo __('Can not confirm purchase', 'tweetblender') . '... ' . __('aborting', 'tweetblender');
		return;
	}

	// Download
	echo __("Downloading package",'tweetblender') . "... \n";
	$response = wp_remote_get('http://tweetblender.com/download.php?item_number=' . $item_number . '&blog_url=' . urlencode(get_bloginfo('url')) . '&txn_id=' . $txn_id);
	
	// if couldn't download - report error
	if(is_wp_error($response)) {
		echo __('not able to download', 'tweetblender') . '. ' . __('Error', 'tweetblender') . ': ' . $response->get_error_message();
		return;
	}
	// else, if validation error
	elseif(isset($response['headers']['validation-error'])) {
		echo __('not able to download', 'tweetblender') . '. ' . __('Error', 'tweetblender') . ': ' . $response['headers']['validation-error'];
		return;
	}
	// else - proceed to save
	else {
		
		$package_file_name = $response['headers']['package-file-name'];

		// form file name
		$file_name = WP_PLUGIN_DIR . '/'. $package_file_name . '.zip';
		
		// if couldn't save - report error
		if (file_put_contents($file_name,wp_remote_retrieve_body($response)) === false) {
			echo sprintf(__("unable to save file %s. Check directory permissions",'tweetblender'),$file_name);
			return;
		}
		// else - proceed to unzip
		else {
			echo __('done', 'tweetblender') . "<br />";
	
			// Unpack
			echo __('Unpacking', 'tweetblender') . "... \n";
			class_exists('PclZip') || include_once dirname(__FILE__) . '/pclzip.lib.php';
			$archive = new PclZip($file_name);
			
			// if can't unzip - report error
			if (($v_result_list = $archive->extract(PCLZIP_OPT_SET_CHMOD, 0777, PCLZIP_OPT_PATH, WP_PLUGIN_DIR)) == 0) {
				echo __('unable to unzip', 'tweetblender') . '. ' . __('Error','tweetblender') . ': ' . $archive->get_error_message();
				return;
			}
			// else - proceed to activate
			else {

				echo __('done', 'tweetblender') . "<br />";

				// clean up by removing zip file
				unlink($file_name);			
					
				// Activate
				echo __('Activating', 'tweetblender') . "... \n";
				$activation = activate_plugin(WP_PLUGIN_DIR . '/' . $package_file_name . '/' . $package_file_name . '.php');
				
				// if can't activate - report error
				if (is_wp_error($activation)) {
					echo  __('unable to activate, please try to do it manually', 'tweetblender') . '. ' . __('Error', 'tweetblender') . ': ' . $activation->get_error_message();
					return;
				}
				// else - wrap it up
				else {
					echo __('done', 'tweetblender') . "<br />";
					
					/* TODO: change permissions to the same as ours
					$info = stat(__FILE__);
					$info['uid'];
					$info['gid']; */

					// Done, link to admin
					$url = tb_get_current_page_url();
					$url = str_replace('&install_addon=1', '', $url);
					echo __('All Done!', 'tweetblender') . "<br /><br /><a href='$url'>" . __('Start using', 'tweetblender') . ' ' . $package_name . "</a><br /><br />";
				}
			}
		}
	}	
}

function tb_chmod_R($path, $filemode) {
 
    $dh = opendir($path);
    while ($file = readdir($dh)) {
        if($file != '.' && $file != '..') {
            $fullpath = $path.'/'.$file;
			chmod($fullpath, $filemode);
            if(is_dir($fullpath)) {
 
               chmod_R($fullpath, $filemode);
 
            } 
        }
    }
 
    closedir($dh);    
}

function tb_get_txn_id($item_number) {
	
	// check WP options
	$txn_id = get_option('txn_id_' . $item_number);
	if (isset($txn_id) && strlen($txn_id) > 10) {
		return $txn_id;
	}
	// if not found check recovery file
	else {
		$txn_id = trim(@file_get_contents(WP_PLUGIN_DIR . '/tweet-blender/' . $item_number . '.txt'));
		
		if (isset($txn_id) && strlen($txn_id) > 10) {
			
			// update option
			update_option('txn_id_'.$item_number,$txn_id);
			
			// remove file
			unlink(WP_PLUGIN_DIR . '/tweet-blender/' . $item_number . '.txt');
			
			// return
			return $txn_id;
		}
		else {
			return null;
		}		
	}
}

function tb_save_txn_id($item_number,$txn_id) {
	// save to WP options
	update_option('txn_id_'.$item_number,$txn_id);
}

function tb_get_cache_stats($sources = null,$period = null) {
	
	global $wpdb;
	$table_name = $wpdb->prefix . "tweetblender";
	
	$sql = "SELECT source, COUNT(*) AS tweets_num, UNIX_TIMESTAMP(MAX(created_at)) AS last_update FROM " . $table_name;
	
	// if we need stats only for specific sources
	$where_parts = array();
	if (isset($sources)) {
		$where_parts[] = 'source IN ("' . implode('","',$sources) . '")';	
	}
	// if we need stats for a specific period
	if (isset($period) && in_array($period,array('hour','day','week','month'))) {
		$where_parts[] = 'created_at >= DATE_SUB(NOW(),INTERVAL 1 ' . $period . ')';
	}
	if (sizeof($where_parts) > 0) {
		$sql .= ' WHERE ' . join(' AND ',$where_parts);
	}

	$sql .= " GROUP BY source";
		
	$results = $wpdb->get_results($sql);
	return $results;
}

// check for addons
function tb_check_addons() {

	global $tb_installed_addons, $tb_active_addons, $tb_addons;
	
	foreach($tb_addons as $addon_id => $addon) {
		$addon_file = $addon['slug'] . '/' . $addon['slug'] . '.php';
		if (file_exists(WP_PLUGIN_DIR . '/' . $addon_file)) { 
			$tb_installed_addons[$addon_id] = true;
			
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
			if(is_plugin_active($addon_file)) {
				$tb_active_addons[$addon_id] = true;
			}
			else {
				$tb_active_addons[$addon_id] = false;
			}
		}
		else {
			$tb_installed_addons[$addon_id] = false;
		}
	}
	
	return false;
}

?>