<?php
/*
 Plugin Name: BuddyPress Block Activity Stream Types
 Plugin URI: http://wordpress.org/extend/plugins/buddypress-block-activity-stream-types/
 Description: Blocks an activity record (based on types) from being saved to the database
 Author: rich fuller - rich! @ etiviti
 Author URI: http://buddypress.org/developers/nuprn1/
 License: GNU GENERAL PUBLIC LICENSE 3.0 http://www.gnu.org/licenses/gpl.txt
 Version: 0.3.0
 Text Domain: bp-activity-block
 Site Wide Only: true
*/

/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function bp_activity_block_init() {

    require( dirname( __FILE__ ) . '/bp-activity-block.php' );
	
}
add_action( 'bp_init', 'bp_activity_block_init' );

//add admin_menu page
function bp_activity_block_admin_add_admin_menu() {
	global $bp;
	
	if ( !is_super_admin() )
		return false;

	//Add the component's administration tab under the "BuddyPress" menu for site administrators
	require ( dirname( __FILE__ ) . '/admin/bp-activity-block-admin.php' );

	add_submenu_page( 'bp-general-settings', __( 'Activity Block Admin', 'bp-activity-block' ), __( 'Activity Block', 'bp-activity-block' ), 'manage_options', 'bp-activity-block-settings', 'bp_activity_block_admin' );	

	//set up defaults

}

//loader file never works - as it doesn't hook the admin_menu
if ( defined( 'BP_VERSION' ) ) {
	add_action( 'admin_menu', 'bp_activity_block_admin_init' );
} else {
	add_action( 'bp_init', 'bp_activity_block_admin_init');
}

function bp_activity_block_admin_init() {
	add_action( 'admin_menu', 'bp_activity_block_admin_add_admin_menu', 25 );
}

?>