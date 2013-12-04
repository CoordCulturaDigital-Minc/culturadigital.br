<?php
/**
 * CategoriasFilhasWidget Class
 */

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
 * Plugin Name: Widget Categorias Extra
 * Plugin URI: http://faracy.com.br/
 * Description: Display categories
 * Author: Fabiano
 * Version: 0.1
 * Author URI: http://www.faracy.com.br/
 */ 
 
 
class CategoriasFilhasWidget extends WP_Widget {
 
    function CategoriasFilhasWidget() {
        parent::WP_Widget(false, $name = 'Categorias Filhas');    
    }
 
 
    function widget($args, $instance) {        
        extract( $args );
        $title = $instance['title'];  // titulo
        $id = $instance['id'];        // id
 
        ?>

<div class="wrapper">
	<div class="outer">
		<div class="inner">
			<div class="widget widget_categories" id="categories-1">
				<div class="bigTitle">
					<div class="previewPattern"></div>
					<h4><?php print $instance['title']; ?><span class="tab"></span></h4>
				</div>
				
				<div style="display: block;" class="tabs">
					<div class="tab">		
						<ul>
							<?php wp_list_categories( "title_li=&depth=1&number=10&orderby=ID&order=desc&child_of=$id" ); ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>			


        <?php
    }
 
    function update($new_instance, $old_instance) {                
        return $new_instance;
    }
 
    function form($instance) {                
        $title = esc_attr($instance['title']);
        $id = esc_attr($instance['id']);
        ?>
            <p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titulo'); ?> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
				</label>
			</p>
            <p><label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('ID da categoria mãe'); ?> <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" /></label></p>

        <?php 
    }
 
} 
 
//function get_post_data($postId) { // the loop
           // global $wpdb;
           // return $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=$postId");
//}
 // register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "CategoriasFilhasWidget" );' ) );

?>