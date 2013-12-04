jQuery(function() {
	jQuery( "#headlines-loading" ).ajaxStart( function() {
		jQuery( this ).show();
	});

	jQuery( "#headlines-loading" ).ajaxStart( function() {
		jQuery( this ).hide();
	});

	jQuery( "#headlines-sortable" ).sortable({
		stop: function( event, ui ) {
			var ordered_posts = '&action=order-headlines&headline-category=' + jQuery( "#headline-category" ).val();

			jQuery( "#headlines-sortable li" ).each( function( i ) {
				jQuery( this ).find( "#order" ).val( i + 1 );

				// result action=headlines-ajax-order&headline-category=3&order1=123&order2=23&order3=98...
				ordered_posts += '&order[]=' + jQuery( this ).find( "#post-id" ).val();
			});

			jQuery.ajax({
				url: ajaxurl,
				type: "POST",
				data: ordered_posts,
				success: function( data ) {
					//alert( data );
				},
				error: function( XMLHttpRequest, textStatus, errorThrown ) {
					alert( textStatus );
				}
			})
		},
	});

	jQuery( "#headlines-sortable" ).disableSelection();
});
