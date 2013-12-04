<?php
/*
Plugin Name: BuddyPress Wiki Component
Plugin URI: http://namoo.co.uk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=A9NEGJEZR23H4
Description: Enables site and group wiki functionality within a Buddypress install.
Version: 0.9.2
Revision Date: May 19, 2010
Requires at least: WP 3.0, BuddyPress 1.2.3
Tested up to: WP 3.0, BuddyPress 1.2.3
License: AGPL http://www.fsf.org/licensing/licenses/agpl-3.0.html
Author: David Cartwright
Author URI: http://namoo.co.uk
Site Wide Only: true
*/
 
/* Only load the component if BuddyPress is loaded and initialized. */
function bp_wiki_init() {
	require( dirname( __FILE__ ) . '/includes/bp-wiki-core.php' );
}
add_action( 'bp_init', 'bp_wiki_init' );

/* Put setup procedures to be run when the plugin is activated in the following function */
function bp_wiki_activate() {
	global $wpdb;

	if ( !empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

	/**
	 * Create tables for the plugin
	 */
	$sql[] = "CREATE TABLE {$wpdb->base_prefix}bp_wiki (
		  		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  		field_1 bigint(20) NOT NULL,
		  		field_2 bigint(20) NOT NULL,
		  		field_3 bool DEFAULT 0,
			    KEY field_1 (field_1),
			    KEY field_2 (field_2)
		 	   ) {$charset_collate};";

	require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );

	/**
	 * The dbDelta call is commented out so the wiki table is not installed.
	 * Once you define the SQL for your new table, uncomment this line to install
	 * the table. (Make sure you increment the BP_wiki_DB_VERSION constant though).
	 */
	//dbDelta($sql);

	update_site_option( 'bp-wiki-db-version', BP_WIKI_DB_VERSION );
}
register_activation_hook( __FILE__, 'bp_wiki_activate' );

/* On deacativation, delete the tables we created */
function bp_wiki_deactivate() {
	/* THIS SECTION IS TODO */
}
register_deactivation_hook( __FILE__, 'bp_wiki_deactivate' );
?>