<?php
/*Set language folder and load textdomain
------------------------------------------------------------ */
if (file_exists(STYLESHEETPATH . '/languages' ))
	$language_folder = (STYLESHEETPATH . '/languages' );
else
	$language_folder = (TEMPLATEPATH . '/languages' );
load_theme_textdomain( 'traction', $language_folder);


/*Redirect to theme options page on activation
------------------------------------------------------------ */
if ( is_admin() && isset($_GET['activated'] ) && $pagenow ==	"themes.php" )
	wp_redirect( 'themes.php?page=traction-admin.php' );


/*Required functions
------------------------------------------------------------ */
if (is_file(STYLESHEETPATH . '/functions/comments.php' ))
	require_once(STYLESHEETPATH . '/functions/comments.php' );
else
	require_once(TEMPLATEPATH . '/functions/comments.php' );

if (is_file(STYLESHEETPATH . '/functions/traction-extend.php' ))
	require_once(STYLESHEETPATH . '/functions/traction-extend.php' );
else
	require_once(TEMPLATEPATH . '/functions/traction-extend.php' );


/*Sidebars
------------------------------------------------------------ */
if ( function_exists( 'register_sidebar_widget' ) )
		register_sidebar(array(
				'name'=> __( 'Sidebar', 'traction' ),
				'id' => 'normal_sidebar',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h2 class="widgettitle">',
				'after_title' => '</h2>',
		));
if ( function_exists( 'register_sidebar_widget' ) )
		register_sidebar(array(
				'name'=> __( 'Footer Left', 'traction' ),
				'id' => 'footer_sidebar_3',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h2 class="widgettitle">',
				'after_title' => '</h2>',
		));
if ( function_exists( 'register_sidebar_widget' ) )
		register_sidebar(array(
				'name'=> __( 'Footer Center', 'traction' ),
				'id' => 'footer_sidebar',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h2 class="widgettitle">',
				'after_title' => '</h2>',
		));
if ( function_exists( 'register_sidebar_widget' ) )
		register_sidebar(array(
				'name'=> __( 'Footer Right', 'traction' ),
				'id' => 'footer_sidebar_2',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h2 class="widgettitle">',
				'after_title' => '</h2>',
		));


/*Custom excerpt length and more string
------------------------------------------------------------ */
function new_excerpt_length($length) { return 5; }
add_filter( 'excerpt_length', 'new_excerpt_length' );

function new_excerpt_more($more) { return ''; }
add_filter( 'excerpt_more', 'new_excerpt_more' );
// Remove auto p tags
remove_filter( 'the_excerpt', 'wpautop' );


/*Custom thumbnails
------------------------------------------------------------ */
if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'index-thumb', 175, 150, true );
	add_image_size( 'feature-small', 30, 30, true );
	add_image_size( 'feature-big', 602, 200, true );
}


/*No more tag jumping
------------------------------------------------------------ */
function remove_more_jump_link($link) {
	$offset = strpos($link, '#more-' );
	if ($offset) {
		$end = strpos($link, '"',$offset);
	}
	if ($end) {
		$link = substr_replace($link, '', $offset, $end-$offset);
	}
	return $link;
}
add_filter( 'the_content_more_link', 'remove_more_jump_link' );