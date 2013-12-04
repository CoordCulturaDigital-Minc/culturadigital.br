<?php 

	// Slider options
	delete_option('graphene_slider_cat', $slider_cat);
	delete_option('graphene_slider_disable', $slider_disable);

	// AdSense options
	delete_option('graphene_show_adsense', $show_adsense);
	delete_option('graphene_adsense_code', $adsense_code);
	delete_option('graphene_adsense_show_frontpage', $adsense_show_frontpage);
	
	// AddThis options
	delete_option('graphene_show_addthis', $show_addthis);
	delete_option('graphene_addthis_code', $addthis_code);
	
	// Google Analytics options
	delete_option('graphene_show_ga', $show_ga);
	delete_option('graphene_ga_code', $ga_code);
	
	// Widget area options
	delete_option('graphene_alt_home_sidebar', $alt_home_sidebar);
	delete_option('graphene_alt_home_footerwidget', $alt_home_footerwidget);
	
	// Footer options
	delete_option('graphene_show_cc', $show_cc);
	delete_option('graphene_copy_text', $copy_text);
	
	// Header options
	delete_option('graphene_light_header');			
	
	// Posts Display options
	delete_option('graphene_hide_post_author');
	delete_option('graphene_hide_post_date');
	delete_option('graphene_hide_post_commentcount');
	delete_option('graphene_hide_post_cat');
	delete_option('graphene_hide_post_tags');
	delete_option('graphene_show_post_avatar');
	
	// Text style options
	delete_option('graphene_header_title_font_type');
	delete_option('graphene_header_title_font_size');
	delete_option('graphene_header_title_font_lineheight');
	delete_option('graphene_header_title_font_weight');
	delete_option('graphene_header_title_font_style');
	
	delete_option('graphene_header_desc_font_type');
	delete_option('graphene_header_desc_font_size');
	delete_option('graphene_header_desc_font_lineheight');
	delete_option('graphene_header_desc_font_weight');
	delete_option('graphene_header_desc_font_style');
	
	delete_option('graphene_content_font_type');
	delete_option('graphene_content_font_size');
	delete_option('graphene_content_font_lineheight');
	delete_option('graphene_content_font_colour');
	
	// Bottom widget display options
	delete_option('graphene_footerwidget_column');
	delete_option('graphene_alt_footerwidget_column');
	
	// Nav menu display options
	delete_option('graphene_navmenu_child_width');
	
	delete_option('graphene');
	switch_theme('default', 'default');
	wp_cache_flush();
	
	wp_redirect('themes.php?activated=true');
	return;
?>