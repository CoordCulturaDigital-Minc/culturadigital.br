<?php

/**
 * Copyright (c) 2010 Ministério da Cultura do Brasil
 *
 * Written by Marcelo Mesquita <marcelo.costa@cultura.gov.br>
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
 */

class Widget_HeadLines extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-07-17
	 * @updated 2010-01-27
	 * @param   array $args - widget structure
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function widget( $args, $instance )
	{
		global $post;

		// load posts
		$headlines_loop = new HL_Query( "showposts={$instance[ 'showposts' ]}&headline_category={$instance[ 'category' ]}" );

		// show posts
		if( $headlines_loop->have_posts() )
		{
			print $args[ 'before_widget' ];

			if( !empty( $instance[ 'title' ] ) ) print $args[ 'before_title' ] . $instance[ 'title' ] . $args[ 'after_title' ];

			$before_loop = $instance[ 'before_loop' ];

			// está pegando o link do loop padrão
			//$before_loop = preg_replace_callback( '/\{next ?(text=[\'\"]([^\}]+)[\'\"])?\}/', create_function( '$matches', 'return get_next_posts_link( $matches[ 2 ] );' ), $before_loop );
			//$before_loop = preg_replace_callback( '/\{prev ?(text=[\'\"]([^\}]+)[\'\"])?\}/', create_function( '$matches', 'return get_previous_posts_link( $matches[ 2 ] );' ), $before_loop );

			print $before_loop;

			while( $headlines_loop->have_posts() )
			{
				$headlines_loop->the_post();

				$loop = $instance[ 'loop' ];

				$loop = str_replace( '{ID}', get_the_ID(), $loop );
				$loop = preg_replace_callback( '/\{title ?(length=[\'\"]?([0-9]+)[\'\"]?)?\}/U', create_function( '$matches', 'return limit_chars( get_the_title(), ( \$matches[ 2 ] ) );' ), $loop );
				$loop = str_replace( '{permalink}', get_permalink(), $loop );
				$loop = preg_replace_callback( '/\{excerpt ?(length=[\'\"]?([0-9]+)[\'\"]?)?\}/U', create_function( '$matches', 'return limit_chars( get_the_excerpt(), ( \$matches[ 2 ] ) );' ), $loop );
				$loop = str_replace( '{content}', get_the_content(), $loop );
				$loop = str_replace( '{author}', get_the_author(), $loop );
				$loop = str_replace( '{author-permalink}', get_author_posts_url( $post->post_author ), $loop );
				$loop = str_replace( '{categories}', get_the_category_list( ', ' ), $loop );
				$loop = str_replace( '{tags}', get_the_tag_list( '', ', ', '' ), $loop );
				$loop = str_replace( '{date}', get_the_time( get_option( 'date_format' ) ), $loop );
				$loop = str_replace( '{time}', get_the_time( get_option( 'time_format' ) ), $loop );
				$loop = preg_replace_callback( '/\{thumb ?(size=[\'\"](thumbnail|post-thumbnail|medium|post-medium|high|post-high)[\'\"])? ?(attr=[\'\"]([^\}]*)[\'\"])?\}/U', create_function( '$matches', 'return get_the_post_thumbnail( NULL, $matches[ 2 ], $matches[ 4 ] );' ), $loop );

				print $loop;
			}

			$after_loop = $instance[ 'after_loop' ];

			// está pegando o link do loop padrão
			//$after_loop = preg_replace_callback( '/\{next ?(text=[\'\"]([^\}]+)[\'\"])?\}/', create_function( '$matches', 'return get_next_posts_link( $matches[ 2 ] );' ), $before_loop );
			//$after_loop = preg_replace_callback( '/\{prev ?(text=[\'\"]([^\}]+)[\'\"])?\}/', create_function( '$matches', 'return get_previous_posts_link( $matches[ 2 ] );' ), $after_loop );

			$after_loop = str_replace( '{index}', $this->loop_index( $headlines_loop ), $after_loop );

			print $after_loop;

			print $args[ 'after_widget' ];
		}
	}

	/**
	 * update data
	 *
	 * @name    update
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-07-17
	 * @updated 2009-12-04
	 * @param   array $new_instance - new values
	 * @param   array $old_instance - old values
	 * @return  array
	 */
	function update( $new_instance, $old_instance )
	{
		if( empty( $new_instance[ 'showposts' ] ) or !is_numeric( $new_instance[ 'showposts' ] ) )
			$new_instance[ 'showposts' ] = 5;

		if( empty( $new_instance[ 'loop' ] ) )
		{
			$loop_model = get_option( 'loop_model' );

			if( empty( $loop_model ) )
			{
				$loop_model = '<p class="post-meta">{categories}</p><p class="post-meta">{date} - {time}</p>{thumb size="thumbnail" attr="class=alignleft"}<h3 class="post-title"><a href="{permalink}" title="{title}">{title}</a></h3><div class="entry">{excerpt}</div><p class="post-meta">por <a href="{author-permalink}" title="{author}">{author}</a></p>';

				update_option( 'loop_model', $loop_model );
			}

			$new_instance[ 'loop' ] = $loop_model;
		}

		return $new_instance;
	}

	/**
	 * widget options form
	 *
	 * @name    form
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-07-17
	 * @updated 2009-12-15
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function form( $instance )
	{
		global $wpdb;

		?>
			<p>
				<label for="<?php print $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?>:</label>
				<input type="text" id="<?php print $this->get_field_id( 'title' ); ?>" name="<?php print $this->get_field_name( 'title' ); ?>" maxlength="26" value="<?php print $instance[ 'title' ]; ?>" class="widefat" />
			</p>

			<p>
				<label for="<?php print $this->get_field_id( 'category' ); ?>"><?php _e( 'Category' ); ?>:</label>
				<?php $categories = $wpdb->get_results( "SELECT t.slug, tt.term_taxonomy_id FROM {$wpdb->terms} as t INNER JOIN {$wpdb->term_taxonomy} as tt ON (t.term_id = tt.term_id) WHERE tt.taxonomy = 'headline_category'" ); ?>
				<select id="<?php print $this->get_field_id( 'category' ); ?>" name="<?php print $this->get_field_name( 'category' ); ?>" class="widefat">
				<?php foreach( $categories as $category ) : ?>
					<option value="<?php print $category->term_taxonomy_id; ?>" <?php if( $instance[ 'category' ] == $category->term_taxonomy_id ) print 'selected="selected"'; ?>><?php print $category->slug; ?></option>
				<?php endforeach; ?>
				</select>
			</p>

			<p>
				<label for="<?php print $this->get_field_id( 'showposts' ); ?>"><?php _e( 'Showposts' ); ?>:</label><br />
				<input type="text" id="<?php print $this->get_field_id( 'showposts' ); ?>" name="<?php print $this->get_field_name( 'showposts' ); ?>" size="2" maxlength="2" value="<?php print $instance[ 'showposts' ]; ?>" />
			</p>

			<p>
				<label for="<?php print $this->get_field_id( 'before_loop' ); ?>"><?php _e( 'Before Loop' ); ?>:</label>
				<textarea id="<?php print $this->get_field_id( 'before_loop' ); ?>" name="<?php print $this->get_field_name( 'before_loop' ); ?>" cols="23" rows="2" class="widefat"><?php print $instance[ 'before_loop' ]; ?></textarea>
				<small><?php _e( 'You can use any of this shortcodes:' ); ?> {next} {prev}</small>
			</p>

			<p>
				<label for="<?php print $this->get_field_id( 'loop' ); ?>"><?php _e( 'Loop' ); ?>:</label>
				<textarea id="<?php print $this->get_field_id( 'loop' ); ?>" name="<?php print $this->get_field_name( 'loop' ); ?>" cols="23" rows="5" class="widefat"><?php print $instance[ 'loop' ]; ?></textarea>
				<small><?php _e( 'You can use any of this shortcodes:' ); ?> {title} {permalink} {excerpt} {content} {author} {author-permalink} {categories} {tags} {date} {time} {thumb}</small>
			</p>

			<p>
				<label for="<?php print $this->get_field_id( 'after_loop' ); ?>"><?php _e( 'After Loop' ); ?>:</label>
				<textarea id="<?php print $this->get_field_id( 'after_loop' ); ?>" name="<?php print $this->get_field_name( 'after_loop' ); ?>" cols="23" rows="2" class="widefat"><?php print $instance[ 'after_loop' ]; ?></textarea>
				<small><?php _e( 'You can use any of this shortcodes:' ); ?> {next} {prev}</small>
			</p>
		<?php
	}

	/**
	 * widget options form
	 *
	 * @name    loop_index
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2010-01-26
	 * @updated 2010-01-26
	 * @param   Object $headlines_loop - the loop
	 * @return  string
	 */
	function loop_index( $headlines_loop )
	{
		// reset the loop
		$headlines_loop->rewind_posts();

		if( $headlines_loop->have_posts() )
		{
			$loop  = '<ol>';

			while( $headlines_loop->have_posts() )
			{
				$headlines_loop->the_post();

				$loop .= '<li><a href="#' . get_the_ID() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
			}

			$loop .= '</ol>';
		}

		return $loop;
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    Widget_HeadLines
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-07-17
	 * @updated 2009-12-04
	 * @return  void
	 */
	function Widget_HeadLines()
	{
		// define plugin path
		$this->path = dirname( __FILE__ ) . '/';

		// register widget
		$this->WP_Widget( 'headlines', 'HeadLines', array( 'classname' => 'widget_headlines', 'description' => __( 'Allow the creation of a headlines loop', 'widget-headlines' ) ), array( 'width' => 400 ) );

		// includes
		//if( !function_exists( 'get_the_thumb' ) )
			//include( $this->path . 'inc/the-thumb.php' );

		if( !function_exists( 'limit_chars' ) )
			include( $this->path . 'inc/limit-chars.php' );

		if( function_exists( 'add_theme_support' ) )
			add_theme_support( 'post-thumbnails' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "Widget_HeadLines" );' ) );

?>
