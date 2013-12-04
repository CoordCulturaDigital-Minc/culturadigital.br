<?php
	/* REQUIRE THE CORE CLASS */
	require_once ('titan-admin.php');
	/*
		Class Definition
	*/
	if (!class_exists('Titan')) {
		class Titan extends JestroCore {

			/* PHP4 Constructor */
			function Titan () {

				/* SET UP THEME SPECIFIC VARIABLES */
				$this->themename = "Titan";
				$this->themeurl = "http://thethemefoundry.com/titan/";
				$this->shortname = "T";
				$directory = get_bloginfo('stylesheet_directory');
				/*
					OPTION TYPES:
					- checkbox: name, id, desc, std, type
					- radio: name, id, desc, std, type, options
					- text: name, id, desc, std, type
					- colorpicker: name, id, desc, std, type
					- select: name, id, desc, std, type, options
					- textarea: name, id, desc, std, type, options
				*/
				$this->options = array(

					array(	"name" => __('Navigation <span>control your top navigation menu</span>', 'titan'),
									"type" => "subhead"),

					array(	"name" => __('Hide all pages', 'titan'),
									"id" => $this->shortname."_hide_pages",
									"desc" => __('Check this box to hide all pages', 'titan'),
									"std" => '',
									"type" => "checkbox"),

					array(	"name" => __('Hide all categories', 'titan'),
									"id" => $this->shortname."_hide_cats",
									"desc" => __('Check this box to hide all categories.', 'titan'),
									"std" => '',
									"type" => "checkbox"),

					array(	"name" => __('Follow Links <span>top right follow links</span>', 'titan'),
									"type" => "subhead"),

					array(	"name" => __('Email updates link', 'titan'),
									"id" => $this->shortname."_feed_email",
									"desc" => __('Enter your feed email link here.', 'titan'),
									"type" => "textarea",
									"options" => array( "rows" => "2",
																			"cols" => "80") ),

					array(	"name" => __('Disable email link', 'titan'),
									"id" => $this->shortname."_email_toggle",
									"desc" => __('Don\'t want to offer email updates, check the box.', 'titan'),
									"std" => '',
									"type" => "checkbox"),

					array(	"name" => __('Twitter updates link', 'titan'),
									"id" => $this->shortname."_twitter",
									"desc" => __('Enter the link to your Twitter page.', 'titan'),
									"type" => "text"),

					array(	"name" => __('Disable Twitter', 'titan'),
									"id" => $this->shortname."_twitter_toggle",
									"desc" => __('Not hip to Twitter? That\'s cool, just check this box.', 'titan'),
									"std" => '',
									"type" => "checkbox"),

					array(	"name" => __('Sidebar Sidebox <span>customize your sidebox</span>', 'titan'),
									"type" => "subhead"),

					array(	"name" => __('Disable sidebox', 'titan'),
									"id" => $this->shortname."_sidebox",
									"desc" => __('Check this box to disable the sidebar sidebox.', 'titan'),
									"std" => '',
									"type" => "checkbox"),

					array(	"name" => __('Custom code', 'titan'),
									"id" => $this->shortname."_sidebox_custom_code",
									"desc" => __('Check this box to use custom code for the sidebox.', 'titan'),
									"std" => '',
									"type" => "checkbox"),

					array(	"name" => __('Custom code content', 'titan'),
									"id" => $this->shortname."_sidebox_custom_code_content",
									"desc" => __('Must use properly formatted XHTML/HTML.', 'titan'),
									"std" => '',
									"type" => "textarea",
									"options" => array( "rows" => "7",
																			"cols" => "70") ),

					array(	"name" => __('Sidebar Adbox <span>control ads in your sidebar</span>', 'titan'),
									"type" => "subhead"),

					array(	"name" => __('Enable adbox', 'titan'),
									"id" => $this->shortname."_adbox",
									"desc" => __('Check this box to enable the sidebar adbox.', 'titan'),
									"std" => '',
									"type" => "checkbox"),

					array(	"name" => __('Ad 1 file name', 'titan'),
									"id" => $this->shortname."_adurl_1",
									"desc" => __('Upload your image here: ', 'titan') .'<code>' . $directory . '/images/sidebar/</code>',
									"std" => '',
									"type" => "text"),

					array(	"name" => __('Ad 1 link', 'titan'),
									"id" => $this->shortname."_adlink_1",
									"desc" => __('Link for the first ad', 'titan'),
									"std" => '',
									"type" => "text"),

					array(	"name" => __('Ad 1 alt tag', 'titan'),
									"id" => $this->shortname."_adalt_1",
									"desc" => __('Alt tag for the first ad', 'titan'),
									"std" => '',
									"type" => "text"),

					array(	"name" => __('Ad 2 file name', 'titan'),
									"id" => $this->shortname."_adurl_2",
									"desc" => __('Upload your image here: ', 'titan') .'<code>' . $directory . '/images/sidebar/</code>',
									"std" => '',
									"type" => "text"),

					array(	"name" => __('Ad 2 link', 'titan'),
									"id" => $this->shortname."_adlink_2",
									"desc" => __('Link for the second ad', 'titan'),
									"std" => '',
									"type" => "text"),

					array(	"name" => __('Ad 2 alt tag', 'titan'),
									"id" => $this->shortname."_adalt_2",
									"desc" => __('Alt tag for the second ad', 'titan'),
									"std" => '',
									"type" => "text"),

					array(	"name" => __('Footer <span>customize your footer</span>', 'titan'),
									"type" => "subhead"),

					array(	"name" => __('About', 'titan'),
									"id" => $this->shortname."_about",
									"desc" => __('Something about you or your business.', 'titan'),
									"type" => "textarea",
									"options" => array( "rows" => "6",
																			"cols" => "80") ),

					array(	"name" => __('Flickr link', 'titan'),
									"id" => $this->shortname."_flickr",
									"desc" => __('Create a <a href="http://www.flickr.com/badge_new.gne">javascript Flickr badge</a>. At the end of the process extract the URL and paste here.', 'titan'),
									"type" => "textarea",
									"options" => array( "rows" => "2",
																			"cols" => "80") ),

					array(	"name" => __('Disable Flickr', 'titan'),
									"id" => $this->shortname."_flickr_off",
									"desc" => __('Check this box to disable Flickr and enable the footer widget instead.', 'titan'),
									"std" => '',
									"type" => "checkbox"),

					array(	"name" => __('Copyright notice', 'titan'),
									"id" => $this->shortname."_copyright_name",
									"desc" => __('Your name or the name of your business.', 'titan'),
									"std" => __('Your Name Here', 'titan'),
									"type" => "text"),

					array(	"name" => __('Stats code', 'titan'),
									"id" => $this->shortname."_stats_code",
									"desc" => __('If you use Google Analytics or need any other tracking script in your footer just copy and paste it here. The script will be inserted before the closing <code>&#60;/body&#62;</code> tag.', 'titan'),
									"std" => '',
									"type" => "textarea",
									"options" => array( "rows" => "5",
																			"cols" => "40") ),

				);
				parent::JestroCore();
			}

			/*
				ALL OF THE FUNCTIONS BELOW
				ARE BASED ON THE OPTIONS ABOVE
				EVERY OPTION SHOULD HAVE A FUNCTION

				THESE FUNCTIONS CURRENTLY JUST
				RETURN THE OPTION, BUT COULD BE
				REWRITTEN TO RETURN DIFFERENT DATA
			*/

			/* NAVIGATION FUNCTIONS */
			function hidePages () {
				return get_option($this->shortname.'_hide_pages');
			}
			function hideCategories () {
				return get_option($this->shortname.'_hide_cats');
			}

			/* FOLLOW LINKS */
			function feedState() {
				return get_option($this->shortname.'_feed_state');
			}
			function feedEmail() {
				return htmlspecialchars(wp_filter_post_kses(get_option($this->shortname.'_feed_email', UTF-8)));
			}
			function emailToggle() {
				return get_option($this->shortname.'_email_toggle');
			}
			function twitter() {
				return htmlspecialchars(wp_filter_post_kses(get_option($this->shortname.'_twitter', UTF-8)));
			}
			function twitterToggle() {
				return get_option($this->shortname.'_twitter_toggle');
			}

			/* SIDEBAR SIDEBOX */
			function sideboxState() {
				return get_option($this->shortname.'_sidebox');
			}
			function sideboxCustom() {
				return	get_option($this->shortname.'_sidebox_custom_code');
			}
			function sideboxCode() {
				return	stripslashes(get_option($this->shortname.'_sidebox_custom_code_content'));
			}

			/* SIDEBAR ADBOX */
			function adboxState() {
				return get_option($this->shortname.'_adbox');
			}
			function adboxImage1() {
				return get_option($this->shortname.'_adurl_1');
			}
			function adboxUrl1() {
			 return wp_filter_post_kses(get_option($this->shortname.'_adlink_1', UTF-8));
			}
			function adboxAlt1() {
				return get_option($this->shortname.'_adalt_1');
			}
			function adboxImage2() {
				return get_option($this->shortname.'_adurl_2');
			}
			function adboxUrl2() {
				return wp_filter_post_kses(get_option($this->shortname.'_adlink_2', UTF-8));
			}
			function adboxAlt2() {
				return get_option($this->shortname.'_adalt_2');
			}

			/* FOOTER FUNCTIONS */
			function footerAbout() {
				return stripslashes(wpautop(get_option($this->shortname.'_about')));
			}
			function flickrLink() {
				return stripslashes(get_option($this->shortname.'_flickr'));
			}
			function flickrState() {
				return stripslashes(get_option($this->shortname.'_flickr_off'));
			}
			function copyrightName() {
				return wp_filter_post_kses(get_option($this->shortname.'_copyright_name'));
			}
			function statsCode() {
				return stripslashes(get_option($this->shortname.'_stats_code'));
			}
		}
	}
	/* SETTING EVERYTHING IN MOTION */
	if (class_exists('Titan')) {
		$titan = new Titan();
	}

?>