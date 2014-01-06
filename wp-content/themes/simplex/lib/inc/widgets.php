<?php
/**
 * Register our sidebars and widgetized areas.
 *
 * @since simpleX 2.0
 */
 
function simplex_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'simplex' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'simplex_widgets_init' );