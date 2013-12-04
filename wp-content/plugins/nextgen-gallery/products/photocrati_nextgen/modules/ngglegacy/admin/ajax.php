<?php
add_action('wp_ajax_ngg_ajax_operation', 'ngg_ajax_operation' );

/**
 * Image edit functions via AJAX
 *
 * @author Alex Rabe
 *
 *
 * @return void
 */
function ngg_ajax_operation() {

	global $wpdb;

	// if nonce is not correct it returns -1
	check_ajax_referer( "ngg-ajax" );

	// check for correct capability
	if ( !is_user_logged_in() )
		die('-1');

	// check for correct NextGEN capability
	if ( !current_user_can('NextGEN Upload images') && !current_user_can('NextGEN Manage gallery') )
		die('-1');

	// include the ngg function
	include_once (dirname (__FILE__) . '/functions.php');

	// Get the image id
	if ( isset($_POST['image'])) {
		$id = (int) $_POST['image'];
		// let's get the image data
		$picture = nggdb::find_image( $id );
		// what do you want to do ?
		switch ( $_POST['operation'] ) {
			case 'create_thumbnail' :
				$result = nggAdmin::create_thumbnail($picture);
			break;
			case 'resize_image' :
				$result = nggAdmin::resize_image($picture);
			break;
			case 'rotate_cw' :
				$result = nggAdmin::rotate_image($picture, 'CW');
				nggAdmin::create_thumbnail($picture);
			break;
			case 'rotate_ccw' :
				$result = nggAdmin::rotate_image($picture, 'CCW');
				nggAdmin::create_thumbnail($picture);
			break;
			case 'set_watermark' :
				$result = nggAdmin::set_watermark($picture);
			break;
			case 'recover_image' :
				$result = nggAdmin::recover_image($picture);
			break;
			case 'import_metadata' :
				$result = nggAdmin::import_MetaData( $id );
			break;
			case 'get_image_ids' :
				$result = nggAdmin::get_image_ids( $id );
			break;
			default :
				do_action( 'ngg_ajax_' . $_POST['operation'] );
				die('-1');
			break;
		}
		// A success should return a '1'
		die ($result);
	}

	// The script should never stop here
	die('0');
}

add_action('wp_ajax_createNewThumb', 'createNewThumb');

function createNewThumb() {

    global $ngg;

	// check for correct capability
	if ( !is_user_logged_in() )
		die('-1');

	// check for correct NextGEN capability
	if ( !current_user_can('NextGEN Manage gallery') )
		die('-1');

	$id 	 = (int) $_POST['id'];
	$picture = nggdb::find_image( $id );

	$x = round( $_POST['x'] * $_POST['rr'], 0);
	$y = round( $_POST['y'] * $_POST['rr'], 0);
	$w = round( $_POST['w'] * $_POST['rr'], 0);
	$h = round( $_POST['h'] * $_POST['rr'], 0);
	$crop_frame = array('x' => $x, 'y' => $y, 'width' => $w, 'height' => $h);

	$registry = C_Component_Registry::get_instance();
	$storage  = $registry->get_utility('I_Gallery_Storage');

	// XXX NextGEN Legacy wasn't handling watermarks or reflections at this stage, so we're forcefully disabling them to maintain compatibility
	$params = array('watermark' => false, 'reflection' => false, 'crop' => true, 'crop_frame' => $crop_frame);
	$result = $storage->generate_thumbnail($id, $params);

	if ($result) {
		echo "OK";
	} else {
		header('HTTP/1.1 500 Internal Server Error');
		echo "KO";
	}

	C_NextGEN_Bootstrap::shutdown();
}

add_action('wp_ajax_rotateImage', 'ngg_rotateImage');

function ngg_rotateImage() {

	// check for correct capability
	if ( !is_user_logged_in() )
		die('-1');

	// check for correct NextGEN capability
	if ( !current_user_can('NextGEN Manage gallery') )
		die('-1');

	require_once( dirname( dirname(__FILE__) ) . '/ngg-config.php');

	// include the ngg function
	include_once (dirname (__FILE__). '/functions.php');

	$ngg_options = get_option('ngg_options');

	$id = (int) $_POST['id'];
	$result = '-1';

	switch ( $_POST['ra'] ) {
		case 'cw' :
			$result = nggAdmin::rotate_image($id, 'CW');
		break;
		case 'ccw' :
			$result = nggAdmin::rotate_image($id, 'CCW');
		break;
		case 'fv' :
			// Note: H/V have been inverted here to make it more intuitive
			$result = nggAdmin::rotate_image($id, 0, 'H');
		break;
		case 'fh' :
			// Note: H/V have been inverted here to make it more intuitive
			$result = nggAdmin::rotate_image($id, 0, 'V');
		break;
	}

    // recreate the thumbnail
    nggAdmin::create_thumbnail($id);

	if ( $result == 1 )
		die('1');

	header('HTTP/1.1 500 Internal Server Error');
	die( $result );

}

add_action('wp_ajax_ngg_dashboard', 'ngg_ajax_dashboard');

function ngg_ajax_dashboard() {

   	require_once( dirname( dirname(__FILE__) ) . '/admin/admin.php');
	require_once( dirname( dirname(__FILE__) ) . '/admin/overview.php');

   	if ( !current_user_can('NextGEN Gallery overview') )
		die('-1');

    @header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );
    @header( 'X-Content-Type-Options: nosniff' );

    switch ( $_GET['jax'] ) {

    case 'ngg_lastdonators' :
    	ngg_overview_donators();
    	break;

    case 'dashboard_primary' :
    	ngg_overview_news();
    	break;

    case 'ngg_locale' :
    	ngg_locale();
    	break;

    case 'dashboard_plugins' :
    	ngg_related_plugins();
    	break;

    }
    die();
}

add_action('wp_ajax_ngg_file_browser', 'ngg_ajax_file_browser');

/**
 * jQuery File Tree PHP Connector
 * @author Cory S.N. LaViska - A Beautiful Site (http://abeautifulsite.net/)
 * @version 1.0.1
 *
 * @return string folder content
 */
function ngg_ajax_file_browser() {

    global $ngg;

	// check for correct NextGEN capability
	if ( !current_user_can('NextGEN Upload images') && !current_user_can('NextGEN Manage gallery') )
		die('No access');

    if ( !defined('ABSPATH') )
        die('No access');

	// if nonce is not correct it returns -1
	check_ajax_referer( 'ngg-ajax', 'nonce' );

    //PHP4 compat script
	if (!function_exists('scandir')) {
		function scandir($dir, $listDirectories = false, $skipDots = true ) {
			$dirArray = array();
			if ($handle = opendir($dir) ) {
				while (false !== ($file = readdir($handle))) {
					if (($file != '.' && $file != '..' ) || $skipDots == true) {
						if($listDirectories == false) { if(is_dir($file)) { continue; } }
						array_push($dirArray, basename($file) );
					}
				}
				closedir($handle);
			}
			return $dirArray;
		}
	}

    // start from the default path
    $root = trailingslashit ( WINABSPATH );
    // get the current directory
	$dir = trailingslashit ( urldecode($_POST['dir']) );

	if( file_exists($root . $dir) ) {
		$files = scandir($root . $dir);
		natcasesort($files);

        // The 2 counts for . and ..
		if( count($files) > 2 ) {
			echo "<ul class=\"jqueryDirTree\" style=\"display: none;\">";

            // return only directories
			foreach( $files as $file ) {

			    //reserved name for the thumnbnails, don't use it as folder name
                if ( $file == 'thumbs')
                    continue;

				if ( file_exists($root . $dir . $file) && $file != '.' && $file != '..' && is_dir($root . $dir . $file) ) {
					echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . esc_html($dir . $file) . "/\">" . esc_html($file) . "</a></li>";
				}
			}

			echo "</ul>";
		}
	}
    die();
}

add_action( 'wp_ajax_ngg_rebuild_unique_slugs', 'ngg_ajax_rebuild_unique_slugs' );
/**
 * This rebuild the slugs for albums, galleries and images as ajax routine, max 50 elements per request
 *
 * @since 1.7.0
 * @return string '1'
 */
function ngg_ajax_rebuild_unique_slugs() {
    global $wpdb;

    // check for correct NextGEN capability
	if ( !current_user_can('NextGEN Change options') )
		die('No access');

	$action = $_POST['_action'];
    $offset = (int) $_POST['offset'];

    switch ($action) {
        case 'images':
        	$images = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures ORDER BY pid ASC LIMIT $offset, 50", OBJECT_K);
        	if ( is_array($images) ) {
                foreach ($images as $image) {
            		//slug must be unique, we use the alttext for that
                    $image->slug = nggdb::get_unique_slug( sanitize_title( $image->alttext ), 'image', $image->pid );
                    $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->nggpictures SET image_slug= '%s' WHERE pid = '%d'" , $image->slug, $image->pid ) );
                }
            }
        break;
        case 'gallery':
        	$galleries = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY gid ASC LIMIT $offset, 50", OBJECT_K);
        	if ( is_array($galleries) ) {
                foreach ($galleries as $gallery) {
            		//slug must be unique, we use the title for that
                    $gallery->slug = nggdb::get_unique_slug( sanitize_title( $gallery->title ), 'gallery', $gallery->gid );
                    $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->nggallery SET slug= '%s' WHERE gid = '%d'" , $gallery->slug, $gallery->gid ) );
                }
            }
        break;
        case 'album':
        	$albumlist = $wpdb->get_results("SELECT * FROM $wpdb->nggalbum ORDER BY id ASC LIMIT $offset, 50", OBJECT_K);
        	if ( is_array($albumlist) ) {
                foreach ($albumlist as $album) {
            		//slug must be unique, we use the name for that
                    $album->slug = nggdb::get_unique_slug( sanitize_title( $album->name ), 'album', $album->id );
                    $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->nggalbum SET slug= '%s' WHERE id = '%d'" , $album->slug, $album->id ) );
                }
            }
        break;
    }

	die(1);
}
add_action('wp_ajax_ngg_image_check', 'ngg_ajax_image_check');
/**
 * Test for various image resolution
 *
 * @since 1.7.3
 * @return result
 */
function ngg_ajax_image_check() {

    // check for correct NextGEN capability
	if ( !current_user_can('NextGEN Upload images') )
		die('No access');

    if ( !defined('ABSPATH') )
        die('No access');

    $step = (int) $_POST['step'];

	// build the test sizes
	$sizes = array();
	$sizes[1] = array ( 'width' => 800,  'height' => 600);
	$sizes[2] = array ( 'width' => 1024, 'height' => 768);
	$sizes[3] = array ( 'width' => 1280, 'height' => 960);  // 1MP
	$sizes[4] = array ( 'width' => 1600, 'height' => 1200); // 2MP
	$sizes[5] = array ( 'width' => 2016, 'height' => 1512); // 3MP
	$sizes[6] = array ( 'width' => 2272, 'height' => 1704); // 4MP
	$sizes[7] = array ( 'width' => 2560, 'height' => 1920); // 5MP
    $sizes[8] = array ( 'width' => 2848, 'height' => 2136); // 6MP
    $sizes[9] = array ( 'width' => 3072, 'height' => 2304); // 7MP
    $sizes[10] = array ( 'width' => 3264, 'height' => 2448); // 8MP
    $sizes[11] = array ( 'width' => 4048, 'height' => 3040); // 12MP

    if ( $step < 1 || $step > 11 )
        die('No vaild value');

    // let's test each image size
    $temp = imagecreatetruecolor ($sizes[$step]['width'], $sizes[$step]['height'] );
    imagedestroy ($temp);

    $result = array ('stat' => 'ok', 'message' => sprintf(__('Could create image with %s x %s pixel', 'nggallery'), $sizes[$step]['width'], $sizes[$step]['height'] ) );

	header('Content-Type: application/json; charset=' . get_option('blog_charset'), true);
	echo json_encode($result);

    die();
}

add_action('wp_ajax_ngg_test_head_footer', 'ngg_ajax_test_head_footer');
/**
 * Check for the header / footer, parts taken from Matt Martz (http://sivel.net/)
 *
 * @see https://gist.github.com/378450
 * @since 1.7.3
 * @return result
 */
function ngg_ajax_test_head_footer() {

	// Build the url to call, NOTE: uses home_url and thus requires WordPress 3.0
	$url = add_query_arg( array( 'test-head' => '', 'test-footer' => '' ), home_url() );
	// Perform the HTTP GET ignoring SSL errors
	$response = wp_remote_get( $url, array( 'sslverify' => false ) );
	// Grab the response code and make sure the request was sucessful
	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code == 200 ) {
		global $head_footer_errors;
		$head_footer_errors = array();

		// Strip all tabs, line feeds, carriage returns and spaces
		$html = preg_replace( '/[\t\r\n\s]/', '', wp_remote_retrieve_body( $response ) );

		// Check to see if we found the existence of wp_head
		if ( ! strstr( $html, '<!--wp_head-->' ) )
			die('Missing the call to wp_head() in your theme, contact the theme author');
		// Check to see if we found the existence of wp_footer
		if ( ! strstr( $html, '<!--wp_footer-->' ) )
			die('Missing the call to wp_footer() in your theme, contact the theme author');
	}
    die('success');
}
