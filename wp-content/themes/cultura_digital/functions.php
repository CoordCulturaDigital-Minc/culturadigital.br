<?php
    // remover widgets
    function remover_widgets() {
        unregister_sidebar_widget('Search');
        unregister_sidebar_widget('Meta'); 
	    unregister_sidebar_widget('buddypress-welcome'); 
		unregister_sidebar_widget('buddypress-whosonline'); 		
		unregister_sidebar_widget('Calendar');
    }
   
    add_action('widgets_init','remover_widgets');

	function fix_format($content)
	{
		$output = str_replace(array('[', ']'), array('<', '>'), $content);
		
		return $output;
	}

	add_filter('bp_get_activity_content', 'fix_format');

	// includes
	include_once(TEMPLATEPATH . '/inc/the_thumb.php');
	include_once(TEMPLATEPATH . '/inc/limit_chars.php');
	
	// widgets
	include_once(TEMPLATEPATH . '/inc/widget_custom_posts.php');
	include_once(TEMPLATEPATH . '/inc/widget_comentarios.php');
	include_once(TEMPLATEPATH . '/inc/widget_content.php');
	include_once(TEMPLATEPATH . '/inc/widget_custom_videos.php');
	
	// my_register_sidebar
	function my_register_sidebar($name)
	{
		register_sidebar(
			array(
				'name'					=> $name,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<h2 class="widgettitle" style="display:none;">',
				'after_title'	 => '</h2>'
			)
		);
	}
	
	// cadastrar sidebars
	if(function_exists('register_sidebar')) :
		
		my_register_sidebar('left-column');
		my_register_sidebar('bottom-column');
		my_register_sidebar('center-column');
		my_register_sidebar('right-column');
		my_register_sidebar('left-column-interna');
		my_register_sidebar('right-column-interna');
		my_register_sidebar('video-home');
		my_register_sidebar('lifestream-interna');
		my_register_sidebar('destaques-home');
		
	endif;
	
	function bp_show_register_page()
	{
		global $bp, $current_blog;
		
		require(BP_PLUGIN_DIR . '/bp-core/bp-core-signup.php');
		
		if($bp->current_component == BP_REGISTER_SLUG && $bp->current_action == '')
		{
			bp_core_signup_set_headers();
			bp_core_load_template( 'register', true );
		}
	}
	add_action('wp', 'bp_show_register_page', 2);
	
	function bp_show_activation_page()
	{
		global $bp, $current_blog;

		require(BP_PLUGIN_DIR . '/bp-core/bp-core-activation.php');
		
		if($bp->current_component == BP_ACTIVATION_SLUG && $bp->current_action == '')
		{
			bp_core_activation_set_headers();
			bp_core_load_template( 'activate', true );
		}
	}
	add_action('wp', 'bp_show_activation_page', 2);
?>
