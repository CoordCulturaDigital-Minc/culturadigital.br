<?php if(! defined('WPS_PLUGIN_PREFIX')) return;

/**
 * Class WsdUtil
 * Static class. Provides utility methods for various tasks
 */
class WsdUtil
{
    /**
     * @return bool
     * Convenient method to check whether or not the plugin's resources can be loaded
     */
    static function canLoad() { return ((false === ($pos = stripos($_SERVER['REQUEST_URI'], WPS_PLUGIN_PREFIX))) ? false : true); }
    static function cssUrl($fileName){ return WPS_PLUGIN_URL.'res/css/'.$fileName; }
    static function imageUrl($fileName){ return WPS_PLUGIN_URL.'res/images/'.$fileName; }
    static function jsUrl($fileName) { return WPS_PLUGIN_URL.'res/js/'.$fileName; }
    static function resUrl() { return WPS_PLUGIN_URL.'res/'; }
    static function includePage($fileName)
    {
        if(! self::canLoad()) { return; }
        $dirPath = WPS_PLUGIN_DIR.'res/pages/';
        if(! is_dir($dirPath)) { return; }
        if(! is_readable($dirPath)) { return; }
        $fname = $dirPath.$fileName;
        if(false !== ($pos = strpos($fname, '../')) || false !== ($pos = strpos($fname, './'))){ return; }
        if(! is_file($fname) || ! is_readable($fname)) { return; }
        include($fname);
    }

    /**
     * @public
     * @static
     * Load the text domain
     * @return void
     */
    static function loadTextDomain(){ if ( function_exists('load_plugin_textdomain') ) { load_plugin_textdomain(WpsSettings::TEXT_DOMAIN, false, WPS_PLUGIN_DIR.'res/languages/'); } }

    /**
     * @public
     * @static
     * @uses self::checkFileName()
     *
     * Retrieve the content of the specified template file.
     *
     * @param type $fileName the name of the template file to load.
     * Without the ".php" file extension.
     * @param array $data The data to send to the template file
     * @return string The parsed content of the template file
     */
    static function loadTemplate($fileName, array $data = array())
    {
        self::checkFileName($fileName);
        $str = '';
        $file = WPS_PLUGIN_DIR.'res/pages/tpl/'.$fileName.'.php';
        if (is_file($file))
        {
            ob_start();
            if (!empty($data)) {
                extract($data);
            }
            include($file);
            $str = ob_get_contents();
            ob_end_clean();
        }
        return $str;
    }

    /**
     * @public
     * @static
     * @uses wp_die()
     *
     * Check the specified file name for directory traversal attacks.
     * Exits the script if the "..[/]" is found in the $fileName.
     *
     * @param string $fileName The name of the file to check
     * @return void
     */
    static function checkFileName($fileName)
    {
        $fileName = trim($fileName);
        //@@ Check for directory traversal attacks
        if (preg_match("/\.\.\//",$fileName)) {
            wp_die('Invalid Request!');
        }
    }

    /**
     * @public
     * @static
     *
     * Attempts to write the provided $data into the specified $file
     * using either file_put_contents or fopen/fwrite functions (whichever is available).
     *
     * @param  string $file The path to the file
     * @param string $data The content to write into the file
     * @param resource $fh The file handle to use if fopen function is available. Optional, defaults to null
     *
     * @return int  The number of bytes written to the file, otherwise -1.
     */
    static function writeFile($file, $data, $fh = null)
    {
        if(! is_null($fh) && is_resource($fh)){
            fwrite($fh,$data);
            return strlen($data);
        }
        else {
            if (function_exists('file_put_contents')) {
                return file_put_contents($file,$data);
            }
        }
        return -1;
    }

    /**
     * @public
     * @param array $acxFileList
     * Apply the suggested permissions for the list of files
     * provided in the global $acxFileList array.
     * @return array  array('success' => integer, 'failed' => integer)
     */
    static function changeFilePermissions($acxFileList)
    {
        if (empty($acxFileList)) {
            return array();
        }
        // chmod doesn't work on windows... :/
        if (self::isWinOs()) {
            return array();
        }

        $s = $f = 0;
        foreach($acxFileList as $k => $v)
        {
            $filePath = $v['filePath'];
            $sp = $v['suggestedPermissions'];
            $sp = (is_string($sp) ? octdec($sp) : $sp);

            // if this is the readme file
            $isReadme = false;
            if(false !== ($pos = stripos($filePath, 'readme'))){
                $isReadme = true;
            }

            //@ include directories too
            if (file_exists($filePath))
            {
                if (false !== @chmod($filePath, $sp)) {
                    $s++;
                }
                else { $f++; }
            }
            else {
                // if no path provided
                if(empty($filePath)){
                    $f++;
                    continue;
                }
                if($isReadme){ // ignore the missing readme.html file
                    continue;
                }
                if (false !== @chmod($filePath, $sp)) {
                    $s++;
                }
                else { $f++; }
            }
        }
        return array('success' => $s, 'failed' => $f);
    }

    static function getWpConfigFilePath()
    {
        $path = ABSPATH.'wp-config.php';
        if(! is_file($path)){
            // search one level up
            $path = realpath('../'.ABSPATH) . '/wp-config.php';
            if(! is_file($path)){
                return '';
            }
        }
        return $path;
    }


    static function getFilePermissions($filePath)
    {
        if (!function_exists('fileperms')) {
            return '-1';
        }
        if (!file_exists($filePath)) {
            return '-1';
        }
        clearstatcache();
        return substr(sprintf("%o", fileperms($filePath)), -4);
    }

    static function normalizePath($path) {
        return str_replace('\\', '/', $path);
    }

    static function isWinOs(){
        return ((strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? true : false);
    }

    /**
     * Check to see whether or not the current user is an administrator
     * @return bool
     */
    static function isAdministrator(){
        self::loadPluggable();
        return user_can(wp_get_current_user(),'administrator');
    }

    /**
     * Check to see whether or not the specified table exists in the database
     * @param $tableName The table to check for existence. It requires the full qualified name of the table
     *                      - which means the prefix must be there as well.
     * @return bool
     */
    static function tableExists($tableName)
    {
        global $wpdb;
        $result = $wpdb->get_var("SHOW TABLES LIKE '$tableName'");
        return (is_null($result) ? false : true);
    }

    /**
     * @public
     * @uses wp_die()
     *
     * Backup the database and save the script to the plug-in's backups directory.
     * This directory must be writable!
     *
     * @return string The name of the generated backup file or empty string on failure.
     */
    static function backupDatabase()
    {
        if (!is_writable(WPS_PLUGIN_BACKUPS_DIR))
        {
            $s = sprintf(__('The %s directory <strong>MUST</strong> be writable for this feature to work!',WpsSettings::TEXT_DOMAIN), WPS_PLUGIN_BACKUPS_DIR);
            wp_die($s);
        }

        $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        if (!$link) {
            wp_die(__('Error: Cannot connect to database.',WpsSettings::TEXT_DOMAIN));
        }
        if (!mysql_select_db(DB_NAME,$link)) {
            wp_die(__('Error: Could not select the database.',WpsSettings::TEXT_DOMAIN));
        }

        //get all of the tables
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while($row = mysql_fetch_row($result))
        {
            if(! empty($row[0])){
                $tables[] = $row[0];
            }
        }

        if (empty($tables))
        {
            wp_die(__('Could not retrieve the list of tables from the database.',WpsSettings::TEXT_DOMAIN));
        }

        $h = null;
        $time = gmdate("m-j-Y-h-i-s", time());
        $rand = self::makeSeed()+rand(12131, 9999999);
        $fname = 'bck_'.$time.'_'.$rand.'.sql';
        $filePath = WPS_PLUGIN_BACKUPS_DIR.$fname;

        if(function_exists('fopen') && function_exists('fwrite') && function_exists('fclose'))
        {
            $h = fopen($filePath,'a+');
            self::__doBackup($filePath, $tables, $h);
            fclose($h);
        }
        else {
            if(function_exists('file_put_contents')){
                self::__doBackup($filePath, $tables, $h);
            }
        }
        if(! is_file($filePath)){
            return '';
        }
        $fs = @filesize($filePath);
        return (($fs > 0) ? $fname : '');
    }

    /**
     * @private
     */
    private static function __doBackup($filePath, array $tables = array(), $h = null)
    {
        $data = 'CREATE DATABASE IF NOT EXISTS '.DB_NAME.';'.PHP_EOL;
        $data .= 'USE '.DB_NAME.';'.PHP_EOL;
        self::writeFile($filePath, $data, $h);

        //cycle through
        foreach($tables as $table)
        {
            $result = mysql_query('SELECT * FROM '.$table);
            $num_fields = mysql_num_fields($result);

            $data = 'DROP TABLE IF EXISTS '.$table.';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
            $data .= $row2[1].';'.PHP_EOL;
            self::writeFile($filePath, $data, $h);

            for ($i = 0; $i < $num_fields; $i++)
            {
                while($row = mysql_fetch_row($result))
                {
                    $data = 'INSERT INTO '.$table.' VALUES(';
                    for($j=0; $j<$num_fields; $j++)
                    {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = @preg_replace("/\n(\s*\n)+/",PHP_EOL,$row[$j]);
                        if (isset($row[$j])) { $data .= '"'.$row[$j].'"' ; } else { $data .= '""'; }
                        if ($j<($num_fields-1)) { $data .= ','; }
                    }
                    $data .= ");".PHP_EOL;
                    self::writeFile($filePath, $data, $h);
                }
            } //#! end for
        } //#! end foreach
    }

    /**
     * @public
     * Retrieve the list of all available backup files from the backups directory
     * @return array
     */
    static function getAvailableBackupFiles()
    {
        $files = glob(WPS_PLUGIN_BACKUPS_DIR.'*.sql');
        if (empty($files)) { return array();}
        return array_map('basename', $files/*, array('.sql')*/);
    }

    /**
     * @public
     * Create a number
     * @return double
     */
    static function makeSeed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return (float)$sec + ((float)$usec * 100000);
    }

    /**
     * @public
     * @global object $wpdb
     * Get the list of tables to modify
     * @return array
     */
    static function getTablesToAlter()
    {
        global $wpdb;
        return $wpdb->get_results("SHOW TABLES LIKE '".$GLOBALS['table_prefix']."%'", ARRAY_N);
    }

    /**
     * @public
     * @global object $wpdb
     * Rename tables from database
     * @param array the list of tables to rename
     * @param string $currentPrefix the current prefix in use
     * @param string $newPrefix the new prefix to use
     * @return array
     */
    static function renameTables($tables, $currentPrefix, $newPrefix)
    {
        global $wpdb;
        $changedTables = array();
        foreach ($tables as $k=>$table){
            $tableOldName = $table[0];
            // Try to rename the table
            $tableNewName = substr_replace($tableOldName, $newPrefix, 0, strlen($currentPrefix));
            // Try to rename the table
            $wpdb->query("RENAME TABLE `{$tableOldName}` TO `{$tableNewName}`");
            array_push($changedTables, $tableNewName);
        }
        return $changedTables;
    }

    /**
     * @public
     * @global object $wpdb
     * Rename some fields from options & usermeta tables in order to reflect the prefix change
     * @param string $oldPrefix the existent db prefix
     * @param string $newPrefix the new prefix to use
     * @return string
     */
    static function renameDbFields($oldPrefix,$newPrefix)
    {
        global $wpdb;
        $str = '';
        if (false === $wpdb->query("UPDATE {$newPrefix}options SET option_name='{$newPrefix}user_roles' WHERE option_name='{$oldPrefix}user_roles';")) {
            $str .= '<br/>'.sprintf(__('Changing value: %suser_roles in table <strong>%soptions</strong>: <span style="color:#ff0000;">Failed</span>',WpsSettings::TEXT_DOMAIN),$newPrefix, $newPrefix);
        }
        $query = 'UPDATE '.$newPrefix.'usermeta
                SET meta_key = CONCAT(replace(left(meta_key, ' . strlen($oldPrefix) . "), '{$oldPrefix}', '{$newPrefix}'), SUBSTR(meta_key, " . (strlen($oldPrefix) + 1) . "))
            WHERE
                meta_key IN ('{$oldPrefix}autosave_draft_ids', '{$oldPrefix}capabilities', '{$oldPrefix}metaboxorder_post', '{$oldPrefix}user_level', '{$oldPrefix}usersettings',
                '{$oldPrefix}usersettingstime', '{$oldPrefix}user-settings', '{$oldPrefix}user-settings-time', '{$oldPrefix}dashboard_quick_press_last_post_id')";

        if (false === $wpdb->query($query)) {
            $str .= '<br/>'.sprintf(__('Changing values in table <strong>%susermeta</strong>: <span style="color:#ff0000;">Failed</span>',WpsSettings::TEXT_DOMAIN), $newPrefix);
        }
        if (!empty($str)) {
            $str = __('Changing database prefix',WpsSettings::TEXT_DOMAIN).': '.$str;
        }
        return $str;
    }

    /**
     * @public
     * Update the wp-config file to reflect the table prefix change.
     * The wp file must be writable for this operation to work!
     *
     * @param string $wsd_wpConfigFile The path to the wp-config file
     * @param string $newPrefix The new prefix to use instead of the old one
     * @return int the number of bytes written to te file or -1 on error
     */
    static function updateWpConfigTablePrefix($wsd_wpConfigFile, $newPrefix)
    {
        // If file is not writable...
        if (!is_writable($wsd_wpConfigFile)){
            return -1;
        }

        // We need the 'file' function...
        if (!function_exists('file')) {
            return -1;
        }

        // Try to update the wp-config file
        $lines = file($wsd_wpConfigFile);
        $fcontent = '';
        $result = -1;
        foreach($lines as $line)
        {
            $line = ltrim($line);
            if (!empty($line)){
                if (strpos($line, '$table_prefix') !== false){
                    $line = preg_replace("/=(.*)\;/", "= '".$newPrefix."';", $line);
                }
            }
            $fcontent .= $line;
        }
        if (!empty($fcontent))
        {
            // Save wp-config file
            $result = self::writeFile($wsd_wpConfigFile, $fcontent);
        }
        return $result;
    }


    private static $_pluginID = 'acx_plugin_dashboard_widget';

    /**
     * @public
     * @static
     * @const BLOG_FEED
     * Retrieve and display a list of links for an existing RSS feed, limiting the selection to the 5 most recent items.
     * @return void
     */
    static function displayDashboardWidget()
    {
        //@ flag
        $run = false;

        //@ check cache
        $optData = WpsOption::getOption(WpsSettings::FEED_DATA_OPTION_NAME);
        if (! empty($optData))
        {
            if (is_object($optData))
            {
                $lastUpdateTime = @$optData->expires;
                // invalid cache
                if (empty($lastUpdateTime)) { $run = true; }
                else
                {
                    $nextUpdateTime = $lastUpdateTime+(24*60*60);
                    if ($nextUpdateTime >= $lastUpdateTime)
                    {
                        $data = @$optData->data;
                        if (empty($data)) { $run = true; }
                        else {
                            // still a valid cache
                            echo $data;
                            return;
                        }
                    }
                    else { $run = true; }
                }
            }
            else { $run = true; }
        }
        else { $run = true; }

        if (!$run) { return; }

        $rss = fetch_feed(WpsSettings::BLOG_FEED);

        $out = '';
        if (is_wp_error( $rss ) )
        {
            $out = '<li>'.__('An error has occurred while trying to load the rss feed.',WpsSettings::TEXT_DOMAIN).'</li>';
            echo $out;
            return;
        }
        else
        {
            // Limit to 5 entries.
            $maxitems = $rss->get_item_quantity(5);

            // Build an array of all the items,
            $rss_items = $rss->get_items(0, $maxitems);

            $out .= '<ul>';
            if ($maxitems == 0)
            {
                $out.= '<li>'.__('There are no entries for this rss feed.',WpsSettings::TEXT_DOMAIN).'</li>';
            }
            else
            {
                foreach ( $rss_items as $item ) :
                    $url = esc_url($item->get_permalink());
                    $out.= '<li>';
                    $out.= '<h4><a href="'.$url.'" target="_blank" title="Posted on '.$item->get_date('F j, Y | g:i a').'">';
                    $out.= esc_html( $item->get_title() );
                    $out.= '</a></h4>';
                    $out.= '<p>';
                    $d = sanitize_text_field( $item->get_description());
                    $p = substr($d, 0, 120).' <a href="'.$url.'" target="_blank" title="Read all article">[...]</a>';
                    $out.= $p;
                    $out.= '</p>';
                    $out.= '</li>';
                endforeach;
            }
            $out.= '</ul>';
            $out .= '<div style="border-top: solid 1px #ccc; margin-top: 4px; padding: 2px 0;">';
            $out .= '<p style="margin: 5px 0 0 0; padding: 0 0; line-height: normal; overflow: hidden;">';
            $out .= '<a href="http://feeds.acunetix.com/acunetixwebapplicationsecurityblog"
                                style="float: left; display: block; width: 50%; text-align: right; margin-left: 30px;
                                padding-right: 22px; background: url('.self::imageUrl('rss.png').') no-repeat right center;"
                                target="_blank">'.__('Follow us on RSS',WpsSettings::TEXT_DOMAIN).'</a>';
            $out .= '</p>';
            $out .= '</div>';
        }
        // Update cache
        $obj = new stdClass();
        $obj->expires = time();
        $obj->data = $out;
        WpsOption::updateOption(WpsSettings::FEED_DATA_OPTION_NAME, $obj);
        echo $out;
    }

    /**
     * @public
     * @static
     * Add the rss widget to dashboard
     * @return void
     */
    static function addDashboardWidget()
    {
        $rssWidgetData = WpsOption::getOption('WSD-RSS-WGT-DISPLAY');
        if(($rssWidgetData == 'yes'))
        {
            if(wpsIsMultisite()){
                global $wpdb;
                $old_blog = $wpdb->blogid;
                // Get all blog ids
                $blogIds = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blogIds as $blog_id) {
                    switch_to_blog($blog_id);
                    wp_add_dashboard_widget('acx_plugin_dashboard_widget', __('Acunetix news and updates',WpsSettings::TEXT_DOMAIN), array('WsdUtil','displayDashboardWidget'));
                }
                switch_to_blog($old_blog);
                wp_add_dashboard_widget('acx_plugin_dashboard_widget', __('Acunetix news and updates',WpsSettings::TEXT_DOMAIN), array('WsdUtil','displayDashboardWidget'));
            }
            else { wp_add_dashboard_widget('acx_plugin_dashboard_widget', __('Acunetix news and updates',WpsSettings::TEXT_DOMAIN), array('WsdUtil','displayDashboardWidget')); }
        }
    }

    /**
     * This method allows the scanner to ignore a default WP file if not found
     * @param string $crtFullFilePath
     * @return bool
     */
    static function canIgnoreScanPath($crtFullFilePath)
    {
        // search in themes
        $themes = WpsSettings::$ignoreThemes;
        foreach($themes as $themeName){
            if(false !== ($pos = stripos($crtFullFilePath, $themeName))){
                return true;
            }
        }
        // search in plugins
        $plugins = WpsSettings::$ignorePlugins;
        foreach($plugins as $pluginPath){
            if(false !== ($pos = stripos($crtFullFilePath, $pluginPath))){
                return true;
            }
        }
        return false;
    }

    static function loadPluggable(){ @require_once(ABSPATH.'wp-includes/pluggable.php'); }

    // utility method to delete backup files. ajax only
    static function ajaxDeleteBackupFile()
    {
        if(! isset($_REQUEST['nonce'])){ exit('Invalid request'); }
        if ( !wp_verify_nonce( $_REQUEST['nonce'], "wpsBackupFileDelete_nonce")) { exit('Invalid request'); }

        $result = array(
            'type' => '',
            'data' => ''
        );

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            if ( !isset( $_REQUEST['file'])) {
                $result['type'] = 'error';
                $result['data'] = 'Invalid request';
                exit(json_encode($result));
            }
            $basePath = self::normalizePath(WPS_PLUGIN_BACKUPS_DIR);
            $fileName = self::normalizePath($_REQUEST['file']);
            $filePath = $basePath.$fileName;
            // prevent directory traversal attacks
            $filePath = self::normalizePath(realpath($filePath));
            if(false === ($pos = strpos($filePath, $basePath))){
                $result['type'] = 'error';
                $result['data'] = 'Invalid request';
                exit(json_encode($result));
            }
            if(! is_file($filePath)){
                $result['type'] = 'error';
                $result['data'] = 'Invalid request';
                exit(json_encode($result));
            }

            if(true === ($e = @unlink($filePath))){
                $result['type'] = 'success';
                $result['data'] = 'File '.$filePath.' has been deleted.';
            }
            else {
                $result['type'] = 'error';
                $result['data'] = 'File '.$filePath.' could not be deleted.';
            }
            exit(json_encode($result));
        }
        exit('Invalid request');
    }
}