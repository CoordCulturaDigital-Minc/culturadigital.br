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
 * * Plugin Name: MinC HeadLines
 * Plugin URI: http://xemele.cultura.gov.br/
 * Description: Allow the editor chose the posts order, besides the chronological order.
 * Author: Marcelo Mesquita
 * Version: 0.3
 * Author URI: http://xemele.cultura.gov.br/
 */

class HeadLines
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * create the plugin permissions
	 *
	 * @name    install
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @return  void
	 */
	function install()
	{
		// get a profile
		$role = get_role( 'administrator' );

		// add capability to selected profile
		$role->add_cap( 'Manage HeadLines' );
		$role->add_cap( 'Manage HeadLines Categories' );

		// get a profile
		$role = get_role( 'editor' );

		// add capability to selected profile
		$role->add_cap( 'Manage HeadLines' );

		// add a headline category
		//$this->update_headline_category( 0, 'Headline' );
	}

	/**
	 * remove the plugin permissions
	 *
	 * @name    uninstall
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @return  void
	 */
	function uninstall()
	{
		global $wp_roles;

		// delete plugins capabilities
		foreach( $wp_roles->role_names as $role => $rolename )
		{
			$wp_roles->role_objects[ $role ]->remove_cap( 'Manage HeadLines' );
			$wp_roles->role_objects[ $role ]->remove_cap( 'Manage HeadLines Categories' );
		}
	}

	/**
	 * load the plugin necessary scripts
	 *
	 * @name    admin_scripts
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-12-09
	 * @updated 2009-12-09
	 * @return  void
	 */
	function admin_scripts()
	{
		$blog_url = get_bloginfo( 'url' );

		$plugin_url = str_replace( ABSPATH, $blog_url . '/', $this->path );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'headlines', $plugin_url . 'js/headlines.js', array( 'jquery-ui-sortable' ) );
	}

	/**
	 * load the plugin necessary styles
	 *
	 * @name    admin_styles
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-12-09
	 * @updated 2009-12-09
	 * @return  void
	 */
	function admin_styles()
	{
		$blog_url = get_bloginfo( 'url' );

		$plugin_url = str_replace( ABSPATH, $blog_url . '/', $this->path );

		wp_enqueue_style( 'headlines', $plugin_url . 'css/headlines.css' );
	}

	/**
	 * create the administrative menus
	 *
	 * @name    menu
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @return  void
	 */
	function menus()
	{
		// add_submenu_page($parent, $page_title, $menu_title, $access_level, $file, $function = '')
		$manage_headlines_page = add_submenu_page( 'edit.php', __( 'HeadLines', 'headlines' ), __( 'HeadLines', 'headlines' ), 'Manage HeadLines', 'manage-headlines', array( &$this, 'show_headlines' ) );
		$manage_headlines_categories_page = add_submenu_page( 'edit.php', __( 'HeadLines Categories', 'headlines' ), __( 'HeadLines Categories', 'headlines' ), 'Manage HeadLines Categories', 'manage-headlines-categories', array( &$this, 'show_headlines_categories' ) );

		// load scripts
		add_action( "admin_print_scripts-{$manage_headlines_page}", array( &$this, 'admin_scripts' ) );

		// load styles
		add_action( "admin_print_styles-{$manage_headlines_page}", array( &$this, 'admin_styles' ) );
	}

	/**
	 * manage headlines
	 *
	 * @name    manage_healines
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-12-08
	 * @updated 2009-12-10
	 * @return  void
	 */
	function manage_headlines()
	{
		$action = $_REQUEST[ 'headlines-action' ];

		if( empty( $action ) )
			$action = $_REQUEST[ 'headlines-action2' ];

		switch( $action )
		{
			case 'order-headlines' :
				$this->order_headlines();
		}
	}

	/**
	 * show headlines
	 *
	 * @name    show_healines
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-12-08
	 * @updated 2009-12-09
	 * @return  void
	 */
	function show_headlines()
	{
		// check permissions
		if( !current_user_can( 'Manage HeadLines' ) )
			return false;

		global $wpdb;

		// get headlines categories
		$categories = $wpdb->get_results( "SELECT t.term_id, t.name, t.slug FROM {$wpdb->terms} AS t INNER JOIN {$wpdb->term_taxonomy} AS tt ON (t.term_id = tt.term_id) WHERE taxonomy = 'headline_category'" );

		// set headline category
		$id = $_REQUEST[ 'headlines_categories_id' ];

		// set defaul category for this user
		if( empty( $id ) )
		{
			if( current_user_can( 'Manage HeadLines Categories' ) )
			{
				$id = $categories[ 0 ]->term_id;
			}
			else
			{
				foreach( $categories as $category )
				{
					if( current_user_can( "HeadLines {$category->term_id}" ) )
					{
						$id = $category->term_id;

						break;
					}
				}
			}
		}

		// get headlines from this category
		$headlines_posts = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title, term_order FROM {$wpdb->posts} JOIN {$wpdb->term_relationships} ON ( ID = object_id ) WHERE term_taxonomy_id = %d ORDER BY term_order ASC", $id ) );
		foreach( $headlines_posts as $headline_post ) $ordered_posts[ $headline_post->term_order ] = $headline_post;

		?>
			<div class="wrap nosubsub">
				<h2><?php _e( 'HeadLines', 'headlines' ); ?></h2>

				<form id="posts-filter" action="edit.php" method="get">
					<input type="hidden" name="page" value="manage-headlines" />
					<input type="hidden" id="headline-category" name="headline-category" value="<?php print $id; ?>" />
					<div class="tablenav">
						<div class="alignleft actions">
							<select name="headlines-categories-id">
								<?php foreach( $categories as $category ) : ?>
									<?php if( !current_user_can( 'Manage HeadLines Categories' ) and !current_user_can( "HeadLines {$category->term_id}" ) ) continue; ?>
									<option value="<?php print $category->term_id; ?>" <?php if( $id == $category->term_id ) print 'selected="selected"'; ?>><?php print $category->name; ?></option>
								<?php endforeach; ?>
							</select>
							<input type="submit" value="<?php _e( 'Filter' ); ?>" class="button-secondary action">
						</div>
						<div id="headlines-loading" style="display:none;"></div>
					</div>
				</form>

				<h3><?php foreach( $categories as $category ) if( $id == $category->term_id ) print $category->name; ?></h3>

				<ul id="headlines-sortable">
					<?php for( $a = 1; $a < 10; $a++ ) : ?>
						<li>
							<input type="hidden" id="order" name="order" value="<?php print $a; ?>" size="4" />
							<input type="hidden" id="post-id" name="post-id" value="<?php print ( empty( $ordered_posts[ $a ]->ID ) ) ? '0' : $ordered_posts[ $a ]->ID; ?>" size="4" />
							<?php print ( empty( $ordered_posts[ $a ]->ID ) ) ? _e( 'No posts' ) : $ordered_posts[ $a ]->post_title; ?>
							<div class="headlines-row-actions">
								<?php if( empty( $ordered_posts[ $a ]->ID ) ) : ?>
									<a href="post-new.php" class="headlines-add"><?php _e( 'Add' ); ?></a>
								<?php else : ?>
									<a href="post.php?action=edit&post=<?php print $ordered_posts[ $a ]->ID; ?>" class="headlines-edit"><?php _e( 'Edit' ); ?></a> |
									<a href="edit.php?headlines-action=delete-headline&post_id=<?php print $ordered_posts[ $a ]->ID; ?>" class="headlines-delete"><?php _e( 'Clear' ); ?></a>
								<?php endif; ?>
							</div>
						</li>
					<?php endfor; ?>
				</ul>
			</div>
		<?php
	}

	/**
	 * order headlines
	 *
	 * @name    order_headlines
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-12-10
	 * @updated 2009-12-11
	 * @return  void
	 */
	function order_headlines()
	{
		// check permissions
		if( !current_user_can( 'Manage HeadLines' ) )
			return false;

		global $wpdb;

		$headline_category = $_POST[ 'headline-category' ];
		$ordered_headlines = $_POST[ 'order' ];

		$term_taxonomy_id = $wpdb->get_var( $wpdb->prepare( "SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy} WHERE term_id = %d", $headline_category ) );

		// remove old post from this headline category
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d", $term_taxonomy_id ) );

		for( $a = 0; $a < 5; $a++ )
		{
			$order = $a + 1;

			// add new post for this position
			if( !empty( $ordered_headlines[ $a ] ) )
				$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->term_relationships} ( object_id, term_taxonomy_id, term_order ) VALUES ( %d, %d, %d )", $ordered_headlines[ $a ], $term_taxonomy_id, $order ) );
		}
	}

	/**
	 * manage headlines categories
	 *
	 * @name    manage_healines_categories
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @return  void
	 */
	function manage_headlines_categories()
	{
		$action = $_REQUEST[ 'headlines-action' ];

		if( empty( $action ) )
			$action = $_REQUEST[ 'headlines-action2' ];

		$id     = $_REQUEST[ 'headlines-categories-id' ];
		$name   = $_REQUEST[ 'headlines-categories-name' ];
		$slug   = $_REQUEST[ 'headlines-categories-slug' ];

		switch( $action )
		{
			case 'update' :
				$this->update_headline_category( $id, $name, $slug );
			break;

			case 'delete' :
				$this->delete_headlines_categories( $id );
			break;
		}
	}

	/**
	 * show healindes categories
	 *
	 * @name    show_headlines_categories
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @return  void
	 */
	function show_headlines_categories()
	{
		// check permissions
		if( !current_user_can( 'Manage HeadLines Categories' ) )
			return false;

		$id = $_REQUEST[ 'headlines-categories-id' ];

		?>
			<div class="wrap nosubsub">
				<h2><?php _e( 'HeadLines Categories', 'headlines' ); ?></h2>

				<div id="col-container">

					<div id="col-right">
						<div class="col-wrap">
							<?php $this->headlines_categories_list(); ?>
						</div>
					</div>

					<div id="col-left">
						<div class="col-wrap">
							<?php $this->headlines_categories_form( $id ); ?>
						</div>
					</div>

				</div>
			</div>
		<?php
	}

	/**
	 * list headlines categories
	 *
	 * @name    headlines_categories_list
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-12-07
	 * @return  void
	 */
	function headlines_categories_list()
	{
		// check permissions
		if( !current_user_can( 'Manage HeadLines Categories' ) )
			return false;

		global $wpdb;

		// get categories
		$categories = $wpdb->get_results( "SELECT t.term_id, t.name, t.slug FROM {$wpdb->terms} AS t INNER JOIN {$wpdb->term_taxonomy} AS tt ON (t.term_id = tt.term_id) WHERE taxonomy = 'headline_category'" );

		?>
			<form action="edit.php?page=manage-headlines-categories" method="get">
				<input type="hidden" name="page" value="manage-headlines-categories">

				<div class="tablenav">
					<div class="alignleft actions">
						<select name="headlines-action">
							<option value=""><?php _e( 'Bulk Actions' ); ?></option>
							<option value="delete"><?php _e( 'Delete' ); ?></option>
						</select>
						<input type="submit" class="button-secondary action" value="<?php _e( 'Apply' ) ?>" />
					</div>
					<br class="clear">
				</div>
				<div class="clear"></div>

				<table class="widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th class="check-column"><input type="checkbox"></th>
							<th><?php _e( 'Name' ); ?></th>
							<th><?php _e( 'Slug' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th class="check-column"><input type="checkbox"></th>
							<th><?php _e( 'Name' ); ?></th>
							<th><?php _e( 'Slug' ); ?></th>
						</tr>
					</tfoot>
					<tbody>
						<?php if( empty( $categories ) ) : ?>
							<tr class="alternate">
								<th colspan="2"><?php _e( 'No categories' ); ?></th>
							</tr>
						<?php else : ?>
							<?php foreach( $categories as $category ) : ?>
								<tr class="alternate">
									<th class="check-column"><input type="checkbox" name="headlines-categories-id[]" value="<?php print $category->term_id; ?>"></th>
									<td>
										<a class="row-title" href="edit.php?page=manage-headlines&headlines-categories-id=<?php print $category->term_id; ?>" title="<?php _e( 'Edit' ); ?> “<?php print $category->name; ?>”"><?php print $category->name; ?></a><br>
										<div class="row-actions">
											<span><a href="edit.php?page=manage-headlines-categories&headlines-categories-id=<?php print $category->term_id; ?>"><?php _e( 'Edit' ); ?></a> | </span>
											<span><a href="edit.php?page=manage-headlines-categories&headlines-action=delete&headlines-categories-id=<?php print $category->term_id; ?>"><?php _e( 'Delete' ); ?></a></span>
										</div>
									</td>
									<td><?php print $category->slug; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>

				<div class="tablenav">
					<div class="alignleft actions">
						<select name="headlines-action2">
							<option value=""><?php _e( 'Bulk Actions' ); ?></option>
							<option value="delete"><?php _e( 'Delete' ); ?></option>
						</select>
						<input type="submit" name="submit" class="button-secondary action" value="<?php _e( 'Apply' ); ?>" />
					</div>
					<br class="clear">
				</div>

			</form>
		<?php
	}

	/**
	 * show the headlines categories form
	 *
	 * @name    headlines_categories_form
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @param   int $id term identifier
	 * @return  void
	 */
	function headlines_categories_form( $id = '' )
	{
		// check permissions
		if( !current_user_can( 'Manage HeadLines Categories' ) )
			return false;

		global $wpdb;

		// get term data
		if( !empty( $id ) )
			$category = $wpdb->get_row( $wpdb->prepare( "SELECT term_id, name, slug FROM {$wpdb->terms} WHERE term_id = %d", $id ) );

		?>
			<div class="form-wrap">
				<h3><?php _e( 'Add Category' ); ?></h3>
				<form action="edit.php?page=manage-headlines-categories" method="get">
					<input type="hidden" name="page" value="manage-headlines-categories" />
					<input type="hidden" name="headlines-action" value="update" />
					<input type="hidden" name="headlines-categories-id" value="<?php print $category->term_id; ?>" />

					<div class="form-field form-required">
						<label for="headlines-categories-name"><?php _e( 'Category Name' ); ?></label>
						<input type="text" name="headlines-categories-name" id="headlines-categories-name" value="<?php print $category->name; ?>" size="40" aria-required="true">
				    <p><?php _e( 'The name is used to identify the category almost everywhere, for example under the post or in the category widget.' ); ?></p>
					</div>

					<div class="form-field">
						<label for="headlines-categories-slug"><?php _e( 'Category Slug' ); ?></label>
						<input type="text" name="headlines-categories-slug" id="headlines-categories-slug" value="<?php print $category->slug; ?>" size="40">
				    <p><?php _e( 'The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.' ); ?></p>
					</div>

					<p class="submit"><input type="submit" name="submit" class="button" value="<?php ( !empty( $id ) ) ? _e( 'Edit Category' ) : _e( 'Add Category' ); ?>"></p>
				</form>
			</div>
		<?php
	}

	/**
	 * update headline category
	 *
	 * @name    update_headline_category
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @param   int $id term identifier
	 * @param   string $name term name
	 * @param   string $slug term slug
	 * @return  void
	 */
	function update_headline_category( $id, $name, $slug = '' )
	{
		// check permissions
		if( !current_user_can( 'Manage HeadLines Categories' ) )
			return false;

		global $wpdb;

		// sanitize name
		$name = stripslashes( $name );

		// check the name
		if( empty( $name ) )
		{
			// redirect
			header( 'Location: edit.php?page=manage-headlines-categories' );

			return false;
		}

		// sanitize slug
		$slug = sanitize_title( $slug );

		// use $name if $slug is empty
		if( empty( $slug ) )
			$slug = sanitize_title( $name );

		// rename slug if duplicated
		if( $wpdb->get_var( $wpdb->prepare( "SELECT slug FROM {$wpdb->terms} WHERE slug = %s AND term_id <> %d", $slug, $id ) ) )
			$slug = $slug . '-' . time();

		if( !empty( $id ) )
		{
			// update term
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->terms} SET name = %s, slug = %s WHERE term_id = %d", $name, $slug, $id ) );
		}
		else
		{
			// insert term
			if( $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->terms} ( name, slug ) VALUES ( %s, %s )", $name, $slug ) ) )
			{
				$id = $wpdb->insert_id;

				// create taxonomy
				$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->term_taxonomy} ( term_id, taxonomy ) VALUES ( %d, 'headline_category' )", $id ) );

				// get a profile
				$role = get_role( 'administrator' );

				// add capability to selected profile
				$role->add_cap( "HeadLines {$id}" );
			}
		}

		// redirect
		header( 'Location: edit.php?page=manage-headlines-categories' );
	}

	/**
	 * delete headline category
	 *
	 * @name    delete_headline_category
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @param   mixed $id term identifier
	 * @return  void
	 */
	function delete_headlines_categories( $id )
	{
		// check permissions
		if( !current_user_can( 'Manage HeadLines Categories' ) )
			return false;

		global $wpdb;

		// simple delete
		if( is_numeric( $id ) )
		{
			// delete term
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->terms} WHERE term_id = %d" , $id ) );

			// delete taxonomy
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->term_taxonomy} WHERE term_id = %d", $id ) );

			// delete capabilities
		}
		// bulk delete
		elseif( is_array( $id ) )
		{
			// prepare sql
			foreach( $id as $term_id )
			{
				if( !empty( $where ) )
					$where .= ' OR ';

				$where .= "term_id = '{$term_id}'";

				// delete capabilities
			}

			// delete terms
			$wpdb->query( "DELETE FROM {$wpdb->terms} WHERE {$where}" );

			// delete taxonomies
			$wpdb->query( "DELETE FROM {$wpdb->term_taxonomy} WHERE {$where}" );
		}

		// redirect
		header( 'Location: edit.php?page=manage-headlines-categories' );
	}

	/**
	 * register metaboxes
	 *
	 * @name    metaboxes
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @return  void
	 */
	function metaboxes()
	{
		add_meta_box( 'headlines', __( 'HeadLines', 'headlines' ), array( &$this, 'headlines_metabox' ), 'post', 'side', 'high' );
	}

	/**
	 * headlines metabox
	 *
	 * @name    headlines_metabox
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @param   Object $post post data
	 * @return  void
	 */
	function headlines_metabox( $post )
	{
		// check permissions
		if( !current_user_can( 'Manage HeadLines' ) )
			return false;

		global $wpdb;

		// get postmetas
		$title = get_post_meta( $post->ID, 'headline-title', true );
		$excerpt = get_post_meta( $post->ID, 'headline-excerpt', true );

		// get headlines categories
		$categories = $wpdb->get_results( "SELECT t.term_id, tt.term_taxonomy_id, t.name, t.slug FROM {$wpdb->terms} as t INNER JOIN {$wpdb->term_taxonomy} as tt ON (t.term_id = tt.term_id) WHERE tt.taxonomy = 'headline_category'" );

		?>
		<input type="hidden" name="headlines-nonce" id="headlines-nonce" value="<?php print wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

		<p>
			<label for="headline-title"><?php print __( 'HeadLine title', 'headlines' ); ?></label>
			<input type="text" name="headline-title" id="headline-title" value="<?php print $title; ?>" class="widefat" />
		</p>

		<p>
			<label for="headline-excerpt"><?php print __( 'HeadLine excerpt', 'headlines' ); ?></label>
			<input type="text" name="headline-excerpt" id="headline-excerpt" value="<?php print $excerpt; ?>" class="widefat" />
		</p>

		<p>
			<strong><?php print __( 'HeadLine Categories', 'headlines' ); ?></strong><br />
		</p>

		<?php foreach( $categories as $category ) : ?>
			<?php if( !current_user_can( 'Manage HeadLines Categories' ) and !current_user_can( "HeadLines {$category->term_id}" ) ) continue; ?>

			<?php $headline_posts = $wpdb->get_results( $wpdb->prepare( "SELECT post_title, term_order FROM {$wpdb->posts} JOIN {$wpdb->term_relationships} ON ( ID = object_id ) WHERE term_taxonomy_id = %d ORDER BY term_order ASC", $category->term_taxonomy_id ) ); ?>
			<?php $ordered_posts = null; foreach( $headline_posts as $headline_post ) $ordered_posts[ $headline_post->term_order ] = $headline_post->post_title; ?>

			<?php $current_post_order = $wpdb->get_var( $wpdb->prepare( "SELECT term_order FROM {$wpdb->term_relationships} WHERE object_id = %d AND term_taxonomy_id = %d", $post->ID, $category->term_taxonomy_id ) ); ?>
			<p>
				<?php print $category->name; ?><br />
				<select name="<?php print $category->slug; ?>-order" id="<?php print $category->slug; ?>-order" class="widefat">
					<option value="out"><?php _e( 'out', 'headlines' ); ?></option>
					<?php for( $a = 1; $a < 10; $a++ ) : ?>
						<option value="<?php print $a; ?>" <?php if( $a == $current_post_order ) print 'selected="selected"'; ?>><?php print $a; if( !empty( $ordered_posts[ $a ] ) ) print ' - ' . substr( $ordered_posts[ $a ], 0, 30 ); ?></option>
					<?php endfor; ?>
				</select><br />
			</p>
		<?php endforeach; ?>

		<?php
	}

	/**
	 * save headline
	 *
	 * @name    save_headline
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @param   int $post_id post identifier
	 * @return  void
	 */
	function save_headlines( $post_id )
	{
		// check authorization
		if( !wp_verify_nonce( $_POST['headlines-nonce'], plugin_basename( __FILE__ ) ) )
			return $post_id;

		// check permission
		if( !current_user_can( 'Manage HeadLines' ) )
			return false;

		global $wpdb;

		// get headlines categories
		$categories = $wpdb->get_results( "SELECT t.slug, tt.term_taxonomy_id FROM {$wpdb->terms} as t INNER JOIN {$wpdb->term_taxonomy} as tt ON (t.term_id = tt.term_id) WHERE tt.taxonomy = 'headline_category'" );

		$title = $_POST[ 'headline-title' ];
		$excerpt = $_POST[ 'headline-excerpt' ];

		// save postmeta
		update_post_meta( $post_id, 'headline-title', $title );
		update_post_meta( $post_id, 'headline-excerpt', $excerpt );

		// re-order the posts
		foreach( $categories as $category )
		{
			$term[ $category->term_taxonomy_id ] = $_POST[ "{$category->slug}-order" ];

			// jump if category is empty
			if( empty( $term[ $category->term_taxonomy_id ] ) )
				continue;

			// if post is set to 'out', remove it from headline
			if( 'out' == $term[ $category->term_taxonomy_id ] )
			{
				$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->term_relationships} WHERE object_id = %d AND term_taxonomy_id = %d", $post_id, $category->term_taxonomy_id ) );
			}
			// add post to headline
			else
			{
				// remove old post from this position
				$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d AND term_order = %d", $category->term_taxonomy_id, $term[ $category->term_taxonomy_id ] ) );

				// add new post for this position
				$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->term_relationships} ( object_id, term_taxonomy_id, term_order ) VALUES ( %d, %d, %d )", $post_id, $category->term_taxonomy_id, $term[ $category->term_taxonomy_id ] ) );
			}
		}
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    HeadLines
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2009-01-08
	 * @updated 2009-11-23
	 * @return  void
	 */
	function HeadLines()
	{
		// define plugin path
		$this->path = dirname( __FILE__ ) . '/';

		// install
		register_activation_hook( __FILE__, array( &$this, 'install' ) );

		// uninstall
		register_deactivation_hook( __FILE__, array( &$this, 'uninstall' ) );

		// load languages
		load_plugin_textdomain( 'headlines', $this->path . 'lang' );

		// menu
		add_action( 'admin_menu', array( &$this, 'menus' ) );

		// manage ajax
		// add_action( 'wp_ajax_order-headlines', array( &$this, 'manage_headlines' ) );

		// manage headlines
		add_action( 'init', array( &$this, 'manage_headlines' ) );

		// manage headlines categories
		add_action( 'init', array( &$this, 'manage_headlines_categories' ) );

		// metaboxes
		add_action( 'do_meta_boxes', array( &$this, 'metaboxes' ) );

		// save metabox data when save posts
		add_action( 'save_post', array( &$this, 'save_headlines' ) );

		// includes
		require( $this->path . 'minc-headlines-query.php' );

		// widgets
		require( $this->path . 'minc-headlines-widget.php' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

$HeadLines = new HeadLines();

?>
