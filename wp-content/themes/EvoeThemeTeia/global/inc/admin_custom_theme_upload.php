<?php
	include '../../../../../wp-blog-header.php';
	include_once(TEMPLATEPATH .   '/global/inc/custom_theme.php');

    global $custom_theme;
	
	if($_POST['upload'] === 'bg') 
	{
		$bgImage = $_FILES['bgImage'];
		$bg = $custom_theme->upload_image( $bgImage );
		
		if( is_array( $bg ) )
		{
			$background['url'] = $bg['url'];
			$background['id'] = $bg['id'];
			
			$custom_theme->set_customTheme_background( $background );
			echo $background['url'];
		}
		else
		{
			echo 'Error';
		}
	}
	
	if($_POST['upload'] === 'logo') 
	{
		$logoImage = $_FILES['logoImage'];			
		$logo = $custom_theme->upload_image( $logoImage );
		
		if( is_array( $logo ) )
		{
			$header_['logo'] = $logo['url'];
			$header_['logoId'] = $logo['id'];
		
			$custom_theme->set_customTheme_header( $header_ );
			echo $header_['logo'];
		}
		else
		{
			echo 'Error';
		}
	}
	
	if($_POST['upload'] === 'header') 
	{
		$headerImage = $_FILES['headerImage'];			
		$header = $custom_theme->upload_image( $headerImage );
		
		if( is_array( $header ) )
		{
			$header_['image'] = $header['url'];
			$header_['imageId'] = $header['id'];
			
			$custom_theme->set_customTheme_header( $header_ );
			echo $header_['image'];
		}
		else
		{
			echo 'Error';
		}
	}
	
	if($_POST['upload'] === 'pattern') 
	{
		$patternImage = $_FILES['patternImage'];			
		$pattern = $custom_theme->upload_image( $patternImage );
		
		if( is_array( $pattern ) )
		{
			$box['pattern'] = $pattern['url'];
			$box['patternId'] = $pattern['id'];
			
			$custom_theme->set_customTheme_content( $box, '' );
			echo $box['pattern'];
		}
		else
		{
			echo 'Error';
		}
	}
	
?>