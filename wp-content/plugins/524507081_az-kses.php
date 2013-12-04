<?php
/*
Plugin Name: Allow embedded videos
Plugin URI: http://wpmudev.org/project/Allow-Embedded-Videos
Description: Allows videos from Google, Flickr, Youtube to be embedded into Wordpress MU.
Version: 1.0
Author: Stuart Maxwell
Author URI: http://stuart.amanzi.co.nz
*/

/*  Copyright 2008  Stuart Maxwell  (email : stuart@amanzi.co.nz)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function az_add_tags(&$content) {
    $content += array(
        'object' => array(
            'width' => array(),
            'height' => array(),
            'data' => array(),
            'type' => array(),
            'classid' => array(),
            ), 
        'param' => array(
            'name' => array(),
            'value' => array(),
            ), 
        'embed' => array(
            'src' => array(),
            'type' => array(),
            'bgcolor' => array(),
            'allowfullscreen' => array(),
            'flashvars' => array(),
            'wmode' => array(),
            'width' => array(),
            'height' => array(),
            'style' => array(),
            'id' => array(),
            'flashvars' => array(),
            )
        );
    return $content;
}
add_filter('edit_allowedposttags', 'az_add_tags');
?>