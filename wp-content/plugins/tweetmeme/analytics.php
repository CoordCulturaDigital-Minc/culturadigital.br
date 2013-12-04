<?php

function tm_tweets($url) {
	if (function_exists('curl_init')) {
		$ch = curl_init();
		// set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, 'http://api.tweetmeme.com/analytics/free.json?url=' . urlencode($url));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // grab URL and pass it to the browser
        $data = curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);

        $data = json_decode($data, true);

        if ($data['status'] != 'success') {
        	return array('success'=>'false',
        					'reason'=>$data['comment']);
		}

        return array('success'=>'true',
        				'data'=>$data);
	}
	return false;
}

function tm_get_analytics($domain = null)
{
	$appid = get_option('tm_api_app_id');
	$apikey = get_option('tm_api_app_key');

	if (!$appid && !$apikey) {
		return array('success'=>'false',
					'reason'=>'App ID and App Key not set');
	}

	if (!is_null($domain)) {
		$domainstring = '&domain=' . urlencode($domain);
	}

	if (function_exists('curl_init')) {
		$ch = curl_init();

		// set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, 'http://api.tweetmeme.com/analytics/built.json?appid=' . $appid . '&apikey=' . $apikey . $domainstring);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 3);
        // grab URL and pass it to the browser
        $data = curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);

        $data = json_decode($data, true);

        if ($data['status'] != 'success') {
        	return array('success'=>'false',
        					'reason'=>$data['comment']);
		}

        return array('success'=>'true',
        				'data'=>$data);
	}
	return array('success'=>'false',
					'reason'=>'cURL is not installed on this server');
}

function tm_queue_analytics($url)
{
	$appid = get_option('tm_api_app_id');
	$apikey = get_option('tm_api_app_key');

	if (!$appid && !$apikey) {
		return array('success'=>'false',
					'reason'=>'App ID and App Key not set');
	}

	if (function_exists('curl_init')) {
		$ch = curl_init();

		// set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, 'http://api.tweetmeme.com/analytics/build.json?appid=' . $appid . '&apikey=' . $apikey . '&url=' . urlencode($url));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // grab URL and pass it to the browser
        $data = curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);

        $data = json_decode($data, true);

        if ($data['status'] != 'success') {
        	return array('success'=>'false',
        					'reason'=>$data['comment']);
		}

        return array('success'=>'true');
	}
	return array('success'=>'false',
					'reason'=>'cURL is not installed on this server');
}

function tm_js_admin_header() {
	// use JavaScript SACK library for Ajax
	wp_print_scripts(array('sack'));
	?>
	<script type="text/javascript">
		//<![CDATA[
		function loadAnalytics(url, row, button) {

			if (button.value == 'View Analytics') {
    			var mysack = new sack("<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php");
				mysack.execute = 1;
				mysack.method = 'POST';
				mysack.setVar("action", "tm_ajax_elev_lookup");
				mysack.setVar("url", url);
				mysack.setVar("id", 't' + row);
				mysack.onError = function() {
					alert('Ajax error in looking up url');
				};
				mysack.runAJAX();
			}

  			return true;
		}
		//]]>
	</script>
<?php
}

function tm_ajax_elev_lookup() {
	// read submitted information
	$url = $_POST['url'];
	$id = $_POST['id'];
	// fetch the data from tweetmeme
	$json = tm_tweets($url);
	// get the data
	$data = $json['data'];
	// build up the output
	ob_start();
	?>
	<table cellpadding="0" cellspacing="0" style="width: 100%;">
		<tr>
			<th>Tweets in 1 Hour</th>
			<th>Tweets in 24 Hours</th>
			<th>Top Sources in 1 Hour</th>
		</tr>
		<tr>
			<td>
				<img src="<?php echo $data['hourChart']; ?>" alt="*" />
			</td>
			<td>
				<img src="<?php echo $data['dayChart']; ?>" alt="*" />
			</td>
			<td>
				<img src="<?php echo $data['sources']; ?>" alt="*" />
			</td>
		</tr>
	</table>
	<?php if (count($data['users']) > 0) { ?>
		<table cellpadding="0" cellspacing="0" style="width: 100%;">
			<tr>
				<th>Tweeter</th>
				<th>Retweet Of</th>
				<th></th>
			</tr>
			<?php foreach ($data['users'] as $user) { ?>
				<tr>
					<td><a href="http://tweetmeme.com/user/<?php echo $user['tweeter'] ?>" target="_blank"><?php echo $user['tweeter'] ?></a></td>
					<td><?php if ($user['isRT']) { ?>
						<a href="http://tweetmeme.com/user/<?php echo $user['RTUser'] ?>" target="_blank"><?php echo $user['RTUser'] ?></a>
					<?php } ?></td>
					<td><a href="http://twitter.com/<?php echo $user['tweeter'] ?>/status/<?php echo $user['tweetid'] ?>" target="_blank">View</a></td>
				</tr>
			<?php } ?>
		</table>
	<?php }

	$output = '<td colspan="2">' . ob_get_clean() . '</td>';
	$output = str_replace(array("\r","\n","\r\n","\n\r"),'', $output);
  	// Compose JavaScript for return
  	die("document.getElementById('$id').innerHTML = '" . $output  . "';");
}

function tm_stats_page() {
	global $post;
	$myposts = get_posts();

	$post = $_POST['tm_post_do'];
	$result = false;
	if ($post) {
		$result = tm_queue_analytics($post);
	}
	?>
	<div class="wrap">
		<div class="icon32" id="icon-edit"><br/></div><h2>Tweet Statistics</h2>
			<p>This page shows you statistics for your recent posts.</p>
			<table class="widefat post fixed" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th>Post</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Post</th>
						<th></th>
					</tr>
				</tfoot>
				<?php foreach($myposts as $row => $post) { ?>
					<tr <?php if ($row%2 == 0) echo 'class="alternate"'; ?>>
						<td>
							<a href="<?php echo get_permalink($post->ID); ?>" target="_blank"><?php echo $post->post_title; ?></a>
						</td>
						<td>
							<input type="button" value="View Analytics" class="button-secondary action" onclick="loadAnalytics('<?php echo get_permalink($post->ID); ?>', <?php echo $row; ?>, this)" />
						</td>
					</tr>
					<tr style="" id="t<?php echo $row; ?>">
					</tr>
	            <?php } ?>
			</table>
		</div>
	</div>
	<?php
}