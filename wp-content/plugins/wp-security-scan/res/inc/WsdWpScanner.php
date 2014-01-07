<?php if(! defined('WPS_PLUGIN_PREFIX')) return;

class WsdWPScanSettings
{
    //#! Whether or not the scan is running
    const SCAN_STATE_DONE = 0;
    const SCAN_STATE_IN_PROGRESS = 1;
    const SCAN_STATE_WAITING = 2; // if no scans completed
    const SCAN_STATE_NONE = 3;

    //#! Scan progress states
    const SCAN_PROGRESS_NONE = 0;
    const SCAN_PROGRESS_ROOT = 1;
    const SCAN_PROGRESS_ADMIN = 2;
    const SCAN_PROGRESS_CONTENT = 3;
    const SCAN_PROGRESS_INCLUDES = 4;

    //#! Scan results
    const SCAN_RESULT_FAIL = 0;
    const SCAN_RESULT_SUCCESS = 1;
    const SCAN_RESULT_NONE = 2;

    //#! cached version of settings
    private static $_settings = null;

    public static function getSettings()
    {
        $optData = WpsOption::getOption(WpsSettings::WP_FILE_SCAN_OPTION_NAME);
        if(false === $optData){
            // option not found : create default settings
            $optData = self::_setDefaultSettings();
            WpsOption::updateOption(WpsSettings::WP_FILE_SCAN_OPTION_NAME, $optData);
        }
        return $optData;
    }

    private static function _setDefaultSettings()
    {
        return array(
            'SCAN_STATE' => self::SCAN_STATE_NONE,
            'SCAN_PROGRESS' => self::SCAN_PROGRESS_NONE,
            'SCAN_RESULT' => self::SCAN_RESULT_NONE,
            'SCAN_TYPE' => 0,
            'SCAN_ID' => 0
        );
    }

    public static function getSetting($name)
    {
        if(empty(self::$_settings)){ self::$_settings = self::getSettings(); }
        $name = strtoupper($name);
        return (isset(self::$_settings[$name]) ? self::$_settings[$name] : null);
    }

    // Add or update a setting
    public static function updateSetting($name, $value, $autoSave = true)
    {
        if(empty(self::$_settings)){ self::$_settings = self::getSettings(); }
        $name = strtoupper($name);
        self::$_settings[$name] = $value;
        if($autoSave){
            self::saveSettings();
        }
    }

    public static function saveSettings()
    {
        WpsOption::updateOption(WpsSettings::WP_FILE_SCAN_OPTION_NAME, self::$_settings);
    }

    public static function isValidState($state)
    {
        if(empty(self::$_settings)){ self::$_settings = self::getSettings(); }
        $state = strtoupper($state);
        if(! in_array($state, array(self::SCAN_STATE_DONE, self::SCAN_STATE_IN_PROGRESS, self::SCAN_STATE_WAITING, self::SCAN_STATE_NONE))){
            return false;
        }
        return true;
    }

    public static function isValidProgress($progress)
    {
        if(empty(self::$_settings)){ self::$_settings = self::getSettings(); }
        $progress = strtoupper($progress);
        if(! in_array($progress, array(self::SCAN_PROGRESS_NONE, self::SCAN_PROGRESS_ROOT, self::SCAN_PROGRESS_ADMIN, self::SCAN_PROGRESS_CONTENT, self::SCAN_PROGRESS_INCLUDES))){
            return false;
        }
        return true;
    }

    public static function isValidScanType($type)
    {
        return (in_array($type, array(0,1,2,3,4,5,6,7,8)) ? true : false);
    }

    public static function deleteSettings() {
        WpsOption::deleteOption(WpsSettings::WP_FILE_SCAN_OPTION_NAME);
        wssLog('Settings deleted.');
    }
}


class WsdWpScanner extends WsdPlugin
{
    // retrieve the current state of the scan or null if not a valid state
    public static function getScanState()
    {
        $state = WsdWPScanSettings::getSetting('SCAN_STATE');
        if(! WsdWPScanSettings::isValidState($state)){
            return null;
        }
        return $state;
    }
    // retrieve the current progress of the scan or null if not a valid progress
    public static function getScanProgress()
    {
        $progress = WsdWPScanSettings::getSetting('SCAN_PROGRESS');
        if(! WsdWPScanSettings::isValidProgress($progress)){
            return null;
        }
        return $progress;
    }

    public static function getScanInfo($scanID)
    {
        global $wpdb;
        $query = "SELECT scanId, scanStartDate, scanEndDate, scanResult, failReason, scanType FROM ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." WHERE scanId = ".$scanID." ORDER BY scanId DESC";
        return $wpdb->get_row($query);
    }

    //
    public static function getScans($_limit = 0)
    {
        global $wpdb;

        //#!++ ignore the currently running scan ID
        $currentScanID = WsdWPScanSettings::getSetting('SCAN_ID');
        $where = '';
        if(! empty($currentScanID)){
            $where = " WHERE scanId != ".$currentScanID;
        }
        $limit = '';
        if(! empty($_limit)){
            $limit = intval($_limit);
            if($limit == 0){
                $limit = 1;
            }
            $limit = " LIMIT 0,".$limit;
        }
        $query = "SELECT scanId, scanEndDate, scanResult FROM ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME).$where." ORDER BY scanId DESC".$limit;
        return $wpdb->get_results($query);
    }


    /**
     * This method will start and check the scan. wp-cron access only.
     */
    public static function checkWpScan()
    {
        wssLog(__METHOD__.'() called.');

        $scanState = WsdWPScanSettings::getSetting('SCAN_STATE');
        if($scanState <> WsdWPScanSettings::SCAN_STATE_WAITING){
            $failReason = "Could not start scan. Invalid scan state provided. Expecting: SCAN_STATE_WAITING (".WsdWPScanSettings::SCAN_STATE_WAITING.") received: ".$scanState;
            wssLog($failReason);
            WpScan::stopScan(false, $failReason);
            return false;
        }
        //#! Start scan
        WpScan::startScan();
    }

    // will register a scan
    // scan type: the date to check against
    public static function registerScan($scanType = 0)
    {
        // check to see whether or not a scan is pending to start
        if(WsdWPScanSettings::getSetting('SCAN_STATE') == WsdWPScanSettings::SCAN_STATE_WAITING){
            return -1;
        }
        // check to see whether or not there is a scan running already
        if(WsdWPScanSettings::getSetting('SCAN_STATE') == WsdWPScanSettings::SCAN_STATE_IN_PROGRESS){
            return 0;
        }
        if(! WsdWPScanSettings::isValidScanType($scanType)){
            return 1;
        }

        global $wpdb;

        // add new scan
        $id = null;
        //$query = "INSERT INTO ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." (scanStartDate, scanEndDate, scanType) VALUES(CURRENT_TIMESTAMP(),'0000-00-00 00:00:00')";
        $query = "INSERT INTO ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." (scanStartDate, scanType) VALUES(CURRENT_TIMESTAMP(),$scanType)";
        $result = $wpdb->query($query);
        if(! empty($result)){
            $query = "SELECT scanId FROM ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." WHERE scanResult = 0 ORDER BY scanId DESC;";
            $id = $wpdb->get_var($query);
        }

        if(empty($id)){
            wssLog('Internal Error: could not retrieve the ID for the last added scan.', array('function'=>__METHOD__, 'line'=>__LINE__));
            return 2;
        }

        // update settings
        WsdWPScanSettings::updateSetting('SCAN_ID', $id, false);
        WsdWPScanSettings::updateSetting('SCAN_TYPE', $scanType);
        WsdWPScanSettings::updateSetting('SCAN_STATE', WsdWPScanSettings::SCAN_STATE_WAITING);

        return 3;
    }

    static function deleteAllScans()
    {
        wssLog(__METHOD__.'() triggered');

        global $wpdb;
        $scanTable = WsdPlugin::getTableName(WpsSettings::SCAN_TABLE_NAME);
        $scansTable = WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME);

        wssLog('Truncate tables.');

        $q = "TRUNCATE $scanTable";
        $result = $wpdb->query($q);
        if(false === $result){
            wssLog('MySQL Error: ', array('query'=>$q, 'error'=>$wpdb->last_error));
            return false;
        }
        $q = "TRUNCATE $scansTable";
        $result = $wpdb->query($q);
        if(false === $result){
            wssLog('MySQL Error: ', array('query'=>$q, 'error'=>$wpdb->last_error));
            return false;
        }
        wssLog('Tables truncated. Done deleting scans.');
        return true;
    }

    /** retrieve the scan id from scans table */
    public static function getLastScanID_table()
    {
        global $wpdb;
        $query = "SELECT scanId FROM ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." ORDER BY scanId DESC;";
        $result = $wpdb->get_var($query);
        return (empty($result) ? 0 : $result);
    }

    public static function getLastCompletedScanID()
    {
        global $wpdb;
        $query = "SELECT scanId FROM ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." WHERE scanResult = 1 ORDER BY scanId DESC;";
        $result = $wpdb->get_var($query);
        return (empty($result) ? 0 : $result);
    }

    public static function isValidScan($scanID)
    {
        $scan_id = WsdWPScanSettings::getSetting('SCAN_ID');
        if($scanID == $scan_id){
            return false;
        }
        global $wpdb;
        $query = $wpdb->prepare("SELECT scanId FROM ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." WHERE scanId = %d",$scanID);
        $result = $wpdb->get_var($query);
        return (empty($result) ? false : true);
    }

    public static function getFailedEntries($scanID)
    {
        global $wpdb;
        $query = $wpdb->prepare("SELECT filePath, dateModified, fileNotFound FROM ".WsdPlugin::getTableName(WpsSettings::SCAN_TABLE_NAME)." WHERE scanId = %d ORDER BY dateModified",$scanID);
        return $wpdb->get_results($query);
    }

    public static function deleteScan($scanID)
    {
        global $wpdb;
        $query1 = $wpdb->prepare("DELETE FROM ".WsdPlugin::getTableName(WpsSettings::SCAN_TABLE_NAME)." WHERE scanId = %d",$scanID);
        $query2 = $wpdb->prepare("DELETE FROM ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." WHERE scanId = %d",$scanID);
        if(($wpdb->query($query1) !== false) && ($wpdb->query($query2) !== false)){
            return true;
        }
        return false;
    }
}

class WpScan
{
    private static $_scanID = 0;

    public static function startScan()
    {
        wssLog(__METHOD__.'() triggered '.PHP_EOL.str_repeat('=', 50));

        $settings = WsdWPScanSettings::getSettings();

        $scanID = $settings['SCAN_ID'];
        $scanState = $settings['SCAN_STATE'];
        $scanProgress = $settings['SCAN_PROGRESS'];
        $scanResult = $settings['SCAN_RESULT'];
        $scanType = $settings['SCAN_TYPE'];
        self::$_scanID = $scanID;

        wssLog('SCAN DATA', array(
            '$scanID' => $scanID,
            '$scanState' => $scanState,
            '$scanProgress' => $scanProgress,
            '$scanResult' => $scanResult,
            '$scanType' => $scanType,
        ));

        // if scan id == 0 there is no scan registered
        if(empty($scanID))
        {
            $failReason = "Internal Error: No scan ID provided.";
            wssLog('No scan ID. Ignoring the scan start request.');
            self::stopScan(false, $failReason);
            return false;
        }

        //#! if scan state is none
        if($scanState == WsdWPScanSettings::SCAN_STATE_NONE)
        {
            wssLog('Invalid scan state. Ignoring the scan start request.', array(
                'state' => 'SCAN_STATE_NONE'
            ));
            return false;
        }

        //#! if scan in progress
        if($scanState == WsdWPScanSettings::SCAN_STATE_IN_PROGRESS)
        {
            wssLog('Scan is running. Ignoring the scan start request.', array(
                'state' => 'SCAN_STATE_IN_PROGRESS',
                'progress' => $scanProgress
            ));
            return false;
        }

        //#! Start scan
        //=============================

        global $wp_version;
        wssLog('WordPress version installed:', array('version'=>$wp_version));
        if(empty($wp_version)){
            $failReason = __("Could not retrieve the WordPress version.",WpsSettings::TEXT_DOMAIN);
            wssLog('Invalid WordPress version detected.');
            self::stopScan(false, $failReason);
            return false;
        }

        wssLog('Starting scan.', array('ID'=>self::$_scanID));

        @ignore_user_abort(true);
        @set_time_limit(WpsSettings::WPS_MAX_TIME_EXEC_LIMIT);

        //#! update scan state
        WsdWPScanSettings::updateSetting('SCAN_STATE', WsdWPScanSettings::SCAN_STATE_IN_PROGRESS);

        //#! Request the json file from server depending on the current WP version
        $json = null;
        $url = WpsSettings::getJsonRepoUrl()."{$wp_version}.json";
        wssLog('Retrieving json file.', array('path'=>$url));
        $c = @file_get_contents($url);
        if(empty($c)){
            $reason = sprintf(__("Error retrieving the json file from server for the detected WordPress version: %s. Scan aborted.",WpsSettings::TEXT_DOMAIN),$wp_version);
            wssLog($reason);
            self::stopScan(false, $reason);
            return false;
        }
        else {
            $data = json_decode($c);
            wssLog('Json file retrieved from path: '.$url);

            if(is_null($data)){
                $failReason = __('Error decoding the json file. The file might be empty or corrupted.',WpsSettings::TEXT_DOMAIN);
                wssLog($failReason,array('path'=>$url));
                self::stopScan(false, $failReason);
                return false;
            }

            //#! Ensure file is valid
            if(isset($data->root) && isset($data->wp_admin) && isset($data->wp_content) && isset($data->wp_includes))
            {
                $rootFiles = $data->root;
                $wpAdminFiles = $data->wp_admin;
                $wpContentFiles = $data->wp_content;
                $wpIncludesFiles = $data->wp_includes;

                if(empty($rootFiles)|| empty($wpAdminFiles) || empty($wpContentFiles) || empty($wpIncludesFiles)){
                    $failReason = __('Invalid json file retrieved from server.',WpsSettings::TEXT_DOMAIN);
                    wssLog($failReason,array('path'=>$url));
                    self::stopScan(false, $failReason);
                    return false;
                }

                //#! mark as ok for GC
                $data = null;

                $now = time();
                $h24 = 24 * 60 * 60;
                $since = 0;
                if($scanType == 0)    { $since = strtotime('midnight'); }
                elseif($scanType == 1){ $since = $now - $h24; }
                elseif($scanType == 2){ $since = $now - 2*$h24; }
                elseif($scanType == 3){ $since = $now - 3*$h24; }
                elseif($scanType == 4){ $since = $now - 4*$h24; }
                elseif($scanType == 5){ $since = $now - 5*$h24; }
                elseif($scanType == 6){ $since = $now - 6*$h24; }
                elseif($scanType == 7){ $since = $now - 7*$h24; }
                elseif($scanType == 8){ $since = strtotime("-1 months") - $h24 - $now; }

                WsdWPScanSettings::updateSetting('SCAN_PROGRESS',WsdWPScanSettings::SCAN_PROGRESS_ROOT, true);
                self::_checkFiles(ABSPATH, $rootFiles, $since, true);
                wssLog("root directory scan complete");

                WsdWPScanSettings::updateSetting('SCAN_PROGRESS',WsdWPScanSettings::SCAN_PROGRESS_ADMIN);
                self::_checkFiles(ABSPATH.'wp-admin/',$wpAdminFiles, $since, false, false, true);
                wssLog("wp-admin directory scan complete");

                WsdWPScanSettings::updateSetting('SCAN_PROGRESS',WsdWPScanSettings::SCAN_PROGRESS_CONTENT);
                self::_checkFiles(ABSPATH.'wp-content/',$wpContentFiles, $since,false,true);
                wssLog("wp-content directory scan complete");

                WsdWPScanSettings::updateSetting('SCAN_PROGRESS',WsdWPScanSettings::SCAN_PROGRESS_INCLUDES);
                self::_checkFiles(ABSPATH.'wp-includes/',$wpIncludesFiles, $since);
                wssLog("wp-includes directory scan complete");

                //#! Mark scan as completed
                self::stopScan(true);
                return true;
            }
            else {
                $failReason = __('Invalid json file retrieved from server.',WpsSettings::TEXT_DOMAIN);
                wssLog($failReason,array('path'=>$url));
                self::stopScan(false, $failReason);
                return false;
            }
        }
    }

    //#!
    private static function _checkFiles($basePath, array $files, $fileModifiedSince, $isWpRoot = false, $isWpContent = false, $isWpAdmin = false)
    {
        wssLog(__METHOD__.'(). Scanning: '.WsdUtil::normalizePath($basePath));
        foreach($files as $file)
        {
            $_file = $basePath.$file;
            $_file = WsdUtil::normalizePath($_file);
            if(! is_file($_file))
            {
                // if this is the root and wp-config.php file...
                if($isWpRoot)
                {
                    // safely ignore this file
                    if(strcasecmp($file,'wp-config-sample.php')==0){
                        wssLog('wp-config-sample.php file is missing but can be ignored. Skipping file check.');
                        continue;
                    }
                    elseif(strcasecmp($file,'wp-config.php')==0)
                    {
                        // check one level above
                        $path = realpath('../'.ABSPATH).'/'.$file;
                        if(is_file($path)){
                            $_file = $path;
                        }
                        else {
                            //#! Mark file not found
                            self::_markFileNotFound($_file);
                            continue;
                        }
                    }
                    elseif(strcasecmp($file,'readme.html')==0){
                        wssLog('readme.html file is missing but can be ignored. Skipping file check.');
                        continue;
                    }
                }
                elseif($isWpContent)
                {
                    // Ignore WP's default themes and plugins if not found
                    if(WsdUtil::canIgnoreScanPath($_file)){
                        wssLog($_file.' file is missing but can be ignored. Skipping file check.');
                        continue;
                    }
                }
                elseif($isWpAdmin)
                {
                    // safely ignore marked files from /wp-admin
                    if(strcasecmp($file,'install.php')==0){
                        wssLog('wp-admin/install.php file is missing but can be ignored. Skipping file check.');
                        continue;
                    }
                    // safely ignore marked files from /wp-admin
                    elseif(strcasecmp($file,'upgrade.php')==0){
                        wssLog('wp-admin/upgrade.php file is missing but can be ignored. Skipping file check.');
                        continue;
                    }
                }
                //#! Mark file not found
                self::_markFileNotFound($_file);
                continue;
            }
            $mdate = filemtime($_file);
            if($mdate > $fileModifiedSince){
                //#! Mark file as modified
                self::_markFileModified($_file, $mdate);
            }
        }
    }

    private static function _markFileNotFound($filePath)
    {
        global $wpdb;
        $query = $wpdb->prepare(
            "INSERT INTO ".WsdPlugin::getTableName(WpsSettings::SCAN_TABLE_NAME)." (scanId, filePath, fileNotFound) VALUES (%d,'%s',%d)"
            ,self::$_scanID, $filePath, 1);
        $wpdb->query($query);
    }

    private static function _markFileModified($filePath, $modifiedDate)
    {
        global $wpdb;
        $query = $wpdb->prepare(
            "INSERT INTO ".WsdPlugin::getTableName(WpsSettings::SCAN_TABLE_NAME)." (scanId, filePath, dateModified) VALUES (%d,'%s', '%s')"
            ,self::$_scanID, $filePath, date('Y-m-d H:i:s', $modifiedDate));
        $wpdb->query($query);
    }


    private static function _markScanFailed($scanID, $failReason = '')
    {
        wssLog(__METHOD__.'() triggered.', array('failReason'=>$failReason));
        global $wpdb;
        if(empty($failReason)){
            $query = $wpdb->prepare("UPDATE ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." SET scanEndDate = CURRENT_TIMESTAMP() WHERE scanId = %d", self::$_scanID);
        }
        else {
            $query = $wpdb->prepare("UPDATE ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." SET scanEndDate = CURRENT_TIMESTAMP(), failReason = '%s' WHERE scanId = %d"
                ,$failReason, $scanID);

        }
        $wpdb->query($query);
    }
    private static function _markScanCompleted()
    {
        $m = __METHOD__.'() ';
        wssLog($m.'triggered.');
        global $wpdb;
        $query = $wpdb->prepare("UPDATE ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME)." SET scanEndDate = CURRENT_TIMESTAMP(), scanResult = 1 WHERE scanId = %d", self::$_scanID);
        $wpdb->query($query);
        wssLog('Scan completed', array('ID'=>self::$_scanID));
    }

    // php shutdown function
    // mark scan as failed and delete settings
    public static function stopScan($completed = false, $failReason = '')
    {
        $scanID = self::$_scanID;
        if(empty($scanID)){
            $optData = WpsOption::getOption(WpsSettings::WP_FILE_SCAN_OPTION_NAME);
            if(empty($optData)){
                wssLog('Empty $optData. Checking db table for any incomplete scan.');
                $sid = WsdWpScanner::getLastScanID_table();
                if(empty($sid)){
                    wssLog('No incomplete scans found either.');
                    return;
                }
                else { wssLog("Incomplete scan found: $sid"); $scanID = $sid; }
            }
            else {
                $scanID = $optData['SCAN_ID'];
                if(empty($scanID)){
                    return;
                }
            }
        }
        $m = __METHOD__.'() ';
        wssLog($m.'triggered.');
        if($completed){
            self::_markScanCompleted();
        }
        else { wssLog('Fail reason: '.$failReason); self::_markScanFailed($scanID,$failReason); }

        WsdWPScanSettings::deleteSettings();
        wssLog('Scan ('.$scanID.') marked as '.($completed ? 'completed' : 'failed').' and options deleted.'.PHP_EOL.str_repeat('=', 50));
    }
}