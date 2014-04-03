<?php
if ( ! function_exists( 'graphene_get_header_image' ) ) :
/**
 * This function retrieves the header image for the theme
*/
function graphene_get_header_image( $post_id = NULL ){
	global $graphene_settings, $post;
	$header_img = '';
	
	if ( ! $post_id ) $post_id = $post->ID;
	if ( ! $post_id ) $header_img = get_header_image();
	
	if ( ! $header_img && is_singular() && has_post_thumbnail( $post_id ) ) {
		$image_id = get_post_thumbnail_id( $post_id );
		$image_meta = wp_get_attachment_metadata( $image_id );
		
		if ( $image_meta && $image_meta['width'] >= HEADER_IMAGE_WIDTH && ! $graphene_settings['featured_img_header'] ) {
			$image = wp_get_attachment_image_src( $image_id, 'post-thumbnail' );
			$header_img = $image[0];
		}
	}
	
	if ( ! $header_img ) $header_img = get_header_image();
	
	return apply_filters( 'graphene_header_image', $header_img, $post_id );
}
endif;


/**
 * Get the attachment ID from the source URL
 *
 * @package Graphene
 * @since 1.9
 */
function graphene_get_attachment_id_from_src( $image_src ) {

	global $wpdb;
	$query = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid='%s'", $image_src );
	$id = $wpdb->get_var($query);
	return $id;
	
}


/**
 * Get the alt text for the header image
 *
 * @package Graphene
 * @since 1.9
 */
function graphene_get_header_image_alt( $image_src ){
	
	$image_id = graphene_get_attachment_id_from_src( $image_src );
	if ( ! $image_id ) return;
	
	$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	return $alt;
}


/**
 * This functions adds additional classes to the <body> element. The additional classes
 * are added by filtering the WordPress body_class() function.
*/
function graphene_body_class( $classes ){
    
	global $graphene_settings;
	
	if ( $graphene_settings['slider_full_width'] ) $classes[] = 'full-width-slider';
	if ( $graphene_settings['slider_position'] ) $classes[] = 'bottom-slider';
	
    $column_mode = graphene_column_mode();
    $classes[] = $column_mode;
    // for easier CSS
    if ( strpos( $column_mode, 'two_col' ) === 0 ){
        $classes[] = 'two-columns';
    } else if ( strpos( $column_mode, 'three_col' ) === 0 ){
        $classes[] = 'three-columns';
    }
	
	if ( has_nav_menu( 'secondary-menu' ) ) $classes[] = 'have-secondary-menu';
    
    // Prints the body class
    return $classes;
}

add_filter( 'body_class', 'graphene_body_class' );


/**
 * Add Social Media icons in top bar
*/
function graphene_top_bar_social(){
    global $graphene_settings;

    /* Loop through the registered custom social modia */
    $social_profiles = $graphene_settings['social_profiles'];
	if ( in_array( false, $social_profiles) ) return;
	
	$count = 1;
    foreach ( $social_profiles as $social_key => $social_profile ) : 
        if ( ! empty( $social_profile['url'] ) || $social_profile['type'] == 'rss' ) : 
            $title = graphene_determine_social_medium_title( $social_profile );
            $class = 'mysocial social-' . $social_profile['type'];
			$id = 'social-id-' . $count;
            
			$extra = $graphene_settings['social_media_new_window'] ?  ' target="_blank"' : '';
			$extra = apply_filters( 'graphene_social_media_attr', $extra, $title, $class, $id );
			
            $url = ( $social_profile['type'] == 'rss' && empty( $social_profile['url'] ) ) ? get_bloginfo('rss2_url') : $social_profile['url'];
            if ( $social_profile['type'] == 'custom' ) {
				$icon_url = $social_profile['icon_url'];
                $class = 'mysocial social-custom';
            } else {
				$icon_url = GRAPHENE_ROOTURI . '/images/social/' . $social_profile['type'] . '.png';
			}
		?>
            <a href="<?php echo esc_url( $url ); ?>" title="<?php echo esc_attr( $title ); ?>" id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>"<?php echo $extra; ?>>
            	<img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $social_profile['name'] ); ?>" title="<?php echo esc_attr( $title ); ?>" />
            </a>
    	<?php endif; $count++;
    endforeach;
}
add_action( 'graphene_social_profiles', 'graphene_top_bar_social' );

/**

 * Determine the title for the social medium.
 * @param array $social_medium
 * @return string 
 */
function graphene_determine_social_medium_title( $social_medium ) {
    if ( isset( $social_medium['title'] ) && ! empty( $social_medium['title']) ) {
        return esc_attr( $social_medium['title'] );
    } else {
        /* translators: %1$s is the website's name, %2$s is the social media name */
        return sprintf( esc_attr__( 'Visit %1$s\'s %2$s page', 'graphene' ), get_bloginfo( 'name' ), ucfirst( $social_profile['type'] ) );
    }
}


/**
 * Returns the width in pixels for the specified grid number
 *
 * @param int $mod Optional Width in pixels to add/subtract from the calculated grid width
 * @param int $grid_one Grid number for 1 column layout
 * @param int $grid_two Grid number for 2 column layout
 * @param int $grid_three Grid number for 3 column layout
 * @return int Grid width in pixels
 *
 * @package Graphene
 * @since 1.6
*/
function graphene_grid_width( $mod = '', $grid_one = 1, $grid_two = '', $grid_three = '', $post_id = NULL ){
	$grid_two = ( ! $grid_two ) ? $grid_one : $grid_two ;
	$grid_three = ( ! $grid_three ) ? $grid_one : $grid_three ;
	
	global $graphene_settings;
	$grid_width = $graphene_settings['grid_width'];
	$gutter_width = $graphene_settings['gutter_width'] * 2;
	$column_mode = graphene_column_mode( $post_id );
	
	$width = $grid_width;
	
	if ( strpos( $column_mode, 'one_col' ) === 0 )
		$width = $grid_width * $grid_one + $gutter_width * ($grid_one - 1);
	if ( strpos( $column_mode, 'two_col' ) === 0 )
		$width = $grid_width * $grid_two + $gutter_width * ($grid_two - 1);
	if ( strpos( $column_mode, 'three_col' ) === 0 )
		$width = $grid_width * $grid_three + $gutter_width * ($grid_three - 1);
		
	if ( $mod )
		$width += $mod;
		
	if ( $width < 0 )
		$width = 0;
		
	return apply_filters( 'graphene_grid_width', $width, $mod, $grid_one, $grid_two, $grid_three );
}


/**
 * Returns the 960 grid system classes.
 *
 * @param string $classes Optional additional classes
 * @param int $grid_one Grid number for 1 column layout
 * @param int $grid_two Grid number for 2 column layout
 * @param int $grid_three Grid number for 3 column layout
 * @param bool $alpha Switch for the alpha class
 * @param bool $omega Switch for the omega class
 * @return array Grid system classes
 *
 * @package Graphene
 * @since 1.6
*/
function graphene_get_grid( $classes = '', $grid_one = 1, $grid_two = '', $grid_three = '', $alpha = false, $omega = false ){
	
	$grid_two = ( ! $grid_two ) ? $grid_one : $grid_two ;
	$grid_three = ( ! $grid_three ) ? $grid_one : $grid_three ;
	
	$column_mode = graphene_column_mode();
	
	$grid = array();
	
	if ( $classes )
		$grid = array_merge( $grid, explode( ' ', trim( $classes ) ) );
	
	if ( strpos( $column_mode, 'one_col' ) === 0 )
		$grid[] = 'grid_' . $grid_one;
	if ( strpos( $column_mode, 'two_col' ) === 0 )
		$grid[] = 'grid_' . $grid_two;
	if ( strpos( $column_mode, 'three_col' ) === 0 )
		$grid[] = 'grid_' . $grid_three;
	
	if ( $alpha ){
		if ( is_rtl() )
			$grid[] = 'alpha-rtl';
		else
			$grid[] = 'alpha';
	}
	if ( $omega ){
		if ( is_rtl() )		
			$grid[] = 'omega-rtl';
		else
			$grid[] = 'omega';
	}
		
	return apply_filters( 'graphene_grid', $grid, $classes, $grid_one, $grid_two, $grid_three, $alpha, $omega );
}

/**
 * Prints the 960 grid system classes
 *
 * @param string $classes Optional additional classes
 * @param int $grid_one grid number for 1 column layout
 * @param int $grid_two grid number for 2 column layout
 * @param int $grid_three grid number for 3 column layout
 * @param bool $alpha switch for the alpha class
 * @param bool $omega switch for the omega class
 *
 * @package Graphene
 * @since 1.6
*/
function graphene_grid( $classes = '', $grid_one = 1, $grid_two = '', $grid_three = '', $alpha = false, $omega = false ){
	// Separates classes with a single space	
	echo 'class="' . implode( ' ', graphene_get_grid( $classes, $grid_one, $grid_two, $grid_three, $alpha, $omega ) ) . '"';
}

if ( ! function_exists( 'graphene_get_avatar_uri' ) ) :
/**
 * Retrieve the avatar URL for a user who provided a user ID or email address.
 *
 * @uses WordPress' get_avatar() function, except that it
 * returns the URL to the gravatar image only, without the <img> tag.
 *
 * @param int|string|object $id_or_email A user ID,  email address, or comment object
 * @param int $size Size of the avatar image
 * @param string $default URL to a default image to use if no avatar is available
 * @param string $alt Alternate text to use in image tag. Defaults to blank
 * @return string URL for the user's avatar
 *
 * @package Graphene
 * @since 1.6
*/
function graphene_get_avatar_uri( $id_or_email, $size = '96', $default = '', $alt = false ) {
	
	// Silently fails if < PHP 5
	if ( ! function_exists( 'simplexml_load_string' ) ) return;
	
	$avatar = get_avatar( $id_or_email, $size, $default, $alt );
	if ( ! $avatar ) return false;
	
	$avatar_xml = simplexml_load_string( $avatar );
	$attr = $avatar_xml->attributes();
	$src = $attr['src'];

	return apply_filters( 'graphene_get_avatar_url', $src, $id_or_email, $size, $default, $alt );
}
endif;


function graphene_feed_link($output, $feed) {
    global $graphene_settings;
    
    if ( ( $feed == 'rss2' || $feed == 'rss' ) 
            && $graphene_settings['use_custom_rss_feed'] && ! empty( $graphene_settings['custom_rss_feed_url'] ) ) {
        $output = $graphene_settings['custom_rss_feed_url'];    
    }
    return $output;
}
add_filter( 'feed_link', 'graphene_feed_link', 1, 2 );


/**
 * Displays a notice to logged in users if there is no widgets placed in the displayed sidebars
 */
function graphene_sidebar_notice( $sidebar_name = '' ){
	$html = '<p>';
	$html .= sprintf( __( 'You haven\'t placed any widget into this widget area. Go to %1$s and place some widgets in the widget area called %2$s.', 'graphene' ), '<em>' . __( 'WP Admin > Appearance > Widgets', 'graphene' ) . '</em>', '<strong>' . $sidebar_name . '</strong>' ) . '</p>';
	$html .= '<p>' . __( "This notice will not be displayed to your site's visitors.", 'graphene' ) . '</p>';
	echo warning_block_shortcode_handler( array(), apply_filters( 'graphene_sidebar_notice', $html, $sidebar_name ) );
}


/**
 * Apply the correct column mode for static posts page as per its page template
 *
 * @package Graphene
 * @since 1.9
 */
function graphene_posts_page_column(){
	if ( ! is_home() ) return;
	
	$home_page = get_option( 'page_for_posts' );
	if ( ! $home_page ) return;
	
	$template = get_post_meta( $home_page, '_wp_page_template', true );
	if ( ! $template || $template == 'default' ) return;
	
	global $graphene_settings;
	switch ( $template ) {
		case 'template-onecolumn.php': $graphene_settings['column_mode'] = 'one_column'; break;
		case 'template-twocolumnsleft.php': $graphene_settings['column_mode'] = 'two_col_left'; break;
		case 'template-twocolumnsright.php': $graphene_settings['column_mode'] = 'two_col_right'; break;
		case 'template-threecolumnsleft.php': $graphene_settings['column_mode'] = 'three_col_left'; break;
		case 'template-threecolumnscenter.php': $graphene_settings['column_mode'] = 'three_col_center'; break;
		case 'template-threecolumnsright.php': $graphene_settings['column_mode'] = 'three_col_right'; break;
	}
}
add_action( 'template_redirect', 'graphene_posts_page_column' );