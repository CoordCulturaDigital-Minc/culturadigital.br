var bgColor;
var titleColor;
var headerColor;
var boxColor;
var textColor;

function bg_Color( color )
{
	jQuery( '#bgColor' ).val( color );
	jQuery( '#themepreview' ).contents().find( '#general' ).css( 'backgroundColor', color );
	bgColor.setColor( color );
}

function title_Color(color)
{
	jQuery( '#titleColorInput' ).val( color );
	jQuery( '#themepreview' ).contents().find( 'div#header h2 a' ).css( 'color', color );
	titleColor.setColor(color);
}

function header_Color(color)
{
	jQuery( '#headerColorInput' ).val( color );
	jQuery( '#themepreview' ).contents().find( 'div#header' ).css( 'backgroundColor', color );
	headerColor.setColor(color);
}

function box_Color(color)
{
	jQuery( '#boxColorInput' ).val( color );
	jQuery( '#themepreview' ).contents().find( 'div.bigTitle' ).css( 'backgroundColor', color );
	boxColor.setColor(color);
}

function text_Color(color)
{
	jQuery( '#textColorInput' ).val( color );
	jQuery( '#themepreview' ).contents().find( 'div.bigTitle h4, div.bigTitle h2' ).css( 'color', color );
	textColor.setColor( color );
}

function link_Color(color)
{
	jQuery( 'div#linkColor input[name=linkColor]' ).val( color );
	jQuery( '#themepreview' ).contents().find( '#content a' ).css( 'color', color );
	linkColor.setColor( color );
}

jQuery(function()
{
	bgColor = jQuery.farbtastic( '#colorPickerDiv', function( color ){ bg_Color( color ); } );
	titleColor = jQuery.farbtastic( '#colorPickerDiv2', function( color ){ title_Color( color ) } );
	headerColor = jQuery.farbtastic( '#colorPickerDiv3', function( color ){ header_Color( color ) } );
	boxColor = jQuery.farbtastic( '#colorPickerDiv4', function( color ){ box_Color( color ) } );
	textColor = jQuery.farbtastic( '#colorPickerDiv5', function( color ){ text_Color( color ) } );
	linkColor = jQuery.farbtastic( '#colorPickerDiv6', function( color ){ link_Color( color ) } );
	
	jQuery( '.colorPicker' ).hide();
	
	jQuery( '.bt_bgColor' ).click(function()
	{
		jQuery( '.colorPicker1' ).show();
	});
	
	jQuery( '.bt_titleColor' ).click(function()
	{
		jQuery( '.colorPicker2' ).show();
	});
	
	jQuery( '.bt_headerColor' ).click(function()
	{
		jQuery( '.colorPicker3' ).show();
	});
	
	jQuery( '.bt_boxColor' ).click(function()
	{
		jQuery( '.colorPicker4' ).show();
	});
	
	jQuery( '.bt_textColor' ).click(function()
	{
		jQuery( '.colorPicker5' ).show();
	});
	
	jQuery( '.bt_linkColor' ).click(function()
	{
		jQuery( '.colorPicker6' ).show();
	});
	
	jQuery( '.colorPicker #bgColor' ).bind( 'change keydown', function()
	{
		bg_Color( jQuery( this ).val() );
	});
	
	jQuery( '.colorPicker #titleColorInput' ).bind( 'change keydown', function()
	{
		title_Color( jQuery( this ).val() );
	});
	
	jQuery( '.colorPicker #headerColorInput' ).bind( 'change keydown', function()
	{
		header_Color( jQuery( this ).val() );
	});
	
	jQuery( '.colorPicker #boxColorInput' ).bind( 'change keydown', function()
	{
		box_Color( jQuery( this ).val() );
	});
	
	jQuery( '.colorPicker #textColorInput' ).bind( 'change keydown', function()
	{
		text_Color( jQuery( this ).val() );
	});
	
	jQuery( '.colorPicker #linkColor' ).bind( 'change keydown', function()
	{
		link_Color( jQuery( this ).val() );
	});
	
	jQuery( document ).mousedown(function(e)
	{
		var colorPicker = jQuery( '.colorPicker input' );
		var etarget = jQuery( e.target ).attr( 'id' );
		
		colorPicker.each(function()
		{
			switch ( etarget )
			{
				case jQuery( colorPicker[0] ).attr( 'id' ):
				case jQuery( colorPicker[1] ).attr( 'id' ):
				case jQuery( colorPicker[2] ).attr( 'id' ):
				case jQuery( colorPicker[3] ).attr( 'id' ):
				case jQuery( colorPicker[4] ).attr( 'id' ):
				case jQuery( colorPicker[5] ).attr( 'id' ):
					break;
					
				default:
					jQuery( '.colorPicker' ).hide();
					break;
			}
		});
	});
	
	jQuery( 'div.bgColor li label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( '#general' ).css( 'backgroundColor', color );
		jQuery( 'div.bgColor input[name=bgColor]' ).val( color );
		bgColor.setColor( color );
	});
	
	jQuery( 'div.titleColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( 'div#header div.middle h2 a' ).css( 'color', color );
		jQuery( 'div.titleColor input' ).val( color );
		titleColor.setColor( color );
	});
	
	jQuery( 'div.headerColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		if( color == 'X' )
		{
			color = '#transparent';
		}
		
		jQuery( '#themepreview' ).contents().find( 'div#header' ).css( 'backgroundColor', color );
		jQuery( 'div.headerColor input' ).val( color );
		headerColor.setColor( color );
	});
	
	jQuery( 'div#boxColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( 'div.bigTitle' ).css( 'backgroundColor', color );
		jQuery( 'div#boxColor input[name=boxColor]' ).val( color );
		boxColor.setColor( color );
	});

	jQuery( 'div#textColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( 'div.bigTitle h4, div.bigTitle h2' ).css( 'color', color );
		jQuery( 'div#textColor input[name=textColor]' ).val( color );
		textColor.setColor( color );
	});
	
	jQuery( 'div#linkColor label' ).click(function()
	{
		var color = jQuery( this ).text();
		
		jQuery( '#themepreview' ).contents().find( '#content a' ).css( 'color', color );
		jQuery( 'div#linkColor input[name=linkColor]' ).val( color );
		linkColor.setColor( color );
	});
});