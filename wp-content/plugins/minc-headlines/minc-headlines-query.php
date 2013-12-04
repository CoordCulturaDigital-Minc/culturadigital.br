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

class HL_Query extends WP_Query
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    HL_Query
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-27-08
	 * @updated 2009-11-23
	 * @return  void
	 */
	function HL_Query( $query )
	{
		global $wpdb;

		$ordered_posts = array();

		// transformar a query em array
		parse_str( $query, $args );

		// recuperar a categoria da manchete
		$showposts              = ( int ) $args[ 'showposts' ];
		$headline_category      = ( int ) $args[ 'headline_category' ];
		$headline_category_slug = $args[ 'headline_category_name' ];

		// se showposts estiver vazio, usar o padrão
		if( empty( $showposts ) )
			$showposts = 10;

		// se a categoria da manchete estiver vazia, procurar o slug
		if( empty( $headline_category ) )
		{
			// se o slug estiver vazio, retornar
			if( empty( $headline_category_slug ) )
			{
				return false;
			}
			else
			{
				// recuperar o id do slug
				$headline_category = $wpdb->get_var( $wpdb->prepare( "SELECT term_id FROM {$wpdb->terms} WHERE slug = %s", $headline_category_slug ) );

				// se o id estiver vazio, retornar
				if( empty( $headline_category ) )
					return false;
			}
		}

		// recuperar as manchetes dessa categoria
		$ids = $wpdb->get_col( $wpdb->prepare( "SELECT tr.object_id FROM {$wpdb->term_relationships} AS tr INNER JOIN {$wpdb->term_taxonomy} AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) WHERE tt.term_id = %d AND tt.taxonomy = 'headline_category' ORDER BY term_order LIMIT %d", $headline_category, $showposts ) );

		// se não tiver nenhuma manchete, retornar
		if( empty( $ids ) )
			return false;

		// adicionar os posts à query
		$headlines_ids = array( 'post__in' => $ids );

		// carregar as manchetes
		$this->query( $headlines_ids );

		// para cada manchete
		foreach( $this->posts as $headline_post )
		{
			// verificar a ordem
			foreach( $ids as $key => $id )
			{
				if( $headline_post->ID == $id )
				{
					// aproveitando a oportunidade para alterar alguns dados da manchete
					$headline_title = get_post_meta( $headline_post->ID, 'headline-title', true );
					$headline_excerpt = get_post_meta( $headline_post->ID, 'headline-excerpt', true );

					// alterar o título do post, caso informado
					if( !empty( $headline_title ) )
						$headline_post->post_title = $headline_title;

					// alterar a chamada do post, caso informada
					if( !empty( $headline_excerpt ) )
						$headline_post->post_excerpt = $headline_excerpt;

					// adiciona-la aos posts ordenados
					$ordered_posts[ $key ] = $headline_post;

					// pular para a próxima
					continue;
				}
			}
		}
		//print_r( $ordered_posts );

		// atualizar a lista dos posts
		$this->posts = $ordered_posts;
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////
}
