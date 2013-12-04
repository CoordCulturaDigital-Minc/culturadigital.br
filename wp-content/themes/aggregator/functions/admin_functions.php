<?php

// Options panel stylesheet
function mytheme_admin_head() { 
  if ('themes.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/functions/admin_style.css" media="screen" />';
	echo '<script type="text/javascript" src="'.get_bloginfo('template_directory').'/js/ptthemes.js"></script>';
	echo '<script type="text/javascript" src="'.get_bloginfo('template_directory').'/js/jquery-ui.js"></script>';
  } //end of theme accesibility mode
}


$options = array();
global $options;

$GLOBALS['template_path'] = get_bloginfo('template_directory');

$layout_path = TEMPLATEPATH . '/layouts/'; 
$layouts = array();

$alt_stylesheet_path = TEMPLATEPATH . '/skins/';
$alt_stylesheets = array();

    global $wpdb;
								
	$pn_categories_obj = $wpdb->get_results("SELECT $wpdb->terms.name as name, $wpdb->term_taxonomy.count as count, $wpdb->terms.term_id as cat_ID 
	                            FROM $wpdb->term_taxonomy,  $wpdb->terms
                                WHERE $wpdb->term_taxonomy.term_id =  $wpdb->terms.term_id
                                AND $wpdb->term_taxonomy.taxonomy = 'category'
								AND $wpdb->term_taxonomy.count > '0'
								ORDER BY name");
$pn_categories = array();
$pne_categories_obj = get_categories('hide_empty=0');
$pne_categories = array();
$pne_pages_obj = get_pages('sort_order=ASC');
$pne_pages = array();

if ( is_dir($layout_path) ) {
	if ($layout_dir = opendir($layout_path) ) { 
		while ( ($layout_file = readdir($layout_dir)) !== false ) {
			if(stristr($layout_file, ".php") !== false) {
				$layouts[] = $layout_file;
			}
		}	
	}
}	

if ( is_dir($alt_stylesheet_path) ) {
	if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
		while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
			if(stristr($alt_stylesheet_file, ".css") !== false) {
				$alt_stylesheets[] = $alt_stylesheet_file;
			}
		}	
	}
}	

// Categories Name Load
foreach ($pn_categories_obj as $pn_cat) {
	$pn_categories[$pn_cat->cat_ID] = $pn_cat->name;
}
$categories_tmp = array_unshift($pn_categories, "Select a category:");

// Pages Exclude Load
foreach ($pne_pages_obj as $pne_pag) {
	$pne_pages[$pne_pag->ID] = $pne_pag->post_title;
}

// Exclude Categories by Name
function category_exclude($options) {
	$options[] = array(	"type" => "wraptop");						
	
	global $wpdb;
								
	$cats = $wpdb->get_results("SELECT $wpdb->terms.name as name, $wpdb->term_taxonomy.count as count, $wpdb->terms.term_id as cat_ID 
	                            FROM $wpdb->term_taxonomy,  $wpdb->terms
                                WHERE $wpdb->term_taxonomy.term_id =  $wpdb->terms.term_id
                                AND $wpdb->term_taxonomy.taxonomy = 'category'
								AND $wpdb->term_taxonomy.count > '0'
								ORDER BY name");

	
	foreach ($cats as $cat) {	
	    if ($cat->cat_ID == '1') { $disabled = "disabled"; } else { $disabled = ""; };
			$options[] = array(	"name" => "",
						"desc" => "",
						"label" => $cat->name . " (" . $cat->count . ") &nbsp;<small style='color:#aaaaaa'>id=" . $cat->cat_ID . "</small>",
						"id" => "cat_exclude_".$cat->cat_ID,
						"std" => "",
						"disabled" => "".$disabled."",
						"type" => "checkbox");						
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Exclude Pages by Name
function pages_exclude($options) {
	$options[] = array(	"type" => "wraptop");						
	$pags = get_pages('sort_order=ASC');	
	foreach ($pags as $pag) {	
			$options[] = array(	"name" => "",
						"desc" => "",
						"label" => $pag->post_title . " &nbsp;<small style='color:#aaaaaa'>id=" . $pag->ID . "</small>",
						"id" => "pag_exclude_".$pag->ID,
						"std" => "",
						"type" => "checkbox");					
	}	
	$options[] = array(	"type" => "wrapbottom");		
	return $options;
}

// Custom Category List
function get_inc_categories($label) {	
	$include = '';
	$counter = 0;
	$catsx = get_categories('hide_empty=0');	
	foreach ($catsx as $cat) {		
		$counter++;		
		if ( get_option( $label.$cat->cat_ID ) ) {
			if ( $counter >= 1 ) { $include .= ','; }
			$include .= $cat->cat_ID;
			}	
	}	
	return $include;
}

// Custom Page List
function get_inc_pages($label) {	
	$include = '';
	$counter = 0;
	$pagsx = get_pages('sort_order=ASC');	
	foreach ($pagsx as $pag) {		
		$counter++;		
		if ( get_option( $label.$pag->ID ) ) {
			if ( $counter <> 1 ) { $include .= ','; }
			$include .= $pag->ID;
			}	
	}	
	return $include;
}

$other_entries = array("Select a Number:","0","1","2","3","4","5","6","7","8","9","10");
$feed_server = array("","http://feeds.feedburner.com","http://feeds2.feedburner.com","http://feedproxy.google.com");


// OTHER FUNCTIONS

$bm_trackbacks = array();
$bm_comments = array();

function split_comments( $source ) {

    if ( $source ) foreach ( $source as $comment ) {

        global $bm_trackbacks;
        global $bm_comments;

        if ( $comment->comment_type == 'trackback' || $comment->comment_type == 'pingback' ) {
            $bm_trackbacks[] = $comment;
        } else {
            $bm_comments[] = $comment;
        }
    }
} 

?>