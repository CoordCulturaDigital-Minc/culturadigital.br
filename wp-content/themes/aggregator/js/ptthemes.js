jQuery(document).ready(function() {
	
	jQuery('#master_switch').children('.neg').hide();
	jQuery('.maintable .subheading a').children('.neg').hide();
	jQuery('.feature-box .subheading a').parents('.subheading').siblings('.options-box').hide();
	
	jQuery('#master_switch').click(function() {
		jQuery(this).toggleClass('active');
		jQuery(this).children('.pos').toggle();
		jQuery(this).children('.neg').toggle();
		jQuery('.subheading a').toggleClass('active');
		jQuery('.subheading a').children('.pos').toggle();
		jQuery('.subheading a').children('.neg').toggle();
		jQuery('.options-box').toggle();
		return false;
	});
	
	jQuery('.feature-box .subheading a').click(function() {
		jQuery(this).toggleClass('active');
		jQuery(this).children('.pos').toggle();
		jQuery(this).children('.neg').toggle();
		jQuery(this).parents('.subheading').siblings('.options-box:first').toggle();
		return false;
	});
	
	

});