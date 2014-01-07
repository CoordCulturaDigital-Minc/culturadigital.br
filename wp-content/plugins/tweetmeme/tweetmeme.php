<?php
/*
Plugin Name: TweetMeme Retweet Button
Plugin URI: http://tweetmeme.com/about/plugins
Description: Adds a button which easily lets you retweet your blog posts.
Version: 3.0
Author: TweetMeme
Author URI: http://tweetmeme.com
*/

function tm_options() {
	add_menu_page('TweetMeme', 'TweetMeme', 8, basename(__FILE__), 'tm_options_page');
	add_submenu_page(basename(__FILE__), 'TweetMeme', 'TweetMeme', 8, basename(__FILE__), 'tm_options_page');
}


/**
* This now generates the Twitter button using firstly the Twitter options but falling back to the 
* TweetMeme options.
*/
function tm_generate_button() {

	global $post;

    // get the permalink
    if (get_post_status($post->ID) == 'publish') {
        $url = get_permalink();
    }

    // take the twitter values by default, fall back to TweetMeme options
    $count = get_option('twitter_version') ? get_option('twitter_version') : get_option('tm_version');
    $related = get_option('twitter_related');
    $description = get_option('twitter_related_description');
    $via = get_option('twitter_via') ? get_option('twitter_via') : get_option('tm_source');
    $lang = get_option('twitter_lang');
    $text = get_post_meta($post->ID, 'twitter_text', true);

    $button = '<div style="' . get_option('tm_style') . '"><a href="https://twitter.com/share" class="twitter-share-button"';

    // via user
    if ($via) {
        $button .= ' data-via="' . $via . '"';
    }

    // append the hashtags
    if (get_option('tm_hashtags') == 'yes') {
        // first lets see if the post has the custom field
        if (($hashtags = get_post_meta($post->ID, 'tm_hashtags')) != false) {
            // first split them out
            $hashtags = explode(',', $hashtags[0]);
            // go through and urlencode
            foreach($hashtags as $row => $tag) {
                $hashtags[$row] = urlencode(trim($tag));
            }
            // nope so lets use them
            $button .= ' data-hashtags="' . implode(',', $hashtags) . '"';
        } else if (($tags = get_the_tags()) != false) {
            // ok, grab them off the post tags
            $hashtags = array();
            foreach ($tags as $tag) {
                $hashtags[] = urlencode($tag->name);
            }
            $button .= ' data-hashtags="' . implode(',', $hashtags) . '"';
        } else if (($hashtags = get_option('tm_hashtags_tags')) != false) {
            // first split them out
            $hashtags = explode(',', $hashtags);
            // go through and urlencode
            foreach($hashtags as $row => $tag) {
                $hashtags[$row] = urlencode(trim($tag));
            }
            // add them all back together
            $button .= ' data-hashtags="' . implode(',', $hashtags) . '"';
        }
    }

    if ($count && $count == 'compact') {
        $button .= ' data-count="horizontal"';
    } else {
        $button .= ' data-count="vertical"';
    }

    $button .= ' data-url="' . $url . '"';
    $button .= '>Tweet</a></div>';

    return $button;
}

/**
* Gets run when the content is loaded in the loop
*/
function tm_update($content) {

    global $post;

    if (get_option('tm_enable') == 'no') {
		return $content;
	}

    // add the manual option, code added by kovshenin
    if (get_option('tm_where') == 'manual') {
        return $content;
	}
    // is it a page
    if (get_option('tm_display_page') == null && is_page()) {
        return $content;
    }
	// are we on the front page
    if (get_option('tm_display_front') == null && is_home()) {
        return $content;
    }
	// are we in a feed
    if (!is_feed()) {
		$button = tm_generate_button();
		$where = 'tm_where';
	}

	// are we just using the shortcode
	if (get_option($where) == 'shortcode') {
		return str_replace(array('[twitter]', '[tweetmeme]'), $button, $content);
	} else {
		// if we have switched the button off
		if (get_post_meta($post->ID, 'tweetmeme') == null) {
			if (get_option($where) == 'beforeandafter') {
				// adding it before and after
				return $button . $content . $button;
			} else if (get_option($where) == 'before') {
				// just before
				return $button . $content;
			} else {
				// just after
				return $content . $button;
			}
		} else {
			// not at all
			return $content;
		}
	}
}

function tm_footer() {
	//echo '<script src="' . get_bloginfo('wpurl') . '/wp-content/plugins/tweetmeme/button.js" type="text/javascript"></script>';
    // Twitter Button script
    echo '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
}

function tm_options_page() {
?>
    <div class="wrap">
    <div class="icon32" id="icon-options-general"><br/></div><h2>Settings for Twitter Button Integration</h2>
    <p>This plugin will install the Twitter button for each of your blog posts in both the content of your posts and the RSS feed.
    </p>
    <form method="post" action="options.php">
    <?php
        // New way of setting the fields, for WP 2.7 and newer
        if(function_exists('settings_fields')){
            settings_fields('tm-options');
        } else {
            wp_nonce_field('update-options');
            ?>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="tm_ping,tm_where,tm_style,twitter_version,tm_display_page,tm_display_front,tm_display_rss,tm_display_feed,tm_source,tm_url_shortner,tm_space,tm_hashtags,tm_hashtags_tags,tm_api_key,tm_enable" />
            <?php
        }
    ?>
        <table class="form-table">
            <tr>
	            <tr>
					<th>Enable</th>
					<td>
						<input type="radio" value="yes" <?php if (get_option('tm_enable') == 'yes' || !get_option('tm_enable')) echo 'checked="checked"'; ?> name="tm_enable" id="tm_enable_yes" group="tm_enable"/>
		                <label for="tm_enable_yes">Yes</label>
		                <br/>
		                <input type="radio" value="no" <?php if (get_option('tm_enable') == 'no') echo 'checked="checked"'; ?> name="tm_enable" id="tm_enable_no" group="tm_enable" />
		                <label for="tm_enable_no">No</label>
            		</td>
				</tr>
	            <tr>
	                <th scope="row" valign="top">
	                    Display
	                </th>
	                <td>
	                    <input type="checkbox" value="1" <?php if (get_option('tm_display_page') == '1') echo 'checked="checked"'; ?> name="tm_display_page" id="tm_display_page" group="tm_display"/>
	                    <label for="tm_display_page">Display the button on pages</label>
	                    <br/>
	                    <input type="checkbox" value="1" <?php if (get_option('tm_display_front') == '1') echo 'checked="checked"'; ?> name="tm_display_front" id="tm_display_front" group="tm_display"/>
	                    <label for="tm_display_front">Display the button on the front page (home)</label>
	                </td>
	            </tr>
                <tr>
                    <th scope="row" valign="top">
                        Position
                    </th>
                    <td>
                    	<select name="tm_where">
                    		<option <?php if (get_option('tm_where') == 'before') echo 'selected="selected"'; ?> value="before">Before</option>
                    		<option <?php if (get_option('tm_where') == 'after') echo 'selected="selected"'; ?> value="after">After</option>
                    		<option <?php if (get_option('tm_where') == 'beforeandafter') echo 'selected="selected"'; ?> value="beforeandafter">Before and After</option>
                    		<option <?php if (get_option('tm_where') == 'shortcode') echo 'selected="selected"'; ?> value="shortcode">Shortcode [twitter]</option>
                    		<option <?php if (get_option('tm_where') == 'manual') echo 'selected="selected"'; ?> value="manual">Manual</option>
                    	</select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" valign="top">
                        Type
                    </th>
                    <td>
                        <input type="radio" value="vertical" <?php if (get_option('twitter_version') == 'vertical') echo 'checked="checked"'; ?> name="twitter_version" id="twitter_version_twitter_vertical" group="twitter_version"/>
                        <label for="twitter_version_twitter_vertical">Twitter Vertical Button</label>
                        <br/>
                        <input type="radio" value="horizontal" <?php if (get_option('twitter_version') == 'horizontal') echo 'checked="checked"'; ?> name="twitter_version" id="twitter_version_twitter_horizontal" group="twitter_version" />
                        <label for="twitter_version_twitter_horizontal">Twitter Compact Button</label>
                        <br/>
                        <input type="radio" value="none" <?php if (get_option('twitter_version') == 'none') echo 'checked="checked"'; ?> name="twitter_version" id="twitter_version_twitter_nocount" group="twitter_version"/>
                        <label for="twitter_version_twitter_nocount">Twitter No Count Button</label>
                    </td>
                </tr>
            </tr>
            <tr>
                <th scope="row" valign="top"><label for="tm_style">Styling</label></th>
                <td>
                    <input type="text" value="<?php echo htmlspecialchars(get_option('tm_style')); ?>" name="tm_style" id="tm_style" />
                    <span class="description">Add style to the div that surrounds the button E.g. <code>float: left; margin-right: 10px;</code></span>
                </td>
            </tr>
            <tr>
                <th scope="row" valign="top">
                    <label for="tm_source">Source</label>
                </th>
                <td>
                    <input type="text" value="<?php echo get_option('tm_source'); ?>" name="tm_source" id="tm_source" />
                    <span class="description">via @your_username</span>
                </td>
            </tr>
            <tr>
            	<th scope="row" valigh="top">
            		Hashtags
            	</th>
            	<td>
            		<input type="radio" value="yes" name="tm_hashtags" group="tm_hashtags" id="tm_hashtags_on" <?php if (get_option('tm_hashtags') == 'yes') echo 'checked="checked"'; ?> />
            		<label for="tm_hashtags_on">Take the top tags from the post and apply to the tweet</label>
            		<br/>
            		<input type="radio" value="no" name="tm_hashtags" group="tm_hashtags" id="tm_hashtags_off" <?php if (get_option('tm_hashtags') == 'no') echo 'checked="checked"'; ?> />
            		<label for="tm_hashtags_off">Dont use hashtags</label>
            		<br/>
            		<label for="tm_hashtags_tags">Use these default tags if there are no tags on the post.</label>
            		<input type="text" value="<?php echo get_option('tm_hashtags_tags'); ?>" name="tm_hashtags_tags" />
            		<br/><br/>
            		<span class="description">You can override any of these by specifying hashtags on a per post basis, by using the custom field tm_hashtags (seperated by ,).</span>
            	</td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
    </div>
<?php
}

// On access of the admin page, register these variables (required for WP 2.7 & newer)
function tm_init(){
    if(function_exists('register_setting')){
        register_setting('tm-options', 'tm_display_page');
        register_setting('tm-options', 'tm_display_front');
        register_setting('tm-options', 'tm_source', 'tm_sanitize_username');
        register_setting('tm-options', 'twitter_version');
        register_setting('tm-options', 'tm_where');
        register_setting('tm-options', 'tm_hashtags');
        register_setting('tm-options', 'tm_hashtags_tags');
		register_setting('tm-options', 'tm_enable');
        register_setting('tm-options', 'tm_style');
    }
}

function tm_sanitize_username($username){
    return preg_replace('/[^A-Za-z0-9_]/','',$username);
}

// Remove the filter excerpts
function tm_remove_filter($content) {
    if (!is_feed()) {
        remove_action('the_content', 'tm_update');
    }
    return $content;
}

// Only all the admin options if the user is an admin
if(is_admin()){
    add_action('admin_menu', 'tm_options');
    add_action('admin_init', 'tm_init');
}

// Set the default options when the plugin is activated
function tm_activate(){
    add_option('tm_where', 'before');
    add_option('tm_source');
    add_option('twitter_version', 'vertical');
    add_option('tm_display_page', '1');
    add_option('tm_display_front', '1');
    add_option('tm_hashtags', 'on');
    add_option('tm_enable', 'yes');
    add_option('tm_style', 'float: right; margin-left: 10px;');
}

// Set the default options when the plugin is activated
function twitter_activate(){
    add_option('twitter_where', 'before');
    add_option('twitter_rss_where', 'before');
    add_option('twitter_style', 'float: right; margin-left: 10px;');
    add_option('twitter_version', 'vertical');
    add_option('twitter_display_page', '1');
    add_option('twitter_display_front', '1');
    add_option('twitter_enable', 'no');
    add_option('twitter_lang', 'en');
}

add_filter('the_content', 'tm_update', 8);
add_filter('get_the_excerpt', 'tm_remove_filter', 9);
add_action('wp_footer', 'tm_footer');

register_activation_hook( __FILE__, 'tm_activate');
