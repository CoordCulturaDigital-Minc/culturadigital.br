<?php

/**
 * Copyright (c) 2010 Faracy
 *
 * Written by Fabiano Rangel Ciade - <fabiano.rangel@gmail.com>
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
 * Plugin Name: Widget Ultimas Blogadas
 * Plugin URI: http://www.faracy.com.br/
 * Description: Exibe posts dos blogs internos
 * Author: 
 * Version: 0.1
 * Author URI: http://www.faracy.com.br/
 */

class Widget_ultimas_blog extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Fabiano Rangel Cidade- <fabiano.rangel@gmail.com>
	 * @since   2012-05-29
	 * @updated 2012-05-29
	 * @param   array $args - widget structure
	 * @param   array $instance - widget data
	 * @return  void
	 */

	function widget($args, $instance)
	{
	
		?>
				
	<ul class="tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="9 ui-state-default ui-corner-top ui-tabs-selected ui-state-active ui-state-focus"><a href="#ultimas-blogadas">Últimas Blogadas na rede</a></li>
	</ul>

		
			<div class="activity">
			
				<div class="blogsTop">
					
						
						
						<?php if ( bp_has_blogs( bp_ajax_querystring( 'blogs' ).'max=10&per_page=10' ) ) : ?>
				
						<ul>
						
						<?php while ( bp_blogs() ) : bp_the_blog(); ?>
						
							<li>
							
							<div class="avatarBlog2">
							<a href="<?php bp_blog_permalink() ?>"><?php bp_blog_avatar('type=medium&width=55&height=55') ?></a></div>
							
							<div class="infoBlog">
								<span class="nomeBlog"><a href="<?php bp_blog_permalink() ?>"><?php limit_chars(bp_get_blog_name(), 36); ?></a></span>
								<br /><span class="tituloPostBlog2"><a href="<?php bp_blog_permalink() ?>" title="">
						<?php bp_blog_latest_post() ?></a></span>
							</div>
							
							</li>
						
						<?php endwhile; ?>
						
						</ul>
						
						<?php endif; ?>
						
						
											
				</div>
			</div>	
	
	


<br clear="all" />


		<?php

	;
	}

	/**
	 * update data
	 *
	 * @name    update
	 * @author  Fabiano Rangel Cidade - <fabiano.rangel@gmail.com>
	 * @since   2012-05-29
	 * @updated 2012-05-29
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
	 * @name    Widget_ultimas_blog
	 * @author  Fabiano Rangel Cidade- <fabiano.rangel@gmail.com>
	 * @since   2012-05-29
	 * @updated 2012-05-29
	 * @return  void
	 */
	function Widget_ultimas_blog()
	{
		// define plugin path
		$this->path = dirname( __FILE__ ) . '/';

		// register widget
		$this->WP_Widget( 'Widget_ultimas_blog', 'Ultimas Blogadas', array( 'classname' => 'Widget_ultimas_blog', 'description' => __( 'Exibe as ultimas blogadas da rede', 'Widget_ultimas_blog' ) ) );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "Widget_ultimas_blog" );' ) );

?>