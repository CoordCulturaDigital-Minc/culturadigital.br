<?php
if ( ! function_exists('mopr_ad'))
{
	function mopr_ad($type)
	{

		// Include the TI library
		require_once(MOPR_PATH . 'libraries/aduity_core.php');

		if ( ! mopr_get_option('aduity_ads_enabled'))
		{
			return;
		}
		
		$ad_location = mopr_get_option('aduity_ads_location');
		
		if ($type == 'top')
		{
			if ( ! ($ad_location == '1' || $ad_location == '0'))
			{
				return;
			}
		}
		else if ($type == 'bottom')
		{
			if ( ! ($ad_location == '2' || $ad_location == '0'))
			{
				return;
			}
		}
		else
		{
			return;
		}
		
		aduity_display_ad();
		
		return;
	}
}
?>