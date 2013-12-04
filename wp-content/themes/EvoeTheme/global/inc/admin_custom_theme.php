<?php
	include '../../../../../wp-config.php';
	include_once(TEMPLATEPATH .   '/global/inc/custom_theme.php');
	
    global $custom_theme;
	
	$bg = get_option('custom_theme');
	
    if($_POST['action'] === 'restoreBg')
    {
        if( $custom_theme->unset_customTheme_image( $bg['background']['id'], '', '', '' ) )
		{
			echo __('Imagem do fundo(background) restaurada!', 'evoeTheme');
		}
    }
    
    elseif($_POST['action'] === 'restoreLogo')
    {
        if( $custom_theme->unset_customTheme_image( '', $bg['header']['logoId'], '', '' ) )
		{
			echo __('Imagem do logotipo restaurada!', 'evoeTheme');
		}
    }
    
    elseif($_POST['action'] === 'restoreHeader')
    {
        if( $custom_theme->unset_customTheme_image( '', '', $bg['header']['imageId'], '' ) )
		{
			echo __('Imagem do cabeçalho restaurada!', 'evoeTheme');
		}
    }
    
    elseif($_POST['action'] === 'restorePattern')
    {
        if( $custom_theme->unset_customTheme_image( '', '', '', $bg['box']['patternId'] ) )
		{
			echo __('Imagem pattern restaurada!', 'evoeTheme');
		}
    }
	
	elseif($_POST['action'] === 'restore')
	{
		$general = array(
        		'custom_theme' => $bg['name'],
        		'custom_themeD' => '1'
		);
		
		if( !empty( $bg['box']['patternId'] ) )
		{
			$custom_theme->unset_customTheme_image( '', '', '', $bg['box']['patternId'] );
		}
		
		if( !empty( $bg['header']['imageId'] ) )
		{
			$custom_theme->unset_customTheme_image( '', '', $bg['header']['imageId'], '' );
		}
		
		if( !empty( $bg['header']['logoId'] ) )
		{
			$custom_theme->unset_customTheme_image( '', $bg['header']['logoId'], '', '' );
		}
		
		if( !empty( $bg['background']['id'] ) )
		{
			$custom_theme->unset_customTheme_image( $bg['background']['id'], '', '', '' );
		}
		
        $custom_theme->set_customTheme_general( $general );
		
		echo __('Tema restaurado!', 'evoeTheme');
	}
    
    elseif($_POST['action'] === 'save')
    {
        $linkColor = $custom_theme->color( $_POST['linkColor'] );
        
        $background = array( 
                'color' => $custom_theme->color( $_POST['bgColor'] ),
                'position' => $_POST['bgPosition'],
                'repeat' => $_POST['bgRepeat'],
                'attachment' => $_POST['bgAttachment'],
        );
        
        $header = array(
        		'menuOpacity' => $_POST['menuOpacity'],
                'text' => $_POST['headerTitle'],
                'textColor' => $custom_theme->color( $_POST['titleColorInput'] ),
                'logoPosition' => $_POST['logoPosition'],
                'color' => $custom_theme->color( $_POST['headerColorInput'] ),
                'imagePosition' => $_POST['headerPosition'],
                'imageRepeat' => $_POST['headerRepeat'],
        );
        
        $box = array(
                'color' => $custom_theme->color( $_POST['boxColor'] ),
                'textColor' => $custom_theme->color( $_POST['textColor'] ),
                'patternOpacity' => $_POST['boxOpacity'],
                'pattern' => empty( $box['patternUrl'] ) ? $_POST['patternValue'] : $box['patternUrl']
        );
        
        $custom_theme->set_customTheme_background( $background );
        $custom_theme->set_customTheme_header( $header );
        $custom_theme->set_customTheme_content( $box, $linkColor );
		
		if( !empty( $_POST['hlCategory'] ) )
		{
			$general = array('hlCategory' => $_POST['hlCategory']);
			
			$custom_theme->set_customTheme_general( $general );
		}
		
		if( $_POST['custom_themeD'] == 1 )
		{
			$general = array(
					'custom_theme' => $_POST['custom_theme'],
					'custom_themeD' => $_POST['custom_themeD']
			);
			
			if( !empty( $bg['box']['patternId'] ) )
			{
				$custom_theme->unset_customTheme_image( '', '', '', $bg['box']['patternId'] );
			}
			
			if( !empty( $bg['header']['imageId'] ) )
			{
				$custom_theme->unset_customTheme_image( '', '', $bg['header']['imageId'], '' );
			}
			
			if( !empty( $bg['header']['logoId'] ) )
			{
				$custom_theme->unset_customTheme_image( '', $bg['header']['logoId'], '', '' );
			}
			
			if( !empty( $bg['background']['id'] ) )
			{
				$custom_theme->unset_customTheme_image( $bg['background']['id'], '', '', '' );
			}
			
			$custom_theme->set_customTheme_general( $general );
		}
        
        echo __('Tema atualizado!', 'evoeTheme');
    }
?>
