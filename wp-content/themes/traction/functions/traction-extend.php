<?php
	/* REQUIRE THE CORE CLASS */
	require_once ( 'traction-admin.php' );
	/*
		Class Definition
	*/
	if (!class_exists( 'Traction' )) {
		class Traction extends JestroCore {

			/* PHP4 Constructor */
			function Traction () {

				/* SET UP THEME SPECIFIC VARIABLES */
				$this->themename = "Traction";
				$this->themeurl = "http://thethemefoundry.com/traction/";
				$this->shortname = "traction";
				$directory = get_bloginfo( 'stylesheet_directory' );
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

					array(
						"name" => __( 'Navigation <span>control your top navigation menu</span>', 'traction' ),
						"type" => "subhead"),

					array(
						"name" => __( 'Hide all pages', 'traction' ),
						"id" => $this->shortname."_hide_pages",
						"desc" => __( 'Check this box to hide all pages.', 'traction' ),
						"std" => '',
						"type" => "checkbox"),

					array(
						"name" => __( 'Hide all categories', 'traction' ),
						"id" => $this->shortname."_hide_cats",
						"desc" => __( 'Check this box to hide all categories.', 'traction' ),
						"std" => '',
						"type" => "checkbox"),

					array(
						"name" => __( 'Advertising <span>fill in your ad spots</span>', 'traction' ),
						"type" => "subhead"),

					array(
						"name" => __( 'Enable sidebar adbox', 'traction' ),
						"id" => $this->shortname."_adbox",
						"desc" => __( 'Check this box to enable the sidebar adbox.', 'traction' ),
						"std" => '',
						"type" => "checkbox"),

					array(
						"name" => __( 'Ad 1 file name', 'traction' ),
						"id" => $this->shortname."_adurl_1",
						"desc" => __( 'Upload your image here: ', 'traction' ) .'<code>' . $directory . '/images/ads/</code>',
						"std" => '',
						"type" => "text"),

					array(
						"name" => __( 'Ad 1 link', 'traction' ),
						"id" => $this->shortname."_adlink_1",
						"desc" => __( 'Link for the first ad', 'traction' ),
						"std" => '',
						"type" => "text"),

					array(
						"name" => __( 'Ad 1 alt tag', 'traction' ),
						"id" => $this->shortname."_adalt_1",
						"desc" => __( 'Alt tag for the first ad', 'traction' ),
						"std" => '',
						"type" => "text"),

					array(
						"name" => __( 'Ad 2 file name', 'traction' ),
						"id" => $this->shortname."_adurl_2",
						"desc" => __( 'Upload your image here: ', 'traction' ) .'<code>' . $directory . '/images/ads/</code>',
						"std" => '',
						"type" => "text"),

					array(
						"name" => __( 'Ad 2 link', 'traction' ),
						"id" => $this->shortname."_adlink_2",
						"desc" => __( 'Link for the second ad', 'traction' ),
						"std" => '',
						"type" => "text"),

					array(
						"name" => __( 'Ad 2 alt tag', 'traction' ),
						"id" => $this->shortname."_adalt_2",
						"desc" => __( 'Alt tag for the second ad', 'traction' ),
						"std" => '',
						"type" => "text"),

					array(
						"name" => __( 'Subscribe Links <span>control the subscribe links</span>', 'traction' ),
						"type" => "subhead"),

					array(
						"name" => __( 'Enable Facebook', 'traction' ),
						"id" => $this->shortname."_facebook_toggle",
						"desc" => __( 'Check this box to show a link to your Facebook page.', 'traction' ),
						"std" => '',
						"type" => "checkbox"),

					array(
						"name" => __( 'Enable Flickr', 'traction' ),
						"id" => $this->shortname."_flickr_toggle",
						"desc" => __( 'Check this box to show a link to Flickr.', 'traction' ),
						"std" => '',
						"type" => "checkbox"),

					array(
						"name" => __( 'Disable all', 'traction' ),
						"id" => $this->shortname."_follow_disable",
						"desc" => __( 'Check this box to hide all follow icons (including RSS). This option overrides any other settings.', 'traction' ),
						"std" => '',
						"type" => "checkbox"),

					array(
						"name" => __( 'Facebook link', 'traction' ),
						"id" => $this->shortname."_facebook",
						"desc" => __( 'Enter your Facebook link.', 'traction' ),
						"type" => "text"),

					array(
						"name" => __( 'Flickr link', 'traction' ),
						"id" => $this->shortname."_flickr",
						"desc" => __( 'Enter your Flickr link.', 'traction' ),
						"type" => "text"),

					array(
						"name" => __( 'Sidebar <span>customize your sidebar</span>', 'traction' ),
						"type" => "subhead"),

					array(
						"name" => __( 'Disable sidebox', 'traction' ),
						"id" => $this->shortname."_sidebox",
						"desc" => __( 'Check this box to disable the sidebar sidebox.', 'traction' ),
						"std" => '',
						"type" => "checkbox"),

					array(
						"name" => __( 'Custom code', 'traction' ),
						"id" => $this->shortname."_sidebox_custom_code",
						"desc" => __( 'Check this box to use custom code for the sidebox.', 'traction' ),
						"std" => '',
						"type" => "checkbox"),

					array(
						"name" => __( 'Custom code content', 'traction' ),
						"id" => $this->shortname."_sidebox_custom_code_content",
						"desc" => __( 'Must use properly formatted XHTML/HTML.', 'traction' ),
						"std" => '',
						"type" => "textarea",
						"options" => array(
							"rows" => "7",
							"cols" => "70") ),

					array(
						"name" => __( 'Enable newsletter', 'traction' ),
						"id" => $this->shortname."_news",
						"desc" => __( 'The newsletter sign-up uses Feedburner by default.', 'traction' ),
						"std" => '',
						"type" => "checkbox"),

					array(
						"name" => __( 'Feed Title', 'traction' ),
						"id" => $this->shortname."_news_title",
						"desc" => __( 'Title displayed inside the newsletter signup box', 'traction' ),
						"std" => "Sign up for our free newsletter",
						"type" => "text"),

					array(
						"name" => __( 'Feedburner feed name', 'traction' ),
						"id" => $this->shortname."_news_name",
						"desc" => __( 'Click the <em>Edit Feed Details</em> link inside FeedBurner', 'traction' ),
						"type" => "text"),

					array(
						"name" => __( 'Footer <span>customize your footer</span>', 'traction' ),
						"type" => "subhead"),

					array(
						"name" => __( 'About', 'traction' ),
						"id" => $this->shortname."_about",
						"desc" => __( 'Something about you or your business.', 'traction' ),
						"type" => "textarea",
						"options" => array(
							"rows" => "6",
							"cols" => "80") ),

					array(
						"name" => __( 'Copyright notice', 'traction' ),
						"id" => $this->shortname."_copyright_name",
						"desc" => __( 'Your name or the name of your business.', 'traction' ),
						"std" => __( 'Your Name Here', 'traction' ),
						"type" => "text"),

					array(
						"name" => __( 'Stats code', 'traction' ),
						"id" => $this->shortname."_stats_code",
						"desc" => __( 'If you use Google Analytics or need any other tracking script in your footer just copy and paste it here. The script will be inserted before the closing <code>&#60;/body&#62;</code> tag.', 'traction' ),
						"std" => '',
						"type" => "textarea",
						"options" => array(
							"rows" => "5",
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

			/* LOGO FUNCTIONS */
			function logoState () {
				return get_option($this->shortname.'_logo' );
			}
			function logoName () {
				return get_option($this->shortname.'_logo_img' );
			}
			function logoAlt () {
				return get_option($this->shortname.'_logo_img_alt' );
			}
			function logoTagline () {
				return get_option($this->shortname.'_tagline' );
			}

			/* SLIDER FUNCTIONS */
			function sliderState () {
				return get_option($this->shortname.'_slider' );
			}
			function sliderStart () {
				return get_option($this->shortname.'_slider_start' );
			}
			function sliderDelay () {
				return get_option($this->shortname.'_slider_delay' );
			}
			function sliderSpeed () {
				return get_option($this->shortname.'_slider_speed' );
			}
			function sliderFade () {
				return get_option($this->shortname.'_slider_fade' );
			}

			/* COLOR FUNCTIONS */
			function colorScheme () {
				return get_option($this->shortname.'_color' );
			}

			/* NAVIGATION FUNCTIONS */
			function excludedPages () {
				return get_option($this->shortname.'_pages_to_exclude' );
			}
			function excludedCategories () {
				return get_option($this->shortname.'_categories_to_exclude' );
			}
			function hidePages () {
				return get_option($this->shortname.'_hide_pages' );
			}
			function hideCategories () {
				return get_option($this->shortname.'_hide_cats' );
			}
			function hideHome () {
				return get_option($this->shortname.'_hide_home' );
			}

			/* ADVERTISING */
			function bannerState() {
			 return get_option($this->shortname.'_banner' );
			}
			function bannerImage() {
			 return get_option($this->shortname.'_banner_img' );
			}
			function bannerUrl() {
			 return htmlspecialchars(wp_filter_post_kses(get_option($this->shortname.'_banner_link', UTF-8)));
			}
			function bannerAlt() {
			 return get_option($this->shortname.'_banner_alt' );
			}
			function adboxState() {
				return get_option($this->shortname.'_adbox' );
			}
			function adboxImage1() {
				return get_option($this->shortname.'_adurl_1' );
			}
			function adboxUrl1() {
			 return htmlspecialchars(wp_filter_post_kses(get_option($this->shortname.'_adlink_1', UTF-8)));
			}
			function adboxAlt1() {
				return get_option($this->shortname.'_adalt_1' );
			}
			function adboxImage2() {
				return get_option($this->shortname.'_adurl_2' );
			}
			function adboxUrl2() {
				return htmlspecialchars(wp_filter_post_kses(get_option($this->shortname.'_adlink_2', UTF-8)));
			}
			function adboxAlt2() {
				return get_option($this->shortname.'_adalt_2' );
			}

			/* SUBSCRIBE FUNCTIONS */
			function twitterToggle() {
			 return get_option($this->shortname.'_twitter_toggle' );
			}
			function facebook() {
			 return htmlspecialchars(wp_filter_post_kses(get_option($this->shortname.'_facebook', UTF-8)));
			}
			function facebookToggle() {
			 return get_option($this->shortname.'_facebook_toggle' );
			}
			function flickr() {
			 return htmlspecialchars(wp_filter_post_kses(get_option($this->shortname.'_flickr', UTF-8)));
			}
			function flickrToggle() {
			 return get_option($this->shortname.'_flickr_toggle' );
			}
			function followDisable() {
			 return get_option($this->shortname.'_follow_disable' );
			}

			/* SIDEBAR */
			function sideboxState() {
				return get_option($this->shortname.'_sidebox' );
			}
			function sideboxCustom() {
				return	get_option($this->shortname.'_sidebox_custom_code' );
			}
			function sideboxCode() {
				return	stripslashes(get_option($this->shortname.'_sidebox_custom_code_content' ));
			}
			function news() {
				return get_option($this->shortname.'_news' );
			}
			function newsTitle() {
				return	get_option($this->shortname.'_news_title' );
			}
			function newsName() {
				return	get_option($this->shortname.'_news_name' );
			}

			/* TWITTER FUNCTIONS */
			function twitter() {
			 return wp_filter_post_kses(get_option($this->shortname.'_twitter', UTF-8));
			}
			function twitterState() {
			 return wp_filter_post_kses(get_option($this->shortname.'_twitter_state', UTF-8));
			}
			function twitterUpdates() {
			 return wp_filter_post_kses(get_option($this->shortname.'_twitter_updates', UTF-8));
			}

			/* FOOTER FUNCTIONS */
			function footerAbout() {
				return stripslashes(wpautop(get_option($this->shortname.'_about' )));
			}
			function footerAboutState() {
				return stripslashes(get_option($this->shortname.'_about_state' ));
			}
			function flickrLink() {
				return stripslashes(get_option($this->shortname.'_flickr_link' ));
			}
			function flickrState() {
				return stripslashes(get_option($this->shortname.'_flickr_off' ));
			}
			function copyrightName() {
				return wp_filter_post_kses(get_option($this->shortname.'_copyright_name' ));
			}
			function statsCode() {
				return stripslashes(get_option($this->shortname.'_stats_code' ));
			}
		}
	}
	/* SETTING EVERYTHING IN MOTION */
	if (class_exists( 'Traction' )) {
		$traction = new Traction();
	}

?>