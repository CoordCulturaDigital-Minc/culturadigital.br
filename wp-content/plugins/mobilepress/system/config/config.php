<?php
// MobilePress path and table. No need to edit these options.
define('MOPR_PATH', trailingslashit(dirname(dirname(__FILE__)))); // System Path
define('MOPR_ROOT_PATH', trailingslashit(dirname(dirname(dirname(__FILE__))))); // Root Path
define('MOPR_SCRIPT_PATH', '/wp-content/plugins/' . trailingslashit(basename(dirname(dirname(dirname(__FILE__)))))); // Script friendly system path
define('MOPR_TABLE', $wpdb->prefix . 'mobilepress');

// For WP versions < 2.6
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
?>