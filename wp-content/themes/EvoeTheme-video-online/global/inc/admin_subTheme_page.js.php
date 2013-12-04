<?php
	header("Content-Type: text/javascript");
	
	include '../../../../../wp-config.php';
	
	$bg = get_option('custom_theme');
?>

// admin_subTheme_page.js

jQuery(function()
{
    jQuery( '<div class="overlay"></div><div class="modal"><img class="loading_" src="<?php bloginfo('template_directory'); ?>/global/img/graph/graph_loading.gif" alt="Uploading..." /><input type="button" class="bt_close" value="OK" /></div>' ).appendTo( 'body' );
    
    if( jQuery.cookie( 'modal' ) )
    {
    	jQuery( 'div.overlay' ).fadeIn( 'fast', function()
        {
        	jQuery( '.bt_close' ).show();
	        jQuery( '<p class="modal_">' + jQuery.cookie( 'modal' ) + '</p>' ).prependTo( 'div.modal' );
        	jQuery( 'div.modal' ).fadeIn( 'slow' );
        });
    }

	// IE PROBLEM
	jQuery( '#SliderSingle2' ).slider({
		from: 0,
		to: 100,
		step: 1.0,
		round: 1,
		dimension: '&nbsp;%',
		onstatechange: function(value)
		{
			jQuery( '#themepreview' ).contents().find( 'div#header div.nav div.bg' ).css({
				opacity : value/100,
				filter : 'alpha(opacity=' + value + ')'
			});
		}
	});
	
	jQuery( '#SliderSingle3' ).slider({
		from: 0,
		to: 100,
		step: 1.0,
		round: 1,
		dimension: '&nbsp;%',
		onstatechange: function(value)
		{
			jQuery( '#themepreview' ).contents().find( 'div.bigTitle div.previewPattern, div#footer div.background' ).css({
				opacity : value/100,
				filter : 'alpha(opacity=' + value + ')'
			});
		}
	});
    
	jQuery( 'form' ).tabs();
	jQuery( 'div#design' ).tabs();
	
	jQuery( '.bt_general' ).click(function()
	{
		jQuery( 'div.preview' ).fadeOut();
	});
	
	jQuery( '.bt_design' ).click(function()
	{
		jQuery( 'div.preview' ).fadeIn();
	});
		
	// IE PROBLEM
	jQuery( 'div.color label' ).each(function()
	{
		jQuery( this ).children( 'span' ).css( 'backgroundColor', jQuery( this ).children( 'span' ).text() );
	});
	
	jQuery( 'div.bgPosition input[name=bgPosition]' ).change(function()
	{
		var pos = jQuery( this ).val();
		
		jQuery( '#themepreview' ).contents().find( 'body' ).css( 'backgroundPosition', pos );
	});
	
	jQuery( 'div#bgAttachment input[name=bgAttachment]' ).change(function()
	{
		var pos = jQuery( this ).val();
		
		jQuery( '#themepreview' ).contents().find( 'body' ).css( 'backgroundAttachment', pos );
	});
	
	jQuery( 'div.bgRepeat input[name=bgRepeat]' ).change(function()
	{
		var repeat = jQuery( this ).val();
		
		jQuery( '#themepreview' ).contents().find( 'body' ).css( 'backgroundRepeat', repeat );
	});
	
	jQuery( 'div.titleDisplay input' ).change(function()
	{
		var disp = jQuery( this ).val();
		
		if( disp == '0' )
		{
			jQuery( '#themepreview' ).contents().find( 'div#header .title a' ).css({ textIndent : '0', overflow : 'auto' });
			jQuery( '#themepreview' ).contents().find( 'div#header .title span' ).show();
			jQuery( 'div.titleColor' ).show();
		}
		else
		{
			jQuery( '#themepreview' ).contents().find( 'div#header .title a' ).css({ textIndent : '-999em', overflow : 'auto' });
			jQuery( '#themepreview' ).contents().find( 'div#header .title span' ).hide();
			jQuery( 'div.titleColor' ).hide();
		}
		
	});
	
	jQuery( 'div.bgPosition input[name=logoPosition]' ).change(function()
	{
		var pos = jQuery( this ).val();
		
		jQuery( '#themepreview' ).contents().find( 'div#header .title a' ).css( 'backgroundPosition', pos );
	});
	
	jQuery( 'div.bgPosition input[name=headerPosition]' ).change(function()
	{
		var pos = jQuery( this ).val();
		
		jQuery( '#themepreview' ).contents().find( 'div#header' ).css( 'backgroundPosition', pos );
	});
	
	jQuery( 'div.bgRepeat input[name=headerRepeat]' ).change(function()
	{
		var repeat = jQuery( this ).val();
		
		jQuery( '#themepreview' ).contents().find( 'div#header' ).css( 'backgroundRepeat', repeat );
	});
	
	jQuery( 'div#pattern li label' ).click(function()
	{
		var src = jQuery( this ).find( 'img' ).attr('src');
		
		if( jQuery( this ).children().is( 'span' ) )
		{
			jQuery( '#themepreview' ).contents().find( 'div.bigTitle div.previewPattern, div#footer div.background' ).css( 'background', 'transparent' );
		}
		else
		{
			jQuery( '#themepreview' ).contents().find( 'div.bigTitle div.previewPattern, div#footer div.background' ).css( 'background', 'transparent url(' + src + ') repeat' );
			jQuery( '#patternValue' ).val( src );
		}
		
	});
	
	jQuery( 'div.color label, div#pattern li label' ).hover(function()
	{
		if( jQuery( this ).hasClass( '.none' ) )
		{
			jQuery( this ).not( 'label.active' ).children( 'span' ).stop().animate({ marginTop: '8px', fontSize: '22px' });
		}
		
		jQuery( this ).not( 'label.active' ).stop().animate({ width: '35px', height: '35px', marginTop: '6px', duration: 100 });
	},function()
	{
		if( jQuery( this ).hasClass( '.none' ) )
		{
			jQuery( this ).not( 'label.active' ).children( 'span' ).stop().animate({ marginTop: '4px', fontSize: '18px' });
		}
		
		jQuery( this ).not( 'label.active' ).stop().animate({ width: '25px', height: '25px', marginTop: '12px', duration: 100 });
	});
	
	jQuery( 'div.color label, div#pattern li label' ).click(function()
	{
		var $this = jQuery( this );
		
		$this.parent().siblings().children( 'label.none' ).children( 'span' ).animate({ marginTop: '4px', fontSize: '18px' });
		$this.parent().siblings().children( 'label' ).animate({ width: '25px', height: '25px', marginTop: '12px', duration: 100 });
		$this.parent().siblings().children( 'label ').removeClass( 'active' );
		
		if( $this.attr( 'class' ) == 'none' )
		{
			$this.children( 'span' ).css( 'display', 'block' );
			$this.children( 'span' ).animate({ marginTop: '13px', fontSize: '25px' });
		}
		
		$this.animate({ width: '45px', height: '45px', marginTop: '0', duration: 100 });
		$this.addClass( 'active' );
	});
	
	jQuery( '.colorpicker_' ).click(function()
	{
		if( jQuery( this ).siblings( 'div' ).css( 'display', 'none' ) )
		{
			jQuery( this ).siblings( 'div' ).show();
		}
		else
		{
			jQuery( this ).siblings( 'div' ).hide();
		}
	});
	
	jQuery( '.bgPicker, .titlePicker, .headerPicker, .boxPicker, .boxTextPicker, .linkPicker' ).ColorPicker({
		flat: true,
		onSubmit: function (hsb, hex, rgm, el) {
			var _el = jQuery( el );
			
			_el.siblings( '.colorpicker_' ).find( 'div' ).css( 'backgroundColor', '#' + hex );
			_el.siblings( 'input.colorpicker' ).val( hex );
			_el.hide();
			
			switch ( _el.attr('class') )
			{
				case 'bgPicker':
					jQuery( '#themepreview' ).contents().find( 'body' ).css( 'backgroundColor', '#' + hex );
				break;
				
				case 'titlePicker':
					jQuery( '#themepreview' ).contents().find( 'div#header div.middle .title a' ).css( 'color', '#' + hex );
				break;
				
				case 'headerPicker':
					jQuery( '#themepreview' ).contents().find( 'div#header' ).css( 'backgroundColor', '#' + hex );
				break;
				
				case 'boxPicker':
					jQuery( '#themepreview' ).contents().find( 'div.bigTitle' ).css( 'backgroundColor', '#' + hex );
				break;
				
				case 'boxTextPicker':
					jQuery( '#themepreview' ).contents().find( 'div.bigTitle h4, div.bigTitle h2' ).css( 'color', '#' + hex );
				break;
				
				case 'linkPicker':
					jQuery( '#themepreview' ).contents().find( '#content a' ).css( 'color', '#' + hex );
				break;
			}
		}	
	});
	
	jQuery( 'div.bgColor li label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( 'body' ).css( 'backgroundColor', color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( 'input.colorpicker' ).val( color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( '.colorpicker_' ).find( 'div' ).css( 'backgroundColor', color );
		jQuery( '.bgPicker' ).ColorPickerSetColor( color );
	});
	
	jQuery( 'div.titleColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( 'div#header div.middle .title a' ).css( 'color', color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( 'input.colorpicker' ).val( color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( '.colorpicker_' ).find( 'div' ).css( 'backgroundColor', color );
		jQuery( '.titlePicker' ).ColorPickerSetColor( color );
	});
	
	jQuery( 'div.headerColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		if( color == 'X' )
		{
			color = 'transparent';
		}
		
		jQuery( '#themepreview' ).contents().find( 'div#header' ).css( 'backgroundColor', color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( 'input.colorpicker' ).val( color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( '.colorpicker_' ).find( 'div' ).css( 'backgroundColor', color );
		jQuery( '.headerPicker' ).ColorPickerSetColor( color );
	});
	
	jQuery( 'div#boxColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( 'div.bigTitle' ).css( 'backgroundColor', color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( 'input.colorpicker' ).val( color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( '.colorpicker_' ).find( 'div' ).css( 'backgroundColor', color );
		jQuery( '.boxPicker' ).ColorPickerSetColor( color );
	});

	jQuery( 'div#textColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( 'div.bigTitle h4, div.bigTitle h2' ).css( 'color', color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( 'input.colorpicker' ).val( color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( '.colorpicker_' ).find( 'div' ).css( 'backgroundColor', color );
		jQuery( '.boxTextPicker' ).ColorPickerSetColor( color );
	});
	
	jQuery( 'div#linkColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( '#content a' ).css( 'color', color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( 'input.colorpicker' ).val( color );
		jQuery( this ).parent().siblings( 'li.picker' ).children( '.colorpicker_' ).find( 'div' ).css( 'backgroundColor', color );
		jQuery( '.linkPicker' ).ColorPickerSetColor( color );
	});
    
    jQuery( 'div#bg div.bgPosition input[value=<?php print $bg['background']['position']; ?>]' ).attr('checked', true);
    jQuery( 'div#bg div.bgRepeat input[value=<?php print $bg['background']['repeat']; ?>]' ).attr('checked', true);
    jQuery( 'div#bg div#bgAttachment input[value=<?php print $bg['background']['attachment']; ?>]' ).attr('checked', true);
    jQuery( 'div#bg div.bgColor input[value=#<?php print $bg['background']['color']; ?>]' ).siblings().addClass( 'active' );
    
    jQuery( 'div#headerDesign div.titleDisplay input[value=<?php print $bg['header']['text']; ?>]' ).attr('checked', true);
    jQuery( 'div#headerDesign div.titleColor input[value=#<?php print $bg['header']['textColor']; ?>]' ).siblings().addClass( 'active' );
    jQuery( 'div#headerDesign div#logoPosition input[value=<?php print $bg['header']['logoPosition']; ?>]' ).attr('checked', true);
    jQuery( 'div#headerDesign div.headerColor input[value=#<?php print $bg['header']['color']; ?>]' ).siblings().addClass( 'active' );
    jQuery( 'div#headerDesign div#headerPosition input[value=<?php print $bg['header']['imagePosition'] ?>]' ).attr('checked', true);
    jQuery( 'div#headerDesign div#headerRepeat input[value=<?php print $bg['header']['imageRepeat'] ?>]' ).attr('checked', true);
    
    jQuery( 'div.boxColor input[value=#<?php print $bg['box']['color']; ?>]' ).siblings().addClass( 'active' );
    jQuery( 'div.textColor input[value=#<?php print $bg['box']['textColor']; ?>]' ).siblings().addClass( 'active' );
    jQuery( 'div#pattern input[value=<?php if( $bg['box']['pattern'] != 'none' ) : print str_replace( '_', '', strstr( $bg['box']['pattern'], '_pattern' ) ); else : print 'none'; endif; ?>]' ).siblings().addClass( 'active' );
    jQuery( 'div.linkColor input[value=<?php print $bg['content']['linkColor']; ?>]' ).siblings().addClass( 'active' );
    
    jQuery( 'div.bgColor input.colorpicker' ).val( '#<?php print $bg['background']['color']; ?>' );
    jQuery( 'div.bgColor .colorpicker_ div' ).css( 'backgroundColor', '#<?php print $bg['background']['color']; ?>' );
    jQuery( '.bgPicker' ).ColorPickerSetColor( '#<?php print $bg['background']['color']; ?>' );
    
    jQuery( 'div.titleColor input.colorpicker' ).val( '#<?php print $bg['header']['textColor']; ?>' );
    jQuery( 'div.titleColor .colorpicker_ div' ).css( 'backgroundColor', '#<?php print $bg['header']['textColor']; ?>' );
    jQuery( '.titlePicker' ).ColorPickerSetColor( '#<?php print $bg['header']['textColor']; ?>' );
    
    jQuery( 'div.headerColor input.colorpicker' ).val( '#<?php print $bg['header']['color']; ?>' );
    jQuery( 'div.headerColor .colorpicker_ div' ).css( 'backgroundColor', '#<?php print $bg['header']['color']; ?>' );
    jQuery( '.headerPicker' ).ColorPickerSetColor( '#<?php print $bg['header']['color']; ?>' );
    
    jQuery( 'div.boxColor input.colorpicker' ).val( '#<?php print $bg['box']['color']; ?>' );
    jQuery( 'div.boxColor .colorpicker_ div' ).css( 'backgroundColor', '#<?php print $bg['box']['color']; ?>' );
    jQuery( '.boxPicker' ).ColorPickerSetColor( '#<?php print $bg['box']['color']; ?>' );
    
    jQuery( 'div.textColor input.colorpicker' ).val( '#<?php print $bg['box']['textColor']; ?>' );
    jQuery( 'div.textColor .colorpicker_ div' ).css( 'backgroundColor', '#<?php print $bg['box']['textColor']; ?>' );
    jQuery( '.boxTextPicker' ).ColorPickerSetColor( '#<?php print $bg['box']['textColor']; ?>' );
    
    jQuery( 'div.linkColor input.colorpicker' ).val( '#<?php print $bg['content']['linkColor']; ?>' );
    jQuery( 'div.linkColor .colorpicker_ div' ).css( 'backgroundColor', '#<?php print $bg['content']['linkColor']; ?>' );
    jQuery( '.linkPicker' ).ColorPickerSetColor( '#<?php print $bg['content']['linkColor']; ?>' );
    
    new AjaxUpload( jQuery( '#bgImage' ), 
    {
        action: '<?php bloginfo('template_directory'); ?>/global/inc/admin_custom_theme_upload.php',
        name: 'bgImage',
        data: { upload: 'bg' },
        onSubmit: function( file, ext )
        {
            if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext)))
            {
                jQuery( '<div style="width:200px;"></div>' ).appendTo( 'div#uploadBg' ).text( file + '. <?php _e('Error: extensão do arquivo invalida.', 'evoeTheme'); ?>').addClass( 'error' );
                
                return false;
            }
            
            jQuery( '<img class="loading" src="<?php bloginfo('template_directory'); ?>/global/img/graph/graph_loading.gif" alt="Uploading..." />' ).appendTo( 'div#uploadBg' );
        },
        onComplete: function( file, response )
        {
            jQuery( 'img.loading' ).remove();
            
            if( response != 'Error' )
            {
                jQuery( '#uploadBg' ).hide();
                jQuery( '#restoreBg' ).show();
                jQuery( 'div.bgOpt' ).show();
                
                jQuery( '#themepreview' ).contents().find( 'body' ).css( 'backgroundImage', 'url("' + response + '")' );
            }
            else
            {
                jQuery( '<div style="width:200px;"></div>' ).appendTo( 'div#uploadBg' ).text( file + ' ' + response ).addClass( 'error' );
            }
        }
	});
    
    new AjaxUpload( jQuery( '#logoImage' ), 
    {
        action: '<?php bloginfo('template_directory'); ?>/global/inc/admin_custom_theme_upload.php',
        name: 'logoImage',
        data: { upload: 'logo' },
        onSubmit: function( file, ext )
        {
            if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext)))
            {
                jQuery( '<div style="width:200px;"></div>' ).appendTo( 'div#uploadLogo' ).text( file + '. <?php _e('Error: extensão do arquivo invalida.', 'evoeTheme'); ?>').addClass( 'error' );
                
                return false;
            }
            
            jQuery( '<img class="loading" src="<?php bloginfo('template_directory'); ?>/global/img/graph/graph_loading.gif" alt="Uploading..." />' ).appendTo( 'div#uploadLogo' );
        },
        onComplete: function( file, response )
        {
            jQuery( 'img.loading' ).remove();
            
            if( response != 'Error' )
            {
                jQuery( '#uploadLogo' ).hide();
                jQuery( '#restoreLogo' ).show();
                jQuery( 'div#logoPosition' ).show();
                
                jQuery( '#themepreview' ).contents().find( '#header .title a' ).css( 'background', 'url("' + response + '") <?php echo !empty($bg['header']['logoPosition']) ? $bg['header']['logoPosition'] : 'left center'; ?> no-repeat' );
            }
            else
            {
                jQuery( '<div style="width:200px;"></div>' ).appendTo( 'div#uploadLogo' ).text( file + ' ' + response ).addClass( 'error' );
            }
        }
	});
    
    new AjaxUpload( jQuery( '#headerImage' ), 
    {
        action: '<?php bloginfo('template_directory'); ?>/global/inc/admin_custom_theme_upload.php',
        name: 'headerImage',
        data: { upload: 'header' },
        onSubmit: function( file, ext )
        {
            if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext)))
            {
                jQuery( '<div style="width:200px;"></div>' ).appendTo( 'div#uploadHeader' ).text( file + '. <?php _e('Error: extensão do arquivo invalida.', 'evoeTheme'); ?>').addClass( 'error' );
                
                return false;
            }
            
            jQuery( '<img class="loading" src="<?php bloginfo('template_directory'); ?>/global/img/graph/graph_loading.gif" alt="Uploading..." />' ).appendTo( 'div#uploadHeader' );
        },
        onComplete: function( file, response )
        {
            jQuery( 'img.loading' ).remove();
            
            if( response != 'Error' )
            {
                jQuery( '#uploadHeader' ).hide();
                jQuery( '#restoreHeader' ).show();
                jQuery( 'div#headerOpt' ).show();
                
                jQuery( '#themepreview' ).contents().find( '#header' ).css( 'backgroundImage', 'url("' + response + '")' );
            }
            else
            {
                jQuery( '<div style="width:200px;"></div>' ).appendTo( 'div#uploadHeader' ).text( file + ' ' + response ).addClass( 'error' );
            }
        }
	});
    
    new AjaxUpload( jQuery( '#patternImage' ), 
    {
        action: '<?php bloginfo('template_directory'); ?>/global/inc/admin_custom_theme_upload.php',
        name: 'patternImage',
        data: { upload: 'pattern' },
        onSubmit: function( file, ext )
        {
            if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext)))
            {
                jQuery( '<div style="width:200px;"></div>' ).appendTo( 'div#uploadPattern' ).text( file + '. <?php _e('Error: extensão do arquivo invalida.', 'evoeTheme'); ?>').addClass( 'error' );
                
                return false;
            }
            
            jQuery( '<img class="loading" src="<?php bloginfo('template_directory'); ?>/global/img/graph/graph_loading.gif" alt="Uploading..." />' ).appendTo( 'div#uploadPattern' );
        },
        onComplete: function( file, response )
        {
            jQuery( 'img.loading' ).remove();
            
            if( response !== 'Error' )
            {
            	jQuery( '#patternValue' ).val( response );
                jQuery( '#uploadPattern' ).hide();
                jQuery( '#patternOpt' ).hide();
                jQuery( '#restorePattern' ).show();
                
                jQuery( '#themepreview' ).contents().find( 'div.bigTitle div.previewPattern, div#footer div.background' ).css({ backgroundImage: 'url("' + response + '")', backgroundRepeat: 'repeat' });
            }
            else
            {
                jQuery( '<div style="width:200px;"></div>' ).appendTo( 'div#uploadPattern' ).text( file + ' ' + response ).addClass( 'error' );
            }
        }
	});
	
    jQuery( 'div.overlay' ).mousedown(function()
    {
        jQuery( 'div.modal' ).fadeOut( 'fast', function()
        {
            jQuery( 'div.overlay' ).fadeOut( 'fast' );
            jQuery( 'div.modal p' ).remove();
        });
    });
    
    function customThemeSend( act, theme, themeD )
    {
    	if( theme || themeD )
        {
            jQuery( 'input#custom_themes' ).val( theme );
            jQuery( 'input#custom_themesD' ).val( '1' );
        }
        
		var send = jQuery.post( '<?php bloginfo('template_directory'); ?>/global/inc/admin_custom_theme.php', jQuery( 'form.custom_theme_form' ).serialize() + '&action=' + act, function( msg )
		{
            if( jQuery( 'input#custom_themesD' ).val() == 1 )
            {
                jQuery( 'input#custom_themesD' ).val( '0' );
                
                jQuery.cookie( 'modal', msg );
                
                document.location.reload();
            }
            else
            {
                jQuery( 'img.loading_' ).hide();
                jQuery( '.bt_close' ).show();
                jQuery( '<p>' + msg + '</p>' ).prependTo( 'div.modal' );
            }
            
            return true;
		});
        
        return send;
    }
    
	jQuery( 'div#skins ul.defaultThemes li a' ).click(function()
	{
    	var theme = jQuery( this ).children( 'span' ).attr( 'class' );
        
		customThemeSend( 'save', theme, true );
		
		return false;
	});
    
	jQuery( '.bt_save' ).click(function()
	{
        jQuery( 'img.loading_' ).show();
        
    	jQuery( 'div.overlay' ).fadeIn( 'fast', function()
        {
        	jQuery( 'div.modal' ).fadeIn( 'slow' );
        });
        
        customThemeSend( 'save', '', '' );        
        
		return false;
	});
    
    jQuery( '.bt_close' ).click(function()
    {
        jQuery( 'div.modal' ).fadeOut( 'fast', function()
        {
            jQuery( 'div.overlay' ).fadeOut( 'fast' );
            jQuery( 'div.modal p' ).remove();
            jQuery.cookie( 'modal', null );
            jQuery( 'p.modal_' ).remove();
        });
    });
    
	jQuery( '#restoreBg' ).click(function()
	{
        jQuery( 'img.loading_' ).show();
        
    	jQuery( 'div.overlay' ).fadeIn( 'fast', function()
        {
        	jQuery( 'div.modal' ).fadeIn( 'slow' );
        });
        
        if( customThemeSend( 'restoreBg', '', '' ) )
        {
            jQuery( '#restoreBg' ).hide();
            jQuery( 'div.bgOpt' ).hide();
            jQuery( '#uploadBg' ).show();
            jQuery( '#themepreview' ).contents().find( 'body' ).css( 'backgroundImage', 'none' );
        }
        
		return false;
	});
    
	jQuery( '#restorePattern' ).click(function()
	{
        jQuery( 'img.loading_' ).show();
        
    	jQuery( 'div.overlay' ).fadeIn( 'fast', function()
        {
        	jQuery( 'div.modal' ).fadeIn( 'slow' );
        });
        
        if( customThemeSend( 'restorePattern', '', '' ) )
        {
            jQuery( '#restorePattern' ).hide();
            jQuery( '#patternOpt' ).show();
            jQuery( '#uploadPattern' ).show();
            jQuery( '#themepreview' ).contents().find( 'div.bigTitle div.previewPattern, div#footer div.background' ).css( 'backgroundImage', 'none' );
		};
        
		return false;
	});
    
	jQuery( '#retoreHeader' ).click(function()
	{
        jQuery( 'img.loading_' ).show();
        
    	jQuery( 'div.overlay' ).fadeIn( 'fast', function()
        {
        	jQuery( 'div.modal' ).fadeIn( 'slow' );
        });
        
        if( customThemeSend( 'restoreHeader', '', '' ) )
        {
		    jQuery( '#restoreHeader' ).hide();
            jQuery( 'div#headerOpt' ).hide();
            jQuery( '#uploadHeader' ).show();
            jQuery( '#themepreview' ).contents().find( '#header' ).css( 'backgroundImage', 'none' );
        };
        
		return false;
	});
    
	jQuery( '#retoreLogo' ).click(function()
	{
        jQuery( 'img.loading_' ).show();
        
    	jQuery( 'div.overlay' ).fadeIn( 'fast', function()
        {
        	jQuery( 'div.modal' ).fadeIn( 'slow' );
        });
        
		if( customThemeSend( 'restoreLogo', '', '' ) )
        {
            jQuery( '#restoreLogo' ).hide();
            jQuery( 'div#logoPosition' ).hide();
            jQuery( '#uploadLogo' ).show();
            jQuery( '#themepreview' ).contents().find( '#header .title a' ).css( 'backgroundImage', 'none' );
         };
        
		return false;
	});
	
	jQuery( '.bt_restore' ).click(function()
	{
		var answer = confirm('<?php _e('Deseja restaurar?', 'evoeTheme'); ?>');
		
		if(answer)
		{
            customThemeSend( 'restore', '', true );
            
            return false;
		}
		
		else
			return false;
	});
});
