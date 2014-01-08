<?php if(! defined('WPS_PLUGIN_PREFIX')) exit;

class WpsSettings
{
    /**
     * Whether or not logging is enabled. Defaults to false
     */
    const WPS_DEBUG = false;
    /**
     * Set the time execution (in seconds) for WP Scanner. Defaults to 0 (no timeout)
     */
    const WPS_MAX_TIME_EXEC_LIMIT = 0;
    /**
     * Informational alert. Value: 0
     */
    const ALERT_INFO = 0;
    /**
     * Low alert. Value: 1
     */
    const ALERT_LOW = 1;
    /**
     * Medium alert. Value: 2
     */
    const ALERT_MEDIUM = 2;
    /**
     * Critical alert. Value: 3
     */
    const ALERT_CRITICAL = 3;

    const ALERT_TYPE_OVERWRITE = 0;
    const ALERT_TYPE_STACK = 1;
    //#! The max number of stacked alerts to keep
    const ALERT_STACK_MAX_KEEP = 15;

    const ALERTS_TABLE_NAME = '_wsd_plugin_alerts';
    const LIVE_TRAFFIC_TABLE_NAME = '_wsd_plugin_live_traffic';
    const SCAN_TABLE_NAME = '_wsd_plugin_scan';
    const SCANS_TABLE_NAME = '_wsd_plugin_scans';

    const TEXT_DOMAIN = 'WSDWP_SECURITY';

    const PLUGIN_CAN_RUN_OPTION = 'WPS_PLUGIN_CAN_RUN';

    const PLUGIN_SETTINGS_OPTION_NAME = 'wps_plugin_settings';
    const PLUGIN_ERROR_NOTICE_OPTION = 'wps_plugin_install_error';
    /**
     * Set the path to the acunetix.com feed
     */
    const BLOG_FEED = 'http://www.acunetix.com/blog/';

    /** Holds the name of the option that will hold the WP Scan settings */
    const WP_FILE_SCAN_OPTION_NAME = 'wps_plugin_wp_scan';

    const FEED_DATA_OPTION_NAME = 'wps_plugin_feed_data';
    const BLOG_DATA_OPTION_NAME = 'wps_plugin_blogfeed_data';
    const ENABLE_LIVE_TRAFFIC = 'wps_plugin_enable_live_traffic';

    //#! Whether or not tasks can run
    const CAN_RUN_TASKS_OPTION_NAME = 'wps_can_run_tasks';

    const LIVE_TRAFFIC_ENTRIES = 'wps_live_traffic_entries';

    static $scanTypes = array(
        0 => 'Today',
        1 => 'Yesterday',
        2 => '2 days ago',
        3 => '3 days ago',
        4 => '4 days ago',
        5 => '5 days ago',
        6 => '6 days ago',
        7 => '7 days ago',
        8 => 'Last month',
    );

    static $ignoreThemes = array('twentyten', 'twentyeleven', 'twentytwelve', 'twentythirteen');
    static $ignorePlugins = array('akismet', 'hello.php');

    /**
     * Sets the list of files to check for permissions
     * @return array
     */
    static function getScanFileList()
    {
        $_wpsPlugin_base_path  = trailingslashit(ABSPATH);
        $_wpsPluginWpConfigPath = WsdUtil::getWpConfigFilePath();
        return array(
            //@@ Directories
            'root directory' => array( 'filePath' => $_wpsPlugin_base_path, 'suggestedPermissions' => '0755'),
            'wp-admin' => array( 'filePath' => $_wpsPlugin_base_path.'wp-admin', 'suggestedPermissions' => '0755'),
            'wp-content' => array( 'filePath' => $_wpsPlugin_base_path.'wp-content', 'suggestedPermissions' => '0755'),
            'wp-includes' => array( 'filePath' => $_wpsPlugin_base_path.'wp-includes', 'suggestedPermissions' => '0755'),
            //@@ Files
            '.htaccess' => array( 'filePath' => $_wpsPlugin_base_path.'.htaccess', 'suggestedPermissions' => '0644'),
            'readme.html' => array( 'filePath' => $_wpsPlugin_base_path.'readme.html', 'suggestedPermissions' => '0400'),
            'wp-config.php' => array( 'filePath' => $_wpsPluginWpConfigPath, 'suggestedPermissions' => '0644'),
            'wp-admin/index.php' => array( 'filePath' => $_wpsPlugin_base_path.'wp-admin/index.php', 'suggestedPermissions' => '0644'),
            'wp-admin/.htaccess' => array( 'filePath' => $_wpsPlugin_base_path.'wp-admin/.htaccess', 'suggestedPermissions' => '0644'),
        );
    }

    /**
     * @TODO: Always keep in sync with methods from WsdSecurity class...
     * @return array
     */
    static function getSettingsList()
    {
        return array(
            array('name' => 'fix_hideWpVersion',
                  'text' => 'Hide WordPress version for all users but administrators'),
            array('name' => 'fix_removeWpMetaGeneratorsFrontend',
                  'text' => 'Remove various meta tags generators from the blog\'s head tag for non-administrators'),
            array('name' => 'fix_removeReallySimpleDiscovery',
                  'text' => 'Remove Really Simple Discovery meta tags from front-end'),
            array('name' => 'fix_removeWindowsLiveWriter',
                  'text' => 'Remove Windows Live Writer meta tags from front-end'),
            array('name' => 'fix_disableErrorReporting',
                  'text' => 'Disable error reporting (php + db) for all but administrators'),
            array('name' => 'fix_removeCoreUpdateNotification',
                  'text' => 'Remove core update notifications from back-end for all but administrators'),
            array('name' => 'fix_removePluginUpdateNotifications',
                  'text' => 'Remove plug-ins update notifications from back-end'),
            array('name' => 'fix_removeThemeUpdateNotifications',
                  'text' => 'Remove themes update notifications from back-end'),
            array('name' => 'fix_removeLoginErrorNotificationsFrontEnd',
                  'text' => 'Remove login error notifications from front-end'),
            array('name' => 'fix_hideAdminNotifications',
                  'text' => 'Hide admin notifications for non admins'),
            array('name' => 'fix_preventDirectoryListing',
                  'text' => 'Try to create the index.php file in the wp-content, wp-content/plugins, wp-content/themes and wp-content/uploads directories to prevent directory listing'),
            array('name' => 'fix_removeWpVersionFromLinks',
                  'text' => 'Remove the version parameter from urls'),
            array('name' => 'fix_emptyReadmeFileFromRoot',
                  'text' => 'Empty the content of the readme.html file from the root directory'),
        );
    }

    static function getJsonRepoUrl(){
        $dirPath = WPS_PLUGIN_DIR.'res/json/fscan/';
        if(! is_dir($dirPath)){
            return null;
        }
        return $dirPath;
    }
}
