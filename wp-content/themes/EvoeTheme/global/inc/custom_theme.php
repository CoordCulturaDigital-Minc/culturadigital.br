<?php
/*
	function name: custom theme
	author: Marcos Maia Lopes
*/
include_once(ABSPATH . 'wp-admin/includes/media.php');
include_once(ABSPATH . 'wp-admin/includes/file.php');

class custom_theme
{
	private $custom_theme;
	private $theme1;
	
	function custom_theme()
	{
		$this->custom_theme = get_option('custom_theme');
	}
	
	// Background style
	function color( $color )
	{
		if(!empty($color))
		{
			$color = str_replace('#', '', $color);
			
			return $color;
		}
	}
	
	function upload_image( $image )
	{
		if(isset($image))
		{
			$fileMaxSize = 800000; // file size in bytes
			$formats = array("image/jpeg", "image/pjpeg", "image/png", "image/gif");
			$overrides = array('test_form' => false);
			$file = wp_handle_upload($image, $overrides);
						
			if (isset($file['error']))
			die($file['error']);
			
			$url = $file['url'];
			$type = $file['type'];
			$file = $file['file'];
			$filename = basename($file);
	
			// Construct the object array
			$object = array(
			'post_title' => $filename,
			'post_content' => $url,
			'post_mime_type' => $type,
			'guid' => $url);
	
			if($file['size'] > $fileMaxSize)
			{
				//print _e('Erro. O arquivo enviado é maior que 800kb!');

				return false;
			}
			elseif(!in_array($type, $formats))
			{
				//print _e('Erro. O formato do arquivo enviado não é suportado!');

				return false;
			}
			else
			{
				// Save data
				$id = wp_insert_attachment($object, $file);
				$id = apply_filters('wp_create_file_in_uploads', $id, $_POST['attachment_id']);
				
				do_action('wp_create_file_in_uploads', $file, $id);
				$arr = array('url' => $url, 'id' => $id);
				
				return $arr;
			}
		}
	}
	
	function set_customTheme_background( $arr )
	{
		if(!empty($arr['color']))
		{
			$this->custom_theme['background']['color'] = $arr['color'];
					
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['url']))
		{
			$this->custom_theme['background']['url'] = $arr['url'];
			$this->custom_theme['background']['id'] = $arr['id'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['position']) and !empty($arr['repeat']))
		{
			$this->custom_theme['background']['position'] = $arr['position'];
			$this->custom_theme['background']['repeat'] = $arr['repeat'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['attachment']))
		{
			$this->custom_theme['background']['attachment'] = $arr['attachment'];
			
			update_option('custom_theme', $this->custom_theme);
		}
	}
	
	function set_customTheme_header( $arr )
	{
		if(!empty($arr['menuOpacity']))
		{
			$this->custom_theme['header']['menuOpacity'] = $arr['menuOpacity'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['text']))
		{	
			$this->custom_theme['header']['text'] = $arr['text'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		else
		{
			$this->custom_theme['header']['text'] = '0';
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['textColor']))
		{
			$this->custom_theme['header']['textColor'] = $arr['textColor'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['logo']))
		{
			$this->custom_theme['header']['logo'] = $arr['logo'];
			$this->custom_theme['header']['logoId'] = $arr['logoId'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['logoPosition']))
		{
			$this->custom_theme['header']['logoPosition'] = $arr['logoPosition'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['color']))
		{
			$this->custom_theme['header']['color'] = $arr['color'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['image']))
		{
			$this->custom_theme['header']['image'] = $arr['image'];
			$this->custom_theme['header']['imageId'] = $arr['imageId'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['imagePosition']) || !empty($arr['imageRepeat']))
		{
			$this->custom_theme['header']['imagePosition'] = $arr['imagePosition'];
			$this->custom_theme['header']['imageRepeat'] = $arr['imageRepeat'];
			
			update_option('custom_theme', $this->custom_theme);
		}
	}
	
	function set_customTheme_content( $arr, $linkColor )
	{
		if(!empty($arr['color']))
		{
			$this->custom_theme['box']['color'] = $arr['color'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['textColor']))
		{
			$this->custom_theme['box']['textColor'] = $arr['textColor'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['pattern']))
		{
			$this->custom_theme['box']['pattern'] = $arr['pattern'];
			
			if( !empty( $arr['patternId'] ) )
			{
				$this->custom_theme['box']['patternId'] = $arr['patternId'];
			}
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($arr['patternOpacity']))
		{
			$this->custom_theme['box']['patternOpacity'] = $arr['patternOpacity'];
			
			update_option('custom_theme', $this->custom_theme);
		}
		
		if(!empty($linkColor))
		{
			$this->custom_theme['content']['linkColor'] = $linkColor;
			
			update_option('custom_theme', $this->custom_theme);
		}
	}
	
	function set_customTheme_general ( $arr )
	{
		if(!empty($arr['custom_theme']))
		{
			switch( $arr['custom_theme'] )
			{
				case 'theme1':
					$this->custom_theme['name'] = 'theme1';
					$this->custom_theme['background']['color'] = 'E0E0E0';
					$this->custom_theme['background']['url'] = '';
					$this->custom_theme['background']['position'] = 'left top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'scroll';
					$this->custom_theme['background']['id'] = '';
					$this->custom_theme['box']['color'] = '026D9B';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = '';
					$this->custom_theme['box']['patternOpacity'] = '100';
					$this->custom_theme['box']['patternId'] = '';
					$this->custom_theme['header']['menuOpacity'] = '100';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = '026D9B';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = '026D9B';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme2':
					$this->custom_theme['name'] = 'theme2';
					$this->custom_theme['background']['color'] = '000000';
					$this->custom_theme['background']['url'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme2/bg.jpg';
					$this->custom_theme['background']['position'] = 'right top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'scroll';
					$this->custom_theme['background']['id'] = 'theme2';
					$this->custom_theme['box']['color'] = '000000';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme2/pattern.jpg';
					$this->custom_theme['box']['patternOpacity'] = '100';
					$this->custom_theme['box']['patternId'] = 'theme2';
					$this->custom_theme['header']['menuOpacity'] = '100';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = 'b31b5c';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme3':
					$this->custom_theme['name'] = 'theme3';
					$this->custom_theme['background']['color'] = '000000';
					$this->custom_theme['background']['url'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme3/bg.jpg';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'scroll';
					$this->custom_theme['background']['id'] = 'theme3';
					$this->custom_theme['box']['color'] = '000000';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme3/pattern.jpg';
					$this->custom_theme['box']['patternOpacity'] = '100';
					$this->custom_theme['box']['patternId'] = 'theme3';
					$this->custom_theme['header']['menuOpacity'] = '100';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = 'c78800';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme4':
					$this->custom_theme['name'] = 'theme4';
					$this->custom_theme['background']['color'] = 'ffffff';
					$this->custom_theme['background']['url'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme4/bg.jpg';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'fixed';
					$this->custom_theme['background']['id'] = 'theme4';
					$this->custom_theme['box']['color'] = '000000';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme4/pattern.jpg';
					$this->custom_theme['box']['patternOpacity'] = '100';
					$this->custom_theme['box']['patternId'] = 'theme4';
					$this->custom_theme['header']['menuOpacity'] = '100';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = '8f337e';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme5':
					$this->custom_theme['name'] = 'theme5';
					$this->custom_theme['background']['color'] = '000000';
					$this->custom_theme['background']['url'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme5/bg.jpg';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'scroll';
					$this->custom_theme['background']['id'] = 'theme5';
					$this->custom_theme['box']['color'] = '000000';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = '';
					$this->custom_theme['box']['patternOpacity'] = '73';
					$this->custom_theme['box']['patternId'] = 'theme5';
					$this->custom_theme['header']['menuOpacity'] = '65';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = '1b3152';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme6':
					$this->custom_theme['name'] = 'theme6';
					$this->custom_theme['background']['color'] = '450305';
					$this->custom_theme['background']['url'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme6/bg.jpg';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'scroll';
					$this->custom_theme['background']['id'] = 'theme6';
					$this->custom_theme['box']['color'] = '450305';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = '';
					$this->custom_theme['box']['patternOpacity'] = '100';
					$this->custom_theme['box']['patternId'] = '';
					$this->custom_theme['header']['menuOpacity'] = '65';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = '450305';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme7':
					$this->custom_theme['name'] = 'theme7';
					$this->custom_theme['background']['color'] = '000000';
					$this->custom_theme['background']['url'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme7/bg.jpg';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'repeat-x';
					$this->custom_theme['background']['attachment'] = 'scroll';
					$this->custom_theme['background']['id'] = 'theme7';
					$this->custom_theme['box']['color'] = '000000';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme7/pattern.jpg';
					$this->custom_theme['box']['patternOpacity'] = '55';
					$this->custom_theme['box']['patternId'] = 'theme7';
					$this->custom_theme['header']['menuOpacity'] = '100';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = '5E3156';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme8':
					$this->custom_theme['name'] = 'theme8';
					$this->custom_theme['background']['color'] = 'ffffff';
					$this->custom_theme['background']['url'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme8/bg.jpg';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'repeat-x';
					$this->custom_theme['background']['attachment'] = 'fixed';
					$this->custom_theme['background']['id'] = 'theme8';
					$this->custom_theme['box']['color'] = '487300';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = '';
					$this->custom_theme['box']['patternOpacity'] = '100';
					$this->custom_theme['box']['patternId'] = '';
					$this->custom_theme['header']['menuOpacity'] = '100';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = '487300';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme9':
					$this->custom_theme['name'] = 'theme9';
					$this->custom_theme['background']['color'] = '000000';
					$this->custom_theme['background']['url'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme9/bg.jpg';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'scroll';
					$this->custom_theme['background']['id'] = 'theme9';
					$this->custom_theme['box']['color'] = '000000';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme9/pattern.jpg';
					$this->custom_theme['box']['patternOpacity'] = '65';
					$this->custom_theme['box']['patternId'] = 'theme9';
					$this->custom_theme['header']['menuOpacity'] = '100';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = '026d9b';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme10':
					$this->custom_theme['name'] = 'theme10';
					$this->custom_theme['background']['color'] = 'ffffff';
					$this->custom_theme['background']['url'] = '';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'scroll';
					$this->custom_theme['background']['id'] = '';
					$this->custom_theme['box']['color'] = '616161';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = '';
					$this->custom_theme['box']['patternOpacity'] = '65';
					$this->custom_theme['box']['patternId'] = '';
					$this->custom_theme['header']['menuOpacity'] = '100';
					$this->custom_theme['header']['textColor'] = '707070';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = '000000';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme11':
					$this->custom_theme['name'] = 'theme11';
					$this->custom_theme['background']['color'] = '000000';
					$this->custom_theme['background']['url'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme11/bg.jpg';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'fixed';
					$this->custom_theme['background']['id'] = '';
					$this->custom_theme['box']['color'] = '200460';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = '';
					$this->custom_theme['box']['patternOpacity'] = '100';
					$this->custom_theme['box']['patternId'] = '';
					$this->custom_theme['header']['menuOpacity'] = '100';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = 'transparent';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['content']['linkColor'] = '200460';
					
					update_option('custom_theme', $this->custom_theme);
				break;
				
				case 'theme12':
					$this->custom_theme['name'] = 'theme12';
					$this->custom_theme['background']['color'] = 'd9d9d9';
					$this->custom_theme['background']['url'] = '';
					$this->custom_theme['background']['position'] = 'center top';
					$this->custom_theme['background']['repeat'] = 'no-repeat';
					$this->custom_theme['background']['attachment'] = 'scroll';
					$this->custom_theme['background']['id'] = '';
					$this->custom_theme['box']['color'] = '092242';
					$this->custom_theme['box']['textColor'] = 'ffffff';
					$this->custom_theme['box']['pattern'] = '';
					$this->custom_theme['box']['patternOpacity'] = '100';
					$this->custom_theme['box']['patternId'] = '';
					$this->custom_theme['header']['menuOpacity'] = '93';
					$this->custom_theme['header']['textColor'] = 'ffffff';
					$this->custom_theme['header']['color'] = '000000';
					$this->custom_theme['header']['text'] = '0';
					$this->custom_theme['header']['logoPosition'] = 'left center';
					$this->custom_theme['header']['image'] = get_bloginfo('template_directory').'/global/img/graph/skins/theme12/bgHeader.jpg';
					$this->custom_theme['header']['imageId'] = 'theme12';
					$this->custom_theme['header']['imagePosition'] = 'center top';
					$this->custom_theme['header']['imageRepeat'] = 'no-repeat';
					$this->custom_theme['content']['linkColor'] = '092242';
					
					update_option('custom_theme', $this->custom_theme);
				break;
			}
		}
		if(!empty($arr['hlCategory']))
		{
			$this->custom_theme['general']['hlCategory'] = $arr['hlCategory'];
			
			update_option('custom_theme', $this->custom_theme);
		}
	}
	
	function unset_customTheme_image( $bg, $logo, $header, $pattern )
	{
		if(!empty($bg))
		{
			$this->custom_theme['background']['url'] = '';
			$this->custom_theme['background']['position'] = '';
			$this->custom_theme['background']['repeat'] = '';
			$this->custom_theme['background']['id'] = '';
		
			update_option('custom_theme', $this->custom_theme);
			wp_delete_attachment( $bg );
			
			return true;
		}
		
		elseif(!empty($logo))
		{
			$this->custom_theme['header']['logo'] = '';
			$this->custom_theme['header']['logoPosition'] = '';
			$this->custom_theme['header']['logoId'] = '';
		
			update_option('custom_theme', $this->custom_theme);
			wp_delete_attachment( $logo );
			
			return true;
		}
		
		elseif(!empty($header))
		{
			$this->custom_theme['header']['image'] = '';
			$this->custom_theme['header']['position'] = '';
			$this->custom_theme['header']['repeat'] = '';
			$this->custom_theme['header']['imageId'] = '';
		
			update_option('custom_theme', $this->custom_theme);
			wp_delete_attachment( $header );
			
			return true;
		}
		
		elseif(!empty($pattern))
		{
			$this->custom_theme['box']['pattern'] = '';
			$this->custom_theme['box']['patternId'] = '';
		
			update_option('custom_theme', $this->custom_theme);
			wp_delete_attachment( $pattern );
			
			return true;
		}
	}
}

$custom_theme = new custom_theme();