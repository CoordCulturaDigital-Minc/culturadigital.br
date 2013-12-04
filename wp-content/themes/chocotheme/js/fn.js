jQuery(function ($) {
    $('.commentlist > li:last-child').addClass('last');
    
    $('#nav li:last-child').addClass('last');
    
    $('#nav > ul > li.has_dropdown').hover(function() {
    	$(this).addClass('hover');
    	$(this).find('.dropdown:eq(0)').show();
    }, function() {
    	$(this).removeClass('hover');
    	$(this).find('.dropdown:eq(0)').hide();
    });
    $('#nav ul li.has_dropdown ul li').hover(function() {
    	$(this).find('.dropdown:eq(0)').show();
    }, function() {
    	$(this).find('.dropdown:eq(0)').hide();
    });
    
    var logo_offset = $('#logo').offset().top;
    var last_nav_elem_offset = $('#nav > ul > li:last').offset().top;
    var nav_padding = parseInt($('#nav').css('margin-top').replace('px'));
    
    while (last_nav_elem_offset - nav_padding != logo_offset && $('#nav li').length>1) {
    	$('#nav > ul > li:last').remove();
    	last_nav_elem_offset = $('#nav > ul > li:last').offset().top
    }
    
});