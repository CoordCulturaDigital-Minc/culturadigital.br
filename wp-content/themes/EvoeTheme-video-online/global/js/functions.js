jQuery( function()
{	
    // RSS mouse hover effect
    jQuery( '.rss' ).hover(function()
    {
        jQuery( this ).stop().animate({ bottom: '0' }, 150);
    },
    function()
    {
        jQuery( this ).stop().animate({ bottom: '-15px' }, 150);
    });
    
    // remove the border-top
    jQuery( 'div.posts ul.posts > li:first-child, #footer div.middle ul li:first-child' ).addClass( 'first' );
	
	// add class odd in sidebar widgets list
	var widgetList = jQuery( 'div.sidebar div.widget div.tab > ul' );
	jQuery( '> li:nth-child(even)', widgetList ).addClass( 'odd' );
	jQuery( '#header div.nav ul.nav li ul > li:nth-child(even), #content div.pings ul > li:nth-child(even)' ).addClass( 'odd' );
    
    // drop-shadow h2
    var header = jQuery( '#header' );
    var title = jQuery( '.middle h2 a', header ).text();
    jQuery( '<span unselectable="on" class="shadow">' + title + '</span>' ).prependTo( jQuery( 'div.middle h2', header ) );
	jQuery( 'div.middle h2 span', header ).css( '-moz-user-select', 'none' );
		
	jQuery( '.widget_calendar table' ).attr( 'cellspacing', '0' );
	
	// Reset Default
	jQuery( '.disabled' ).resetDefaultValue();
	
	// Animate scroll footer
	jQuery( '#footer a.backTop' ).click( function()
	{
		jQuery( 'html, body' ).animate({scrollTop: 0}, 1500, 'bounceout');
		
		return false;
	});
	
	// Menu dropDown
	jQuery( '#header div.nav ul.nav > li' ).hover(function()
	{
		jQuery( this ).children( 'ul' ).stop(true, true).slideDown( 'fast' );
	},function()
	{
		jQuery( this ).children( 'ul' ).stop(true, true).slideUp( 'fast' );
	});
	
	// Lava lump
	jQuery(function() { jQuery( '.nav' ).lavaLamp({ fx: 'backout', speed: 400}) });
	
	// Sidebar tab
	jQuery( 'div.widget' ).each(function()
	{
		var $widget = jQuery.cookie( jQuery( this ).attr( 'id' ) );
		
		if( $widget )
		{
			if( $widget != 1 )
			{
				jQuery( this ).toggleClass( 'tab' );
				jQuery( this ).find( 'div.tabs' ).stop(true, true).animate({ height: 'toggle' }, 1000, 'bounceout');
			}
		}
	});
	jQuery( 'h4 span.tab' ).click(function()
	{
		var $widget = jQuery( this ).parents( 'div.widget' ).attr( 'id' );
		
		jQuery( this ).parents( 'div.widget' ).toggleClass( 'tab' );
		
		if( jQuery( this ).parents( 'div.widget' ).children( 'div.tabs' ).is( ':visible' ) )
		{
			jQuery( this ).parents( 'div.widget' ).children( 'div.tabs' ).stop(true, true).animate({ height: 'toggle' }, 1000, 'bounceout');
			
			jQuery.cookie( $widget, 0 );
		}
		else
		{
			jQuery( this ).parents( 'div.widget' ).children( 'div.tabs' ).stop(true, true).animate({ height: 'toggle' }, 1000, 'bounceout');
			
			jQuery.cookie( $widget, 1 );
		}
	});
});