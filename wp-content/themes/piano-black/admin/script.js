jQuery(function(){
function showhide(){
	if(jQuery('#use_wp_nav_menu').attr('checked')) jQuery('#old_menu_function').hide();
	else jQuery('#old_menu_function').show();
}
	jQuery('#use_wp_nav_menu').click(showhide);
	showhide();
});