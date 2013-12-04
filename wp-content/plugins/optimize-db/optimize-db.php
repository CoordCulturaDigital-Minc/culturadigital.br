<?php
/*
Plugin Name: Optimize DB
Plugin URI: http://yoast.com/wordpress/optimize-db/
Description: Let's you optimize your WordPress database tables with one click. Click optimize on the right to start optimizing!
Version: 1.3
Author: Joost de Valk
Author URI: http://yoast.com
*/
/* 
Changelog

Changes in 1.3
- Changed ownership of plugin
- Fixed to work with 2.7 and appear in the Tools menu
- Fixed some typo's
- Added Ozh Admin menu icon
- Added Optimize link on plugins page
- Added "Finished optimizing" message

Changes in 1.2
- changes suggested by Quail (http://wordpress.designpraxis.at/plugins/optimize-db/#comment-942)

Changes in 1.1
- bug fixed for script loading with  
	add_action('admin_print_scripts-manage_page_optimize-db/optimize-db', 'dprx_opt_loadjs');

*/

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
 
// Guess the location
$optdbpluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

add_action('init', 'dprx_opt_init_locale',98);
function dprx_opt_init_locale() {
	$locale = get_locale();
	$mofile = dirname(__FILE__) . "/locale/".$locale.".mo";
	load_textdomain('dprx_opt', $mofile);
}

add_action('admin_menu', 'dprx_opt_add_admin_pages');

function optdb_add_ozh_adminmenu_icon( $hook ) {
	global $optdbpluginpath;
	static $optdbicon;
	if (!$optdbicon) {
		$optdbicon = $optdbpluginpath . 'database_gear.png';
	}
	if ($hook == 'optimize-db.php') return $optdbicon;
	return $hook;
}

function optdb_filter_plugin_actions( $links, $file ){
	//Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ){
		$settings_link = '<a href="tools.php?page=optimize-db.php">' . __('Optimize') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

function dprx_opt_add_admin_pages() {
	add_submenu_page('tools.php', 'Optimize DB', 'Optimize DB', 10, basename(__FILE__), 'dprx_opt_manage_page');
	add_filter( 'plugin_action_links', 'optdb_filter_plugin_actions', 10, 2 );
	add_filter( 'ozh_adminmenu_icon', 'optdb_add_ozh_adminmenu_icon' );
}

if (eregi("optimize-db",$_GET['page'])) {
	wp_enqueue_script('prototype');
	add_action('admin_print_scripts', 'dprx_opt_loadjs');
}

function dprx_opt_loadjs() {
	?>	
	<script type="text/javascript">
	function dprx_opt_js() {
		dprxu = new Ajax.Updater(
		'dprx_optimizedb',
		'<?php bloginfo("wpurl"); ?>/wp-admin/edit.php?page=<?php echo $_REQUEST['page']; ?>',
			{method: 'get', 
			 parameters:'dprx_opt_ajax=1',
			 evalScripts: true}
		);
	}
	</script>
	<style type="text/css" media="screen">
		.pluginmenu li {
			list-style-type: square;
			margin-left: 20px;
			padding-left: 5px;
		}
	</style>
	<?php
}

add_action('init', 'dprx_optimize_do',99);
function dprx_optimize_do() {
	global $wpdb;
	if (!empty($_REQUEST['dprx_optdo_ajax'])) {
		?>
		<p>
		<?php _e("Optimizing ...","dprx_opt"); ?>
		</p>
		<?php
		$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME;
		$res = $wpdb->get_results($sql, ARRAY_A);
		foreach($res as $r) {
			if(!empty($r['Data_free'])) {
				$sql = 'OPTIMIZE TABLE '.$r['Name'];
				$res2 = $wpdb->query($sql);
				if (!isset($res2)) {
					?>
					<p>
					<?php _e("Error: Database table","dprx_opt"); ?>
					<?php echo $r['Name']; ?>
					<?php _e("could not be optimized","dprx_opt"); ?>.
					</p>
					<?php
				} else {
					?>
					<p>
					<?php _e("Database table","dprx_opt"); ?>
					<?php echo $r['Name']; ?>
					<?php _e("optimized","dprx_opt"); ?>.
					</p>
					<?php
				}
			}
		}
		_e("Finished optimizing your tables!","dprx_opt");
		exit;
	}
	if (!empty($_REQUEST['dprx_opt_ajax'])) {
		$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME;
		$res = $wpdb->get_results($sql, ARRAY_A);
		$sum_free = 0;
		$sum_data = 0;
		foreach($res as $r) {
			$sum_free = $sum_free + $r['Data_free'];
			$sum_data = $sum_data + $r['Data_length'];
		}
		if ($sum_free < 1) {
		?>
		<p>
		<?php _e("Congratulations, your database is already completely optimized","dprx_opt"); ?>!
		</p>
		<?php exit;
		}
		?>
		<p>
		<?php _e("Your database holds","dprx_opt"); ?>
		<b><?php echo dprx_opt_format_size($sum_data); ?></b>
		<?php _e("of Data","dprx_opt"); ?>.
		</p>
		<p>
		<?php _e("It can be reduced by","dprx_opt"); ?>
		<b><?php echo dprx_opt_format_size($sum_free); ?></b>
		<input type="button" class="button" value="<?php _e("Optimize Now","dprx_opt"); ?>" onclick="new Ajax.Updater(
		'dprx_optimizedb',
		'<?php bloginfo("wpurl"); ?>/wp-admin/edit.php?page=<?php echo $_REQUEST['page']; ?>',
			{method: 'get', 
			 parameters:'dprx_optdo_ajax=1'}
		);" />
		</p>
		<table class="widefat">
		<thead>
		<tr>
		<th scope="col"><?php _e("Table Name","dprx_opt"); ?></th>
		<th scope="col"><?php _e("Data stored","dprx_opt"); ?></th>
		<th scope="col"><?php _e("Overhead","dprx_opt"); ?></th>
		</tr>
		</thead>
		<tbody id="the-list">
		<?php
		$sum = 0;
		foreach($res as $r) {
			if(!empty($r['Data_free'])) {
				echo "<tr>";
				echo "<td>".$r['Name']."</td><td>";
				echo dprx_opt_format_size($r['Data_length']);
				echo "</td>";
				echo "<td>".dprx_opt_format_size($r['Data_free'])."</td>";
				echo "</tr>";
			}
		}
		?>
		</tbody>
		</table>
		<?php
	exit;
	}
}

function dprx_opt_format_size($rawSize) {
    if ($rawSize / 1048576 > 1) 
        return round($rawSize/1048576, 1) . ' MB'; 
    else if ($rawSize / 1024 > 1) 
        return round($rawSize/1024, 1) . ' KB'; 
    else 
        return round($rawSize, 1) . ' bytes';
}

function dprx_opt_manage_page() {
	?>
	<div class="wrap">
		<h2><?php _e('Optimize Database') ?></h2>
		<div id="dprx_optimizedb"><?php _e('Checking database tables. Please wait.','dprx_opt') ?></div>
		<br/><br/>
		<h3>Like this plugin?</h3>
		<p><?php _e('Why not do any of the following:','dprx_opt'); ?></p>
		<ul class="pluginmenu">
			<li><?php _e('Link to it so other folks can find out about it.','dprx_opt'); ?></li>
			<li><?php _e('<a href="http://wordpress.org/extend/plugins/optimize-db/">Give it a good rating</a> on WordPress.org.','dprx_opt'); ?></li>
			<li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=2017947"><?php _e('Donate a token of your appreciation','dprx_opt'); ?></a>.</li>
		</ul>
		
	</div>
	 <script type="text/javascript">
	 	dprx_opt_js();
	 </script>
	<?php
}
?>
