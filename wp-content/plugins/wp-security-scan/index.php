<?php
/**
 * Plugin Name: Acunetix WP Security
 * Plugin URI: http://www.acunetix.com/websitesecurity/wordpress-security-plugin/
 * Description: The Acunetix WP Security plugin is the ultimate must-have tool when it comes to WordPress security. The plugin is free and monitors your website for security weaknesses that hackers might exploit and tells you how to easily fix them.
 * Version: 4.0.3
 * Author: Acunetix
 * Author URI: http://www.acunetix.com/
 * License: GPLv2 or later
 * Text Domain: WSDWP_SECURITY
 * Domain Path: /languages
 */
 /*  Copyright 2013 Acunetix

	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define('WPS_PLUGIN_PREFIX', 'wps_');
define('WPS_PLUGIN_NAME', 'Acunetix WP Security');
define('WPS_PLUGIN_URL', trailingslashit(plugins_url('', __FILE__)));
define('WPS_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)));
if(defined('__DIR__')) { define('WPS_PLUGIN_BASE_NAME', basename(__DIR__)); }
else { define('WPS_PLUGIN_BASE_NAME', basename(dirname(__FILE__))); }
define('WPS_PLUGIN_BACKUPS_DIR', WPS_PLUGIN_DIR.'res/backups/');

require('wss-settings.php');
require('res/inc/alerts.php');
require('res/inc/WpsOption.php');
require('res/inc/WsdUtil.php');
require('res/inc/WsdPlugin.php');
require('res/inc/WsdInfo.php');
require('res/inc/WsdSecurity.php');
require('res/inc/WsdCheck.php');
require('res/inc/WsdScheduler.php');
require('res/inc/WsdWatch.php');
require('res/inc/WsdLiveTraffic.php');
require('res/inc/WsdWpScanner.php');
require('res/inc/wss-functions.php');

//#!--
if(wpsIsMultisite()){
    add_action('network_admin_menu', 'wpsCreateNetworkMenu');
    add_action('admin_menu', 'wpsCreateSiteMenu');
}
else { add_action('admin_menu', 'wpsCreateNetworkMenu'); }
add_action('init', array('WsdUtil','loadPluggable'));
add_action('init', array('WsdPlugin','loadResources'));

$wpsCanRun = (bool)WpsOption::getOption(WpsSettings::CAN_RUN_TASKS_OPTION_NAME);

if($wpsCanRun)
{
    add_action('init', array('WsdLiveTraffic','registerHit'));
    add_action('wp_ajax_ajaxGetTrafficData', array('WsdLiveTraffic','ajaxGetTrafficData'));
    add_action('wp_ajax_nopriv_ajaxGetTrafficData', array('WsdLiveTraffic','ajaxGetTrafficData'));
}
add_action('wp_ajax_ajaxDeleteBackupFile', array('WsdUtil','ajaxDeleteBackupFile'));
add_action('wp_dashboard_setup', array('WsdUtil','addDashboardWidget'));

register_activation_hook( __FILE__, 'wpsNetworkActivate' );
register_deactivation_hook( __FILE__, 'wpsNetworkDeactivate' );
register_uninstall_hook( __FILE__, array('WsdPlugin', 'uninstall') );
//#++

// Add custom links on plugins page
function wssCustomLinks($links) { if(wpsIsValidInstall()){ return array_merge(array('<a href="admin.php?page='.WPS_PLUGIN_PREFIX.'settings">'.__('Settings',WpsSettings::TEXT_DOMAIN).'</a>'), $links);} else { return $links; } }
add_filter("plugin_action_links_".plugin_basename(__FILE__), 'wssCustomLinks' );

//#! register tasks
if($wpsCanRun)
{
// register cron job
    WsdScheduler::registerCronTask('wssPlugin_WpScanCheckState', array('WsdWpScanner','checkWpScan'), '1m');

// override - scheduled task
    WsdScheduler::registerCronTask('wps_check_user_admin', array('WsdCheck','adminUsername'), '8h');
    WsdScheduler::registerCronTask('wps_check_admin_install_file', array('WsdCheck','check_adminInstallFile'), 'hourly');
    WsdScheduler::registerCronTask('wps_check_admin_upgrade_file', array('WsdCheck','check_adminUpgradeFile'), 'hourly');

// scheduled task - hourly cleanup of events in live traffic
    WsdScheduler::registerCronTask('wps_cleanup_live_traffic', array('WsdLiveTraffic','clearEvents'), 'hourly');

// stacked
    WsdScheduler::registerTask(array('WsdWatch','userPasswordUpdate'));

// #! run fixes. Only those checked by the user will run (@see: settings page)
    wpsRunFixes();

//#! run checks.
    add_action('init', array('WsdCheck','check_tablePrefix'));
    add_action('init', array('WsdCheck','check_currentVersion'));
    add_action('init', array('WsdCheck','check_files'));
}
//#! End index.php