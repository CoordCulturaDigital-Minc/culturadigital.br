<?php
// Load wp-config
if ( !defined('ABSPATH') ) 
	require_once( dirname(__FILE__) . '/ngg-config.php');

// reference thumbnail class
include_once( nggGallery::graphic_library() );
include_once('lib/core.php');

// get the plugin options
$ngg_options = get_option('ngg_options');	

// Some parameters from the URL
if ( !isset($_GET['pid']) ) C_NextGEN_Bootstrap::shutdown();
    
$pictureID = (int) $_GET['pid'];
$mode = isset($_GET['mode']) ? $_GET['mode'] : '';

// let's get the image data
$picture  = nggdb::find_image( $pictureID );

if ( !is_object($picture) ) C_NextGEN_Bootstrap::shutdown();
    
$thumb = new ngg_Thumbnail( $picture->imagePath );

// Resize if necessary
if ( !empty($_GET['width']) || !empty($_GET['height']) ) {
 	// Sanitize
 	$w = ( !empty($_GET['width'])) ? intval($_GET['width']) : 0;
 	$h = ( !empty($_GET['height'])) ? intval($_GET['height']) : 0;
	// limit the maxium size, prevent server memory overload
	if ($w > 1920) $w = 1920;
	if ($h > 1280) $h = 1280;
    // Crop mode for post thumbnail
    if ($mode == 'crop') {
		// calculates the new dimentions for a downsampled image
        list ( $ratio_w, $ratio_h ) = wp_constrain_dimensions($thumb->currentDimensions['width'], $thumb->currentDimensions['height'], $w, $h);
        // check ratio to decide which side should be resized
        ( $ratio_h <  $h || $ratio_w ==  $w ) ? $thumb->resize(0, $h) : $thumb->resize($w, 0);
        // get the best start postion to crop from the middle    
        $ypos = ($thumb->currentDimensions['height'] - $h) / 2;
		$thumb->crop(0, $ypos, $w, $h);	
    } else
        $thumb->resize( $w, $h );   
}

// Apply effects according to the mode parameter
if ($mode == 'watermark') {
	if ($ngg_options['wmType'] == 'image') {
		$thumb->watermarkImgPath = $ngg_options['wmPath'];
		$thumb->watermarkImage($ngg_options['wmPos'], $ngg_options['wmXpos'], $ngg_options['wmYpos']); 
	} else if ($ngg_options['wmType'] == 'text') {
		$thumb->watermarkText = $ngg_options['wmText'];
		$thumb->watermarkCreateText($ngg_options['wmColor'], $ngg_options['wmFont'], $ngg_options['wmSize'], $ngg_options['wmOpaque']);
		$thumb->watermarkImage($ngg_options['wmPos'], $ngg_options['wmXpos'], $ngg_options['wmYpos']);  
	}
} else if ($mode == 'web20') {
	$thumb->createReflection(40,40,50,false,'#a4a4a4');
}

// Show thumbnail
$thumb->show();
$thumb->destruct();

C_NextGEN_Bootstrap::shutdown();
