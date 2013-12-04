<?php
// Need to move this somewhere else - sets up the banned post slugs
if ( !get_option( 'bp_wiki_banned_page_slugs' ) ) {
	$bp_wiki_banned_slugs[] = 'edit';
	$bp_wiki_banned_slugs[] = 'history';
	$bp_wiki_banned_slugs[] = 'revision';
	$bp_wiki_banned_slugs[] = 'discussion';
	$bp_wiki_banned_slugs[] = 'new';
	update_option('bp_wiki_banned_page_slugs', serialize( $bp_wiki_banned_slugs ) );
}


/* Define a constant that can be checked to see if the component is installed or not. */
define ( 'BP_WIKI_IS_INSTALLED', 1 );

/* Define a constant that will hold the current version number of the component */
define ( 'BP_WIKI_VERSION', '1.0' );

/* Define a constant that will hold the database version number that can be used for upgrading the DB
 *
 * NOTE: When table defintions change and you need to upgrade,
 * make sure that you increment this constant so that it runs the install function again.
 *
 * Also, if you have errors when testing the component for the first time, make sure that you check to
 * see if the table(s) got created. If not, you'll most likely need to increment this constant as
 * BP_wiki_DB_VERSION was written to the wp_usermeta table and the install function will not be
 * triggered again unless you increment the version to a number higher than stored in the meta data.
 */
define ( 'BP_WIKI_DB_VERSION', '1' );

/* Define a slug constant that will be used to view this components pages (http://wiki.org/SLUG) */
if ( !defined( 'BP_WIKI_SLUG' ) )
	define ( 'BP_WIKI_SLUG', 'wiki' );
	

define ( 'BP_WIKI_GROUP_WIKI_SLUG', 'wiki' );
define ( 'BP_WIKI_GROUP_WIKI_PAGES_NAME', 'Wiki' );

define ( 'BP_WIKI_ACTIVITY_STREAM_METHOD', 'minimal' );

/* Define the plugin name and the plugin url */
define ( 'BP_WIKI_PLUGIN_NAME', 'bp-wiki' );
define ( 'BP_WIKI_PLUGIN_URL', WP_PLUGIN_URL . '/' . BP_WIKI_PLUGIN_NAME );
define ( 'BP_WIKI_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . BP_WIKI_PLUGIN_NAME );

/*
 * If you want the users of your component to be able to change the values of your other custom constants,
 * you can use this code to allow them to add new definitions to the wp-config.php file and set the value there.
 *
 *
 *	if ( !defined( 'BP_WIKI_CONSTANT' ) )
 *		define ( 'BP_WIKI_CONSTANT', 'some value' // or some value without quotes if integer );
 */

/**
 * You should try hard to support translation in your component. It's actually very easy.
 * Make sure you wrap any rendered text in __() or _e() and it will then be translatable.
 *
 * You must also provide a text domain, so translation files know which bits of text to translate.
 * Throughout this wiki the text domain used is 'bp-wiki', you can use whatever you want.
 * Put the text domain as the second parameter:
 *
 * __( 'This text will be translatable', 'bp-wiki' ); // Returns the first parameter value
 * _e( 'This text will be translatable', 'bp-wiki' ); // Echos the first parameter value
 */

if ( file_exists( dirname( __FILE__ ) . '/languages/' . get_locale() . '.mo' ) )
	load_textdomain( 'bp-wiki', dirname( __FILE__ ) . '/bp-wiki/languages/' . get_locale() . '.mo' );

/**
 * The next step is to include all the files you need for your component.
 * You should remove or comment out any files that you don't need.
 */

/* The classes file should hold all database access classes and functions */
require ( dirname( __FILE__ ) . '/bp-wiki-classes.php' );

/* The ajax file should hold all functions used in AJAX queries */
require ( dirname( __FILE__ ) . '/bp-wiki-ajax.php' );

/* Functions for processing form input (non-ajax) */
require ( dirname( __FILE__ ) . '/bp-wiki-forms.php' );

/* The cssjs file should set up and enqueue all CSS and JS files used by the component */
require ( dirname( __FILE__ ) . '/bp-wiki-cssjs.php' );

/* The templatetags file should contain classes and functions designed for use in template files */
require ( dirname( __FILE__ ) . '/bp-wiki-templatetags.php' );

/* The widgets file should contain code to create and register widgets for the component */
require ( dirname( __FILE__ ) . '/bp-wiki-widgets.php' );

/* The notifications file should contain functions to send email notifications on specific user actions */
require ( dirname( __FILE__ ) . '/bp-wiki-notifications.php' );

/* The filters file should create and apply filters to component output functions. */
require ( dirname( __FILE__ ) . '/bp-wiki-filters.php' );

/**
 * bp_wiki_setup_globals()
 *
 * Sets up global variables for your component.
 */
function bp_wiki_setup_globals() {
	global $bp, $wpdb;

	/* For internal identification */
	$bp->wiki->id = 'wiki';

	$bp->wiki->table_name = $wpdb->base_prefix . 'bp_wiki';
	$bp->wiki->format_notification_function = 'bp_wiki_format_notifications';
	$bp->wiki->slug = BP_WIKI_SLUG;

	/* Register this in the active components array */
	$bp->active_components[ $bp->wiki->slug ] = $bp->wiki->id;
}
add_action( 'bp_setup_globals', 'bp_wiki_setup_globals' );

/**
 * bp_wiki_add_admin_menu()
 *
 * This function will add a WordPress wp-admin admin menu for your component under the
 * "BuddyPress" menu.
 */
function bp_wiki_add_admin_menu() {
	global $bp;

	if ( !$bp->loggedin_user->is_site_admin )
		return false;

	require ( dirname( __FILE__ ) . '/bp-wiki-admin.php' );

	add_submenu_page( 'bp-general-settings', __( 'Wiki Site-Admin', 'bp-wiki' ), __( 'Wiki Site-Admin', 'bp-wiki' ), 'manage_options', 'bp-wiki-settings', 'bp_wiki_admin' );
}
add_action( 'admin_menu', 'bp_wiki_add_admin_menu' );


/**
 * bp_wiki_setup_nav()
 *
 * Sets up the user profile navigation items for the component. This adds the top level nav
 * item and all the sub level nav items to the navigation array. This is then
 * rendered in the template.
 */
function bp_wiki_setup_nav() {
	global $bp;

}
add_action( 'bp_setup_nav', 'bp_wiki_setup_nav' );



/**
 * bp_wiki_load_template_file()
 *
 * This function checks for a template file in the theme directory.  If it exists then the found file is used.
 * If no file is found, the template file in the plugin directory is loaded instead.
 * TODO: Credit the quickpress author that posted the original method on the $bp forums
 */
function bp_wiki_load_template_file( $file ) {

	$theme_path = STYLESHEETPATH . '/wiki';

	if ( file_exists( $theme_path . '/' . $file ) ) {
		return $theme_path . '/' . $file;
	} else {
		return BP_WIKI_PLUGIN_DIR . '/includes/templates/wiki/' . $file;
	}
}

function bp_wiki_load_template_url( $file ) {

	$theme_path = STYLESHEETPATH . '/wiki';

	if ( file_exists( $theme_path . '/' . $file ) ) {
		return $theme_url . '/' . $file;
	} else {
		return BP_WIKI_PLUGIN_URL . '/includes/templates/wiki/' . $file;
	}
}


/********************************************************************************
 * Registering "wiki" post type
 *
 *
 */
function post_type_wiki() {
    $args = array(
        'label' => 'Wiki Pages',
        'singular_label' => 'Wiki Page',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'page',
        'hierarchical' => false,
        'rewrite' => true,
        'supports' => array( 'title', 'editor', 'author', 'excerpts', 'revisions', 'comments' )
        );

    register_post_type( 'wiki' , $args );
}
add_action( 'init', 'post_type_wiki' );




/********************************************************************************
 * Activity & Notification Functions
 *
 * These functions handle the recording, deleting and formatting of activity and
 * notifications for the user and for the wiki component.
 */


/**
 * bp_wiki_record_activity()
 *
 * If the activity stream component is installed, this function will record activity items for the wiki
 * component.
 *
 */
function bp_wiki_record_activity( $args = '' ) {
	global $bp;

	if ( !function_exists( 'bp_activity_add' ) )
		return false;

	$defaults = array(
		'id' => false,
		'user_id' => $bp->loggedin_user->id,
		'action' => '',
		'content' => '',
		'primary_link' => '',
		'component' => $bp->wiki->id,
		'type' => false,
		'item_id' => false,
		'secondary_item_id' => false,
		'recorded_time' => gmdate( "Y-m-d H:i:s" ),
		'hide_sitewide' => false
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	return bp_activity_add( array( 'id' => $id, 'user_id' => $user_id, 'action' => $action, 'content' => $content, 'primary_link' => $primary_link, 'component' => $component, 'type' => $type, 'item_id' => $item_id, 'secondary_item_id' => $secondary_item_id, 'recorded_time' => $recorded_time, 'hide_sitewide' => $hide_sitewide ) );
}


/**
 * bp_wiki_activity_tag_extensions()
 *
 * Adds support for <del> html tags
 */
function bp_wiki_activity_tag_extensions( $activity_allowedtags ) {

	$activity_allowedtags['del'] = array();

	return $activity_allowedtags;
}
add_filter( 'bp_activity_allowed_tags', 'bp_wiki_activity_tag_extensions' );



/**
 * bp_wiki_diff_full()
 *
 * Takes old post_content and new post_content as params and returns html formatted diff for
 * use in activity stream updates
 */
function bp_wiki_diff_full( $old, $new ) {
 	$old = preg_replace( '/ +/', ' ', $old );
 	$new = preg_replace( '/ +/', ' ', $new );
 	$lo = explode( "\n", trim( $old ) . "\n" ); 	
	$ln = explode( "\n", trim( $new ) . "\n" ); 	
	$size = max( count( $lo ), count( $ln ) ); 	
	$ins = array_diff( $ln, $lo ); 	
	$del = array_diff( $lo, $ln ); 	
	$output = '';	
	$del_output = __( 'Edited content:', 'bp-wiki' ) . "\n";	
	$ins_output = __( 'Added content:', 'bp-wiki' ) . "\n";	
	
	$del_output_count = 1;	
	$ins_output_count = 1;	
	
	for ( $i = 0; $i < $size; $i++ ) { 
			
		if ( isset( $del[$i] ) ) { 			
			if ( $del[$i] != ' ' && $del[$i] != '&nbsp;' && $del[$i] != '' ) {
				$del_output .= $del_output_count . '. <del>' . $del[$i] . '</del>' . "\n";
				$del_output_count++;
			}
		}
		
		if ( isset( $ins[$i] ) ) {
			if ( $ins[$i] != ' ' && $ins[$i] != '&nbsp;' && $ins[$i] != '' ) {
				$ins_output .= $ins_output_count . '. ' . $ins[$i] . "\n";
				$ins_output_count++;
			}
		}
	}
	
	if ( $del_output_count == 1 && $ins_output_count == 1 ) {
	
		$output = __( '*** No changes made ***', 'bp-wiki' );
		
	} else {
	
		if ( $del_output_count > 1 ) {
		
			$output .= $del_output;
			
		}
		
		if ( $ins_output_count > 1 ) {
		
			$output .= $ins_output;
			
		}								
		
	}
	
 	return $output;
}




/**
 * bp_wiki_diff_concise()
 *
 * Takes old post_content and new post_content as params and returns html formatted diff for
 * use in activity stream updates
 */
function bp_wiki_diff_concise( $old, $new ) {
 	$old = preg_replace( '/ +/', ' ', $old );
 	$new = preg_replace( '/ +/', ' ', $new );
 	$lo = explode( "\n", trim( $old ) . "\n" ); 	
	$ln = explode( "\n", trim( $new ) . "\n" ); 	
	$size = max( count( $lo ), count( $ln ) ); 	
	$ins = array_diff( $ln, $lo ); 	
	$del = array_diff( $lo, $ln ); 	
	
	$ins_output = '';	
	
	$ins_output_count = 1;	
	
	for ( $i = 0; $i < $size; $i++ ) { 
					
		if ( isset( $ins[$i] ) ) {
			if ( $ins[$i] != ' ' && $ins[$i] != '&nbsp;' && $ins[$i] != '' ) {
				$ins_output .= $ins[$i] . "\n";
				$ins_output_count++;
			}
		}
	}
	
	if ( $ins_output_count == 1 ) {
	
		return __( '*** No changes made ***', 'bp-wiki' );
		
	} else {

		return substr( strip_tags( $ins_output ), 0, 250 );
		
	}
	
}


/**
 * bp_wiki_diff_minimal()
 *
 * Takes old post_content and new post_content as params and returns html formatted diff for
 * use in activity stream updates
 */
function bp_wiki_diff_minimal( $old, $new ) {

	// Return no content
	return '';
	
}


/**
 * Slugifies a string passed to it via $text and returns that string
 *
 * @param string   $text String to be slugified
 *
 * @return string  $text The slugified string
 */
function bp_wiki_slugified_title( $text ) {

	// replace non letter or digits by -
	$text = preg_replace( '~[^\\pL\d]+~u', '-', $text );
	// trim
	$text = trim( $text, '-' );
	// transliterate
	if ( function_exists( 'iconv' ) ) {
		$text = iconv( 'utf-8', 'us-ascii//TRANSLIT', $text );
	}
	// lowercase
	$text = strtolower( $text );
	// remove unwanted characters
	$text = preg_replace( '~[^-\w]+~', '', $text );
	if ( empty( $text ) ) {
		return __( 'n-a', 'bp-wiki' );
	}
	
	return $text;
}


/**
 * bp_wiki_remove_group_data()
 *
 * Removes the wiki pages from a deleted group.
 * In future they will be transferred to a site admin group/wiki.
 */
function bp_wiki_remove_group_data( $group ) {

	// Remember to remove group meta for this component for the user being deleted
	// delete_usermeta( $group->id, 'bp_wiki_some_setting' );

	do_action( 'bp_wiki_remove_group_data', $group->id );
}
add_action( 'bp_groups_delete_group', 'bp_wiki_remove_group_data', 1 );



/**
 * bp_wiki_get_group_page_url( $bp->groups->current_group->id, $group_wiki_page_id )
 *
 * Returns a link to the wiki page based on the group id and page id.  
 * Format is http://sitename/groups/groupname/wiki/wikipagename
 */
function bp_wiki_get_group_page_url( $group_id, $group_wiki_page_id ) {

	$wiki_group = new BP_Groups_Group( $group_id, false, false );	 	
	$group_url = bp_get_group_permalink( $wiki_group );
	$wiki_page = get_post( $group_wiki_page_id );
	$group_wiki_page_url = $group_url . BP_WIKI_SLUG . '/' . bp_wiki_remove_group_id_from_page_slug( $wiki_page->post_name );
	
	return $group_wiki_page_url;
}





/**
 * Formats a given time stamp string into a standard date format 
 * to give a consistent format 
 * 
 * @param string A time stamp string.
 *
 * @return string The formatted time stamp.
 */ 

function bp_wiki_to_wiki_date_format( $time_stamp ) {

	$formated_time_stamp = 
		sprintf( '%1$s ' . __( 'on', 'bp-wiki' ) . ' %2$s', bp_wiki_format_date( $time_stamp, __( 'H:i', 'bp-wiki' ) ), bp_wiki_format_date($time_stamp, __( 'd F Y', 'bp-wiki' ) ) );
		
	return $formated_time_stamp;
}

/**
 * Formats a given time stamp string.
 *
 * @param string A time stamp string.
 * @param string A given date format in PHP date formating notation.
 *
 * @return string The formatted time stamp.
 */ 

function bp_wiki_format_date($time_stamp, $format) {

	$date = mysql2date( $format, $time_stamp );
	
	return apply_filters( 'bp_wiki_format_date', $date, $format );
}




/**
 * bp_wiki_remove_group_id_from_page_slug( $page_slug )
 *
 * Strips out the group id prefix from the start of a group wiki page_name and returns the modified value
 * e.g. 16-test-wiki >>> test-wiki
 */
function bp_wiki_remove_group_id_from_page_slug( $page_slug ) {
	global $bp;
	
	// Get the string lenght of the group id and remove this many chars +1 (for the "-") from the start of the slug
	$wiki_page_name_prefix_length = strlen( strval( $bp->groups->current_group->id ) ) + 1;
	$looks_nice_page_slug = substr( $page_slug, $wiki_page_name_prefix_length );
	
	return $looks_nice_page_slug;
}



/**
 * bp_wiki_can_view_wiki_page( $group_wiki_page_id )
 *
 * Returns true if loggedin user can view this wiki page.  false if not.
 */
function bp_wiki_can_view_wiki_page( $group_wiki_page_id ) {

	// Placeholder
	if ( !$group_wiki_page_id ) {
		return false;
	}
	// Placeholder
	return true;
}


/**
 * bp_wiki_can_edit_wiki_page( $group_wiki_page_id )
 *
 * Returns true if loggedin user can view this wiki page.  false if not.
 */
function bp_wiki_can_edit_wiki_page( $group_wiki_page_id ) {

	// Placeholder
	if ( !$group_wiki_page_id ) {
		return false;
	}
	// Placeholder
	return true;
}



function bp_wiki_get_page_from_slug( $page_slug ) {
	global $bp;
	
	// Add the group id and a hyphen to the start of the page slug
	$full_page_slug = $bp->groups->current_group->id . '-' . $page_slug;
	
	$group_wiki_page_ids_array = maybe_unserialize( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_wiki_group_wiki_page_ids' ) );

	// Check the page slug against these pages
	if ( $group_wiki_page_ids_array ) {
	
		foreach ( $group_wiki_page_ids_array as $group_wiki_page_id ) {
		
			$wiki_page = get_post( $group_wiki_page_id );
			
			if ( $wiki_page->post_name == $full_page_slug ) {
			
				return $wiki_page;
				
			}
			
		}
		
	}
	
	return false;
}


/**
 * bp_wiki_group_breadcrumbs()
 * 
 * Returns a breadcrumb nav trail starting from the group home and ending at current page
 */
function bp_wiki_group_breadcrumbs( $bp_wiki_group_new_wiki_page = false ) {
	global $bp;
	
	$output = '<ul class="bp-wiki-group-breadcrumbs">'; 
	$wiki_group = new BP_Groups_Group( $bp->groups->current_group->id, false, false );	 	
	$group_url = bp_get_group_permalink( $wiki_group );
	$action_slug = $bp->current_action;
	
	if ( !$action_slug ) {
	
		// No action slug means that we're in the group home. Format Home as (unclickable) and that's it
		$output .= '<li class="bp-wiki-group-breadcrumbs">' . __( 'Home', 'bp-wiki' ) . '</li>';
		
	} else {
	
		// There's more to come, so the group home should be (clickable)
		$output .= '<li class="bp-wiki-group-breadcrumbs"><a href="' . $group_url . '">' . __( 'Home', 'bp-wiki' ) . '</a>&nbsp;&raquo;</li>';
		
		if ( $bp->action_variables ) {
			
			// Make wiki link (clickable)
			$output .= '<li class="bp-wiki-group-breadcrumbs"><a href="' . $group_url . $action_slug . '">' . __( 'Wiki', 'bp-wiki' ) . '</a>&nbsp;&raquo;</li>';
			
			foreach ( $bp->action_variables as $key => $bp_action_var ) {
			
				$wiki_page = bp_wiki_get_page_from_slug( $bp_action_var );
				
				if ( bp_wiki_can_view_wiki_page( $wiki_page->ID ) ) {
				
					if ( $key == ( count( $bp->action_variables ) - 1 ) ) {
					
						// This is the last item in the set, so set it as the current page (non-clickable)
						$output .= '<li class="bp-wiki-group-breadcrumbs">' . $wiki_page->post_title . '</li>';
						
					} else {
					
						// This isn't the last item in the set, so set it as previous page (clickable)
						$output .= '<li class="bp-wiki-group-breadcrumbs"><a href="' . $group_url . $action_slug . '/' . bp_wiki_remove_group_id_from_page_slug( $wiki_page->post_name ) . '">' . $wiki_page->post_title . '</a>&nbsp;&raquo;</li>';
						
					}
					
				} else {
					
					if ( $bp_action_var == 'edit' ) {
						$output .= '<li class="bp-wiki-group-breadcrumbs wiki-breadcrumb-action">' . __( '!Edit', 'bp-wiki' ) . '</li>';
						break;
					}
					
					if ( $bp_action_var == 'history' || $bp_action_var == 'revisions' ) {
						$output .= '<li class="bp-wiki-group-breadcrumbs wiki-breadcrumb-action">' . __( '!History', 'bp-wiki' ) . '</li>';
						break;
					}
					
					if ( $bp_action_var == 'discussion' ) {
						$output .= '<li class="bp-wiki-group-breadcrumbs wiki-breadcrumb-action">' . __( '!Discussion', 'bp-wiki' ) . '</li>';
						break;
					}
					
					if ( $bp_wiki_group_new_wiki_page ) {
						$output .= '<li class="bp-wiki-group-breadcrumbs wiki-breadcrumb-action">' . __( '!New Page', 'bp-wiki' ) . '</li>';
						break;
					}
					
					// User isn't allowed to see this page so return "Error" (non-clickable)
					// Alternatively, the page doesn't exist, so return "Error" (non-clickable)
					$output .= '<li class="bp-wiki-group-breadcrumbs wiki-breadcrumb-error">' . __( 'Error', 'bp-wiki' ) . '</li>';
					
					break;
					
				}
				
			}
			
		} else {  
		
			// We're just viewing the wiki home screen so wiki is (non-clickable)
			$output .= '<li class="bp-wiki-group-breadcrumbs">' . __( 'Wiki', 'bp-wiki' ) . '</li>';
		
		}
		
	}
	$output .= '</ul>';
	
	return $output;
}


/**
 * bp_wiki_create_group_wiki()
 * 
 * Creates a new group wiki based on the create screen form input
 */
function bp_wiki_create_group_wiki( $group_id ) {
	global $bp;

	// Set the group wiki homepage text
	groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_index_text', substr( $_POST['wiki-homepage-summary'], 0, 350 ) );
	
	// Set the default wiki page privacy level
	if ( $_POST['wiki-privacy'] == 'public' || $_POST['wiki-privacy'] == 'member-only' ) {
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_default_page_privacy', $_POST['wiki-privacy'] );
	} else { // Default to member-only if invalid input
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_default_page_privacy', 'member-only' );
	}
	
	// Set the default wiki page edit group priv level required
	if ( $_POST['wiki-edit-rights'] == 'all-members' || $_POST['wiki-edit-rights'] == 'moderator-only' || $_POST['wiki-edit-rights'] == 'admin-only' ) {
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_default_edit_rights', $_POST['wiki-edit-rights'] );
	} else { // Default to all group members can edit if invalid input
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_default_edit_rights', 'all-members' );
	}
	
	// Set the frontend page creation setting
	if ( $_POST['groupwiki-member-page-create'] == 'yes' ) {
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_member_page_create', $_POST['groupwiki-member-page-create'] );
	} else { // Default to disabled if answer is no or input invalid
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_member_page_create', 'no' );
	}
	
	if ( $_POST['wiki-page-title'] ) {
		foreach ( $_POST['wiki-page-title'] as $key => $wiki_page_title ) {
			// Don't create pages with blank titles
			if ( $wiki_page_title == '' ) continue;
			// Check against banned names
			if ( bp_wiki_post_slug_not_banned( bp_wiki_slugified_title( $wiki_page_title ) ) ) {
				// Create post object
				$wiki_post = array(
					'post_content' => '', 
					'post_excerpt' => __( 'No content has been added to this page yet.', 'bp-wiki' ), 
					'post_name' => $group_id . '-' . bp_wiki_slugified_title( $wiki_page_title ), 
					'post_status' => 'publish', 
					'post_title' => $wiki_page_title,
					'post_type' => 'wiki', // Custom post type - wiki
					'menu_order' => $key // Order page will appear in nav menu - lower number appears first
				); 
				// Insert the post and return the post ID
				$wiki_post_id = wp_insert_post( $wiki_post );
				if ( $wiki_post_id > 0 ) {
					// Update the post meta for wiki page view/edit access
					$wiki_post_ids_array[] = $wiki_post_id;
					update_post_meta( $wiki_post_id , 'wiki_view_access' , groups_get_groupmeta( $group_id, 'bp_wiki_group_wiki_default_page_privacy' ) );
					update_post_meta( $wiki_post_id , 'wiki_edit_access' , groups_get_groupmeta( $group_id, 'bp_wiki_group_wiki_default_edit_rights' ) );
					update_post_meta( $wiki_post_id , 'wiki_page_visible' , 'yes' );
				}
			}
		}
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_page_ids', $wiki_post_ids_array );
	}
	groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_enabled', 'yes' );
}






function bp_wiki_post_slug_not_banned( $wiki_page_slug ) {
	global $bp;
	
	$banned_slugs = maybe_unserialize( get_option( 'bp_wiki_banned_page_slugs' ) );
	
	if ( $banned_slugs ) {
	
		foreach ( $banned_slugs as $banned_slug ) {
		
			if ( $wiki_page_slug == $banned_slug ) {
			
				return false;
				
			}
			
		}
	
	}
	
	return true;
}

/**
 * bp_wiki_edit_group_wiki()
 * 
 * Changes settings of group wiki based on the edit screen form input
 */
function bp_wiki_admin_group_wiki( $group_id ) {
	global $bp;

	// Set the group wiki homepage text
	groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_index_text', substr( $_POST['wiki-homepage-summary'], 0, 350 ) );
	
	// Get the settings for all the pages from $_POST
	$group_wiki_admin_page_ids = $_POST['wiki-group-page-id'];
	$group_wiki_admin_page_privacy = $_POST['wiki-group-admin-page-privacy'];
	$group_wiki_admin_page_editing = $_POST['wiki-group-admin-page-edit-rights'];
	$group_wiki_admin_page_comments = $_POST['wiki-page-comments-on'];
	$group_wiki_admin_page_visible = $_POST['wiki-page-visible'];
	if ( $group_wiki_admin_page_ids ) {
	
		foreach ( $group_wiki_admin_page_ids as $key => $wiki_page_id ) {
			
			// Update the page privacy settings
			switch ( $group_wiki_admin_page_privacy[$key] ) {
				case 'public':
					update_post_meta( $wiki_page_id, 'wiki_view_access', 'public' );
					break;
				case 'member-only':
					update_post_meta( $wiki_page_id, 'wiki_view_access', 'member-only' );
					break;
			}
			
			// Update the page edit settings
			switch ( $group_wiki_admin_page_editing[$key] ) {
				case 'all-members':
					update_post_meta( $wiki_page_id, 'wiki_edit_access', 'public' );
					break;
				case 'moderator-only':
					update_post_meta( $wiki_page_id, 'wiki_edit_access', 'moderator-only' );
					break;
				case 'admin-only':
					update_post_meta( $wiki_page_id, 'wiki_edit_access', 'admin-only' );
					break;
			}
			
			// Update comment_status ( TODO: Title and menu_order support )
			$wiki_post = array();
			$wiki_post['ID'] = $wiki_page_id;
			if ( $group_wiki_admin_page_comments[$key] == 'yes' ) {
				$wiki_post['comment_status'] = 'open';
			} else {
				$wiki_post['comment_status'] = 'closed';
			}
			remove_action('pre_post_update', 'wp_save_post_revision'); // No revision for this update
			wp_update_post( $wiki_post );				
			
			// Set page visible or not
			if ( $group_wiki_admin_page_visible[$key] == 'yes' ) {
				update_post_meta( $wiki_page_id, 'wiki_page_visible', 'yes' );
			} else {
				update_post_meta( $wiki_page_id, 'wiki_page_visible', 'no' );
			}
			
		}
		
	}
	
	// Set the default wiki page privacy level
	if ( $_POST['wiki-privacy'] == 'public' || $_POST['wiki-privacy'] == 'member-only' ) {
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_default_page_privacy', $_POST['wiki-privacy'] );
	} else { // Default to member-only if invalid input
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_default_page_privacy', 'member-only' );
	}
	
	// Set the default wiki page edit group priv level required
	if ( $_POST['wiki-edit-rights'] == 'all-members' || $_POST['wiki-edit-rights'] == 'moderator-only' || $_POST['wiki-edit-rights'] == 'admin-only' ) {
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_default_edit_rights', $_POST['wiki-edit-rights'] );
	} else { // Default to all group members can edit if invalid input
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_default_edit_rights', 'all-members' );
	}
	
	// Set the frontend page creation setting
	if ( $_POST['groupwiki-member-page-create'] == 'yes' ) {
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_member_page_create', $_POST['groupwiki-member-page-create'] );
	} else { // Default to disabled if answer is no or input invalid
		groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_member_page_create', 'no' );
	}
	
	groups_update_groupmeta( $group_id, 'bp_wiki_group_wiki_enabled', 'yes' );
}






function bp_wiki_user_can_create_group_page() {
	global $bp;
	
	// Override check - if group admin always return true
	if ( groups_is_user_admin( $bp->loggedin_user->id, $bp->groups->current_group->id ) ) {
		return true;
	}
	
	// Standard check - returns true if user is a group member and frontend page creation enabled
	if ( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_wiki_group_wiki_member_page_create' ) == 'yes'  && groups_is_user_member( $bp->loggedin_user->id, $bp->groups->current_group->id ) ) {
		return true;
	}
	
	return false;
}


/**
 * This class allows group-based wiki support.  
 *
 */
if ( class_exists( BP_Group_Extension ) ) {

	class BP_Wiki extends BP_Group_Extension {	

		function bp_wiki() {
			global $bp;
			
			$this->name = BP_WIKI_GROUP_WIKI_PAGES_NAME;
			$this->slug = BP_WIKI_GROUP_WIKI_SLUG;

			$this->create_step_position = 16;
			$this->nav_item_position = 41;
			
			// Only enable the group nav item if the groupwiki has been activated
			if ( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_wiki_group_wiki_enabled' ) == 'yes' ) {
				$this->enable_nav_item = true;
			} else {
				$this->enable_nav_item = false;
			}		
			
			// Make private groups also display the wiki nav item, even if people aren't members
			add_action( 'wp', array( &$this, 'wiki_nav_for_private_groups' ), 2 );
			add_action( 'admin_menu', array( &$this, 'wiki_nav_for_private_groups' ), 2 );
			
		}	
		
		/**
		 * This function is added to the WP wp and admin_menu actions to enable the group wiki nav 
		 * item when a non-member views a private group with a group wiki enabled
		 * NOTE: This needs to be cleaned up/removed.  
		 */ 
		function wiki_nav_for_private_groups() {
			global $bp;

			if ( $bp->current_component == $bp->groups->slug && $bp->is_single_item ) {
				wp_enqueue_script( 'tiny_mce' );
				if ( $bp->groups->current_group->status == 'private' && groups_get_groupmeta( $bp->groups->current_group->id, 'bp_wiki_group_wiki_enabled' ) == true )  {
					if ( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_wiki_group_wiki_enabled' ) == 'yes' ) {
						bp_core_new_subnav_item( array( 'name' => ( !$this->nav_item_name ) ? $this->name : $this->nav_item_name, 'slug' => $this->slug, 'parent_slug' => BP_GROUPS_SLUG, 'parent_url' => bp_get_group_permalink( $bp->groups->current_group ) . '/', 'position' => $this->nav_item_position, 'item_css_id' => 'nav-' . $this->slug, 'screen_function' => array( &$this, '_display_hook' ), 'user_has_access' => $this->enable_nav_item ) );
					}
				}
			}
		}

		function create_screen() {
			global $bp;
			
			if ( !bp_is_group_creation_step( $this->slug ) )
				return false;

			// If group meta has been set then the wiki was enabled at some point - use admin screen rather than create
			if ( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_wiki_group_wiki_enabled' ) ) {
				require_once( apply_filters( 'bp_wiki_locate_group_wiki_admin', 'group-wiki-admin.php' ) );
			} else {
				require_once( apply_filters( 'bp_wiki_locate_group_wiki_create', 'group-wiki-create.php' ) );
			}
			
			wp_nonce_field( 'groups_create_save_' . $this->slug );
		}

		function create_screen_save() {
			global $bp;

			check_admin_referer( 'groups_create_save_' . $this->slug );	
			// If enable wiki not ticked exit
			if ( $_POST['groupwiki-enable-wiki'] == 0 ) {
				groups_update_groupmeta( $bp->groups->new_group_id, 'bp_wiki_group_wiki_enabled', 'no' );
				return false;
			}
			
			if ( groups_get_groupmeta( $bp->groups->new_group_id, 'bp_wiki_group_wiki_enabled' ) != 'yes' ) {
				bp_wiki_create_group_wiki( $bp->groups->new_group_id );
			} else { // The wiki was already enabled - we're processing the wiki page admin form now
				bp_wiki_admin_group_wiki( $bp->groups->new_group_id );
			}
		}

		function edit_screen() {
			global $bp;
			
			if ( !bp_is_group_admin_screen( $this->slug ) )
				return false;

			// If group meta has been set then the wiki was enabled at some point - use admin screen rather than create
			if ( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_wiki_group_wiki_enabled' ) ) {
				require_once( apply_filters( 'bp_wiki_locate_group_wiki_admin', 'group-wiki-admin.php' ) );
			} else {
				require_once( apply_filters( 'bp_wiki_locate_group_wiki_create', 'group-wiki-create.php' ) );
			}
			
			?>
			<input type="submit" name="save" value="Save" />
			<?php
			wp_nonce_field( 'groups_edit_save_' . $this->slug );
		}

		function edit_screen_save() {
			global $bp;

			if ( !isset( $_POST['save'] ) )
				return false;

			check_admin_referer( 'groups_edit_save_' . $this->slug );
			
			// If enable wiki not ticked exit
			if ( $_POST['groupwiki-enable-wiki'] == 0 ) {
				groups_update_groupmeta( $bp->groups->current_group->id, 'bp_wiki_group_wiki_enabled', 'no' );
				$success = true;
			} else {
				if ( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_wiki_group_wiki_enabled' ) != 'yes' ) {
					bp_wiki_create_group_wiki( $bp->groups->current_group->id );
					$success = true;
				} else { // The wiki was already enabled - we're processing the wiki page admin form now
					bp_wiki_admin_group_wiki( $bp->groups->current_group->id );
					$success = true;
				}
			}
				
			if ( !$success ) {
				bp_core_add_message( __( 'There was an error saving, please try again', 'buddypress' ), 'error' );
			} else {
				bp_core_add_message( __( 'Settings saved successfully', 'buddypress' ) );
			}
			
			bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . 'admin/' . $this->slug );
		}

		function display() {
			global $bp;
			
			// If action vars are set then check for page view/edit/etc, else show index
			if ( $bp->action_variables ) {
				// Work out the page we're looking at
				$wiki_page = bp_wiki_get_page_from_slug( $bp->action_variables[0] );
				// Get the last action var.  If this is edit, history or discussion load the necc file.
				// else assume we're viewing a page
				switch ( end( $bp->action_variables ) ) {
					case 'edit':
						require_once( apply_filters( 'bp_wiki_locate_edit_group_page', 'edit-group-page.php' ) );
						break;
					case 'history':
						require_once( apply_filters( 'bp_wiki_locate_view_group_revision', 'history-group-page.php' ) );
						break;
					case 'discussion':
						require_once( apply_filters( 'bp_wiki_locate_view_group_discussion', 'discuss-group-page.php' ) );
						break;
					default:
						// If the page exist, load the view page.  
						// Else load the edit page with $new_wiki_page = true (if user allowed)
						if ( $wiki_page ) {
							require_once( apply_filters( 'bp_wiki_locate_view_group_page', 'view-group-page.php' ) );
						} elseif ( bp_wiki_user_can_create_group_page() ) {
							// Page doesn't exist but user has rights to create pages
							$bp_wiki_group_new_wiki_page = true;
							require_once( apply_filters( 'bp_wiki_locate_edit_group_page', 'edit-group-page.php' ) );
						} else {
							// Page doesn't exist and user isn't allowed to create pages
							?>
							<div id="message" class="info">
								<p><?php _e( 'That page does not exist and you are not permitted to create new pages for this group.', 'bp-wiki' ); ?></p>
							</div>
							<?php
							require_once( apply_filters( 'bp_wiki_locate_view_group_index', 'view-group-index.php' ) );
						}
						break;
				}
			} else {
				require_once( apply_filters( 'bp_wiki_locate_view_group_index', 'view-group-index.php' ) );
			}
			
		}

		function widget_display() { 
			// Not used
		}
	}
	
	bp_register_group_extension( 'BP_Wiki' );
	
}
?>