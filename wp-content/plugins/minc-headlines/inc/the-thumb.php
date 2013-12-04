<?php

/**
 * Copyright (c) 2009 Marcelo Mesquita
 *
 * Written by Marcelo Mesquita <stallefish@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 *
 * Public License can be found at http://www.gnu.org/copyleft/gpl.html
 *
 * Function Name: The Thumb
 * Function URI: http://marcelomesquita.com/the-thumb
 * Description: Returns the first image of a post
 * Author: Marcelo Mesquita
 * Author URI: http://marcelomesquita.com/
 * Version: 0.3
 */

function the_thumb( $size = 'thumbnail', $add = '' )
{
	print get_the_thumb( $size, $add );
}

function get_the_thumb( $size = 'thumbnail', $add = '' )
{
  global $wpdb, $post;

  $thumb = $wpdb->get_row( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_parent = {$post->ID} AND post_mime_type LIKE 'image%' ORDER BY menu_order" );

  if( !empty( $thumb ) )
  {
    $image = image_downsize( $thumb->ID, $size );

    return "<img src='{$image[0]}' alt='{$thumb->post_title}' {$add} />";
  }
}
?>
