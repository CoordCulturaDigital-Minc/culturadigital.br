jQuery(document).ready(function($) {
	// hides as soon as the DOM is ready
	$( 'div.v-option-body' ).hide();
	// shows on clicking the noted link
	$( 'h3' ).click(function() {
		$(this).toggleClass("open");
		$(this).next("div").slideToggle( '1000' );
		return false;
	});
});
function toggleColorpicker (link, id, toggledir, opentext, closetext) {
	jQuery( '.colorpicker_container' ).hide();
	if (toggledir == "open") {
		jQuery( '#'+id+'_colorpicker' ).show();
		jQuery(link).replaceWith( '<a href="javascript:return false;" onclick="toggleColorpicker (this, \''+id+'\', \'close\', \''+opentext+'\', \''+closetext+'\' )">'+closetext+'</a>' );
	} else {
		jQuery(link).replaceWith( '<a href="javascript:return false;" onclick="toggleColorpicker (this, \''+id+'\', \'open\', \''+opentext+'\', \''+closetext+'\' )">'+opentext+'</a>' );
	}
}