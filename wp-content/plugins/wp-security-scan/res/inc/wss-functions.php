<?php if(! defined('WPS_PLUGIN_PREFIX')) return;

/**
 * Common function to add custom time intervals to wp cron.
 * This function should not be called directly.
 *
 * Usage: add_filter( 'cron_schedules', 'wpsPlugin_addCronIntervals' );
 *
 * @param $schedules
 * @return mixed
 */
function wpsPlugin_addCronIntervals( $schedules )
{
    //#! @see WsdWpScanner::registerScan()
    $schedules['1m'] = array(
        'interval' => 60,
        'display' => __('Every 1 minute',WpsSettings::TEXT_DOMAIN)
    );
    //#! @see WsdWpScanner::registerScan()
    $schedules['5m'] = array(
        'interval' => 300,
        'display' => __('Every 5 minutes',WpsSettings::TEXT_DOMAIN)
    );
    #! used for admin user check
    //#! @see WsdCheck::adminUsername()
    $schedules['8h'] = array( // The name to be used in code
        'interval' => 28800, // Intervals: in seconds
        'display' => __('Every 8 hours',WpsSettings::TEXT_DOMAIN) // display name
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'wpsPlugin_addCronIntervals' );

function wpsAdminNotice($message=''){ if(!empty($message)){ echo '<div class="updated"><p>'.$message.'</p></div>'; } }
function wpsAdminNoticeError($error){ if(!empty($error)){ echo '<div class="error"><p>'.$error.'</p></div>';} }
function wpsDisplayInstallErrorNotices() {
    if ($notices = WpsOption::getOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array())) {
        if(empty($notices)){ return false; }
        foreach ($notices as $notice) { wpsAdminNoticeError($notice); }
        return true;
    }
    return false;
}
/* Check to see whether or not the plugin was installed successfully. */
function wpsIsValidInstall(){
    if(wpsIsMultisite()){
        $_ = WpsOption::getOption('WPS_NETWORK_INSTALL');
        if(empty($_)){
            return false;
        }
    }
    return true;
}

/**
 * make sure we can display any of the plugin's pages
 * @return bool
 */
function wpsCanDisplayPage()
{
    if(! WsdUtil::canLoad()) { return false; }
    if(! WsdUtil::isAdministrator()){ return false; }
    if(! wpsIsValidInstall()){
        wpsDisplayInstallErrorNotices();
        return false;
    }
    return true;
}

//#! Logging method
function wssLog($message, $data = null)
{
    if(! WpsSettings::WPS_DEBUG){
        return;
    }
    $m = '['.@date("M d @H:i:s").'] Debug: '.$message;
    if(!is_null($data)){
        $m .= ' Data: '.var_export($data, true);
    }
    $m .= PHP_EOL;
    error_log($m, 3, WPS_PLUGIN_DIR.'debug.log');
}

//#! Shutdown function called on each script exit
function wssPlugin_shutdown()
{
    $error = error_get_last();
    if(!empty($error))
    {
        // Only fatal errors, otherwise it will kill every scan
        if($error['type'] === E_ERROR)
        {
            if(! empty($error['message']))
            {
                $data = array(
                    'type' => $error["type"],
                    'file' => (empty($error["file"]) ? '' : $error["file"]),
                    'line' => (empty($error["line"]) ? '' : $error["line"]),
                    'message' => $error["message"],
                );
                wssLog(__FUNCTION__.'() triggered.');
                wssLog('Shutdown function called by system.', $data);
                WpScan::stopScan(false, $data['message']);
            }
        }
    }
}
register_shutdown_function('wssPlugin_shutdown');


/*
 * @since 4.0.2
 * MultiSite functions
 */
function wpsGetBasePrefix() { global $wpdb; return $wpdb->base_prefix; }
function wpsIsMultisite(){ return (function_exists('is_multisite') && is_multisite()); }
function wpsIsMainSite(){ global $wpdb; return($wpdb->prefix == $wpdb->base_prefix); }
function _wpsSiteActivate($mu = false, $blogID = 1)
{
    wssLog(__FUNCTION__."() called with args: ",array('$mu'=>$mu, '$blogID'=>$blogID));
    //#! check if already installed if MU
    if($mu){ return WsdPlugin::networkActivate(); }
    else { return WsdPlugin::activate(); }
}
function _wpsSiteDeactivate($mu = false, $blogID=1)
{
    WsdScheduler::unregisterCronTasks();
    if($mu){
        delete_blog_option($blogID, 'WPS_NETWORK_INSTALL');
        delete_blog_option($blogID, 'WPS_PLUGIN_ACTIVATED');
        delete_blog_option($blogID, WpsSettings::WP_FILE_SCAN_OPTION_NAME);
        delete_blog_option($blogID, WpsSettings::PLUGIN_ERROR_NOTICE_OPTION);
        delete_blog_option($blogID, WpsSettings::CAN_RUN_TASKS_OPTION_NAME);
        delete_blog_option($blogID, WpsSettings::ENABLE_LIVE_TRAFFIC);
    }
    else {
        delete_option('WPS_PLUGIN_ACTIVATED');
        delete_option(WpsSettings::WP_FILE_SCAN_OPTION_NAME);
        delete_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION);
        delete_option(WpsSettings::CAN_RUN_TASKS_OPTION_NAME);
        delete_option(WpsSettings::ENABLE_LIVE_TRAFFIC);
    }
}
function wpsNetworkActivate($networkwide=false){
    if (wpsIsMultisite())
    {
        global $wpdb;
        if($networkwide)
        {
            $old_blog = $wpdb->blogid;
            // Get all blog ids
            $blogIds = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blogIds as $blog_id) {
                switch_to_blog($blog_id);
                update_blog_option($blog_id, 'WPS_PLUGIN_ACTIVATED', 1);
                delete_blog_option($blog_id, WpsSettings::PLUGIN_ERROR_NOTICE_OPTION);
            }
            switch_to_blog($old_blog);

            if(_wpsSiteActivate(true, $old_blog))
            {
                add_blog_option($old_blog, 'WPS_KEEP_NUM_ENTRIES_LT',500);
                add_blog_option($old_blog, 'WPS_REFRESH_RATE_AJAX_LT',10);

                add_blog_option($old_blog, 'WPS_NETWORK_INSTALL', 1);
                add_blog_option($old_blog, 'WPS_PLUGIN_ACTIVATED', 1);
                add_blog_option($old_blog, WpsSettings::ENABLE_LIVE_TRAFFIC, 1);
            }
            else {
                $notices = get_blog_option($wpdb->blogid, WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());
                $notices[]= '<p><strong>'.WPS_PLUGIN_NAME.'</strong></p><p><strong>Error:</strong> An error has occurred while installing the plugin.</p>';
                update_site_option($wpdb->blogid, WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
            }
        }
        else {
            wp_redirect(network_admin_url('plugins.php'));
            exit;
        }
    }
    else {
        add_option('WPS_KEEP_NUM_ENTRIES_LT',500);
        add_option('WPS_REFRESH_RATE_AJAX_LT',10);
        add_option(WpsSettings::ENABLE_LIVE_TRAFFIC,1);
        _wpsSiteActivate();
    }
}
function wpsNetworkDeactivate($networkwide=false){
    if (wpsIsMultisite() && $networkwide) {
        global $wpdb;
        $old_blog = $wpdb->blogid;
        // network deactivate
        $blogIds = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ($blogIds as $blog_id) {
            switch_to_blog($blog_id);
            delete_blog_option($blog_id, 'WPS_PLUGIN_ACTIVATED');
            delete_blog_option($blog_id, WpsSettings::PLUGIN_ERROR_NOTICE_OPTION);
        }
        // main site
        switch_to_blog($old_blog);
        _wpsSiteDeactivate(true, $old_blog);
    }
    else { _wpsSiteDeactivate(); }
}

function wpsCreateSiteMenu(){
    $reqCap = 'administrator';
    if (current_user_can($reqCap) && function_exists('add_menu_page')){
        add_menu_page('WP Security', 'WP Security', $reqCap, WPS_PLUGIN_PREFIX, array('WsdPlugin','pageMain'), WsdUtil::imageUrl('logo-small.png'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'Dashboard', 'Dashboard', $reqCap, WPS_PLUGIN_PREFIX, array('WsdPlugin','pageMain'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'WP Info', 'WP Info', $reqCap, WPS_PLUGIN_PREFIX.'scanner', array('WsdPlugin','pageWpInfo'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'Live traffic', 'Live traffic', $reqCap, WPS_PLUGIN_PREFIX.'live_traffic', array('WsdPlugin','pageLiveTraffic'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'Blog', 'Blog', $reqCap, WPS_PLUGIN_PREFIX.'blog', array('WsdPlugin','pageBlog'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'About', 'About', $reqCap, WPS_PLUGIN_PREFIX.'about', array('WsdPlugin','pageAbout'));
    }
}
function wpsCreateNetworkMenu(){
    $reqCap = 'administrator';
    if (current_user_can($reqCap) && function_exists('add_menu_page')){
        add_menu_page('WP Security', 'WP Security', $reqCap, WPS_PLUGIN_PREFIX, array('WsdPlugin','pageMain'), WsdUtil::imageUrl('logo-small.png'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'Dashboard', 'Dashboard', $reqCap, WPS_PLUGIN_PREFIX, array('WsdPlugin','pageMain'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'Database', 'Database', $reqCap, WPS_PLUGIN_PREFIX.'database', array('WsdPlugin','pageDatabase'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'WP Info', 'WP Info', $reqCap, WPS_PLUGIN_PREFIX.'scanner', array('WsdPlugin','pageWpInfo'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'WP File Scan', 'WP File Scan', $reqCap, WPS_PLUGIN_PREFIX.'wpscan', array('WsdPlugin','pageWpFileScan'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'Live traffic', 'Live traffic', $reqCap, WPS_PLUGIN_PREFIX.'live_traffic', array('WsdPlugin','pageLiveTraffic'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'Blog', 'Blog', $reqCap, WPS_PLUGIN_PREFIX.'blog', array('WsdPlugin','pageBlog'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'Settings', 'Settings', $reqCap, WPS_PLUGIN_PREFIX.'settings', array('WsdPlugin','pageSettings'));
        add_submenu_page(WPS_PLUGIN_PREFIX, 'About', 'About', $reqCap, WPS_PLUGIN_PREFIX.'about', array('WsdPlugin','pageAbout'));
    }
}

function wpsRunFixes(){
    $methods = WpsSettings::getSettingsList();
    if(empty($methods)){ return false;}
    foreach($methods as $method){
        add_action('init', array('WsdSecurity',$method['name']));
    }
}

