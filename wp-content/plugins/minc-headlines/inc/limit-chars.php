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
 * Function Name: Limit Chars
 * Function URI: http://marcelomesquita.com/
 * Description: Limit the quantity of characters
 * Author: Marcelo Mesquita
 * Author URI: http://marcelomesquita.com/
 * Version: 0.2
 */

function limit_chars( $content, $length )
{
	$content = strip_tags( $content );

	if( !is_numeric( $length ) or empty( $length ) )
		$length = 100;

	if( strlen( $content ) > $length )
	{
		$content = substr( $content, 0, $length );
		$content = substr( $content, 0, strrpos( $content, ' ' ) ) . '...';
	}

	return $content;
}
?>
