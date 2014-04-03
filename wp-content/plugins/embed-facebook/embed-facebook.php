<?php
/*
Plugin Name: Embed Facebook
Plugin URI: http://sohailabid.com/projects/embed-facebook/
Description: Embed various facebook objects (album, photo, event, video, page, group, or note) by just pasting the URL anywhere in a WordPress page or post
Author: Sohail Abid
Version: 1.4
Author URI: http://www.sohailabid.com
*/

include_once('json.php');
add_action('wp_head', 'sohail_embed_facebook_head');

define('SOHAIL_EMBED_FACEBOOK_URL', get_option( 'siteurl' ). '/wp-content/plugins/embed-facebook');

function sohail_embed_facebook_head() {

	echo '<script type="text/javascript" src="'.SOHAIL_EMBED_FACEBOOK_URL.'/slidewindow/slidewindow_min.js"></script>' . "\n";
	echo '<script type="text/javascript">currPath = "'.SOHAIL_EMBED_FACEBOOK_URL.'/slidewindow/"; strImgOf="";</script>' . "\n";
	echo "<style>
	.sohailerror { border:1px solid #eee; background:#f9f9f9; padding:10px; margin-bottom:15px;}
	.sohailfbbox { margin:0 0 25px 0; padding:0; font-family: 'lucida grande', tahoma, verdana; font-size: 12px; line-height:18px; border: 1px solid #C6CEDD; border-top-color:#315C99; }
	#content .sohailfbbox a, .sohailfbbox a { color:#3B5998;text-decoration:none; }
	#content .sohailfbbox a:hover, .sohailfbbox a:hover { color:#3B5998;text-decoration:underline; }
	.sohailfbboxhead { margin: 0; padding: 10px; font-size: 17px; border-bottom: 1px solid #D8DFEA; background: #EDEFF4; height:40px; }
	.sohailfbboxhead span { margin: 0; padding: 0px; font-size: 12px; }
	.sohailfbboxbody { margin: 0; padding: 12px 10px 8px 10px; }
	.sohailfbboxinfo { margin: 0 0 0 10px; padding: 10px; float:right; width:250px; border:1px solid #D8DFEA; background:#EDEFF4; tex-align:right; font-size:11px; }
	#content .sohailfbthumb, .fbalbumpics .sohailfbthumb { border:1px solid #ccc; padding:4px; margin-right:6px; width:130px; height:98px; }
</style>\n";
}    
 

add_filter('the_content', 'sohail_embed_facebook');

function sohail_embed_facebook($the_content) {

	preg_match_all("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", $the_content, $matches, PREG_SET_ORDER);
	
    foreach($matches as $match) {
    	$the_content = preg_replace("/<p>(http|https):\/\/www\.facebook\.com\/([^<\s]*)<\/p>/", sohail_do_embed($match), $the_content, 1);
    }

	return $the_content;
}


function sohail_do_embed($url) {
	
	$raw_query_string = explode('?', $url[2]);
	
	$query_string = str_replace(array('#038;', '&amp;'), array('&', '&'), $raw_query_string[1]);
	
	parse_str($query_string, $query_vars);

	if(!$query_vars) {
		
		return sohail_do_embed_profile($raw_query_string[0], $url[2]);

	} else {
				
		if(strpos($raw_query_string[0], 'media/set/') !== false)
		
			return sohail_do_embed_new_album($query_vars, $url[2]);
			
		elseif(strpos($raw_query_string[0], 'media/set/fbx/') !== false)
		
			return sohail_do_embed_new_album($query_vars, $url[2]);
			
		elseif(strpos($raw_query_string[0], 'album.php') !== false)
		
			return sohail_do_embed_album($query_vars, $url[2]);
			
		elseif(strpos($raw_query_string[0], 'video.php') !== false)
		
			return sohail_do_embed_video($query_vars, $url[2]);
			
		elseif(strpos($raw_query_string[0], 'photo.php') !== false)
		
			return sohail_do_embed_photo($query_vars, $url[2]);
			
		elseif(strpos($raw_query_string[0], 'event.php') !== false)
		
			return sohail_do_embed_event($query_vars, $url[2]);
			
		elseif(strpos($raw_query_string[0], 'group.php') !== false)
		
			return sohail_do_embed_group($query_vars, $url[2]);
			
		elseif(strpos($raw_query_string[0], 'note.php') !== false)
		
			return sohail_do_embed_note($query_vars, $url[2]);
			
		else
		
			return "<p><a href='".strip_tags($url[0])."' target='_blank'>".strip_tags($url[0])."</a></p>";
	}
}

function sohail_do_embed_profile($query_vars, $url) {

	if(strpos($query_vars, 'pages/') == 0)
		$fb_profile = "http://graph.facebook.com/". end(explode('/', $query_vars));
	else
		$fb_profile = "http://graph.facebook.com/{$query_vars}";
	
	if (function_exists("curl_init"))
		$fb_profile = @curl_get_content($fb_profile);
	else
		$fb_profile = @file_get_contents($fb_profile);
	
	if(function_exists("json_decode"))
		$fb_profile = json_decode($fb_profile);
	else
		$fb_profile = sohail_json_decode($fb_profile);
	
	if(!isset($fb_profile->name)) {
	
		$return = "<p><a href='http://www.facebook.com/$url'>http://www.facebook.com/$url</a></p>";
		if(is_user_logged_in())
			$return .= '<div class="sohailerror">Error: Couldn\'t embed the profile.<br />(this message is visible to logged in users only)</div>';
	
	} else {
	
		if($fb_profile->category == 'Television') {
			$return .= "<div class='sohailfbbox'>\n";
			$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/{$fb_profile->id}/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/page.png' style='vertical-align:text-top' /> {$fb_profile->name}<br /><span>{$fb_profile->category} ({$fb_profile->genre}) &nbsp;|&nbsp; {$fb_profile->fan_count} fans &nbsp;|&nbsp; <a href='http://www.facebook.com/profile.php?id={$fb_profile->id}' target='_blank'>View on Facebook</a></span></div>\n"; 
			$return .= "<div class='sohailfbboxbody'>\n";
			$return .= "<p>";
			if($fb_profile->network) $return .= "Network: {$fb_profile->network} / ";
			if($fb_profile->directed_by) $return .= "Directed by: {$fb_profile->directed_by} / ";
			if($fb_profile->starring) $return .= "Starring: {$fb_profile->starring}";
			$return .= "</p>";
			
			$return .= "{$fb_profile->plot_outline}";
			$return .= "</div>\n";
			$return .= "</div>\n";
			
		} elseif($fb_profile->category == 'Public_figures_other') {
		
			$return .= "<div class='sohailfbbox'>\n";
			$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/{$fb_profile->id}/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/page.png' style='vertical-align:text-top' /> {$fb_profile->name}<br /><span>Public Figure &nbsp;|&nbsp; {$fb_profile->fan_count} fans &nbsp;|&nbsp; <a href='http://www.facebook.com/profile.php?id={$fb_profile->id}' target='_blank'>View on Facebook</a></span></div>\n"; 
			$return .= "<div class='sohailfbboxbody'>\n";
			$return .= "{$fb_profile->personal_info}";
			$return .= "</div>\n";
			$return .= "</div>\n";
		
		} else {
			$return .= "<div class='sohailfbbox'>\n";
			$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/{$fb_profile->id}/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/page.png' style='vertical-align:text-top' /> {$fb_profile->name}<br /><span>{$fb_profile->category} {$fb_profile->genre} &nbsp;|&nbsp; {$fb_profile->fan_count} fans &nbsp;|&nbsp; <a href='http://www.facebook.com/profile.php?id={$fb_profile->id}' target='_blank'>View on Facebook</a></span></div>\n"; 
			$return .= "<div class='sohailfbboxbody'>\n";

		if(strlen($fb_profile->company_overview) < 500)
			$return .= nl2br($fb_profile->company_overview);
		else
			$return .= nl2br(substr($fb_profile->company_overview, 0, 500)) . '<span id="'.$fb_profile->id.'" style="display:none">' . nl2br(substr($fb_profile->company_overview, 500)) . '</span><span id="sohailmorelink'.$fb_profile->id.'">... <a href="javascript:void(0)" onclick="javascript:sohail_expand_content(\''.$fb_profile->id.'\')">See full description</a></span>';

			$return .= "</div>\n";
			$return .= "</div>\n";
		}
	
	}
	return $return;
}

function sohail_do_embed_album($query_vars, $url) {

	$fb_albums = "http://graph.facebook.com/{$query_vars[id]}/albums?limit=400";

	if (function_exists("curl_init"))
		$fb_albums = @curl_get_content($fb_albums);
	else
		$fb_albums = @file_get_contents($fb_albums);
	
	if(function_exists("json_decode"))
		$fb_albums = json_decode($fb_albums);
	else
		$fb_albums = sohail_json_decode($fb_albums);
	
	if(!isset($fb_albums->data)) {
	
		$return .= "<p><a href='http://www.facebook.com/$url'>http://www.facebook.com/$url</a></p>";
		
		if(is_user_logged_in())
			$return .= '<div class="sohailerror">Error: Couldn\'t embed the album, are you sure it belongs to a facebook page?<br />(this message is visible to logged in users only)</div>';
	
	} else {
	
		foreach($fb_albums->data as $album) {
			
			if (strpos($album->link, $query_vars['aid'])) {
			
				$return  = "<div class='sohailfbbox'>\n";
				$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/{$album->from->id}/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/photos.png' style='vertical-align:text-top' /> ".substr($album->name, 0, 60)."<br /><span>By <a href='http://www.facebook.com/profile.php?id={$album->from->id}' target='_blank'>{$album->from->name}</a> &nbsp;|&nbsp; <a href='{$album->link}' target='_blank'>View on Facebook</a></span></div>\n"; 
				$return .= "<div class='sohailfbboxbody'>\n";
			
				if($query_vars[limit])
					$fb_photos = "http://graph.facebook.com/{$album->id}/photos?limit={$query_vars[limit]}";
				else
					$fb_photos = "http://graph.facebook.com/{$album->id}/photos?limit=400";
				
				if (function_exists("curl_init"))
					$fb_photos = @curl_get_content($fb_photos);
				else
					$fb_photos = @file_get_contents($fb_photos);
				
				if(function_exists("json_decode"))
					$fb_photos = json_decode($fb_photos);
				else
					$fb_photos = sohail_json_decode($fb_photos);
				
				/*
				if(is_user_logged_in()) {
					print '<pre>';
					print_r($fb_photos);
					print '</pre>';
				}
				*/
				
				if(!isset($fb_photos->data)) {
					$return .= "<p><a href='http://www.facebook.com/$url'>www.facebook.com/$url</a></p>";
					
					if(is_user_logged_in())
						$return .= '<div class="sohailerror">Error: Couldn\'t access the photos inside the album. Please check privacy settings or contact me with album URL: <a href="http://sohailabid.com/">sohailabid.com</a><br />(this message is visible to logged in users only)</div>';
				} else {
					foreach($fb_photos->data as $photo) {
						$return .= "<a href='{$photo->source}' title='{$photo->name}' onclick='return showSlideWindow(this, 600, 400);' class='viewable'><img src='{$photo->picture}' class='sohailfbthumb' style='border:1px solid #ccc; padding:4px; margin-right:6px; width:130px; height:98px;' /></a>";
					}
				}
				$return .= "</div>\n";
				$return .= "</div>\n";
			}
		}
		
	}
	
	return $return;
}

function sohail_do_embed_new_album($query_vars, $url) {
	
	$query_vars = explode('.', $query_vars['set']);

	$fb_albums = "http://graph.facebook.com/{$query_vars[3]}/albums?limit=400";

	if (function_exists("curl_init"))
		$fb_albums = @curl_get_content($fb_albums);
	else
		$fb_albums = @file_get_contents($fb_albums);
	
	if(function_exists("json_decode"))
		$fb_albums = json_decode($fb_albums);
	else
		$fb_albums = sohail_json_decode($fb_albums);
	
	if(!isset($fb_albums->data)) {
	
		$return .= "<p><a href='http://www.facebook.com/$url'>http://www.facebook.com/$url</a></p>";
		
		if(is_user_logged_in())
			$return .= '<div class="sohailerror">Error: Couldn\'t embed the album, are you sure it belongs to a facebook page?<br />(this message is visible to logged in users only)</div>';
	
	} else {
	
		foreach($fb_albums->data as $album) {
			
			if (strpos($album->link, $query_vars[2])) {
			
				$return  = "<div class='sohailfbbox'>\n";
				$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/{$album->from->id}/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/photos.png' style='vertical-align:text-top' /> ".substr($album->name, 0, 60)."<br /><span>By <a href='http://www.facebook.com/profile.php?id={$album->from->id}' target='_blank'>{$album->from->name}</a> &nbsp;|&nbsp; <a href='{$album->link}' target='_blank'>View on Facebook</a></span></div>\n"; 
				$return .= "<div class='sohailfbboxbody'>\n";
			
				if($query_vars[limit])
					$fb_photos = "http://graph.facebook.com/{$album->id}/photos?limit={$query_vars[limit]}";
				else
					$fb_photos = "http://graph.facebook.com/{$album->id}/photos?limit=400";
				
				if (function_exists("curl_init"))
					$fb_photos = @curl_get_content($fb_photos);
				else
					$fb_photos = @file_get_contents($fb_photos);
				
				if(function_exists("json_decode"))
					$fb_photos = json_decode($fb_photos);
				else
					$fb_photos = sohail_json_decode($fb_photos);
				
				/*
				if(is_user_logged_in()) {
					print '<pre>';
					print_r($fb_photos);
					print '</pre>';
				}
				*/
				
				if(!isset($fb_photos->data)) {
					$return .= "<p><a href='http://www.facebook.com/$url'>www.facebook.com/$url</a></p>";
					
					if(is_user_logged_in())
						$return .= '<div class="sohailerror">Error: Couldn\'t access the photos inside the album. Please check privacy settings or contact me with album URL: <a href="http://sohailabid.com/">sohailabid.com</a><br />(this message is visible to logged in users only)</div>';
				} else {
					foreach($fb_photos->data as $photo) {
						$return .= "<a href='{$photo->source}' title='{$photo->name}' onclick='return showSlideWindow(this, 600, 400);' class='viewable'><img src='{$photo->picture}' class='sohailfbthumb' style='border:1px solid #ccc; padding:4px; margin-right:6px; width:130px; height:98px;' /></a>";
					}
				}
				$return .= "</div>\n";
				$return .= "</div>\n";
			}
		}
		
	}
	
	return $return;
}

function sohail_do_embed_video($query_vars, $url) {
	
	$fb_video = 'http://www.facebook.com/video/video.php?v='.$query_vars[v];
	
	if (function_exists("curl_init"))
		$fb_video = @curl_get_content($fb_video);
	else
		$fb_video = @file_get_contents($fb_video);
	
	preg_match_all('/<h3 class="video_title datawrap">(.*?)<\/h3>/', $fb_video, $title, PREG_SET_ORDER);
	preg_match_all('/<a class="video_owner_link" href="(.*?)">(.*?)<\/a>/', $fb_video, $owner, PREG_SET_ORDER);
	
	if(!isset($title[0][1])) {
	
		$return = "<p><a href='http://www.facebook.com/$url'>http://www.facebook.com/$url</a></p>";
		if(is_user_logged_in())
			$return .= '<div class="sohailerror">Error: Couldn\'t embed the video.<br />(this message is visible to logged in users only)</div>';
	
	} else {
	
		$return  = "<div class='sohailfbbox'>\n";
		$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/".end(explode('/', $owner[0][1]))."/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/video.png' style='vertical-align:text-top' /> ".substr($title[0][1], 0, 70)."<br /><span>By {$owner[0][0]} &nbsp;|&nbsp; <a href='http://www.facebook.com/video/video.php?v={$query_vars[v]}' target='_blank'>View on Facebook</a></span></div>\n"; 
		$return .= "<div class='sohailfbboxbody'>\n";
		$return .= "<object width=\"100%\" height=\"40%\" ><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://www.facebook.com/v/{$query_vars[v]}\" /><embed src=\"http://www.facebook.com/v/{$query_vars[v]}\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"100%\" height=\"40%\"></embed></object>";
		$return .= "</div>\n";
		$return .= "</div>\n";
	
	}
	
	return $return;
}

function sohail_do_embed_photo($query_vars, $url) {

	$fb_photo = "http://graph.facebook.com/{$query_vars[fbid]}";
	
	if (function_exists("curl_init"))
		$fb_photo = @curl_get_content($fb_photo);
	else
		$fb_photo = @file_get_contents($fb_photo);
	
	if(function_exists("json_decode"))
		$fb_photo = json_decode($fb_photo);
	else
		$fb_photo = sohail_json_decode($fb_photo);
	
	if(!isset($fb_photo->source)) {
	
		$return = "<p><a href='http://www.facebook.com/$url'>http://www.facebook.com/$url</a></p>";
		if(is_user_logged_in())
			$return .= '<div class="sohailerror">Error: Couldn\'t embed the photo, are you sure it belongs to a facebook Page?<br />(this message is visible to logged in users only)</div>';

	} else {
	
		$return  = "<div class='sohailfbbox'>\n";
		$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/{$fb_photo->from->id}/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/photos.png' style='vertical-align:text-top' /> ".substr($fb_photo->name, 0, 60)."<br /><span>By <a href='http://www.facebook.com/profile.php?id={$fb_photo->from->id}' target='_blank'>{$fb_photo->from->name}</a> &nbsp;|&nbsp; <a href='{$fb_photo->link}' target='_blank'>View on Facebook</a></span></div>\n"; 
		$return .= "<div class='sohailfbboxbody'>\n";
		$return .= "<a href='{$fb_photo->source}' title='{$fb_photo->name}' onclick='return showSlideWindow(this, 600, 400);' class='viewable'><img src='". str_replace('_s.jpg', '_a.jpg', $fb_photo->source) ."' style='max-width:100%' /></a>";
		$return .= "</div>\n";
		$return .= "</div>\n";
	
	}
	
	return $return;
}

function sohail_do_embed_event($query_vars, $url) {

	$fb_event = "http://graph.facebook.com/{$query_vars[eid]}";
	
	if (function_exists("curl_init"))
		$fb_event = @curl_get_content($fb_event);
	else
		$fb_event = @file_get_contents($fb_event);

	if(function_exists("json_decode"))
		$fb_event = json_decode($fb_event);
	else
		$fb_event = sohail_json_decode($fb_event);

	if(!isset($fb_event->name)) {
	
		$return = "<p><a href='http://www.facebook.com/$url'>http://www.facebook.com/$url</a></p>";
		
		if(is_user_logged_in())
			$return .= '<div class="sohailerror">Error: Couldn\'t embed the event, are you sure it belongs to a facebook Page?<br />(this message is visible to logged in users only)</div>';
			
	} else {
	
		$start_time = sohail_iso8601($fb_event->start_time);
		$end_time = sohail_iso8601($fb_event->end_time);
		
		if($fb_event->location)
			$location = $fb_event->location . '<br />';
			
		if($fb_event->venue->street)
			$address = $fb_event->venue->street;
			
		if($fb_event->venue->city)
			$address .= ', ' . $fb_event->venue->city;
			
		if($fb_event->venue->state)
			$address .= ', ' . $fb_event->venue->state;
			
		if($fb_event->venue->country)
			$address .= ', ' . $fb_event->venue->country;
				 
		$return  = "<div class='sohailfbbox'>\n";
		$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/{$fb_event->owner->id}/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/event.png' style='vertical-align:text-top' /> ".substr($fb_event->name, 0, 60)."<br /><span>By <a href='http://www.facebook.com/profile.php?id={$fb_event->owner->id}' target='_blank'>{$fb_event->owner->name}</a> &nbsp;|&nbsp; <a href='http://www.facebook.com/event.php?eid={$fb_event->id}' target='_blank'>View on Facebook</a></span></div>\n"; 
		$return .= "<div class='sohailfbboxbody'>\n";
		$return .= "<div class='sohailfbboxinfo'><b>When:</b> ";
			
			if( date('M j Y', $start_time) == date('M j Y', $end_time) )
				$return .= date('M j, Y', $start_time) . ' (' . date('g:ia', $start_time) . ' - ' . date('g:ia', $end_time) . ')';
			else
				$return .= date('M j', $start_time) . ' '. date('g:ia', $start_time) . ' - ' . date('M j', $end_time) . ' ' . date('g:ia', $end_time);
		
		$return .= "<br /><br /><b>Where:</b> {$location}{$address}<br /><br />&raquo; <a target='_blank' href='http://maps.google.com/maps?q={$address}'>View map</a></div>";
		if(strlen($fb_event->description) < 500)
			$return .= nl2br($fb_event->description);
		else
			$return .= nl2br(substr($fb_event->description, 0, 500)) . '<span id="'.$fb_event->id.'" style="display:none">' . nl2br(substr($fb_event->description, 500)) . '</span><span id="sohailmorelink'.$fb_event->id.'">... <a href="javascript:void(0)" onclick="javascript:sohail_expand_content(\''.$fb_event->id.'\')">See full description</a></span>';
		$return .= "</div>\n";
		$return .= "</div>\n";

	}
	
	return $return;
}

function sohail_do_embed_group($query_vars, $url) {

	$fb_group = "http://graph.facebook.com/{$query_vars[gid]}";
	
	if (function_exists("curl_init"))
		$fb_group = @curl_get_content($fb_group);
	else
		$fb_group = @file_get_contents($fb_group);

	if(function_exists("json_decode"))
		$fb_group = json_decode($fb_group);
	else
		$fb_group = sohail_json_decode($fb_group);

	if(!isset($fb_group->name)) {
	
		$return = "<p><a href='http://www.facebook.com/$url'>http://www.facebook.com/$url</a></p>";

		if(is_user_logged_in())
			$return .= '<div class="sohailerror">Error: Couldn\'t embed the group, are you sure it is public and not private?<br />(this message is visible to logged in users only)</div>';
	
	} else {
	
		$return  = "<div class='sohailfbbox'>\n";
		$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/{$fb_group->owner->id}/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/group.png' style='vertical-align:text-top' /> ".substr($fb_group->name, 0, 60)."<br /><span>By <a href='http://www.facebook.com/profile.php?id={$fb_group->owner->id}' target='_blank'>{$fb_group->owner->name}</a> &nbsp;|&nbsp; <a href='http://www.facebook.com/group.php?gid={$fb_group->id}' target='_blank'>View on Facebook</a></span></div>\n"; 
		$return .= "<div class='sohailfbboxbody'>\n";
		if(strlen($fb_group->description) < 500)
			$return .= nl2br($fb_group->description);
		else
			$return .= nl2br(substr($fb_group->description, 0, 500)) . '<span id="'.$fb_group->id.'" style="display:none">' . nl2br(substr($fb_group->description, 500)) . '</span><span id="sohailmorelink'.$fb_group->id.'">... <a href="javascript:void(0)" onclick="javascript:sohail_expand_content(\''.$fb_group->id.'\')">See full description</a></span>';
		$return .= "</div>\n";
		$return .= "</div>\n";
	
	}
	
	return $return;
}

function sohail_do_embed_note($query_vars, $url) {
	
	$fb_note = 'http://www.facebook.com/note.php?note_id='.$query_vars[note_id];
	
	if (function_exists("curl_init"))
		$fb_note = @curl_get_content($fb_note);
	else
		$fb_note = @file_get_contents($fb_note);
	
	preg_match_all('/<h2 class="uiHeaderTitle">(.*?)<\/h2>/', $fb_note, $title, PREG_SET_ORDER);
	preg_match_all('/<div class="mbs mbs uiHeaderSubTitle lfloat fsm fwn fcg">by <a href="(.*?)">(.*?)<\/a>/', $fb_note, $owner, PREG_SET_ORDER);
	preg_match_all('/<div class="mbl notesBlogText clearfix"><div>(.*?)<\/div><\/div>/s', $fb_note, $note, PREG_SET_ORDER);
	
	//print '<pre>';
	//print_r($title);
	//print_r($owner);
	//print_r($note);
	//print '</pre>';
	
	if(!isset($title[0][1])) {
	
		$return = "<p><a href='http://www.facebook.com/$url'>http://www.facebook.com/$url</a></p>";

		if(is_user_logged_in())
			$return .= '<div class="sohailerror">Error: Couldn\'t embed the note, are you sure it is public and not private?<br />(this message is visible to logged in users only)</div>';

	} else {
		
		$return  = "<div class='sohailfbbox'>\n";
		$return .= "<div class='sohailfbboxhead'><img src='http://graph.facebook.com/".end(explode('/', $owner[0][1]))."/picture' align='left' style='margin-right:10px; width:40px; height:40px;' /><img src='".SOHAIL_EMBED_FACEBOOK_URL."/images/note.png' style='vertical-align:text-top' /> ".substr($title[0][1], 0, 70)."<br /><span>By ".str_replace('<div class="mbs mbs uiHeaderSubTitle lfloat fsm fwn fcg">by ', '', $owner[0][0]) . " &nbsp;|&nbsp; <a href='http://www.facebook.com/note.php?note_id={$query_vars[note_id]}' target='_blank'>View on Facebook</a></span></div>\n"; 
		$return .= "<div class='sohailfbboxbody'>\n";
		$return .= strip_tags($note[0][1], '<p><a><br><br /><img>');
		$return .= "</div>\n";
		$return .= "</div>\n";

	}
		
	return $return;
}


function curl_get_content($url)
{
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, "Firefox (WindowsXP) - Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HEADER, 0);
    ob_start();
    curl_exec ($ch);
    curl_close ($ch);
    $return = ob_get_contents();
    ob_end_clean();
    return $return;    
}

function sohail_json_encode($value) {
	$json = new Services_JSON();
	return $json->encode($value);
}

function sohail_json_decode($value) {
	$json = new Services_JSON();
	return $json->decode($value);
}

function sohail_iso8601($tstamp) {
	sscanf($tstamp,"%u-%u-%uT%u:%u:%uZ",$year,$month,$day,
	$hour,$min,$sec);
	$newtstamp=mktime($hour,$min,$sec,$month,$day,$year);
	return $newtstamp;
}
?>