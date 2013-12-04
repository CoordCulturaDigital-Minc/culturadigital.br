<?php
/*
This file is part of Codium. Hack and customize by henri labarre and based on the marvelous sandbox theme

Codium is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

*/


// This theme allows users to set a custom background
	add_custom_background();

// Post thumbnails support for post
	if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
	add_theme_support( 'post-thumbnails', array( 'post' ) ); // Add it for posts
	set_post_thumbnail_size( 220, 165, true ); // Normal post thumbnails
	}

// Set the content width based on the theme's design and stylesheet.
	if ( ! isset( $content_width ) )
	$content_width = 620;
	
// Make theme available for translation
// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'codium', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );
	

// Generates semantic classes for BODY element
function codium_body_class( $print = true ) {
	global $wp_query, $current_user;

	// It's surely a WordPress blog, right?
	$c = array('wordpress');

	// Applies the time- and date-based classes (below) to BODY element
	codium_date_classes( time(), $c );

	// Generic semantic classes for what type of content is displayed
	is_front_page()  ? $c[] = 'home'       : null; // For the front page, if set
	is_home()        ? $c[] = 'blog'       : null; // For the blog posts page, if set
	is_archive()     ? $c[] = 'archive'    : null;
	is_date()        ? $c[] = 'date'       : null;
	is_search()      ? $c[] = 'search'     : null;
	is_paged()       ? $c[] = 'paged'      : null;
	is_attachment()  ? $c[] = 'attachment' : null;
	is_404()         ? $c[] = 'four04'     : null; // CSS does not allow a digit as first character
	is_tax() 		 ? $c[] = 'taxonomy'   : null;
	is_sticky() 	 ? $c[] = 'sticky'     : null;

	// Special classes for BODY element when a single post
	if ( is_single() ) {
		$postID = $wp_query->post->ID;
		the_post();

		// Adds 'single' class and class with the post ID
		$c[] = 'single postid-' . $postID;

		// Adds classes for the month, day, and hour when the post was published
		if ( isset( $wp_query->post->post_date ) )
			codium_date_classes( mysql2date( 'U', $wp_query->post->post_date ), $c, 's-' );

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$c[] = 's-category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag )
				$c[] = 's-tag-' . $tag->slug;

		// Adds MIME-specific classes for attachments
		if ( is_attachment() ) {
			$mime_type = get_post_mime_type();
			$mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
				$c[] = 'attachmentid-' . $postID . ' attachment-' . str_replace( $mime_prefix, "", "$mime_type" );
		}

		// Adds author class for the post author
		$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta('login')));
		rewind_posts();
	}

	// Author name classes for BODY on author archives
	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}
	
	// Category name classes for BODY on category archvies
	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->slug;
	}

	// Tag name classes for BODY on tag archives
	elseif ( is_tag() ) {
		$tags = $wp_query->get_queried_object();
		$c[] = 'tag';
		$c[] = 'tag-' . $tags->slug;
	}

	// Page author for BODY on 'pages'
	elseif ( is_page() ) {
		$pageID = $wp_query->post->ID;
		$page_children = wp_list_pages("child_of=$pageID&echo=0");
		the_post();
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author()));
		// Checks to see if the page has children and/or is a child page; props to Adam
		if ( $page_children )
			$c[] = 'page-parent';
		if ( $wp_query->post->post_parent )
			$c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
		if ( is_page_template() ) // Hat tip to Ian, themeshaper.com
			$c[] = 'page-template page-template-' . str_replace( '.php', '-php', get_post_meta( $pageID, '_wp_page_template', true ) );
		rewind_posts();
	}

	// Search classes for results or no results
	elseif ( is_search() ) {
		the_post();
		if ( have_posts() ) {
			$c[] = 'search-results';
		} else {
			$c[] = 'search-no-results';
		}
		rewind_posts();
	}

	// For when a visitor is logged in while browsing
	if ( $current_user->ID )
		$c[] = 'loggedin';

	// Paged classes; for 'page X' classes of index, single, etc.
	if ( ( ( $page = $wp_query->get('paged') ) || ( $page = $wp_query->get('page') ) ) && $page > 1 ) {
		// Thanks to Prentiss Riddle, twitter.com/pzriddle, for the security fix below.
		$page = intval($page); // Ensures that an integer (not some dangerous script) is passed for the variable
		$c[] = 'paged-' . $page;
		if ( is_single() ) {
			$c[] = 'single-paged-' . $page;
		} elseif ( is_page() ) {
			$c[] = 'page-paged-' . $page;
		} elseif ( is_category() ) {
			$c[] = 'category-paged-' . $page;
		} elseif ( is_tag() ) {
			$c[] = 'tag-paged-' . $page;
		} elseif ( is_date() ) {
			$c[] = 'date-paged-' . $page;
		} elseif ( is_author() ) {
			$c[] = 'author-paged-' . $page;
		} elseif ( is_search() ) {
			$c[] = 'search-paged-' . $page;
		}
	}

	// Separates classes with a single space, collates classes for BODY
	$c = join( ' ', apply_filters( 'body_class',  $c ) ); // Available filter: body_class

	// And tada!
	return $print ? print($c) : $c;
}

// Generates semantic classes for each post DIV element
function codium_post_class( $print = true ) {
	global $post, $codium_post_alt;

	// hentry for hAtom compliace, gets 'alt' for every other post DIV, describes the post type and p[n]
	$c = array( 'hentry', "p$codium_post_alt", $post->post_type, $post->post_status );

	// Author for the post queried
	$c[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author()));

	//If post is a sticky post
	if (is_sticky())
		$c[] = 'sticky';

	// Category for the post queried
	foreach ( (array) get_the_category() as $cat )
		$c[] = 'category-' . $cat->slug;

	// Tags for the post queried; if not tagged, use .untagged
	if ( get_the_tags() == null ) {
		$c[] = 'untagged';
	} else {
		foreach ( (array) get_the_tags() as $tag )
			$c[] = 'tag-' . $tag->slug;
	}

	// For password-protected posts
	if ( $post->post_password )
		$c[] = 'protected';

	// Applies the time- and date-based classes (below) to post DIV
	codium_date_classes( mysql2date( 'U', $post->post_date ), $c );

	// If it's the other to the every, then add 'alt' class
	if ( ++$codium_post_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for post DIV
	$c = join( ' ', apply_filters( 'post_class', $c ) ); // Available filter: post_class

	// And tada!
	return $print ? print($c) : $c;
}

// Define the num val for 'alt' classes (in post DIV and comment LI)
$codium_post_alt = 1;
//$codium_comment_alt = 1;

// Generates semantic classes for each comment LI element
function codium_comment_class( $print = true ) {
	global $comment, $post, $codium_comment_alt;

	// Collects the comment type (comment, trackback),
	$c = array( get_comment_type() );

	// Counts trackbacks (t[n]) or comments (c[n])
	if ( $comment->comment_type == 'comment' ) {
		$c[] = "c$codium_comment_alt";
	} else {
		$c[] = "t$codium_comment_alt";
	}

	// If the comment author has an id (registered), then print the log in name
	if ( $comment->user_id > 0 ) {
		$user = get_userdata($comment->user_id);
		// For all registered users, 'byuser'; to specificy the registered user, 'commentauthor+[log in name]'
		$c[] = 'byuser comment-author-' . sanitize_title_with_dashes(strtolower( $user->user_login ));
		// For comment authors who are the author of the post
		if ( $comment->user_id === $post->post_author )
			$c[] = 'bypostauthor';
	}

	// If it's the other to the every, then add 'alt' class; collects time- and date-based classes
	codium_date_classes( mysql2date( 'U', $comment->comment_date ), $c, 'c-' );
	if ( ++$codium_comment_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for comment LI
	$c = join( ' ', apply_filters( 'comment_class', $c ) ); // Available filter: comment_class
	
	// Tada again!
	return $print ? print($c) : $c;
}

// count comment
function codium_comment_count( $print = true ) {
	global $comment, $post, $codium_comment_alt;

	// Counts trackbacks and comments
	if ( $comment->comment_type == 'comment' ) {
		$count[] = "$codium_comment_alt";
	} else {
		$count[] = "$codium_comment_alt";
	}

	$count = join( ' ', $count ); // Available filter: comment_class

	// Tada again!
	echo $count;
	//return $print ? print($count) : $count;
}


// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function codium_date_classes( $t, &$c, $p = '' ) {
	$t = $t + ( get_option('gmt_offset') * 3600 );
	$c[] = $p . 'y' . gmdate( 'Y', $t ); // Year
	$c[] = $p . 'm' . gmdate( 'm', $t ); // Month
	$c[] = $p . 'd' . gmdate( 'd', $t ); // Day
	$c[] = $p . 'h' . gmdate( 'H', $t ); // Hour
}

// For category lists on category archives: Returns other categories except the current one (redundant)
function codium_cats_meow($glue) {
	$current_cat = single_cat_title( '', false );
	$separator = "\n";
	$cats = explode( $separator, get_the_category_list($separator) );
	foreach ( $cats as $i => $str ) {
		if ( strstr( $str, ">$current_cat<" ) ) {
			unset($cats[$i]);
			break;
		}
	}
	if ( empty($cats) )
		return false;

	return trim(join( $glue, $cats ));
}

// For tag lists on tag archives: Returns other tags except the current one (redundant)
function codium_tag_ur_it($glue) {
	$current_tag = single_tag_title( '', '',  false );
	$separator = "\n";
	$tags = explode( $separator, get_the_tag_list( "", "$separator", "" ) );
	foreach ( $tags as $i => $str ) {
		if ( strstr( $str, ">$current_tag<" ) ) {
			unset($tags[$i]);
			break;
		}
	}
	if ( empty($tags) )
		return false;

	return trim(join( $glue, $tags ));
}

// Produces an avatar image with the hCard-compliant photo class
function codium_commenter_link() {
	$commenter = get_comment_author_link();
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
	}
	$avatar_email = get_comment_author_email();
	$avatar_size = apply_filters( 'avatar_size', '60' ); // Available filter: avatar_size
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, $avatar_size ) );
	echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
}

if ( ! function_exists( 'codium_posted_on' ) ) :
// data before post
function codium_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s.', 'codium' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'codium' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'codium_posted_in' ) ) :
// data after post
function codium_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'codium' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'codium' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'codium' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;


// Widgets plugin: intializes the plugin after the widgets above have passed snuff
function codium_widgets_init() {

		register_sidebar(array(
		'name' => 'SidebarTop',
		'description' => 'Top sidebar',
		'before_widget'  =>   "\n\t\t\t" . '<li id="%1$s" class="widget %2$s"><div class="widgetblock">',
		'after_widget'   =>   "\n\t\t\t</div></li>\n",
		'before_title'   =>   "\n\t\t\t\t". '<div class="widgettitleb"><h3 class="widgettitle">',
		'after_title'    =>   "</h3></div>\n" .''
		));
		
		register_sidebar(array(
		'name' => 'SidebarBottom',
		'description' => 'Bottom sidebar',
		'before_widget'  =>   "\n\t\t\t" . '<li id="%1$s" class="widget %2$s"><div class="widgetblock">',
		'after_widget'   =>   "\n\t\t\t</div></li>\n",
		'before_title'   =>   "\n\t\t\t\t". '<div class="widgettitleb"><h3 class="widgettitle">',
		'after_title'    =>   "</h3></div>\n" .''
		));

	}



// Changes default [...] in excerpt to a real link
function new_excerpt_more($more) {
       global $post;
       $readmore = __(' read more <span class="meta-nav">&raquo;</span>', 'codium' );
	return ' <a href="'. get_permalink($post->ID) . '">' . $readmore . '</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');


// Runs our code at the end to check that everything needed has loaded
add_action( 'init', 'codium_widgets_init' );


// Adds filters for the description/meta content in archives.php
add_filter( 'archive_meta', 'wptexturize' );
add_filter( 'archive_meta', 'convert_smilies' );
add_filter( 'archive_meta', 'convert_chars' );
add_filter( 'archive_meta', 'wpautop' );

// Remember: the codium is for play.

// footer link 
add_action('wp_footer', 'footer_link');

function footer_link() {	
	//$weburl = $_SERVER["SERVER_NAME"];
	//$weburlcount = strlen($weburl);
	$anchorthemeowner='<a href="http://www.code-2-reduction.fr/codium/" title="code reduction" target="blank">code reduction</a>';
  	$textfooter = __('A nice revamping of Sandbox theme for <a href="http://www.wordpress.org">Wordpress</a> by ', 'codium' );
  	$content = '<div id="footer"><div class="center"><p>' .$textfooter. $anchorthemeowner.'</p></div><div class="clear"></div></div></div>';
  	echo $content;
}

//Remove <p> in excerpt
function strip_para_tags ($content) {
if ( is_home() && ($paged < 2 )) {
  $content = str_replace( '<p>', '', $content );
  $content = str_replace( '</p>', '', $content );
  return $content;
}
} 

//Comment function
function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   
   <li id="comment-<?php comment_ID() ?>" class="<?php codium_comment_class() ?>">
      <span class="count"><?php echo codium_comment_count();?></span>
      <div class="comment-author vcard">
        <?php echo get_avatar($comment,$size='48',$default='<path_to_url>' ); ?>
		<?php printf(__('<div class="fn">%s</div> '), get_comment_author_link()) ?>
      </div>
        
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is in moderation.') ?></em>
         <br />
      <?php endif; ?>

      <div class="comment-meta"><?php printf(__('%1$s - %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Permalink to this comment">Permalink</a>', 'codium'),
										get_comment_date(),
										get_comment_time(),
										'#comment-' . get_comment_ID() );
										edit_comment_link(__('Edit', 'codium'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
	  <div class="clear"></div>	
			
      <?php comment_text() ?>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
<?php
        }
        
//custom menu support
add_action( 'init', 'register_my_menu' );

function register_my_menu() {
	register_nav_menu( 'primary-menu', __( 'Primary Menu' ) );
}        


?>