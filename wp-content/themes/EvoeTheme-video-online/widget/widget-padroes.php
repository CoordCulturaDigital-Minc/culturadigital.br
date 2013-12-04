<?php

/**
 * Copyright (c) 2010 Faracy
 *
 * Written by Fabiano Rangel Cidade <fabiano.rangel@gmail.com>
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
 * Plugin Name: Widget Padrões
 * Plugin URI: http://faracy.com.br/
 * Description: Display categories
 * Author: Fabiano
 * Version: 0.1
 * Author URI: http://www.faracy.com.br/
 */

class Widget_Padroes extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Fabiano Rangel Cidade <fabiano.rangel@gmail.com>
	 * @since   2010-04-21
	 * @updated 2010-04-21
	 * @param   array $args - widget structure
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function widget( $args, $instance )
	{		
			?>
<div class="wrapper">
	<div class="outer">
		<div class="inner">
			<div class="widget widget_categories" id="categories-1">
				<div class="bigTitle">
					<div class="previewPattern"></div>
					<h4>Padrões<span class="tab"></span></h4>
				</div>
		
							
				<div style="display: block;" class="tabs">
					<div class="tab">		
						<ul>									
							<li><a href="<?php print get_category_link(3614); ?>">Arquivos de vídeo</a></li>
							<li><a href="<?php print get_category_link(1650); ?>">Distribuição</a></li>
							<li><a href="<?php print get_category_link(2410); ?>">Dicas</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


			
			<?php
	}

	/**
	 * update data
	 *
	 * @name    update
	 * @author  Fabiano Rangel Cidade <fabiano.rangel@gmail.com>
	 * @since   2010-04-21
	 * @updated 2010-04-21
	 * @param   array $new_instance - new values
	 * @param   array $old_instance - old values
	 * @return  array
	 */
	function update( $new_instance, $old_instance )
	{
		return $new_instance;
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    Widget_Padroes
	 * @author  Fabiano Rangel Cidade <fabiano.rangel@gmail.com>
	 * @since   2010-04-21
	 * @updated 2010-04-21
	 * @return  void
	 */
	function Widget_Padroes()
	{
		// define plugin path
		$this->path = dirname( __FILE__ ) . '/';

		// register widget
		$this->WP_Widget( 'categorias-Padroes', 'Widget Padroes', array( 'classname' => 'Widget_Padroes', 'description' => __( 'Mostrar o widget personalizado', 'widget-categorias-Padroes' ) ) );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "Widget_Padroes" );' ) );

?>
