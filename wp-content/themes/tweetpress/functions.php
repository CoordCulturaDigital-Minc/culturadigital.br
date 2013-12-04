<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */
?>
<?php
add_action('admin_menu', 'tweetpress_user_update');
function tweetpress_user_update() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == basename(__FILE__) ) {
		if ( isset( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {
			check_admin_referer('tweetpress-console');
			if (isset($_REQUEST['tp_twitter_username'])) update_option('tp_twitter_username', $_REQUEST['tp_twitter_username']);
			if (isset($_REQUEST['tp_twitter_password']) && isset($_REQUEST['tp_password_verify'])) {
				if ($_REQUEST['tp_twitter_password'] == $_REQUEST['tp_password_verify']) {
					update_option('tp_twitter_password', $_REQUEST['tp_twitter_password']);
				} else {
					delete_option('tp_twitter_password');
					wp_redirect("themes.php?page=functions.php&error=true");
					die;
				}
			} else {
				delete_option('tp_twitter_password');
				wp_redirect("themes.php?page=functions.php&error=true");
				die;
			}
			if (isset($_REQUEST['tp_feedburner_account_name'])) {
				update_option('tp_feedburner_account_name', $_REQUEST['tp_feedburner_account_name']);
			}
			if (isset($_REQUEST['tp_ga_tracker_key'])) {
				update_option('tp_ga_tracker_key', $_REQUEST['tp_ga_tracker_key']);
			}
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
		}
	}
	add_theme_page(__('TweetPress Admin'), __('TweetPress Admin'), 'edit_themes', basename(__FILE__), 'twitter_theme_page');
}

function twitter_theme_page() {
	if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Settings saved.').'</strong></p></div>';
	if ( isset( $_REQUEST['error'] ) ) echo '<div id="message" class="error fade"><p><strong>'.__('Please verify password.').'</strong></p></div>';
	?>
	<div class="wrap">
		<h2><?php _e('TweetPress Admin Console'); ?></h2>
		<form method="post" action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>">
			<?php wp_nonce_field('tweetpress-console'); ?>
			<label for="tp_twitter_username">Twitter Username: </label>
			<input type="text" name="tp_twitter_username" value="<?php echo esc_attr(twitter_username()); ?>" />
			<br/>
			<label for="tp_twitter_password">Twitter Password: </label>
			<input type="password" name="tp_twitter_password" value="<?php echo esc_attr(twitter_password()); ?>" />
			<br/>
			<label for="tp_password_verify">Verify Password: </label>
			<input type="password" name="tp_password_verify" value="<?php echo esc_attr(twitter_password()); ?>" />
			<br/>
			<label for="tp_feedburner_account_name">Feedburner Account Name: </label>
			<input name="tp_feedburner_account_name" type="text" value="<?php echo esc_attr(feedburner_account_name()); ?>" />
			<br/>
			<label for="tp_ga_tracker_key">Google Analytics Tracker Key: </label>
			<input type="text" name="tp_ga_tracker_key" value="<?php echo esc_attr(google_analytics_key()); ?>" />
			<input type="hidden" name="action" value="save" />
			<p class="submit"><input type="submit" name="submitform" class="button-primary" value="<?php esc_attr_e('Save Settings'); ?>" /></p>
		</form>
	</div>
	<?php
}
function twitter_username() {
	return apply_filters('tp_twitter_username',get_option('tp_twitter_username'));
}
function twitter_password() {
	return apply_filters('tp_twitter_password',get_option('tp_twitter_password'));
}
function feedburner_account_name() {
	return apply_filters('tp_feedburner_account_name',get_option('tp_feedburner_account_name'));
}
function google_analytics_key() {
	return apply_filters('tp_ga_tracker_key',get_option('tp_ga_tracker_key'));
}
// Add feed link to address bar
automatic_feed_links();
// Twitter user object - don't touch
$t = null;

// Register sidebar for widgets
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<hr /><h2 class="sidebar-title collapsible">',
		'after_title' => '</h2>',
	));
}

// Remove pages from search queries
add_filter('pre_get_posts', 'exclude_page_filter');
function exclude_page_filter($query) {
	if($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}

// Add Twitter profile image to avatar list
add_filter('avatar_defaults', 'tweetpress_gravatar');
function tweetpress_gravatar($avatar_defaults) {
	$myavatar = get_bloginfo('template_url') . '/images/default_profile_1_normal.png';
    $avatar_defaults[$myavatar] = 'TweetPress';
    return $avatar_defaults;
}

// Helper function - determines whether site is WPMU or a single WP instance
function get_files_path() {
	global $blog_id;
	if (function_exists('is_site_admin')) {
		return WP_CONTENT_DIR.'/blogs.dir/'.$blog_id.'/files/';
	} else {
		return WP_CONTENT_DIR.'/files/';
	}
}

// Create files directory in wp-content for user.xml and friends directory
add_action('wp_head', 'create_files_dir');
function create_files_dir() {
	if (!is_dir(get_files_path())) {
		mkdir(get_files_path(), 0777, true);
	}
	if (!is_dir(get_files_path().'friends/')) {
		mkdir(get_files_path().'friends/', 0777, true);
	}
}

// Create Twitter user xml and global object
add_action('wp_head', 'create_twitter_user');
function create_twitter_user() {
	global $t;
	$screen_name = twitter_username();
	$user = get_files_path().$screen_name.'.xml';
	if(file_exists($user)) {
		$fmod = filemtime($user);
		if(($fmod+3600) < time()) {
			$update = true;
		} else {
			$xml = simplexml_load_file($user);
			//print_r($xml);
		}
	} else {
		$update = true;
	}
	if ($update) {
		$apiurl = "http://twitter.com/users/show.xml?screen_name=" . $screen_name;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $apiurl);
		$data = curl_exec($ch);
		curl_close($ch);
		$xml = new SimpleXMLElement($data);
		if (!$xml->error) {
			$fp = fopen($user, "w");
			fwrite($fp, $xml->asXML());
			fclose($fp);
		}
	}
	$t = $xml;
}

// Creates Twitter friends xml files
add_action('wp_head', 'create_twitter_friends');
function create_twitter_friends() {
	$screen_name = twitter_username();
	$count = 18;
	$friends_dir = get_files_path().'friends';
	$scan = scandir($friends_dir);
	if (2 == count($scan)) {
		$update = true;
	} else {
		if ((filemtime($friends_dir.'/'.$scan[2]) + 86400) < time()) $update = true;
	}
	if ($update) {
		foreach($scan as $file) {
			if($file!='.'&&$file!='..') unlink($friends_dir . '/' .$file);
		}
		$apiurl = "http://twitter.com/friends/ids.xml?screen_name=" . $screen_name;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $apiurl);
		$data = curl_exec($ch);
		curl_close($ch);
		$xml = new SimpleXMLElement($data);
		$fids = $xml->children();
		foreach($fids as $fid) {
			if($count==0) continue;
			$count--;
			$apiurl = "http://twitter.com/users/show.xml?user_id=" . $fid;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $apiurl);
			$data = curl_exec($ch);
			curl_close($ch);
			$xml = new SimpleXMLElement($data);
			if(!$xml->error) {
				$file = $friends_dir . '/' . $fid . '.xml';
				$fp = fopen($file, "w");
				fwrite($fp, $xml->asXML());
				fclose($fp);
				chmod($file, 0755);
			}
		}
	}
}

// Adds Twitter styles specific to user
add_action('wp_head', 'twitter_styles');
function twitter_styles() {
	global $t;
	$repeat = ($t->profile_background_tile=='true') ? " 0 0;" : " no-repeat fixed 0 0;";
	$style = "<style type='text/css' media='screen' rel='stylesheet'>";
	$style .= "body {
		background:#".$t->profile_background_color." url(".$t->profile_background_image_url.")" . $repeat . "
		color:#".$t->profile_text_color.";
	}
	a {
		color:#".$t->profile_link_color.";
	}
	ol.posts span.post-body a {
		color:#".lighten($t->profile_link_color,1.35).";
	}
	#sidebar {
		background:#".$t->profile_sidebar_fill_color.";
		border-left: 1px solid #".$t->profile_sidebar_border_color.";
	}
	#side hr {
		background:#".$t->profile_background_color." scroll 0 0;
		color:#".$t->profile_background_color.";
	}
	ul.sidebar-menu li.current_page_item a {
		background-color:#".lighten($t->profile_sidebar_fill_color, .55).";
	}
	ul.sidebar-menu li:hover a, .widget_categories ul > li:hover a, .widget_links ul > li:hover a, 
	.widget_archive ul > li:hover a, .widget_meta ul > li:hover a, .widget_pages ul > li:hover a {
		background-color:#".lighten($t->profile_sidebar_fill_color, .55).";
	}

	#custom_search.current_page_item {
		background-color:#".lighten($t->profile_sidebar_fill_color, .55).";
	}
	#wp-calendar tr > td {
		background-color:#".lighten($t->profile_sidebar_fill_color,.6).";
	}
	#wp-calendar #today {
		background-color:#".lighten($t->profile_sidebar_fill_color,1.5).";
	}";
	$style .= "</style>";
	echo $style;
}

// Displays Twitter friend images in Followers list
function get_twitter_friends() {
	$friends_dir = get_files_path().'friends';
	$fdir = scandir($friends_dir);
	foreach($fdir as $file) {
		if(($file == '.')||($file=='..')) {
			// empty file(dir)
		} else {
			$xml = simplexml_load_file($friends_dir . '/' . $file);
			$f = $xml;
			?>
			<li>
				<a href="<?php if($f->url!='') { echo $f->url; } else { echo 'http://twitter.com/' . $f->screen_name; } ?>">
					<img alt="<?php echo $f->screen_name; ?>" src="<?php echo $f->profile_image_url; ?>" height="24px" width="24px" />
				</a>
			</li>
			<?php
		}
	}
}

// Posts Twitter status - available when logged in
function post_twitter_status($txt='') {
	$screen_name = twitter_username();
	$passwrd = twitter_password();
	$user = get_files_path().$screen_name.'.xml';
	if($txt == '') return;
	$txt = urlencode(stripslashes(urldecode($txt)));
	$headers = array("X-Twitter-Client"=>"TweetPress","X-Twitter-Client-Version"=>"2.0");
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,"http://twitter.com/statuses/update.xml");
	curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,"status=$txt&source=tweetpress");
	curl_setopt($ch,CURLOPT_USERPWD,"$screen_name:$passwrd");
	$data = curl_exec($ch);
	curl_close($ch);
	$xml = new SimpleXMLElement($data);
	if(!$xml->error) {
		$usrxml = simplexml_load_file($user);
		$usrxml->status->created_at = $xml->created_at;
		$usrxml->status->id = $xml->id;
		$usrxml->status->text = $xml->text;
		$usrxml->status->source = $xml->source;
		$usrxml->status->truncated = $xml->truncated;
		$usrxml->status->in_reply_to_status_id = $xml->in_reply_to_status_id;
		$usrxml->status->in_reply_to_user_id = $xml->in_reply_to_user_id;
		$usrxml->status->favorited = $xml->favorited;
		$usrxml->status->in_reply_to_screen_name = $xml->in_reply_to_screen_name;
		$fp = fopen( $user, "w" );
		fwrite( $fp, $usrxml->asXML() );
		fclose( $fp );
		touch($user,(time()-3600));
		return $xml->text;
	} else {
		return false;
	}
}

// Displays time as '... ago'
function format_time_ago($timestamp, $granularity = 1) {
	$date = strtotime($timestamp);
	$diff = time() - $date;
	if(($diff/86400)>1) {
		return date('g:i A M jS Y',strtotime($timestamp));
	} else {
		$periods = array('hour' => 3600,
			'minute' => 60,
			'second' => 1
		);
		foreach($periods as $key => $value) {
			if($diff >= $value) {
				$time = floor($diff/$value);
				$diff %= $value;
				$retval .= ($retval ? ' ' : '').$time.' ';
				$retval .= (($time>1) ? $key.'s' : $key);
				$granularity--;
			}
			if($granularity==0)break;
		}
		if(strrpos($retval,'minute')!==false) {
			if($time<10) return $retval.' ago';
			else return 'about ' . $retval . ' ago';
		} else {
			return ' about '.$retval.' ago';
		}
	}
}

// Replace plain text URL's into links and @<user> to Twitter links
function format_status($status) {
	$status = preg_replace("/(http:\/\/[^\s]+)/", "<a href=\"$1\">$1</a>", $status);
	echo preg_replace('/(?<=@)(\w+)/', "<a href='http://twitter.com/$0'>$0</a>" , $status);
	 
}

// Replace plain text URL's into links and @<user> to Twitter links
function format_excerpt($excerpt) {
	$excerpt = (strlen($excerpt)>140) ? substr($excerpt,0,137) . '...' : $excerpt;
	preg_match("/(http:\/\/[^\s]+)/", $excerpt, $matches);
	if(!$matches || !strrpos($matches[0],'...')) {
		$excerpt = preg_replace("/(http:\/\/[^\s]+)/", "<a target='_blank' href=\"$1\">$1</a>", $excerpt);
	}
	return $excerpt;
}

// Returns your own retweet url using bitly api
function retweet_url($url,$headline) {
	$screen_name = twitter_username();
	if(!$url || $url=='') return;
	return 'http://twitter.com/?status=RT @' . $screen_name . ': ' . $headline . ' ' . bitly_url($url);
}

// Bit.ly api stuff - default is TweetPress key, change to your own if you like, but you dont have to
function bitly_url($url) {
	$bitly_id = "tweetpress";
	$bitly_key = "R_5e496e175db584ab27410d1d4824870a";
	$apiurl = "http://api.bit.ly/shorten?version=2.0.1&format=xml&login=$bitly_id&apiKey=$bitly_key&longUrl=$url";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $apiurl);
	$data = curl_exec($ch);
	$xml = new SimpleXMLElement($data);
	if($xml->errorCode==0) {
		return $xml->results->nodeKeyVal->shortUrl;
	}
}

// Returns Feedburner url
function feedburner_url() {
	$fbacct = feedburner_account_name();
	echo 'http://feeds.feedburner.com/'.$fbacct;
}

// Returns Feedburner count
function feedburner_count() {
	$fbacct = feedburner_account_name();
	$apiurl = "http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=" . $fbacct;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $apiurl);
	$data = curl_exec($ch);
	curl_close($ch);
	$xml = new SimpleXMLElement($data);
	$count = $xml->feed->entry['circulation'];
	if(!$count) $count = '??????';
	echo number_format($count, 0, '.', ',');
}

// Color functions...
function lighten($hex, $pct) {
	$rgb = hex2rgb($hex);
	for($i = 0; $i < 3; $i++) {
		$rgb[$i] = ceil( ($rgb[$i]*$pct)+(255*(1-$pct)) );
		if( $rgb[$i]>255 ) $rgb[$i] = 255;
	}
	return rgb2hex( $rgb );
}
function hex2rgb($hex) {
	$d = '[a-fA-F0-9]';
	if(preg_match("/^($d$d)($d$d)($d$d)\$/", $hex, $rgb)) {
  		return array(
   			hexdec($rgb[1]),
   			hexdec($rgb[2]),
   			hexdec($rgb[3])
   		);
	}
	if(preg_match("/^($d)($d)($d)$/", $hex, $rgb)) {
  		return array(
   			hexdec($rgb[1] . $rgb[1]),
   			hexdec($rgb[2] . $rgb[2]),
   			hexdec($rgb[3] . $rgb[3])
   		);
	}
	return false;
}
function rgb2hex($rgb) {
	$hex = "";
 	for($i=0; $i < 3; $i++) {
  		$hexDigit = dechex($rgb[$i]);
  		if(strlen( $hexDigit ) == 1) {
				$hexDigit = "0" . $hexDigit;
		}
  		$hex .= $hexDigit;
	}
	return $hex;
}
?>