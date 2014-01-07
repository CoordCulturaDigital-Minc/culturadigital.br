<?php

	// css
	function painter_styles()
	{
		wp_enqueue_style( 'icon', get_template_directory_uri() . '/font/awesome/awesome.css', array(), false, 'screen' );

		wp_enqueue_style( 'grid', get_template_directory_uri() . '/css/grid.css', array(), false, 'screen' );
		wp_enqueue_style( 'style', get_template_directory_uri() . '/css/style.css', array(), false, 'screen' );
		wp_enqueue_style( 'screen', get_template_directory_uri() . '/css/screen.css', array(), false, 'screen' );
		wp_enqueue_style( 'tablet', get_template_directory_uri() . '/css/tablet.css', array(), false, 'screen' );
		wp_enqueue_style( 'mobile', get_template_directory_uri() . '/css/mobile.css', array(), false, 'screen' );
		wp_enqueue_style( 'painter', get_template_directory_uri() . '/css/painter.php', array(), false, 'screen' );

		wp_enqueue_style( 'printer', get_template_directory_uri() . '/css/printer.css', array(), false, 'print' );
	}

	add_action( 'wp_enqueue_scripts', 'painter_styles' );

	// js
	function painter_scripts()
	{
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-flexslider', get_template_directory_uri() . '/js/jquery-flexslider.js', array( 'jquery' ) );
		wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', array( 'jquery' ) );

		if( is_single() or is_page() )
			wp_enqueue_script( 'comment-reply' );
	}

	add_action( 'wp_enqueue_scripts', 'painter_scripts' );

	// theme options
	$painter = get_option( 'painter' );

	// theme setup
	function painter_setup()
	{
		$content_width = 940;

		// languages
		load_theme_textdomain( 'painter', get_template_directory() . '/lang' );

		// feed
		add_theme_support( 'automatic-feed-links' );

		// menus
		add_theme_support( 'menus' );

			// define menu location
			register_nav_menu( 'header', 'Header' );
			register_nav_menu( 'footer', 'Footer' );

		// thumbnails
		add_theme_support( 'post-thumbnails' );

			// define thumbnail size
			add_image_size( 'slideshow', 940, 300, true );

		// editor style
		add_editor_style( 'css/editor.css' );
	}

	add_action( 'after_setup_theme', 'painter_setup' );

	// comments
	function painter_comments( $comment, $args, $depth )
	{
		$GLOBALS[ 'comment' ] = $comment;

		?>
			<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
				<?php echo get_avatar( $comment, '30' ); ?>
				<?php comment_reply_link( array( 'reply_text' => '<span class="fa fa-comments"></span>', 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] ) ); ?>
				<h2 class="comment_author"><?php comment_author_link(); ?></h2>
				<h5 class="comment_date comment_meta"><?php comment_time( get_option( 'date_format' ) ); ?></h5>
				<div class="comment_entry entry">
					<?php if( '0' == $comment->comment_approved ) : ?>
						<p class="comment_wait"><?php _e( 'your comment is awaiting moderation', 'painter' ); ?></p>
					<?php endif; ?>
					<?php comment_text(); ?>
				</div>
			</li>
		<?php
	}

	// sidebar structure
	function painter_sidebar( $name )
	{
		register_sidebar( array(
			'id'            => sanitize_title( $name ),
			'name'          => $name,
			'before_widget' => '<div id="%1$s" class="section %2$s">',
			'after_widget'  => '</div>',
			'before_head'   => '<div class="head">',
			'after_head'    => '</div>',
			'before_title'  => '<h1 class="section_title">',
			'after_title'   => '</h1>',
			'before_body'   => '<div class="body">',
			'after_body'    => '</div>',
			'before_foot'   => '<div class="foot">',
			'after_foot'    => '</div>',
		) );
	}

	// sidebar
	function painter_register_sidebar()
	{
		painter_sidebar( 'Index' );
		painter_sidebar( 'Single' );
		painter_sidebar( 'Page' );

		painter_sidebar( 'Home Primary' );
		painter_sidebar( 'Home Secondary' );

		painter_sidebar( 'Footer Primary' );
		painter_sidebar( 'Footer Secondary' );
		painter_sidebar( 'Footer Tertiary' );
	}

	add_action( 'widgets_init', 'painter_register_sidebar' );

	// title
	function painter_title( $title, $sep )
	{
		$title .= get_bloginfo( 'name' );

		if( is_front_page() )
			$title = "{$title} {$sep} " . get_bloginfo( 'description' );

		return $title;
	}

	add_filter( 'wp_title', 'painter_title', 10, 2 );

	// limit chars
	function painter_limit_chars( $content, $length )
	{
		$content = strip_tags( $content );

		if( !is_numeric( $length ) or empty( $length ) )
			$length = 100;

		if( strlen( $content ) > $length )
		{
			$content = substr( $content, 0, $length );
			$content = substr( $content, 0, strrpos( $content, ' ' ) ) . '...';
		}

		return $content;
	}


	// includes
	include( get_template_directory() . '/inc/painter-custom.php' );

?>