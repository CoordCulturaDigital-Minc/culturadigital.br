<?php 
error_reporting(0);
$ThemeName = "Arthemia Premium";

include_once(TEMPLATEPATH . '/inc/the_thumb.php');
include_once(TEMPLATEPATH . '/inc/limit_chars.php');

// WP 2.7 vs WP 2.6 comment compatibility
add_filter('comments_template', 'legacy_comments');
function legacy_comments($file) {
	if(!function_exists('wp_list_comments')) : // WP 2.7-only check
		$file = TEMPLATEPATH . '/legacy.comments.php';
	endif;
	return $file;
}

// sidebar stuff
if ( function_exists('register_sidebar') ) 
{     
register_sidebar(array('name' => 'Sidebar Top','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));     
register_sidebar(array('name' => 'Sidebar Left','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>')); 
register_sidebar(array('name' => 'Sidebar Right','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));   
register_sidebar(array('name' => 'Sidebar Bottom','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));    
register_sidebar(array('name' => 'Footer Left','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>'));
register_sidebar(array('name' => 'Footer Center','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>')); 
register_sidebar(array('name' => 'Footer Right','before_widget' => '','after_widget' => '','before_title' => '<h3>','after_title' => '</h3>')); 
}

// WP bug workaround to get W3C compliant
remove_filter('term_description','wpautop');


// excerpt
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'custom_trim_excerpt');

function custom_trim_excerpt($text) { // Fakes an excerpt if needed
global $post;
	if ( '' == $text ) {
		$text = get_the_content('');

		$text = strip_shortcodes( $text );

		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text);
		$excerpt_length = apply_filters('excerpt_length', 35);
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words) > $excerpt_length) {
			array_pop($words);
			array_push($words, '...');
			$text = implode(' ', $words);
		}
	}
	return $text;
}

// manual timthumb
function timthumb($atts) {

//Check if custom field key "Image" has a value
$values = get_post_custom_values("Image");

extract(shortcode_atts(array(
		"width" => '150',
		"height" => '150',
		"quality" => '90',
		"orientation" => 'left'
	), $atts));

$home = get_option('home');
$images = $values[0];
$bloginfo = get_bloginfo('template_url');
$alt = the_title("","",false);

if (isset($values[0])) {
$thumb .= '<img src="'.$bloginfo.'/scripts/timthumb.php?src='.$home.'/'.$images.'&amp;w='.$width.'&amp;h='.$height.'&amp;zc=1&amp;q='.$quality.'" width="'.$width.'px" height="'.$height.'px" class="'.$orientation.'" alt="'.$alt.'" />';
	}

return $thumb;

}

add_shortcode("thumb", "timthumb");


// offset and pagination
function my_post_limit($limit) {
	global $paged, $myOffset;
	if (empty($paged)) {
			$paged = 1;
	}
	$postperpage = intval(get_option('posts_per_page'));
	$pgstrt = ((intval($paged) -1) * $postperpage) + $myOffset . ', ';
	$limit = 'LIMIT '.$pgstrt.$postperpage;
	return $limit;
}


// admin panel
function cp_input( $var, $type, $description = "", $value = "", $selected="", $onchange="" ) {

	echo "\n";
 	
	switch( $type ){
	
	    case "text":

	 		echo "<input name=\"$var\" id=\"$var\" type=\"$type\" style=\"width: 80%\" class=\"code\" value=\"$value\" onchange=\"$onchange\"/>";
	 		echo "<p style=\"font-size:0.9em; color:#999; margin:0;\">$description</p>";
			
			break;

		case "smalltext":

	 		echo "<input name=\"$var\" id=\"$var\" type=\"$type\" style=\"width: 30%\" class=\"code\" value=\"$value\" onchange=\"$onchange\"/>";
	 		echo "<span style=\"font-size:0.9em; color:#999; margin:0; padding-left:5px; \">$description</span><br />";
			
			break;
			
		case "submit":
		
	 		echo "<p class=\"submit\" align=\"right\"><input name=\"$var\" type=\"$type\" value=\"$value\" /></p>";

			break;

		case "option":
		
			if( $selected == $value ) { $extra = "selected=\"true\""; }

			echo "<option value=\"$value\" $extra >$description</option>";
		
		    break;
		    
  		case "radio":
  		
			if( $selected == $value ) { $extra = "checked=\"true\""; }
  			
  			echo "<label><input name=\"$var\" id=\"$var\" type=\"$type\" value=\"$value\" $extra /> $description</label><br/>";
  			
  			break;
  			
		case "checkbox":
		
			if( $selected == $value ) { $extra = "checked=\"true\""; }

  			echo "<label><input name=\"$var\" id=\"$var\" type=\"$type\" value=\"$value\" $extra /> $description</label><br/>";

  			break;

		case "textarea":
		
		    echo "<textarea name=\"$var\" id=\"$var\" style=\"width: 60%; height: 10em;\" class=\"code\">$value</textarea>";
		
		    break;
	}

}

function cp_select( $var, $arrValues, $selected, $label ) {

	if( $label != "" ) {
		echo "<label for=\"$var\">$label</label>";
	}
	
	echo "<select name=\"$var\" id=\"$var\">\n";

	forEach( $arrValues as $arr ) {
		
		$extra = "";	
		if( $selected == $arr[ 0 ] ) { $extra = " selected=\"true\""; }

		echo "<option value=\"" . $arr[ 0 ] . "\"$extra>" . $arr[ 1 ] . "</option>\n";

	}
	
	echo "</select>";
	
}

function cp_multiSelect( $var, $arrValues, $arrSelected, $label, $description ) {

	if( $label != "" ) {
		echo "<label for=\"$var\">$label</label>";
	}
	
	echo "<select multiple=\"true\" size=\"7\" name=\"$var\" id=\"$var\" style=\"height:150px;\">\n";

	forEach( $arrValues as $arr ) {
		
		$extra = "";	
		if( in_array( $arr[ 0 ], $arrSelected ) ) { $extra = " selected=\"true\""; }

		echo "<option value=\"" . $arr[ 0 ] . "\"$extra>" . $arr[ 1 ] . "</option>\n";

	}
	
	echo "</select>";
	
	echo "<p style=\"font-size:0.9em; color:#999; margin:0;\">$description</p>";
	
}

function cp_th( $title ) {

   	echo "<tr valign=\"top\">";
	echo "<th width=\"63%\" scope=\"row\">$title</th>";
	echo "<td>";

}

function cp_cth() {

	echo "</td>";
	echo "</tr>";
	
}


// Theme Administration Panel

function cp_addThemePage() {

	global $ThemeName;
    
    	if ( $_GET['page'] == basename(__FILE__) ) {
	
	    // save settings
		if ( 'save' == $_REQUEST['action'] ) {
		
			check_admin_referer( 'save-theme-properties' );

			// text input		
			update_option( 'cp_feedlinkURL', $_REQUEST[ 'cp_feedlinkURL' ] );
			update_option( 'cp_feedlinkComments', $_REQUEST[ 'cp_feedlinkComments' ] );
			update_option( 'cp_analytics', $_REQUEST[ 'cp_analytics' ] );		
			update_option( 'ar_headline', $_REQUEST[ 'ar_headline' ] );	
			update_option( 'ar_featured', $_REQUEST[ 'ar_featured' ] );
            update_option( 'ar_video', $_REQUEST[ 'ar_video' ] );
			update_option( 'ar_categories', $_REQUEST[ 'ar_categories'] );
			update_option( 'ar_spoilers', $_REQUEST[ 'ar_spoilers' ] );

			update_option( 'cp_thumbAuto', $_REQUEST[ 'cp_thumbAuto' ] );
			update_option( 'cp_styleHead', $_REQUEST[ 'cp_styleHead' ] );
			update_option( 'cp_numFeatured', $_REQUEST[ 'cp_numFeatured' ] );
            update_option( 'cp_autoScroll', $_REQUEST[ 'cp_autoScroll' ] );
            update_option( 'cp_ScrollSpeed', $_REQUEST[ 'cp_ScrollSpeed' ] );
            update_option( 'cp_preventHeadline', $_REQUEST[ 'cp_preventHeadline' ] );
            update_option( 'cp_preventLatest', $_REQUEST[ 'cp_preventLatest' ] );

			update_option( 'cp_thumbHeight_LatestPost', $_REQUEST[ 'cp_thumbHeight_LatestPost' ] );
			update_option( 'cp_thumbWidth_LatestPost', $_REQUEST[ 'cp_thumbWidth_LatestPost' ] );

			update_option( 'cp_status_Column', $_REQUEST[ 'cp_status_Column' ] );
			update_option( 'cp_thumbHeight_Column', $_REQUEST[ 'cp_thumbHeight_Column' ] );
			update_option( 'cp_thumbWidth_Column', $_REQUEST[ 'cp_thumbWidth_Column' ] );
			update_option( 'cp_excerptColumn', $_REQUEST[ 'cp_excerptColumn' ] );

			update_option( 'cp_thumbWidth_Spoilers', $_REQUEST[ 'cp_thumbWidth_Spoilers' ] );
			update_option( 'cp_thumbHeight_Spoilers', $_REQUEST[ 'cp_thumbHeight_Spoilers' ] );
			update_option( 'cp_excerptSpoilers', $_REQUEST[ 'cp_excerptSpoilers' ] );
            update_option( 'cp_catSpoilers', $_REQUEST[ 'cp_catSpoilers' ] );

			update_option( 'cp_showindexcatbar', $_POST[ 'cp_showindexcatbar' ] );
                	update_option( 'cp_showindexgallery', $_POST[ 'cp_showindexgallery' ] );

			update_option( 'cp_thumbHeight_Post', $_REQUEST[ 'cp_thumbHeight_Post' ] );
			update_option( 'cp_thumbWidth_Post', $_REQUEST[ 'cp_thumbWidth_Post' ] );
			update_option( 'cp_postThumb', $_POST[ 'cp_postThumb' ] );
            	update_option( 'cp_showpostcatbar', $_POST[ 'cp_showpostcatbar' ] );
            	update_option( 'cp_showpostheadline', $_POST[ 'cp_showpostheadline' ] );
            	update_option( 'cp_showpostgallery', $_POST[ 'cp_showpostgallery' ] );

			update_option( 'cp_thumbWidth_Archive', $_REQUEST[ 'cp_thumbWidth_Archive' ] );
			update_option( 'cp_thumbHeight_Archive', $_REQUEST[ 'cp_thumbHeight_Archive' ] );
			update_option( 'cp_showarchivecatbar', $_POST[ 'cp_showarchivecatbar' ] );
            update_option( 'cp_showarchiveheadline', $_POST[ 'cp_showarchiveheadline' ] );
            update_option( 'cp_showarchivegallery', $_POST[ 'cp_showarchivegallery' ] );
            
            
            update_option( 'cp_catColor', $_REQUEST[ 'cp_catColor' ] );
            $cp_categories = get_categories('hide_empty=0');
            $cp_numCats = 0;
            foreach ( $cp_categories as $b ) {
			$cp_numCats ++;	}
            
            for( $cp_x = 1; $cp_x <= $cp_numCats; $cp_x ++ ) {
                update_option( 'cp_colorCategory_' . $cp_x, $_REQUEST[ 'cp_colorCategory_' . $cp_x ] );
                update_option( 'cp_hexColor_' . $cp_x, $_REQUEST[ 'cp_hexColor_' . $cp_x ] );
                update_option( 'cp_textColor_' . $cp_x, $_REQUEST[ 'cp_textColor_' . $cp_x ] );
                update_option( 'cp_hoverColor_' . $cp_x, $_REQUEST[ 'cp_hoverColor_' . $cp_x ] );
               }
            
			update_option( 'cp_adImage_1', $_REQUEST[ 'cp_adImage_1' ] );
			update_option( 'cp_adURL_1', $_REQUEST[ 'cp_adURL_1' ] );
			update_option( 'cp_adImage_2', $_REQUEST[ 'cp_adImage_2' ] );
			update_option( 'cp_adURL_2', $_REQUEST[ 'cp_adURL_2' ] );
			update_option( 'cp_adImage_3', $_REQUEST[ 'cp_adImage_3' ] );
			update_option( 'cp_adURL_3', $_REQUEST[ 'cp_adURL_3' ] );

			update_option( 'cp_adGoogleID', $_REQUEST[ 'cp_adGoogleID' ] );
			update_option( 'cp_adAdsenseCode_1', $_REQUEST[ 'cp_adAdsenseCode_1' ] );
			update_option( 'cp_adAdsenseCode_2', $_REQUEST[ 'cp_adAdsenseCode_2' ] );
			update_option( 'cp_adAdsenseCode_3', $_REQUEST[ 'cp_adAdsenseCode_3' ] );


			update_option( 'cp_logo', $_REQUEST[ 'cp_logo' ] );
			update_option( 'cp_favICON', $_REQUEST[ 'cp_favICON' ] );

			
			// goto theme edit page
			header("Location: themes.php?page=functions.php&saved=true");
			die;

  		// reset settings
		} else if( 'reset' == $_REQUEST['action'] ) {

			delete_option( 'cp_feedlinkURL' );
			delete_option( 'cp_feedlinkComments' );
			delete_option( 'cp_analytics' );
									
			delete_option( 'ar_headline' );
			delete_option( 'ar_featured' );
			delete_option( 'ar_video' );
			delete_option( 'ar_categories' );
			delete_option( 'ar_spoilers' );
	
			delete_option( 'cp_thumbAuto' );
			delete_option( 'cp_styleHead' );
			delete_option( 'cp_numFeatured' );
            delete_option( 'cp_autoScroll' );
            delete_option( 'cp_ScrollSpeed' );
            delete_option( 'cp_preventHeadline' );
            delete_option( 'cp_preventLatest' );
            
			delete_option( 'cp_thumbHeight_LatestPost' );
			delete_option( 'cp_thumbWidth_LatestPost' );
	
			delete_option( 'cp_status_Column' );
			delete_option( 'cp_thumbHeight_Column' );
			delete_option( 'cp_thumbWidth_Column' );
			delete_option( 'cp_excerptColumn' );

			delete_option( 'cp_thumbWidth_Spoilers' );
			delete_option( 'cp_thumbHeight_Spoilers' );
			delete_option( 'cp_excerptSpoilers' );
            	delete_option( 'cp_catSpoilers' );

			delete_option( 'cp_showindexcatbar' );
           		delete_option( 'cp_showindexgallery' );

			delete_option( 'cp_thumbHeight_Post' );
			delete_option( 'cp_thumbWidth_Post' );
			delete_option( 'cp_postThumb' );
            	delete_option( 'cp_showpostcatbar' );
            	delete_option( 'cp_showpostheadline' );
            	delete_option( 'cp_showpostgallery' );

			delete_option( 'cp_thumbWidth_Archive' );
			delete_option( 'cp_thumbHeight_Archive' );
            	delete_option( 'cp_showarchivecatbar' );
            	delete_option( 'cp_showarchiveheadline' );
            	delete_option( 'cp_showarchivegallery' );
            
            delete_option( 'cp_catColor' );

            $cp_categories = get_categories('hide_empty=0');
            $cp_numCats = 0;
            foreach ( $cp_categories as $b ) {
			$cp_numCats ++;	}
            
            for( $cp_x = 1; $cp_x <= $cp_numCats; $cp_x ++ ) {
                delete_option( 'cp_colorCategory_' . $cp_x );
                delete_option( 'cp_hexColor_' . $cp_x );	
                delete_option( 'cp_textColor_' . $cp_x );
                delete_option( 'cp_hoverColor_' . $cp_x );
               }

			delete_option( 'cp_adImage_1' );
			delete_option( 'cp_adURL_1' );		
			delete_option( 'cp_adImage_2' );
			delete_option( 'cp_adURL_2' );
			delete_option( 'cp_adImage_3' );
			delete_option( 'cp_adURL_3' );
			
			delete_option( 'cp_adGoogleID' );
			delete_option( 'cp_adAdsenseCode_1' );		
			delete_option( 'cp_adAdsenseCode_2' );
			delete_option( 'cp_adAdsenseCode_3' );
			
			delete_option( 'cp_logo' );
			delete_option( 'cp_favICON' );
			
			// goto theme edit page
			header("Location: themes.php?page=functions.php&reset=true");
			die;

		}
	}

	add_theme_page( $ThemeName . ' Theme Options', $ThemeName . ' Options', 'edit_themes', basename(__FILE__), 'cp_themePage' );

}

function cp_themePage() {

	global $ThemeName;
	
	if ( $_REQUEST[ 'saved' ] ) echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
	if ( $_REQUEST[ 'reset' ] ) echo '<div id="message" class="updated fade"><p><strong>Settings reset.</strong></p></div>';
	
	?>
	
	<style>
	h3 { border-bottom:1px solid #ddd; padding-top:20px; padding-bottom:3px; }


	</style>
   
	<div class="wrap">
	 <form method="post">
	<h2 style="border-bottom:none;"><?php echo $ThemeName; ?> Theme Configuration</h2>
	
	
	
	<?php if ( function_exists('wp_nonce_field') ) { wp_nonce_field( 'save-theme-properties' ); } ?>
	
	<div style="padding-bottom:30px;">

	<h3>General Settings</h3>	
	<p>You can assign categories for headline, featured, and video section here. <strong>For category bar and category spoilers, select multiple categories by holding the Ctrl key.</strong> Category description in the category bar can be edited from Manage>Categories menu in Wordpress admin panel.</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php 

		$cp_categories = get_categories('hide_empty=0');
		foreach ( $cp_categories as $b ) {
			$cp_cat[] = array( $b->cat_ID, $b->cat_name );
		}

		cp_th( "Headline" );
		cp_select( "ar_headline", $cp_cat, get_settings( "ar_headline" ), "" );		
		cp_cth();

		cp_th( "Featured" );
		cp_select( "ar_featured", $cp_cat, get_settings( "ar_featured" ), "" );		
		cp_cth();
        
        cp_th( "Video" );
		cp_select( "ar_video", $cp_cat, get_settings( "ar_video" ), "" );		
		cp_cth();

		cp_th( "Category Bar" );
		cp_multiSelect( "ar_categories[]", $cp_cat, get_settings( "ar_categories" ), "", "Hold down Ctrl button to select multiple categories. You must select 5 categories to get the best layout." );
		cp_cth();
		
		cp_th( "Category Spoilers" );
		cp_multiSelect( "ar_spoilers[]", $cp_cat, get_settings( "ar_spoilers" ), "", "Hold down Ctrl button to select multiple categories." );
		cp_cth();
		
	?>
	</table>	

	<h3>Theme Sections and Thumbnail Settings</h3>
	<p>Configuration for page sections and thumbnail generation across your website. Thumbnail are generated and resized automatically using the TimThumb script. <strong>Do not forget to make the 'scripts' and 'cache' folder of the theme writable</strong> (CHMOD 777 or 755 from FTP).</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
		$cp_thumb[] = array( "first", "First Image" );
		$cp_thumb[] = array( "", "Post Custom Field" );

		cp_th( "Thumbnail assignment" );
		cp_select( "cp_thumbAuto", $cp_thumb, get_settings( "cp_thumbAuto" ), "" );
		cp_cth();
	?>
	</table>

<p>In case you want to have full control over the image thumbnail assignment, please refer to the tutorial <a href="http://michaelhutagalung.com/2008/08/tutorial-timthumb-thumbnail-generation-with-arthemia-theme/" target="_blank">here</a>. You have to do manual thumbnail assignment using Custom Field if you don't want to assign the first image of the post as thumbnail, or perhaps to have a different image as thumbnail.</p>

	<h4>Front Page Settings</h4>
	<p>You can customize the front page here. Thumbnail sizes are in pixels (px). To control number of post shown in the column list, go to Settings > Reading > Blog post.</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
        
        $cp_headimage[] = array( "wide", "Wide Image" );
		$cp_headimage[] = array( "", "Square Image" );

        cp_th( "Headline Style" );
        cp_select( "cp_styleHead", $cp_headimage, get_settings( "cp_styleHead" ), "" );
		cp_cth();
        
        cp_th( "Featured Items" );
        cp_input( "cp_numFeatured", "smalltext", "Total item in vertical carousel", get_settings( "cp_numFeatured" ) );
		cp_cth();
        
        $cp_scroll[] = array( "", "Yes" );
		$cp_scroll[] = array( "No", "No" );

        cp_th( "Auto scroll and circular carousel?" );
        cp_select( "cp_autoScroll", $cp_scroll, get_settings( "cp_autoScroll" ), "" );
		cp_cth();
        
        cp_th( "Carousel pause time" );
        cp_input( "cp_ScrollSpeed", "smalltext", "in miliseconds (default: 1000)", get_settings( "cp_ScrollSpeed" ) );
		cp_cth();
        
	?>
	</table>
      
	<div style="font-size:0.9em; color:#999;"><p>For the best layout, assign more than 6 posts to the Featured category.</div>
	
	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">

	<?php
		cp_th( "Latest Post Section" );
		cp_input( "cp_thumbWidth_LatestPost", "smalltext", "Thumbnail Width (default. 150)", get_settings( "cp_thumbWidth_LatestPost" ) );
		cp_input( "cp_thumbHeight_LatestPost", "smalltext", "Thumbnail Height (default. 150)", get_settings( "cp_thumbHeight_LatestPost" ) );
		cp_cth();
	?>

</table>

	<div style="font-size:0.9em; color:#999;"><p>Latest Post automated thumbnail will be hidden if you assign thumbnails to be taken from First Image in posts.</p></div>
	
	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">

	<?php
		$cp_column[] = array( "no", "Hide Post Excerpt" );
		$cp_column[] = array( "", "Show Post Excerpt" );
		$cp_num_column[] = array( "", "Show Two-Column List" );
		$cp_num_column[] = array( "one", "Show One-Column List" );

		cp_th( "Column List" );
		cp_input( "cp_thumbWidth_Column", "smalltext", "Thumbnail Width (default. 80)", get_settings( "cp_thumbWidth_Column" ) );
		cp_input( "cp_thumbHeight_Column", "smalltext", "Thumbnail Height (default. 80)", get_settings( "cp_thumbHeight_Column" ) );
		cp_select( "cp_excerptColumn", $cp_column, get_settings( "cp_excerptColumn" ), "" );
		cp_select ("cp_status_Column", $cp_num_column, get_settings( "cp_status_Column" ), "" );
		cp_cth();


?>
	</table>

	<div style="font-size:0.9em; color:#999;"><p>Recommended values for above Column List fields are:
	<ul><li>width = 80, height = 80, show post excerpts, two-column list</li><li>width = 265, height = 80, show post excerpts, two-column list</li><li>width = 265, height = 80, hide post excerpts, two-column list</li><li>width = 150, height = 90, show post excerpts, one-column list</li></ul></p></div>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">

	<?php
		$cp_indexcatbar[] = array( "no", "Hide Category Bar" );
		$cp_indexcatbar[] = array( "", "Show Category Bar" );
               
        	$cp_indexgallery[] = array( "no", "Hide Video and Random Post Gallery" );
		$cp_indexgallery[] = array( "", "Show Video and Random Post Gallery" );
		
		cp_th( "Index Page" );
		cp_select( "cp_showindexcatbar", $cp_indexcatbar, get_settings( "cp_showindexcatbar" ), "" );
		cp_select( "cp_showindexgallery", $cp_indexgallery, get_settings( "cp_showindexgallery" ), "" );
		
		cp_cth();


?>
	</table>
	
    <h4>Sidebar Settings</h4>
	<p>You can customize category spoilers here. To add widgets in sidebar and footer, you need to go to Design > Widgets.</p>
	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">

	<?php
		
		$cp_spoilers[] = array( "no", "Hide Post Excerpt" );
		$cp_spoilers[] = array( "", "Show Post Excerpt" );

        $cp_showspoilers[] = array( "no", "Hide Category Spoilers" );
		$cp_showspoilers[] = array( "", "Show Category Spoilers" );

		cp_th( "Category Spoilers" );
		cp_input( "cp_thumbWidth_Spoilers", "smalltext", "Thumbnail Width (default. 80)", get_settings( "cp_thumbWidth_Spoilers" ) );
		cp_input( "cp_thumbHeight_Spoilers", "smalltext", "Thumbnail Height (default. 80)", get_settings( "cp_thumbHeight_Spoilers" ) );
		cp_select( "cp_excerptSpoilers", $cp_spoilers, get_settings( "cp_excerptSpoilers" ), "" );
        cp_select( "cp_catSpoilers", $cp_showspoilers, get_settings( "cp_catSpoilers" ), "" );

		cp_cth();

?>
	</table>

	<div style="font-size:0.9em; color:#999;"><p>Recommended values for above Category Spoilers fields are:
	<ul><li>width = 80, height = 80, and show post excerpts</li><li>width = 290, height = 80, and hide post excerpts</li></ul></p></div>
    
    <h4>Single Post Page Settings</h4>
	<p>You can customize the single-post page here.</p>
	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">

	<?php

		$cp_postview[] = array( "no", "Hide Top-Left Thumbnail" );
		$cp_postview[] = array( "", "Show Top-Left Thumbnail" );

        	$cp_postcatbar[] = array( "no", "Hide Category Bar" );
		$cp_postcatbar[] = array( "", "Show Category Bar" );
        
        	$cp_postheadline[] = array( "no", "Hide Headline Spoiler" );
		$cp_postheadline[] = array( "", "Show Headline Spoiler" );
        
        	$cp_postgallery[] = array( "no", "Hide Video and Random Post Gallery" );
		$cp_postgallery[] = array( "", "Show Video and Random Post Gallery" );
		
		cp_th( "Single Post View" );
		cp_input( "cp_thumbWidth_Post", "smalltext", "Thumbnail Width (default. 150)", get_settings( "cp_thumbWidth_Post" ) );
		cp_input( "cp_thumbHeight_Post", "smalltext", "Thumbnail Height (default. 150)", get_settings( "cp_thumbHeight_Post" ) );
		cp_select( "cp_postThumb", $cp_postview, get_settings( "cp_postThumb" ), "" );
		cp_select( "cp_showpostcatbar", $cp_postcatbar, get_settings( "cp_showpostcatbar" ), "" );
		cp_select( "cp_showpostheadline", $cp_postheadline, get_settings( "cp_showpostheadline" ), "" );
		cp_select( "cp_showpostgallery", $cp_postgallery, get_settings( "cp_showpostgallery" ), "" );
		
        cp_cth(); ?>

</table>

	<div style="font-size:0.9em; color:#999;"><p>Single-Post View automated thumbnail will be hidden if you assign thumbnails to be taken from First Image in posts.</p></div>
	
    <h4>Archive and Search Page Settings</h4>
	<p>You can customize the archive and search page here.</p>
	
	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">

	<?php
    
        $cp_archivecatbar[] = array( "no", "Hide Category Bar" );
		$cp_archivecatbar[] = array( "", "Show Category Bar" );
        
        $cp_archiveheadline[] = array( "no", "Hide Headline Spoiler" );
		$cp_archiveheadline[] = array( "", "Show Headline Spoiler" );
        
        $cp_archivegallery[] = array( "no", "Hide Video and Random Post Gallery" );
		$cp_archivegallery[] = array( "", "Show Video and Random Post Gallery" );
    
		cp_th( "Archive and Search" );
		cp_input( "cp_thumbWidth_Archive", "smalltext", "Thumbnail Width (default. 150)", get_settings( "cp_thumbWidth_Archive" ) );
		cp_input( "cp_thumbHeight_Archive", "smalltext", "Thumbnail Height (default. 150)", get_settings( "cp_thumbHeight_Archive" ) );
		cp_select( "cp_showarchivecatbar", $cp_archivecatbar, get_settings( "cp_showarchivecatbar" ), "" );
		cp_select( "cp_showarchiveheadline", $cp_archiveheadline, get_settings( "cp_showarchiveheadline" ), "" );
		cp_select( "cp_showarchivegallery", $cp_archivegallery, get_settings( "cp_showarchivegallery" ), "" );
		cp_cth();
    	
	?>
	</table>
    
    <h3>Post Duplication Prevention</h3>
    <p>You can prevent posts from being displayed more than once on the front page. <strong>Notice: this settings will be only implemented on the front page.</strong></p>
    <div style="font-size:0.9em; color:#999;"><p>Default settings:
    <ul><li>headline and featured posts are only displayed in the top-fold of the front page and are excluded from the latest post list (column list on the left) and from the category spoilers in the sidebar.</li>
    <li>latest posts on the column list will not be displayed again in the category spoilers. (This will make category spoilers' content vary in every page.)</li>
    </ul>
    </p></div>
    <table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
        
        $cp_headpost[] = array( "", "Yes" );
		$cp_headpost[] = array( "No", "No" );

        cp_th( "Headline and featured post exclusion?" );
        cp_select( "cp_preventHeadline", $cp_headpost, get_settings( "cp_preventHeadline" ), "" );
		cp_cth();
        
        $cp_latestpost[] = array( "", "Yes" );
		$cp_latestpost[] = array( "No", "No" );

        cp_th( "Column list posts exclusion on category spoilers?" );
        cp_select( "cp_preventLatest", $cp_latestpost, get_settings( "cp_preventLatest" ), "" );
		cp_cth();
        
        
	?>
	</table>

	
    <h3>Logo Image</h3>
	<p>Logo is shown in the top-left section of your website. To set up a logo, <strong>upload an image to the logo folder in the theme folder</strong>. To set up favorite/bookmark icon, <strong>upload an icon file to the icons folder in the theme folder</strong>.</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php

		// get styles
		$cp_logoDir = opendir( TEMPLATEPATH . "/images/logo/" );
		$cp_Alogo[] = array( "", "None (default)" );
		// Open a known directory, and proceed to read its contents
	    while (false !== ( $cp_logoFolder = readdir( $cp_logoDir ) ) ) {
	    	if( $cp_logoFolder != "." && $cp_logoFolder != ".." ) {
	    		$cp_logoName = $cp_logoFolder;
	    		$cp_Alogo[] = array( $cp_logoFolder, $cp_logoName );
    		}
		}
		
		closedir( $cp_logoDir );
		
		cp_th( "Logo" );
		cp_select( "cp_logo", $cp_Alogo, get_settings( "cp_logo" ), "" );
		cp_cth();


		// get styles
		$cp_icoDir = opendir( TEMPLATEPATH . "/images/icons/" );

		$cp_Aico[] = array( "", "None (default)" );
		// Open a known directory, and proceed to read its contents
	    while (false !== ( $cp_icoFolder = readdir( $cp_icoDir ) ) ) {
	    	if( $cp_icoFolder != "." && $cp_icoFolder != ".." ) {
	    		$cp_icoName = $cp_icoFolder;
	    		$cp_Aico[] = array( $cp_icoFolder, $cp_icoName );
    		}
		}
		
		closedir( $cp_icoDir );

		cp_th( "Favorite Icons" );
		cp_select( "cp_favICON", $cp_Aico, get_settings( "cp_favICON" ), "" );

		cp_cth();
			    	
    	
	?>
	</table>
	
	<h3>Category Color Assignment</h3>
	<p>Assign color to each category here. You can only assign one color per category. This list below will display all categories you have and the order does not represent the category order in the front page as categories are always sorted alphabetically.</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
		
		$cp_categories = get_categories('hide_empty=0');
        $cp_numCats = 0;
		foreach ( $cp_categories as $b ) {
			$cp_Colorcat[] = array( $b->cat_ID, $b->cat_name );
            $cp_numCats ++;
		}
		
        cp_th( "Category Spoilers Title Color" );
        cp_input( "cp_catColor", "smalltext", "Text (start with #)", get_settings( "cp_catColor" ) );
        cp_cth();
        
		cp_th( "Category Bar Colors" );
		for( $cp_x = 1; $cp_x <= $cp_numCats; $cp_x ++ ) {
		
        cp_select ( "cp_colorCategory_" . $cp_x, $cp_Colorcat, get_settings( "cp_colorCategory_" . $cp_x ), "" );
		echo '<br />'; cp_input( "cp_hexColor_" . $cp_x, "smalltext", "Background (start with #)", get_settings( "cp_hexColor_" . $cp_x ) );
		cp_input( "cp_textColor_" . $cp_x, "smalltext", "Text (start with #)", get_settings( "cp_textColor_" . $cp_x ) );
        cp_input( "cp_hoverColor_" . $cp_x, "smalltext", "Link Hover (start with #)", get_settings( "cp_hoverColor_" . $cp_x ) );
        echo '<br />'; }
		cp_cth();
    	
	?>
	</table>			

    <h3>RSS and Statistics Settings</h3>
	<p>Manage your RSS Feeds with Google Feedburner and website statistics with Google Analytics. Put your Feedburner URLs and Analytics ID here.</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
		cp_th( "Google Analytics ID" );
		cp_input( "cp_analytics", "smalltext", "Google Analytics ID. Start with UA-", get_settings( "cp_analytics" ) );
		cp_cth();		

		cp_th( "Feedburner URL" );
		cp_input( "cp_feedlinkURL", "text", "Feedburner URL. This will replace RSS feed link. Start with http://", get_settings( "cp_feedlinkURL" ) );
		cp_cth();
		
		cp_th( "Feedburner Comments URL" );
		cp_input( "cp_feedlinkComments", "text", "Feedburner URL. This will replace RSS comment feed link. Start with http://", get_settings( "cp_feedlinkComments" ) );
		cp_cth();
		
		
	?>
	</table>
    
	<h3>Google Adsense and User-Specified Ads</h3>
	<p>Ads will appear on the righthand sidebar of the homepage, top section of the page, and in single post views. To display ads from Google Adsense, complete the Adsense account settings and select "Adsense" from the drop-down menu below. Ad click URLs will be neglected.</p>

	<h4>Global Ad Settings</h4>	
	<p>If you want to set up your own ad simply <strong>upload the image to the ads folder in your theme folder</strong> and the ad file name will show up in the drop-down menu.</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
		// get styles
		$cp_adsDir = opendir( TEMPLATEPATH . "/images/ads/" );

		$cp_ads[] = array( "", "None (default)" );
		$cp_ads[] = array( "Adsense", "Adsense" );
		// Open a known directory, and proceed to read its contents
	    while (false !== ( $cp_adsFolder = readdir( $cp_adsDir ) ) ) {
	    	if( $cp_adsFolder != "." && $cp_adsFolder != ".." ) {
	    		$cp_adName = $cp_adsFolder;
	    		$cp_ads[] = array( $cp_adsFolder, $cp_adName );
    		}
		}
		
		closedir( $cp_adsDir );
		
		cp_th( "Header Section Ad" );
		cp_select( "cp_adImage_1", $cp_ads, get_settings( "cp_adImage_1" ), "" );
		echo "<br />";
		cp_input( "cp_adURL_1", "text", "Header Section Ad click URL", get_settings( "cp_adURL_1" ) );
		cp_cth();
		
		cp_th( "Sidebar Section Ad" );
		cp_select( "cp_adImage_2", $cp_ads, get_settings( "cp_adImage_2" ), "" );
		echo "<br />";
		cp_input( "cp_adURL_2", "text", "Sidebar Section Ad click URL", get_settings( "cp_adURL_2" ) );
		cp_cth();
		
		cp_th( "Single Post View Ad" );
		cp_select( "cp_adImage_3", $cp_ads, get_settings( "cp_adImage_3" ), "" );
		echo "<br />";
		cp_input( "cp_adURL_3", "text", "Single Post View Ad click URL", get_settings( "cp_adURL_3" ) );
		cp_cth();
	    	
    	
	?>
	</table>

	<h4>Adsense Account Settings</h4>
	<p>To set up Adsense, <strong>add your Google Ad Client ID and Ad Slot IDs</strong> to the fields below.</p>

	<p>This is the example code that you get from Google:</p>
	<pre><code style="font-size:0.8em;">&lt;script type=&quot;text/javascript&quot;&gt;&lt;!--
google_ad_client = &quot;pub-XXXXXXXXXXXXXXXX&quot;;
/* Your Ad description here */
google_ad_slot = &quot;YYYYYYYYYY&quot;;
google_ad_width = width;
google_ad_height = height;
//--&gt;
&lt;/script&gt;
&lt;script type=&quot;text/javascript&quot;
src=&quot;http://pagead2.googlesyndication.com/pagead/show_ads.js&quot;&gt;
&lt;/script&gt;</code></pre>
	<p>Notice the value for "google_ad_client" and "google_ad_slot".</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
		
		cp_th( "Google Ad Client ID" );
		cp_input( "cp_adGoogleID", "text", "Put your Google Ad Client ID here. (Eg. pub-XXXXXXXXXXXXXXXX)", get_settings( "cp_adGoogleID" ) );
		cp_cth();

		cp_th( "728x90 Ad Slot ID" );
		cp_input( "cp_adAdsenseCode_1", "smalltext", "Put the Ad Slot ID for here. (Eg. YYYYYYYYYY)", get_settings( "cp_adAdsenseCode_1" ) );
		cp_cth();
		
		cp_th( "300x225 Ad Slot ID" );
		cp_input( "cp_adAdsenseCode_2", "smalltext", "Put the Ad Slot ID for here. (Eg. YYYYYYYYYY)", get_settings( "cp_adAdsenseCode_2" ) );
		cp_cth();
		
		cp_th( "468x60 Ad Slot ID" );
		cp_input( "cp_adAdsenseCode_3", "smalltext", "Put the Ad Slot ID for here. (Eg. YYYYYYYYYY)", get_settings( "cp_adAdsenseCode_3" ) );
		cp_cth();
	    	
    	
	?>
	</table>	

	</div>

	
	<br style="clear:both;" />
    
    <input type="hidden" name="action" value="save" />
	<?php cp_input( "save", "submit", "", "Save Settings" ); ?>
	
	</form>
	</div>

	<?php



}

function cp_getProperty( $property ) {

	global $ThemeName;

	$value = get_settings( "ThemeName_" . $property );
	if( $value == "1" ) { return 1; } else { return 0; }	

}

function cp_catProperties( $id ) {

	global $cp_categories;

	foreach( $cp_categories as $bC ) {
	
		if( $bC->cat_ID == $id ) {
			return $bC;
			exit;
		}
	
	}

}

// Plugin by Steve Smith - http://orderedlist.com/wordpress-plugins/feedburner-plugin/ and feedburner - http://www.feedburner.com/fb/a/help/wordpress_quickstart
function feed_redirect() {

	global $wp, $feed, $withcomments;
	
	$newURL1 = trim( get_settings( "cp_feedlinkURL" ) );
	$newURL2 = trim( get_settings( "cp_feedlinkComments" ) );
	
	if( is_feed() ) {

		if ( $feed != 'comments-rss2' 
				&& !is_single() 
				&& $wp->query_vars[ 'category_name' ] == ''
				&& !is_author() 
				&& ( $withcomments != 1 )
				&& $newURL1 != '' ) {
		
			if ( function_exists( 'status_header' ) ) { status_header( 302 ); }
			header( "Location:" . $newURL1 );
			header( "HTTP/1.1 302 Temporary Redirect" );
			exit();
			
		} elseif ( ( $feed == 'comments-rss2' || $withcomments == 1 ) && $newURL2 != '' ) {
	
			if ( function_exists( 'status_header' ) ) { status_header( 302 ); }
			header( "Location:" . $newURL2 );
			header( "HTTP/1.1 302 Temporary Redirect" );
			exit();
			
		}
	
	}

}

function feed_check_url() {

	switch ( basename( $_SERVER[ 'PHP_SELF' ] ) ) {
		case 'wp-rss.php':
		case 'wp-rss2.php':
		case 'wp-atom.php':
		case 'wp-rdf.php':
		
			$newURL = trim( get_settings( "cp_feedlinkURL" ) );
			
			if ( $newURL != '' ) {
				if ( function_exists('status_header') ) { status_header( 302 ); }
				header( "Location:" . $newURL );
				header( "HTTP/1.1 302 Temporary Redirect" );
				exit();
			}
			
			break;
			
		case 'wp-commentsrss2.php':
		
			$newURL = trim( get_settings( "cp_feedlinkComments" ) );
			
			if ( $newURL != '' ) {
				if ( function_exists('status_header') ) { status_header( 302 ); }
				header( "Location:" . $newURL );
				header( "HTTP/1.1 302 Temporary Redirect" );
				exit();
			}
			
			break;
	}
}

if (!preg_match("/feedburner|feedvalidator/i", $_SERVER['HTTP_USER_AGENT'])) {
	add_action('template_redirect', 'feed_redirect');
	add_action('init','feed_check_url');
}
add_action( 'admin_menu', 'cp_addThemePage' );

?>