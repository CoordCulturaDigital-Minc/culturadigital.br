<?php
/*
Plugin Name: WP Activate Users
Plugin URI: http://countingrows.com/wp-activate-user/
Description: For BuddyPress: Shows users who have not activated their account
Version: 1.2
Author: Matthew Price
Author URI: http://countingrows.com
License: GPL2
*/

if($_GET['action']); {

	switch ($_GET['action']) {
		
	case 'activate-user': activate_user(); break;
	case 'wpmu-activate': wpmu_activate(); break;

	}
}	

function add_to_head() {
?>
<!-- SHOW-HIDE MULTIPLE SCRIPT --> 
<script type="text/javascript">
function expandCollapse() {
for (var i=0; i<expandCollapse.arguments.length; i++) {
var element = document.getElementById(expandCollapse.arguments[i]);
element.style.display = (element.style.display == "none") ? "block" : "none";
}
}
</script>
<script type="text/javascript">
function readOnlyCheckBox() {
   return false;
}
</script>

<script type="text/javascript">
$(“#signup-welcome).p(;:last-child').css('display', 'none');
</script>
<?php
}

function wp_activate_users() {
global $wpdb;
$users = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users WHERE user_status = '2' ORDER BY user_registered DESC");
$user_mu = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}signups WHERE active = '0' ORDER BY registered");

?>
<div class="wrap">
<h2>Users Who Have Not Activated Their Account</h2>
Total: <?php echo $wpdb->num_rows; ?><br><br>	

<?php if ($_GET['user'] != '') { 
$user = $_GET['user'];
$user_activated = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users WHERE ID = '$user'");
$user_activated_mu = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}signups WHERE user_login = '$user'");

?>
<div id="message" class="updated">User: <?php echo $user_activated[0]->display_name . $user_activated_mu[0]->user_login; ?> has been activated.</div>
<?php } ?>
<table class="widefat" cellspacing="0">
	<thead>
		<tr class="thead">
			<th scope="col" id="user-registered" class="manage-column">Registration Date</th>
			<th scope="col" id="user-login" class="manage-column">Login</th>
			<th scope="col" id="user-email" class="manage-column">Email</th>
			<th scope="col" id="display-name" class="manage-column">Display Name</th>
			<th scope="col" id="activate" class="manage-column">Activate</th>
		</tr>
	</thead>
<?php			
	foreach ($users as $user) {
	echo '<tr class="alternate">';
			echo "<td>" . $user->user_registered . "</td><td>" . $user->user_login . "</td><td><a href=\"mailto:" . $user->user_email . "\">" . $user->user_email . "</a></td><td>" . $user->display_name . "</td>"; 
	?>
	<?php $plugin_path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); ?>
	<td width="100"><img src="<?php echo $plugin_path; ?>activate.png" style="cursor: pointer;" onclick="javascript: expandCollapse('activator<?php echo $user->ID; ?>');"><br>
	<div id="activator<?php echo $user->ID; ?>" style="display: none;">
		<form action="?action=activate-user" method="post">
			<input type="radio" value="0" name="activate">Activate<div style="clear: both;"></div>
			<input type="radio" value="2" name="activate"> Cancel<br>
			<input type="hidden" name="id" value="<?php echo $user->ID; ?>">
			<input type="submit" value="Confirm">
		</form>
	</div>
	<?php
	echo "</tr>";
	}
				
	foreach ($user_mu as $userMU) {
	echo '<tr class="alternate">';
			echo "<td>" . $userMU->registered . "</td><td>" . $userMU->user_login . "</td><td><a href=\"mailto:" . $userMU->user_email . "\">" . $userMU->user_email . "</a></td><td>";
			$display_name = unserialize($userMU->meta);
			echo $display_name['field_1'];
			echo "</td>"; 
	?>
	<?php $plugin_path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); ?>
	<td width="100"><img src="<?php echo $plugin_path; ?>activate.png" style="cursor: pointer;" onclick="javascript: expandCollapse('activatorMU<?php echo $userMU->user_login; ?>');"><br>
	<div id="activatorMU<?php echo $userMU->user_login; ?>" style="display: none;">
		<form action="?action=wpmu-activate" method="post">
  	 		<input type="radio" value="1" name="activateMU">Activate<div style="clear: both;"></div>
  	 		<input type="radio" value="0" name="activateMU"> Cancel<br>		 			
			<input type="hidden" name="key" value="<? echo $userMU->activation_key; ?>">	
			<input type="submit" value="Confirm">
		</form>
	</div>
	<?php
	echo "</tr>";
	}
echo '</tbody></table>';
echo "</div>";

	if ($_GET['key'] == '') { echo ""; } else {
	$key = explode("&", $_SERVER['QUERY_STRING']);
	$key = explode("=",$key['1']);
	?>
	<br>
	<?php
	global $wpdb;
	$table_name = $wpdb->prefix . "bp_xprofile_data";
	if($wpdb->get_var("show tables like '$table_name'") == $table_name) {	
	?>
	&nbsp;&nbsp;<a href="<? echo $_SERVER['PHP_SELF']; ?>?page=wp-activate-users">REFRESH THIS PAGE TO UPDATE USER LIST</a>
	<br><br>	
	<iframe src="<?php bloginfo('wpurl'); ?>/activate/activate?key=<?php echo $key['1']; ?>" width="1000" height="600" style="border: 0;">		
	<?php } else { ?>
	&nbsp;&nbsp;<a href="<? echo $_SERVER['PHP_SELF']; ?>?page=wp-activate-users">REFRESH THIS PAGE TO UPDATE USER LIST</a>
	<br><br>
	<iframe src="<?php bloginfo('wpurl'); ?>/wp-activate.php?key=<?php echo $key['1']; ?>" width="1000" height="600" style="border: 0;">		
	<?	
		}
	}
}

function wpmu_activate() {
	$key = $_POST['key'];
	header("Location: " . $_SERVER['PHP_SELF'] . "?page=wp-activate-users&key=" . $key);
}

function activate_user() {
	global $wpdb;
	$uid = $_POST['id'];
	$user_login = $_POST['user_login'];
	$activate = $_POST['activate'];	
	$wpdb->query("UPDATE " . $wpdb->prefix . "users SET user_status = '$activate' WHERE ID = '$uid'"); 
	header("Location: " . $_SERVER['PHP_SELF'] . "?page=wp-activate-users&user=" . $uid . $user_login);

}

function add_to_users_menu() {
add_users_page(__('WP Activate Users','wp activate users'), __('WP Activate Users','wp activate users'), 'manage_options', 'wp-activate-users', 'wp_activate_users', '', '4');
}


add_action( 'admin_menu', 'add_to_users_menu' );
add_action( 'admin_head', 'add_to_head' );
add_action( 'wp_head', 'add_to_head' );
?>