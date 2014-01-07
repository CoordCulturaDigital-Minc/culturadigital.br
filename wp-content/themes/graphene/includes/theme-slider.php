<?php 
global $graphene_settings;
/**
 * Creates the functions that output the slider
*/
function graphene_slider(){
	global $graphene_settings, $graphene_in_slider;
	
	$graphene_in_slider = true;
	if ( $graphene_settings['slider_display_style'] == 'bgimage-excerpt' )
		graphene_set_excerpt_length( 35 );
	
	do_action( 'graphene_before_slider' ); ?>
    <?php 
		/* Generate classes for the slider wrapper */
		$class = array( 'featured_slider', 'clearfix' );
		$class[] = $graphene_settings['slider_display_style'];
		$class[] = $graphene_settings['slider_animation'];
		
		/* For backward compatibility */
		if ( $graphene_settings['slider_display_style'] == 'bgimage-excerpt' )
			$class[] = 'full-sized';
			
		$class = apply_filters( 'graphene_slider_class', $class );
		$class = implode( ' ', $class );
	?>
    <div class="<?php echo $class; ?>">
	    <?php do_action( 'graphene_before_slider_root' ); ?>
        <div id="slider_root" class="clearfix">
       		<?php do_action( 'graphene_before_slideritems' ); ?>
	        <div class="slider_items">
	    <?php        
        /* Get the posts to be displayed */
		$sliderposts = graphene_get_slider_posts();
		
        /* Display each post in the slider */
        $slidernav_html = '';
        $i = 0;
        while ( $sliderposts->have_posts() ) : $sliderposts->the_post();
			
			$style = '';
			/* Slider background image*/
			if ( $graphene_settings['slider_display_style'] == 'bgimage-excerpt' ) {
					$image = graphene_get_slider_image( get_the_ID(), 'graphene_slider', true);
					if ( $image ){
							$style .= 'style="background-image:url(';
							$style .= ( is_array( $image ) ) ? $image[0] : $image;
							$style .= ');"';
					}
			}

            
			$slider_link_url = esc_url( graphene_get_post_meta( get_the_ID(), 'slider_url' ) );
			if ( ! $slider_link_url )
				$slider_link_url = get_permalink();
            
			$slider_link_url = apply_filters( 'graphene_slider_link_url', $slider_link_url, get_the_ID() );  
			?>
            
            <div <?php graphene_grid( 'slider_post clearfix', 16, 11, 8, true, true ); ?> id="slider-post-<?php the_ID(); ?>" <?php echo $style; ?>>
                <?php do_action( 'graphene_before_sliderpost' ); ?>
                
                <?php if ( $graphene_settings['slider_display_style'] == 'bgimage-excerpt' ) : ?>
                	<a href="<?php echo $slider_link_url; ?>" class="permalink-overlay"><span><?php _e( 'View full post', 'graphene' ); ?></span></a>
                <?php endif; ?>
                
                <?php if ( $graphene_settings['slider_display_style'] == 'thumbnail-excerpt' ) : ?>
					<?php /* The slider post's featured image */ ?>
                    <?php 
					$slider_img_setting = graphene_get_post_meta( get_the_ID(), 'slider_img' );
                    if ( $slider_img_setting != 'disabled' && ! ( ( $slider_img_setting == 'global' || $slider_img_setting == '' ) && $graphene_settings['slider_img'] == 'disabled' ) ) : 
						$image = graphene_get_slider_image( get_the_ID(), apply_filters( 'graphene_slider_image_size', 'thumbnail' ) );
						if ( $image ) :
						?>
						<div class="sliderpost_featured_image">
							<a href="<?php echo $slider_link_url; ?>"><?php echo $image; ?></a>
						</div>
                    <?php endif; endif; ?>
                <?php endif; ?>
                
                <div class="slider-entry-wrap clearfix">
                	<div class="slider-content-wrap">
						<?php /* The slider post's title */ ?>
                        <h2 class="slider_post_title"><a href="<?php echo $slider_link_url; ?>"><?php the_title(); ?></a></h2>
                        
                        <?php /* The slider post's excerpt */ ?>
                        <div class="slider_post_entry clearfix">
                        	<?php 
							if ( $graphene_settings['slider_display_style'] != 'full-post' ){
								the_excerpt(); 
							?>
                            <?php if ( $graphene_settings['slider_display_style'] == 'thumbnail-excerpt' ) : ?>
                            	<a class="block-button" href="<?php echo $slider_link_url; ?>"><?php _e( 'View full post', 'graphene' ); ?></a>
                            <?php endif; ?>
                            <?php } else { the_content(); }?>
                            
                            <?php do_action( 'graphene_slider_postentry' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php	
            $slidernav_html .= '<a href="#"'. ( $i == 0 ? ' class="active"' : '' ) .'><span>'. get_the_title(). '</span></a>';
            $i++;
        endwhile;
        wp_reset_postdata();
    ?>
            </div>
        </div>
        
        <?php /* The slider navigation */ ?>
        <div class="slider_nav">
            <?php echo $slidernav_html; ?>            
            <?php do_action( 'graphene_slider_nav' ); ?>
        </div>
        
    </div>
    <?php
	do_action( 'graphene_after_slider' );
	
	graphene_reset_excerpt_length();
	$graphene_in_slider = false;
}


/**
 * Create an intermediate function that controls where the slider should be displayed
 */
if ( ! function_exists( 'graphene_display_slider' ) ) :
	function graphene_display_slider(){
		if ( is_front_page() ){
			graphene_slider();
		}
	}
endif;


/**
 * Hook the slider to the appropriate action hook 
 */
if ( ! $graphene_settings['slider_disable'] ){
	if ( ! $graphene_settings['slider_position'] ) {
		if ( $graphene_settings['slider_full_width'] )
			add_action( 'graphene_before_content-main', 'graphene_display_slider' );
		else
			add_action( 'graphene_top_content', 'graphene_display_slider' );	
	} else {
		if ( $graphene_settings['slider_full_width'] )
			add_action( 'graphene_after_content', 'graphene_display_slider' );
		else
			add_action( 'graphene_bottom_content', 'graphene_display_slider', 11 );
	}
}


if ( ! function_exists( 'graphene_get_slider_image' ) ) :
/**
 * This function determines which image to be used as the slider image based on user
 * settings, and returns the <img> tag of the the slider image.
 *
 * It requires the post's ID to be passed in as argument so that the user settings in
 * individual post / page can be retrieved.
*/
function graphene_get_slider_image( $post_id = NULL, $size = 'thumbnail', $urlonly = false, $default = '' ){
	global $graphene_settings;
	
	// Throw an error message if no post ID supplied
	if ( $post_id == NULL){
		echo '<strong>ERROR:</strong> Post ID must be passed as an input argument to call the function <code>graphene_get_slider_image()</code>.';
		return;
	}
	
	// First get the settings
	$global_setting = ( $graphene_settings['slider_img'] ) ? $graphene_settings['slider_img'] : 'featured_image';
	$local_setting = graphene_get_post_meta( $post_id, 'slider_img' );
	$local_setting = ( $local_setting ) ? $local_setting : '';
	
	// Determine which image should be displayed
	$final_setting = ( $local_setting == '' ) ? $global_setting : $local_setting;
	
	// Build the html based on the final setting
	$html = '';
	if ( $final_setting == 'disabled' ){					// image disabled
	
		return false;
		
	} elseif ( $final_setting == 'featured_image' ){		// Featured Image
	
		if ( has_post_thumbnail( $post_id ) ) :
			if ( $urlonly )
				$html = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
			else
				$html .= get_the_post_thumbnail( $post_id, $size );
		endif;
		
	} elseif ( $final_setting == 'post_image' ){			// First image in post
		
			$html = graphene_get_post_image( $post_id, $size, '', $urlonly);
		
	} elseif ( $final_setting == 'custom_url' ){			// Custom URL
		
		if ( ! $urlonly ){
			$html .= '';
			if ( $local_setting != '' ) :
				$html .= '<img src="' . esc_url( graphene_get_post_meta( $post_id, 'slider_imgurl' ) ) . '" alt="" />';
			else :
				$html .= '<img src="' . esc_url( $graphene_settings['slider_imgurl'] ) . '" alt="" />';
			endif;
		} else {
			if ( $local_setting != '' ) :
				$html .= esc_url( graphene_get_post_meta( $post_id, 'slider_imgurl' ) );
			else :
				$html .= esc_url(  $graphene_settings['slider_imgurl'] );
			endif;
		}
		
	}
	
	if ( ! $html )
		$html = $default;
	
	// Returns the html
	return $html;
	
}
endif;


/**
 * Returns the posts to be displayed in the slider
 *
 * @return object Object containing the slider posts
 
 * @package Graphene
 * @since 1.6
*/
if ( ! function_exists( 'graphene_get_slider_posts' ) ) :

function graphene_get_slider_posts(){
	global $graphene_settings;
	
	/* Get the category whose posts should be displayed here. */
	$slidertype = ( $graphene_settings['slider_type'] != '' ) ? $graphene_settings['slider_type'] : false;
	
	/* Set the post types to be displayed */
	$slider_post_type = ( in_array( $slidertype, array( 'posts_pages', 'categories' ) ) ) ? array( 'post', 'page' ) : array( 'post' ) ;
	$slider_post_type = apply_filters( 'graphene_slider_post_type', $slider_post_type );
		
	/* Get the number of posts to show */
	$postcount = ( $graphene_settings['slider_postcount'] ) ? $graphene_settings['slider_postcount'] : 5 ;
		
	$args = array( 
				'posts_per_page'	=> $postcount,
				'orderby' 			=> 'menu_order date',
				'order' 			=> 'DESC',
				'suppress_filters' 	=> 0,
				'post_type' 		=> $slider_post_type,
				'ignore_sticky_posts' => 1, // otherwise the sticky posts show up undesired
				 );		
	
	if ( $slidertype && $slidertype == 'random' ) {
		$args = array_merge( $args, array( 'orderby' => 'rand' ) );
	}		
	if ( $slidertype && $slidertype == 'posts_pages' ) {                    
		$post_ids = $graphene_settings['slider_specific_posts'];
		$post_ids = preg_split("/[\s]*[,][\s]*/", $post_ids, -1, PREG_SPLIT_NO_EMPTY); // post_ids are comma separated, the query needs a array
		$post_ids = graphene_object_id( $post_ids );
		$args = array_merge( $args, array( 'post__in' => $post_ids, 'posts_per_page' => -1, 'orderby' => 'post__in' ) );
	}
	if ( $slidertype && $slidertype == 'categories' && is_array( $graphene_settings['slider_specific_categories'] ) ) {        
		$cats = $graphene_settings['slider_specific_categories'];
		$cats = graphene_object_id( $cats, 'category' );
		$args = array_merge( $args, array( 'category__in' => $cats ) );
		
		if ( $graphene_settings['slider_random_category_posts'] )
			$args = array_merge( $args, array( 'orderby' => 'rand' ) );
	}
	
	/* Get the posts */
	$sliderposts = new WP_Query( apply_filters( 'graphene_slider_args', $args ) );
	return apply_filters( 'graphene_slider_posts', $sliderposts );
}

endif;


/**
 * Exclude posts that belong to the categories displayed in slider from the posts listing
 */
function graphene_exclude_slider_categories( $request ){
	global $graphene_settings, $graphene_defaults;

	if ( $graphene_settings['slider_type'] != 'categories' ) return $request;
	if ( is_admin() ) return $request;
	
	if ( $graphene_settings['slider_exclude_categories'] != $graphene_defaults['slider_exclude_categories'] ){
		$dummy_query = new WP_Query();
    	$dummy_query->parse_query( $request );
		
		if ( get_option( 'show_on_front' ) == 'page' && $dummy_query->query_vars['page_id'] == get_option( 'page_on_front' ) ) return $request;
		
		if ( ( $graphene_settings['slider_exclude_categories'] == 'everywhere' ) || 
				$graphene_settings['slider_exclude_categories'] == 'homepage' && $dummy_query->is_home() )
			$request['category__not_in'] =  graphene_object_id( $graphene_settings['slider_specific_categories'], 'category' );
	}
	
	return $request;
}
add_filter( 'request', 'graphene_exclude_slider_categories' );