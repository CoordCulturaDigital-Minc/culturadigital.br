<?php
if ( ! class_exists('MobilePress_core'))
{
	/**
	 * The core MobilePress class where the magic happens
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	class MobilePress_core {
		
		/**
		 * Calls the method to create admin menus and to set up the admin area
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function create_admin()
		{
			// Action to initiate the admin setup and admin menu setup
			add_action('admin_menu', array(&$this, 'load_admin'));
		}
		
		/**
		 * Loads the install class and setups the plugin including database creation
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function load_activation()
		{
			require_once(MOPR_PATH . 'classes/install.php');
			$install = new MobilePress_install;
		}
		
		/**
		 * Creates the MobilePress menus and creates an admin object which creates the menu content
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function load_admin()
		{
			require_once(MOPR_PATH . 'classes/admin.php');
			
			$MobilePress_admin = new MobilePress_admin;
			
			// Add admin 'MobilePress' to the top level menu area
			add_menu_page('MobilePress', 'MobilePress', 10, 'mobilepress', '', WP_PLUGIN_URL . '/mobilepress/system/views/images/icon.png');
			
				//Add the 'Options' submenu to the MobilePress menu and render the page
				add_submenu_page('mobilepress', 'MobilePress Options', 'Options', 10, 'mobilepress', array(&$MobilePress_admin, 'render_options'));
				
				//Add the 'Themes' submenu to the MobilePress menu and render the page
				add_submenu_page('mobilepress', 'MobilePress Themes', 'Themes', 10, 'mobilepress-themes', array(&$MobilePress_admin, 'render_themes'));
				
				//Add the 'Ads' submenu to the MobilePress menu and render the page
				add_submenu_page('mobilepress', 'MobilePress Ads', 'Mobile Ads', 10, 'mobilepress-ads', array(&$MobilePress_admin, 'render_ads'));
		}
		
		/**
		 * Deactivates the plugin
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function load_deactivation()
		{
			// Shutdown the plugin (nothing here yet)
		}
		
		/**
		 * Does the checks and decides whether to render a mobile or normal website
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function load_site()
		{
			if (isset($_GET['killsession']))
			{
				session_unset();
				session_destroy();
			}
			
			// Plugin preference is set to render entire site in mobile.
			if (mopr_get_option('force_mobile', 1))
			{
				$_SESSION['MOPR_MOBILE_ACTIVE'] = TRUE;
			}
			
			// Check if mobile sesison var exists
			// Also, check if ?mobile or ?nomobile is set. If so, establish the session var so that subsequent page calls will render in the desired mode.
			if (( ! isset($_SESSION['MOPR_MOBILE_ACTIVE']) || (trim($_SESSION['MOPR_MOBILE_ACTIVE']) == '')) || (isset($_GET['mobile'])) || (isset($_GET['nomobile'])))
			{
				// If the session var doesn't exist, proceed to init and check to see if we are dealing with a mobile browser
				require_once(MOPR_PATH . 'classes/check.php');
				$checkmobile = new MobilePress_check;
				$checkmobile->init();
			}
			
			// Set the browser variable
			$browser = $_SESSION['MOPR_MOBILE_BROWSER'];
			
			// This is for testing mobile themes in a normal browser and mobile browsers, resets browser session if you are switching between mobile browsers
			if ((isset($_GET['mobile']) && ($browser != "mobile")) || (isset($_GET['iphone']) && ($browser != "iphone")))
			{
				require_once(MOPR_PATH . 'classes/check.php');
				$checkmobile = new MobilePress_check;
				$checkmobile->init();
				
				// Reset the browser variable
				$browser = $_SESSION['MOPR_MOBILE_BROWSER'];
			}
			
			if ($_SESSION['MOPR_MOBILE_ACTIVE'] === TRUE)
			{
				// Double check session var for theme, fall back on default if any problems
				if ( ! isset($_SESSION['MOPR_MOBILE_THEME']) || (trim($_SESSION['MOPR_MOBILE_THEME']) == ''))
				{
					$_SESSION['MOPR_MOBILE_THEME'] = mopr_get_option('default_theme', 1);
				}

				// Render time!
				require_once(MOPR_PATH . 'classes/render.php');
				$render = new MobilePress_render();
				$render->init();
			}
			else
			{
				// MOPR_MOBILE_ACTIVE has explicitly been set to false. Either by following nomobile link, or there were no matches in the detection script (We're using a web browser)
			}
		}

	}
}
?>