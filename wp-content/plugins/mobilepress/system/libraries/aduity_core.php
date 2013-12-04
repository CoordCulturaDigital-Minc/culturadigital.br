<?php
/*
	Aduity Ad Request Code
	Copyright Aduity (Pty) Ltd, All Rights Reserved
	Language: PHP (PHP 4 and PHP 5) using fsockopen()
	Docs & Support: http://help.aduity.com
	Version: 110909 
*/

// Set this value to 1 for debug mode
define('REQUEST_DEBUG', mopr_get_option('aduity_debug_mode'));

define('ACCOUNT_PUBLIC_KEY', mopr_get_option('aduity_account_public_key'));
define('SITE_PUBLIC_KEY', mopr_get_option('aduity_site_public_key'));

// NO NEED TO EDIT ANYTHING BELOW THIS LINE
if ( ! function_exists('aduity_display_ad'))
{
	function aduity_display_ad($request_data = NULL)
	{
		// Make the request
		$request = new Aduity_request($request_data);
	}
}

if ( ! class_exists('Aduity_request'))
{
	class Aduity_request {
	
		var $account_public_key		= NULL;
		var $site_public_key		= NULL;
		var $ad_type				= NULL;
		var $request_server 		= 'r.aduity.com';
		var $version				= 'v1';
		var $request_data;
		
		function Aduity_request($request_data)
		{
			if (ACCOUNT_PUBLIC_KEY != '' && SITE_PUBLIC_KEY != '')
			{
				// Set the account public key
				$this->account_public_key = ACCOUNT_PUBLIC_KEY;
				
				// Set site public key
				$this->site_public_key = SITE_PUBLIC_KEY;
					
				if (is_array($request_data))
				{
					// Set the ad type if available
					if (array_key_exists('ad_type', $request_data))
						$this->ad_type = $request_data['ad_type'];
				}
				
				// Setup the data
				$this->_set_request_data();
				
				echo $this->make_request();
			}
			
			return;
		}
		
		function make_request()
		{
			// Open a new connection to the server
			$conn = @fsockopen($this->request_server, 80, $errno, $errstr, 5);
			
			if ( ! $conn)
			{
				return NULL;
			}
			else
			{
				$header	 = "POST /" . $this->version . "/ HTTP/1.0\r\n";
				$header	.= "Host: " . $this->request_server . "\r\n";
				$header	.= "Content-Type: application/x-www-form-urlencoded\r\n";
				$header	.= "Content-Length: " . strlen($this->request_data) . "\r\n";
				$header	.= "Connection: close\r\n\r\n";
				$header	.= $this->request_data;
				
				fputs($conn, $header, strlen($header));
				
				// Lets grab the ad code
				while ( ! feof($conn))
				{
					$data		= fgets($conn);
					$content	= '';
					
					if (isset($start_copy))
					{					
						$content .= $data;
					}
					
					if ($data == "\r\n" && ! isset($start_copy))
					{
						$start_copy = TRUE;
					}
				}
			}
			
			fclose($conn);
			
			if (isset($content))
			{
				return $content;
			}
		}
		
		function _set_request_data()
		{
			// Create the data we need
			$this->request_data = '&apk=' . $this->account_public_key;
			
			$this->request_data .= '&ppk=' . $this->site_public_key;
			
			// We only need this data it is set and we are serving an ad
			if (isset($this->ad_type))
				$this->request_data .= '&adt=' . $this->ad_type;
			
			// some variables we can determine on our own
			if (array_key_exists('HTTP_HOST', $_SERVER))
				$this->request_data .= '&sc=' . $_SERVER['HTTP_HOST'];
			
			if (array_key_exists('REQUEST_URI', $_SERVER))
				$this->request_data .= '&sn=' . $_SERVER['REQUEST_URI'];
			
			if (array_key_exists('HTTP_REFERER', $_SERVER))
				$this->request_data .= '&rf=' . $_SERVER['HTTP_REFERER'];
			
			// is the user's brower Opera Mini?
			if (array_key_exists('HTTP_X_OPERAMINI_PHONE_UA', $_SERVER))
			{
				$this->request_data .= '&ua=' . $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'];
				$this->request_data .= '&br=1';
			}
			else
			{
				$this->request_data .= '&ua=' . $_SERVER['HTTP_USER_AGENT'];	
				$this->request_data .= '&br=0';		
			}
			
			if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
			{
				$this->request_data .= '&ip=' . $_SERVER['HTTP_X_FORWARDED_FOR'];	
			}
			else
			{
				$this->request_data .= '&ip=' . $_SERVER['REMOTE_ADDR'];
			}
			
			$this->request_data .= '&db=' . REQUEST_DEBUG;
		}
	}
}
?>