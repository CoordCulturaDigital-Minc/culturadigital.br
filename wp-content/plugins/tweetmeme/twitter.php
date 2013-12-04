<?php

function twitter_build_options()
{
	// get the post varibale (should be in the loop)
	global $post;
	// get the permalink
    if (get_post_status($post->ID) == 'publish') {
        $url = get_permalink();
    }
    $button = '?url=' . urlencode($url);

    // count box position
    if (get_option('twitter_version')) {
        $button .= '&amp;count=' . urlencode(get_option('twitter_version'));
    }

    if (get_option('twitter_related')) {
    	$button .= '&amp;related=' . urlencode(get_option('twitter_related')) . ':' . urlencode(get_option('twitter_related_description'));
	}

	if (get_option('twitter_via')) {
		$button .= '&amp;via=' . urlencode(get_option('twitter_via'));
	}

	if (get_option('twitter_lang')) {
		$button .= '&amp;lang=' . urlencode(get_option('twitter_lang'));
	}

	// does the post have the tweet text
	if (($text = get_post_meta($post->ID, 'twitter_text', true)) != false) {
		$button .= '&amp;text=' . urlencode($text);
	}

    return $button;
}

function twitter_generate_button()
{
	// build up the outer style
    $button = '<div class="twitter_button" style="' . get_option('twitter_style') . '">';
    $button .= '<iframe src="http://platform.twitter.com/widgets/tweet_button.html' . twitter_build_options() . '" ';

	$sizes = array(
		'en' => array(
			'vertical' => array(62, 55),
			'horizontal' => array(20, 110),
			'none' => array(20, 55)
		),
		'fr' => array(
			'vertical' => array(62, 65),
			'horizontal' => array(20, 117),
			'none' => array(20, 65)
		),
		'de' => array(
			'vertical' => array(62, 67),
			'horizontal' => array(20, 119),
			'none' => array(20, 67)
		),
		'es' => array(
			'vertical' => array(62, 64),
			'horizontal' => array(20, 116),
			'none' => array(20, 64)
		),
		'ja' => array(
			'vertical' => array(62, 80),
			'horizontal' => array(20, 130),
			'none' => array(20, 80)
		)
	);

	$button .= 'height="' . $sizes[get_option('twitter_lang')][get_option('twitter_version')][0] . '" width="' . $sizes[get_option('twitter_lang')][get_option('twitter_version')][1] . '"';

	// close off the iframe
	$button .= ' frameborder="0" scrolling="no" allowtransparency="true"></iframe></div>';
	// return the iframe code
    return $button;
}

function twitter_update($content)
{
	global $post;

	if (get_option('twitter_enable') == 'yes') {
		$button = twitter_generate_button();

	    // add the manual option, code added by kovshenin
	    if (get_option('twitter_where') == 'manual') {
	        return $content;
		}
	    // is it a page
	    if (get_option('twitter_display_page') == null && is_page()) {
	        return $content;
	    }
		// are we on the front page
	    if (get_option('twitter_display_front') == null && is_home()) {
	        return $content;
	    }

		// are we displaying in a feed
		if (is_feed()) {
			return $content;
		}

		// are we just using the shortcode
		if (get_option('twitter_where') == 'shortcode') {
			return str_replace('[twitter]', $button, $content);
		} else {
			// if we have switched the button off
			if (get_post_meta($post->ID, 'twitter') == null) {
				if (get_option('twitter_where') == 'beforeandafter') {
					// adding it before and after
					return $button . $content . $button;
				} else if (get_option('twitter_where') == 'before') {
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
	} else {
		return $content;
	}
}

function twitter_options_page() {
?>
    <div class="wrap">
    <div class="icon32" id="icon-options-general"><br/></div><h2>Settings for Twitter Tweet Button Integration</h2>
    <img style="float: right; margin-left: 10px;" src="http://s.twimg.com/a/1283397887/images/goodies/tweetv.png" />
    <p>This plugin will install the Twitter Tweet Button into your Wordpress blog. To find out more about the Twitter Tweet Button or it's settings visit <a href="http://twitter.com/goodies/tweetbutton" target="_blank">http://twitter.com/goodies/tweetbutton</a>.</p>
    <form method="post" action="options.php">
    <?php
        // New way of setting the fields, for WP 2.7 and newer
        if(function_exists('settings_fields')){
            settings_fields('twitter_options');
        } else {
            wp_nonce_field('update-options');
            ?>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="twitter_where,twitter_style,twitter_version,twitter_display_page,twitter_display_front,twitter_related,twitter_related_description,twitter_via,twitter_enable,twitter_lang" />
            <?php
        }
    ?>
        <table class="form-table">
			<tr>
				<th>Enable</th>
				<td>
					<input type="radio" value="yes" <?php if (get_option('twitter_enable') == 'yes') echo 'checked="checked"'; ?> name="twitter_enable" id="twitter_enable_yes" group="twitter_enable"/>
	                <label for="twitter_enable_yes">Yes</label>
	                <br/>
	                <input type="radio" value="no" <?php if (get_option('twitter_enable') == 'no' || !get_option('twitter_enable')) echo 'checked="checked"'; ?> name="twitter_enable" id="twitter_enable_no" group="twitter_enable" />
	                <label for="twitter_enable_no">No</label>
	                <span class="description"></span>
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

	        <tr>
	            <th scope="row" valign="top">
	                Display
	            </th>
	            <td>
	                <input type="checkbox" value="1" <?php if (get_option('twitter_display_page') == '1') echo 'checked="checked"'; ?> name="twitter_display_page" id="twitter_display_page" group="twitter_display"/>
	                <label for="tm_display_page">Display the button on pages</label>
	                <br/>
	                <input type="checkbox" value="1" <?php if (get_option('twitter_display_front') == '1') echo 'checked="checked"'; ?> name="twitter_display_front" id="twitter_display_front" group="twitter_display"/>
	                <label for="tm_display_front">Display the button on the front page (home)</label>
	            </td>
	        </tr>

	        <tr>
                <th scope="row" valign="top">
                    Position
                </th>
                <td>
                	<select name="twitter_where">
                		<option <?php if (get_option('twitter_where') == 'before') echo 'selected="selected"'; ?> value="before">Before</option>
                		<option <?php if (get_option('twitter_where') == 'after') echo 'selected="selected"'; ?> value="after">After</option>
                		<option <?php if (get_option('twitter_where') == 'beforeandafter') echo 'selected="selected"'; ?> value="beforeandafter">Before and After</option>
                		<option <?php if (get_option('twitter_where') == 'shortcode') echo 'selected="selected"'; ?> value="shortcode">Shortcode [twitter]</option>
                		<option <?php if (get_option('twitter_where') == 'manual') echo 'selected="selected"'; ?> value="manual">Manual</option>
                	</select>
                	<span class="description">Position of the button on the page</span>
                </td>
            </tr>

        	<tr>
                <th scope="row" valign="top"><label for="twitter_style">Styling</label></th>
                <td>
                    <input type="text" value="<?php echo htmlspecialchars(get_option('twitter_style')); ?>" name="twitter_style" id="twitter_style" />
                    <span class="description">Add style to the div that surrounds the button E.g. <code>float: left; margin-right: 10px;</code></span>
                </td>
            </tr>

            <tr>
                <th scope="row" valign="top"><label for="twitter_via">Via User</label></th>
                <td>
                    <input type="text" value="<?php echo htmlspecialchars(get_option('twitter_via')); ?>" name="twitter_via" id="twitter_via" />
                    <span class="description">This user will be @ mentioned in the suggested Tweet.</span>
                </td>
            </tr>

            <tr>
                <th scope="row" valign="top"><label for="twitter_related">Related Account</label></th>
                <td>
                    <input type="text" value="<?php echo htmlspecialchars(get_option('twitter_related')); ?>" name="twitter_related" id="twitter_related" />
                    <span class="description">Recommend a Twitter account for users to follow after they share content from your website. This account could include your own, or that of a contributor or a partner.</span>
                </td>
            </tr>
            <tr>
            	<th scope="row" valign="top"><label for="twitter_related_description">Related Discription</label></th>
                <td>
                    <textarea name="twitter_related_description" id="twitter_related_description"><?php echo htmlspecialchars(get_option('twitter_related_description')); ?></textarea>
                </td>
            </tr>
			<tr>
            	<th scope="row" valign="top"><label for="twitter_lang">Language</label></th>
                <td>
                	<select name="twitter_lang" id="twitter_lang">
                		<option value="en" <?php if (get_option('twitter_lang') == 'en') echo 'selected="selected"'; ?>>English</option>
						<option value="fr" <?php if (get_option('twitter_lang') == 'fr') echo 'selected="selected"'; ?>>French</option>
						<option value="de" <?php if (get_option('twitter_lang') == 'de') echo 'selected="selected"'; ?>>German</option>
						<option value="es" <?php if (get_option('twitter_lang') == 'es') echo 'selected="selected"'; ?>>Spanish</option>
						<option value="ja" <?php if (get_option('twitter_lang') == 'ja') echo 'selected="selected"'; ?>>Japanese</option>
                	</select>
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
function twitter_init(){
    if(function_exists('register_setting')){
        register_setting('twitter_options', 'twitter_display_page');
        register_setting('twitter_options', 'twitter_display_front');
        register_setting('twitter_options', 'twitter_style');
        register_setting('twitter_options', 'twitter_version');
        register_setting('twitter_options', 'twitter_where');
        register_setting('twitter_options', 'twitter_via');
        register_setting('twitter_options', 'twitter_related');
        register_setting('twitter_options', 'twitter_related_description');
        register_setting('twitter_options', 'twitter_enable');
        register_setting('twitter_options', 'twitter_lang');
    }
}

// Only all the admin options if the user is an admin
if(is_admin()){
    add_action('admin_init', 'twitter_init');
}

add_filter('the_content', 'twitter_update', 9);
