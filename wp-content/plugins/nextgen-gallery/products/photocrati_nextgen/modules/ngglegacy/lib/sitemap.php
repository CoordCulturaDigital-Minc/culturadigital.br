<?php
/**
* Main PHP Class for XML Image Sitemaps
*
* @author 		Alex Rabe
* @version      1.0
* @copyright 	Copyright 2011
*
*/
class nggSitemaps {

    var $images	= array();

    /**
     * nggSitemaps::__construct()
     *
     * @return
     */
    function __construct() {

        add_filter('wpseo_sitemap_urlimages', array( &$this, 'add_wpseo_xml_sitemap_images'), 10, 2);

    }

    /**
     * Filter support for WordPress SEO by Yoast 0.4.0 or higher ( http://wordpress.org/extend/plugins/wordpress-seo/ )
     *
     * @since Version 1.8.0
     * @param array $images
     * @param int $post ID
     * @return array $image list of all founded images
     */
    function add_wpseo_xml_sitemap_images( $images, $post_id )  {

        $this->images = $images;

        // first get the content of the post/page
        $p = get_post($post_id);

        // Backward check for older images
        $p->post_content = NextGEN_Shortcodes::convert_shortcode($p->post_content);

        // Don't process the images in the normal way
  		remove_all_shortcodes();

        // We cannot parse at this point a album, just galleries & single images
		C_NextGen_Shortcode_Manager::add( 'singlepic', array(&$this, 'add_images' ) );
		C_NextGen_Shortcode_Manager::add( 'thumb', array(&$this, 'add_images' ) );
		C_NextGen_Shortcode_Manager::add( 'nggallery', array(&$this, 'add_gallery') );
		C_NextGen_Shortcode_Manager::add( 'imagebrowser', array(&$this, 'add_gallery' ) );
		C_NextGen_Shortcode_Manager::add( 'slideshow', array(&$this, 'add_gallery' ) );

        // Search now for shortcodes
        do_shortcode( $p->post_content );

        return $this->images;
    }

    /**
     * Parse the gallery/imagebrowser/slideshow shortcode and return all images into an array
     *
     * @param string $atts
     * @return
     */
    function add_gallery( $atts ) {

        global $wpdb;

        extract(shortcode_atts(array(
            'id'        => 0
        ), $atts ));

        // backward compat for user which uses the name instead, still deprecated
        if( !is_numeric($id) )
            $id = $wpdb->get_var( $wpdb->prepare ("SELECT gid FROM $wpdb->nggallery WHERE name = '%s' ", $id) );

        $images = nggdb::get_gallery($id, 'pid', 'ASC', true, 1000);

        foreach ($images as $image) {
            $newimage = array();
            $newimage['src']   = $newimage['sc'] = $image->imageURL;
            if ( !empty($image->title) )
                $newimage['title'] = $image->title;
            if ( !empty($image->alttext) )
                $newimage['alt']   = $image->alttext;
            $this->images[] = $newimage;
        }

        return '';
    }

    /**
     * Parse the single image shortcode and return all images into an array
     *
     * @param array $atts
     * @return
     */
    function add_images( $atts ) {

        extract(shortcode_atts(array(
            'id'        => 0
        ), $atts ));

        // make an array out of the ids (for thumbs shortcode))
        $pids = explode( ',', $id );

        // Some error checks
        if ( count($pids) == 0 )
            return;

        $images = nggdb::find_images_in_list( $pids );

        foreach ($images as $image) {
            $newimage = array();
            $newimage['src']   = $newimage['sc'] = $image->imageURL;
            if ( !empty($image->title) )
                $newimage['title'] = $image->title;
            if ( !empty($image->alttext) )
                $newimage['alt']   = $image->alttext;
            $this->images[] = $newimage;
        }

        return '';
    }

}
$nggSitemaps = new nggSitemaps();
