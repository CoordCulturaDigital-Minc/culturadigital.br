<?php
if ( ! class_exists('MobilePress_render'))
{
	/**
	 * Class that deals with all aspects of rendering the mobile website
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	class MobilePress_render {
	
		/**
		 * @var string $default_theme The default mobile theme
		 */
		var $default_theme;
		
		/**
		 * @var string $title The title of the mobile blog
		 */
		var $title;
		
		/**
		 * @var string $description The description of the mobile blog
		 */
		var $description;
		
		/**
		 * Constructor which sets up the variables we will need to use
		 *
		 * @package MobilePress
		 * @since 1.0
		 * @param STRING $browser The browser to render for
		 */
		function MobilePress_render()
		{
			$this->default_theme	= mopr_get_option('default_theme', 1);
			$this->title			= mopr_get_option('title', 1);
			$this->description		= mopr_get_option('description', 1);
		}
		
		/**
		 * Initialize the rendering of the mobile website
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function init()
		{	
			add_filter('stylesheet', array(&$this, 'set_stylesheet'));
			add_filter('theme_root', array(&$this, 'set_theme_root'));
			add_filter('theme_root_uri', array(&$this, 'set_theme_uri'));
			add_filter('template', array(&$this, 'set_template'));
			
			if ($this->title != '')
			{
				add_filter('option_blogname', array(&$this, 'set_title'));
			}
			
			if ($this->description != '')
			{
				add_filter('option_blogdescription', array(&$this, 'set_description'));
			}
		}
		
		/**
		 * Returns the mobile blogs description
		 *
		 * @package MobilePress
		 * @since 1.0
		 * @return STRING The mobile blogs description
		 */
		function set_description()
		{
			return $this->description;
		}
		
		/**
		 * Sets the stylesheet to the themes mobile stylesheet
		 *
		 * @package MobilePress
		 * @since 1.0
		 * @return STRING Name of the theme where the stylesheet will be called
		 */
		function set_stylesheet()
		{	
			if ( ! isset($_SESSION['MOPR_MOBILE_THEME']) || trim($_SESSION['MOPR_MOBILE_THEME']) == '')
			{
				$stylesheet = $this->default_theme;
			}
			else
			{
				$stylesheet = $_SESSION['MOPR_MOBILE_THEME'];
			}
			
			return $stylesheet;
		}
		
		/**
		 * Sets the blogs template to the MobilePress template
		 *
		 * @package MobilePress
		 * @since 1.0
		 * @return STRING The name of the MobilePress template to be used for rendering
		 */
		function set_template()
		{
			if ( ! isset($_SESSION['MOPR_MOBILE_THEME']) || trim($_SESSION['MOPR_MOBILE_THEME']) == '')
			{
				$template = $this->default_theme;
			}
			else
			{
				$template = $_SESSION['MOPR_MOBILE_THEME'];
			}
			
			return $template;
		}
		
		/**
		 * Sets the theme root to the MobilePress theme directory
		 *
		 * @package MobilePress
		 * @since 1.0
		 * @return STRING Root directory of the MobilePress theme directory
		 */
		function set_theme_root()
		{
			if ( ! isset($_SESSION['MOPR_MOBILE_THEME']) || trim($_SESSION['MOPR_MOBILE_THEME']) == '')
			{
				$template = $this->default_theme;
			}
			else
			{
				$template = $_SESSION['MOPR_MOBILE_THEME'];
			}
			
			if ($template == 'default' || $template == 'iphone')
			{
				return MOPR_ROOT_PATH . "system/themes";
			}
			else
			{
				return rtrim(WP_CONTENT_DIR, '/') . '/' . mopr_get_option('themes_directory', 1);
			}
		}
		
		/**
		 * Sets the path to the themes directory
		 *
		 * @package MobilePress
		 * @since 1.0
		 * @return STRING The MobilePress theme directory
		 */
		function set_theme_uri()
		{
			if ( ! isset($_SESSION['MOPR_MOBILE_THEME']) || trim($_SESSION['MOPR_MOBILE_THEME']) == '')
			{
				$template = $this->default_theme;
			}
			else
			{
				$template = $_SESSION['MOPR_MOBILE_THEME'];
			}
			
			if ($template == 'default' || $template == 'iphone')
			{
				return get_bloginfo('wpurl') . "/wp-content/plugins/mobilepress/system/themes";
			}
			else
			{
				return get_bloginfo('wpurl') . "/wp-content/" . mopr_get_option('themes_directory', 1);
			}
		}
		
		/**
		 * Returns the mobile blogs title
		 *
		 * @package MobilePress
		 * @since 1.0
		 * @return STRING The mobile blogs title
		 */
		function set_title()
		{
			return $this->title;
		}
		
	}
}
?>