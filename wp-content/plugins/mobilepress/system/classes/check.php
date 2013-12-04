<?php
if ( ! class_exists('MobilePress_check'))
{
	/**
	 * Class that does all the checks to determine if we are dealing with a Mobile browser
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	class MobilePress_check {
		
		/**
		 * Initialize the checking of the mobile browse
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		function init()
		{
			// Get the theme we need to render
			$this->theme = mopr_get_option('default_theme', 1);
			
			// If viewing the mobile website
			switch(TRUE)
			{	
				// ?mobile accesses the mobile version of the website
				case (isset($_GET['mobile'])):
					$browser	= "mobile";
					$activated	= TRUE;
					break;
					
				// If forcing iphone theme
				case (isset($_GET['iphone'])):
					$browser	= "iphone";
					$activated	= TRUE; 
					$theme		= mopr_get_option('iphone_theme',1);
					break;
				
				// ?nomobile renders the orignial website
				case (isset($_GET['nomobile'])):
					$activated	= FALSE;
					$theme		= '';
					break;
					
				// Apple/iPhone browser renders as mobile
				case (preg_match('/(apple|iphone|ipod)/i', $_SERVER['HTTP_USER_AGENT']) && preg_match('/mobile/i', $_SERVER['HTTP_USER_AGENT'])):
					$browser	= "iphone";
					$activated	= TRUE;
					$theme		= mopr_get_option('iphone_theme',1);
					break;
					
				// Other mobile browsers render as mobile
				case (preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])):
					$browser	= "mobile";
					$activated	= TRUE;
					break;
					
				// Wap browser
				case (((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'text/vnd.wap.wml') > 0) || (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0)) || ((isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])))):
					$activated = TRUE;
					break;
				
				// Shortend user agents
				case (in_array(strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,3)),array('lg '=>'lg ','lg-'=>'lg-','lg_'=>'lg_','lge'=>'lge'))); 
					$browser = "mobile";
					$activated = TRUE;
					break;
				
				// More shortend user agents
				case (in_array(strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4)),array('acs-'=>'acs-','amoi'=>'amoi','doco'=>'doco','eric'=>'eric','huaw'=>'huaw','lct_'=>'lct_','leno'=>'leno','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','nec-'=>'nec-','phil'=>'phil','sams'=>'sams','sch-'=>'sch-','shar'=>'shar','sie-'=>'sie-','wap_'=>'wap_','zte-'=>'zte-')));
					$browser	= "mobile";
					$activated	= TRUE;
					break;
					
				// Render mobile site for mobile search engines
				case (preg_match('/Googlebot-Mobile/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/YahooSeeker\/M1A1-R2D2/i', $_SERVER['HTTP_USER_AGENT'])):
					$browser	= "mobile";
					$activated	= TRUE;
					break;
			}
			
			$_SESSION['MOPR_MOBILE_BROWSER'] 	= $browser;
			$_SESSION['MOPR_MOBILE_ACTIVE'] 	= $activated;
			$_SESSION['MOPR_MOBILE_THEME'] 		= $theme;
		}
		
	}
}
?>