if (window.frameElement) {
	document.getElementsByTagName('html')[0].id = 'iframely';
	jQuery(function($){
		// Concentrate only on the content of the page
		$('#wpwrap').html($('#wpbody').html($('#wpbody-content').html($('#ngg_page_content'))));

		// We need to ensure that any POST operation includes the "attach_to_post"
		// parameter, to display subsequent clicks in iframely.
		$('form').each(function(){
			$(this).append("<input type='hidden' name='attach_to_post' value='1'/>");
		});
		
		var parent = window.parent;
		
		if (parent == null || typeof(parent.adjust_height_for_frame) == "undefined") {
			if (window != null && typeof(window.adjust_height_for_frame) != "undefined") {
				parent = window;
			}
		}
		
		if (typeof(parent.adjust_height_for_frame) != "undefined") {
			// Adjust the height of the frame
			parent.adjust_height_for_frame(window.frameElement, function(){
				$('#iframely').css({
					position: 'static',
					visibility: 'visible'
				}).animate({
					opacity: 1.0
				});
			});
		}
	});
}
