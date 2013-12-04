<?php
/*
Plugin Name: Invisible Defender
Plugin URI: http://www.poradnik-webmastera.com/projekty/invisible_defender/
Description: This plugin protects your registration, login and comment forms against spambots.
Author: Daniel Frużyński
Version: 1.8.1
Author URI: http://www.poradnik-webmastera.com/
Text Domain: invisible-defender
*/

/*  Copyright 2009-2010  Daniel Frużyński  (email : daniel [A-T] poradnik-webmastera.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( !class_exists( 'InvisibleDefender' ) || ( defined( 'WP_DEBUG') && WP_DEBUG ) ) {

class InvisibleDefender {
	// Constructor
	function InvisibleDefender() {
		// Initialisation and admin section
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		
		// Protect registration form
		if ( get_option( 'indef_protect_registration' ) ) {
			add_action( 'register_form', array( &$this, 'add_hidden_fields' ) );
			add_action( 'register_post', array( &$this, 'register_post' ), 10, 2 );
		}
		// Protect login form
		if ( get_option( 'indef_protect_login' ) ) {
			add_action( 'login_form', array( &$this, 'add_hidden_fields' ) );
			add_action( 'wp_authenticate', array( &$this, 'wp_authenticate' ), 10, 2 );
		}
		// Protect comments form
		if ( get_option( 'indef_protect_comments' ) ) {
			add_action( 'comment_form', array( &$this, 'add_hidden_fields' ) );
			add_filter( 'preprocess_comment', array( &$this, 'preprocess_comment' ) );
		}
		
		// Show stats in Dashboard
		if ( get_option( 'indef_stats_admin' ) ) {
			add_action( 'activity_box_end', array( &$this, 'activity_box_end' ) );
		}
	}
	
	// Initialise plugin
	function init() {
		load_plugin_textdomain( 'invisible-defender', false, dirname( plugin_basename( __FILE__ ) ).'/lang' );
	}
	
	// Initialise plugin - admin part
	function admin_init() {
		register_setting( 'invisible-defender', 'indef_protect_registration', array( &$this, 'sanitize_01' ) );
		register_setting( 'invisible-defender', 'indef_protect_login', array( &$this, 'sanitize_01' ) );
		register_setting( 'invisible-defender', 'indef_protect_comments', array( &$this, 'sanitize_01' ) );
		register_setting( 'invisible-defender', 'indef_stats_admin', array( &$this, 'sanitize_01' ) );
		register_setting( 'invisible-defender', 'indef_credits', array( &$this, 'sanitize_01' ) );
		register_setting( 'invisible-defender', 'indef_stats_forms', array( &$this, 'sanitize_01' ) );
	}
	
	// Add hidden fields to the form
	function add_hidden_fields() {
		echo '<div style="display:none">'.__('Please leave these two fields as-is:', 'invisible-defender').' ';
		echo '<input type="text" name="indefvalue0" value="" />';
		echo '<input type="text" name="indefvalue1" value="1" />';
		echo '</div>';
		if ( get_option( 'indef_credits' ) ) {
			echo '<div><br style="clear:both" /><p><small>';
			printf( __('Protected by %s.', 'invisible-defender'), 
				'<a href="http://www.poradnik-webmastera.com/projekty/invisible_defender/">Invisible Defender</a>' );
			if ( get_option( 'indef_stats_forms' ) ) {
				$stats = get_option( 'indef_stats' );
				$sum = $stats['register'] + $stats['login'] + $stats['comment'];
				if ( isset( $stats['blacklist'] ) ) { // Added in version 1.4.2
					$sum += $stats['blacklist'];
				}
				echo ' ';
				printf( __('Showed <strong>403</strong> to <strong>%s</strong> bad guys.', 
					'invisible-defender'), number_format_i18n( $sum ) );
			}
			echo '</small></p></div>';
		}
	}
	
	// Protection function for submitted register form
	function register_post( $user_login, $user_email ) {
		if ( ( $user_login != '' ) && ( $user_email != '' ) ) {
			$this->check_hidden_fields( 'register' );
		}
	}
	
	// Protection function for submitted login form
	function wp_authenticate( $user_login, $user_password ) {
		if ( ( $user_login != '' ) && ( $user_password != '' ) ) {
			$this->check_hidden_fields( 'login' );
		}
	}
	
	// Protection function for submitted comment form
	function preprocess_comment( $commentdata ) {
		$this->check_hidden_fields( 'comment', $commentdata['comment_content'] );
		return $commentdata;
	}
	
	// Check for hidden fields and wp_die() in case of error
	function check_hidden_fields( $form_type, $comment_text = null ) {
		// Skip check for AJAX
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		// Skip check for XML-RPC
		if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
			return;
		}
		// Skip check for Trackbacks/Pingbacks
		if ( is_trackback() ) {
			return;
		}
		
		// Get values from POST data
		$val0 = '';
		$val1 = '';
		if ( isset( $_POST['indefvalue0'] ) ) {
			$val0 = $_POST['indefvalue0'];
		}
		if ( isset( $_POST['indefvalue1'] ) ) {
			$val1 = $_POST['indefvalue1'];
		}
		
		// Check values
		if ( ( $val0 != '' ) || ( $val1 != '1' ) ) {
			// Increase stats counter
			$stats = get_option( 'indef_stats' );
			++$stats[$form_type];
			update_option( 'indef_stats', $stats );
			
			if ( defined( 'INDEF_LOG_SPAMMERS' ) && INDEF_LOG_SPAMMERS ) {
				$this->log_spammer( 'F' );
			}
			
			// Block spammer!
			$this->block_spammer();
		}
	}
	
	// Show stats in Dashboard
	function activity_box_end() {
		$stats = get_option( 'indef_stats' );
		$sum = $stats['register'] + $stats['login'] + $stats['comment'];
		if ( isset( $stats['blacklist'] ) ) { // Added in version 1.4.2
			$blacklist = $stats['blacklist'];
			$sum += $blacklist;
		} else {
			$blacklist = 0;
		}
		echo '<p>';
		echo sprintf( __('<strong>Invisible Defender</strong> showed <strong>403</strong> to <strong>%1$s</strong> bad guys (<strong>%2$s</strong> was blacklisted, <strong>%3$s</strong> on register form, <strong>%4$s</strong> on login form, <strong>%5$s</strong> on comments form).',
			'invisible-defender'), number_format_i18n( $sum ), number_format_i18n( $blacklist ), 
			number_format_i18n( $stats['register'] ), number_format_i18n( $stats['login'] ), 
			number_format_i18n ($stats['comment'] ) );
		echo '</p>';
	}
	
	// Add Admin menu option
	function admin_menu() {
		add_submenu_page( 'options-general.php', 'Invisible Defender', 
			'Invisible Defender', 'manage_options', __FILE__, array( &$this, 'options_panel' ) );
	}
	
	// Handle options panel
	function options_panel() {
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php _e('Invisible Defender - Options', 'invisible-defender'); ?></h2>

<form name="dofollow" action="options.php" method="post">
<?php settings_fields( 'invisible-defender' ); ?>
<table class="form-table">

<tr><th colspan="2"><h3><?php _e('Forms protection:', 'invisible-defender'); ?></h3></th></tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<label for="indef_protect_registration"><?php _e('Protect registration form:', 'invisible-defender'); ?></label>
</th>
<td>
<input type="checkbox" id="indef_protect_registration" name="indef_protect_registration" value="yes" <?php checked( 1, get_option( 'indef_protect_registration' ) ); ?> />
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<label for="indef_protect_login"><?php _e('Protect login form:', 'invisible-defender'); ?></label>
</th>
<td>
<input type="checkbox" id="indef_protect_login" name="indef_protect_login" value="yes" <?php checked( 1, get_option( 'indef_protect_login' ) ); ?> />
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<label for="indef_protect_comments"><?php _e('Protect comments form:', 'invisible-defender'); ?></label>
</th>
<td>
<input type="checkbox" id="indef_protect_comments" name="indef_protect_comments" value="yes" <?php checked( 1, get_option( 'indef_protect_comments' ) ); ?> />
</td>
</tr>

<tr><th colspan="2"><h3><?php _e('Stats and "Protected by" link:', 'invisible-defender'); ?></h3></th></tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<label for="indef_stats_admin"><?php _e('Show stats in Dashboard:', 'invisible-defender'); ?></label>
</th>
<td>
<input type="checkbox" id="indef_stats_admin" name="indef_stats_admin" value="yes" <?php checked( 1, get_option( 'indef_stats_admin' ) ); ?> />
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<label for="indef_credits"><?php _e('Show "Protected by" link:', 'invisible-defender'); ?></label>
</th>
<td>
<input type="checkbox" id="indef_credits" name="indef_credits" value="yes" <?php checked( 1, get_option( 'indef_credits' ) ); ?> /><br /><?php _e('If you think that this plugin is great and want to tell others about it, please check this option. Invisible Defender will show small "Protected by" link under your forms. This way you can also credit me for my work.', 'invisible-defender'); ?>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<label for="indef_stats_forms"><?php _e('Show number of blocked bots under forms:', 'invisible-defender'); ?></label>
</th>
<td>
<input type="checkbox" id="indef_stats_forms" name="indef_stats_forms" value="yes" <?php checked( 1, get_option( 'indef_stats_forms' ) ); ?> /><br /><?php _e('Note: this will be shown only when "Protected by" link is enabled too.', 'invisible-defender'); ?>
</td>
</tr>

</table>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save settings', 'invisible-defender'); ?>" /> 
</p>

</form>
</div>
<?php
	}

	// Sanitization functions
	function sanitize_01( $value ) {
		if ( ( $value == 'yes' ) || ( $value == 1 ) ) {
			return 1;
		} else {
			return 0;
		}
	}
	
	// Log spam
	function log_spammer( $code ) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$host = gethostbyaddr( $ip );
		$agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$timestamp = date( 'Y-m-d H:i:s' );
		$line = "$code\t$timestamp\t$ip\t$host\t$agent\n";
		
		$dir = dirname( __FILE__ ). '/logs/';
		if ( !file_exists( $dir ) ) {
			@mkdir( $dir );
		}
	
		$fname = $dir . date( 'Y-m-d' ) . '.txt';
		$file = @fopen( $fname, 'a' );
		if ( $file ) {
			flock( $file,  LOCK_EX );
			fwrite( $file, $line );
			flock( $file,  LOCK_UN );
			fclose( $file );
		}
	}
	
	// Block spammer
	function block_spammer() {
		// Die and return error 403 Forbidden
		wp_die( 'Bye Bye, SPAMBOT!', '403 Forbidden', array( 'response' => 403 ) );
	}
}

// Add functions from WP2.8 for previous WP versions
if ( !function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return wp_specialchars( $text );
	}
}

if ( !function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return attribute_escape( $text );
	}
}

add_option( 'indef_protect_registration', 1 ); // Protect registration form
add_option( 'indef_protect_login', 1 ); // Protect login form
add_option( 'indef_protect_comments', 1 ); // Protect comments form
add_option( 'indef_stats', array( 'blacklist' => 0, 'register' => 0, 'login' => 0, 'comment' => 0 ) ); // Number of blocked bots
add_option( 'indef_stats_admin', 1 ); // Show stats in Dashboard
add_option( 'indef_credits', 1 ); // Show 'Protected by' link
add_option( 'indef_stats_forms', 1 ); // Show stats in forms

$wp_invisible_defender = new InvisibleDefender();

// Ban heavy spammer's IPs
$ip = @ip2long( $_SERVER['REMOTE_ADDR'] );
if ( ( $ip !== -1 ) && ( $ip !== false )) {
	// Banned address spaces
	$banned_ranges = array(
		// netdirekt e.K. (89.149.241.0 - 89.149.244.255)
		array( '89.149.241.0', '89.149.244.255' ),
		// AltusHost Inc. (89.248.160.191 - 89.248.160.254)
		array( '89.248.160.191', '89.248.160.254' ),
		// Business Communication Agency, Ltd. (92.242.64.0 - 92.242.95.255)
		array( '92.242.64.0', '92.242.95.255' ),
		// SIA "CSS GROUP" (94.142.128.0 - 94.142.135.255)
		array( '94.142.128.0', '94.142.135.255' ),
		// AD TECHNOLOGY SIA (188.92.72.0 - 188.92.79.255)
		array( '188.92.72.0', '188.92.79.255' ),
		// Dragonara Alliance Ltd (194.8.74.0 - 194.8.75.255)
		array( '194.8.74.0', '194.8.75.255' ),
	);
	foreach( $banned_ranges as $range ) {
		$block = false;
		if ( is_array( $range ) ) {
			if ( ( $ip >= ip2long( $range[0] ) ) && ( $ip <= ip2long( $range[1] ) ) ) {
				$block = true;
			}
		} else {
			if ( $ip == ip2long( $range ) ) {
				$block = true;
			}
		}
		
		if ( $block ) {
			// Increase stats counter
			$stats = get_option( 'indef_stats' );
			if ( isset( $stats['blacklist'] ) ) {
				++$stats['blacklist'];
			} else {
				$stats['blacklist'] = 1;
			}
			update_option( 'indef_stats', $stats );
			
			if ( defined( 'INDEF_LOG_SPAMMERS' ) && INDEF_LOG_SPAMMERS ) {
				$wp_invisible_defender->log_spammer( 'B' );
			}
			
			// Block spammer!
			$wp_invisible_defender->block_spammer();
		}
	}
}

} // END

?>