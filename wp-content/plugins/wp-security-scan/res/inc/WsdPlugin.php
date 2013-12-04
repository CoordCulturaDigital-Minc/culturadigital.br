<?php if(! defined('WPS_PLUGIN_PREFIX')) return;
/**
 * Class WsdPlugin
 * Static class
 */
class WsdPlugin
{
    public static function pageMain() {
        wp_enqueue_style('wss-css-bootstrap', WsdUtil::cssUrl('bootstrap.css'));
        wp_enqueue_style('wss-css-bootstrap-overrides', WsdUtil::cssUrl('bootstrap.overrides.css'));
        wp_enqueue_script('wss-js-bootstrap', WsdUtil::jsUrl('bootstrap.min.js'), array('jquery'));
        WsdUtil::includePage('dashboard.php');
    }
    public static function pageDatabase() { WsdUtil::includePage('database.php'); }
    public static function pageWpInfo() { WsdUtil::includePage('scanner.php'); }
    public static function pageWpFileScan() {
        wp_enqueue_style('wss-css-bootstrap', WsdUtil::cssUrl('bootstrap.css'));
        wp_enqueue_style('wss-css-bootstrap-overrides', WsdUtil::cssUrl('bootstrap.overrides.css'));
        wp_enqueue_script('wss-js-bootstrap', WsdUtil::jsUrl('bootstrap.min.js'), array('jquery'));
        WsdUtil::includePage('wp-scan.php');
    }
    public static function pageLiveTraffic() {
        wp_enqueue_style('wss-live-traffic', WsdUtil::cssUrl('styles.live-traffic.css'));
        WsdUtil::includePage('live_traffic.php');
    }
    public static function pageBlog() { WsdUtil::includePage('blog.php'); }
    public static function pageSettings() { WsdUtil::includePage('settings.php'); }
    public static function pageAbout() { WsdUtil::includePage('about.php'); }

    public static function loadResources()
    {
        if(WsdUtil::canLoad()){
            wp_enqueue_style('wss-css-base', WsdUtil::cssUrl('styles.base.css'));
            wp_enqueue_style('wss-css-alerts', WsdUtil::cssUrl('styles.alerts.css'));
            wp_enqueue_style('wss-css-general', WsdUtil::cssUrl('styles.general.css'));
            wp_enqueue_style('wss-css-status', WsdUtil::cssUrl('styles.status.css'));
            wp_enqueue_script('wss-js-util', WsdUtil::jsUrl('wsd-util.js'), array('jquery'));
        }
    }


    /**
     * Common method to add an alert to database.
     * @static
     * @param string $actionName The name of the action of the alert
     * @param int $type Can only be one of the following: ALERT_TYPE_OVERWRITE | ALERT_TYPE_STACK. Defaults to ALERT_TYPE_OVERWRITE
     * @param int $severity Can only have one of the following values: 0 1 2 3. Defaults to 0.
     * @param string $title
     * @param string $description
     * @param string $solution
     * @return bool
     */
    public static function alert($actionName, $type = 0, $severity = 0, $title = '', $description = '', $solution = '') {
        global $wpdb;

        $table = self::getTableName();

        if($type == WpsSettings::ALERT_TYPE_STACK)
        {
            //#! Check the max number of stacked alerts to keep and remove the exceeding ones
            $afsDate = $wpdb->get_var("SELECT alertFirstSeen FROM $table WHERE alertActionName = '$actionName' ORDER BY `alertDate`;");
            if(empty($afsDate)){ $afsDate = "CURRENT_TIMESTAMP()";}
            else { $afsDate = "'".$afsDate."'"; }
            $result = $wpdb->get_var("SELECT COUNT(alertId) FROM $table WHERE alertActionName = '$actionName';");
            if($result >= WpsSettings::ALERT_STACK_MAX_KEEP){
                // remove older entries to make room for the new ones
                $query = "DELETE FROM $table ORDER BY alertDate ASC LIMIT ".($result - (WpsSettings::ALERT_STACK_MAX_KEEP - 1));
                $wpdb->query($query);
            }

            //Add the new entry
            $query = $wpdb->prepare(
                "INSERT INTO $table
                (`alertType`,
                `alertSeverity`,
                `alertActionName`,
                `alertTitle`,
                `alertDescription`,
                `alertSolution`,
                `alertDate`,
                `alertFirstSeen`)
                VALUES
                (%d,
                 %d,
                 '%s',
                 '%s',
                 '%s',
                 '%s',
                 CURRENT_TIMESTAMP(),
                 $afsDate
                );",
                $type, $severity, $actionName, $title, $description, $solution);
        }
        elseif($type == WpsSettings::ALERT_TYPE_OVERWRITE)
        {
            //#! Find the record by actionName and update fields
            $result = $wpdb->get_var("SELECT alertId FROM $table WHERE alertActionName = '".$actionName."'; ");
            //#! found. do update
            if($result > 0){
                $query = $wpdb->prepare("UPDATE $table
                    SET
                    `alertType` = %d,
                    `alertSeverity` = %d,
                    `alertActionName` = '%s',
                    `alertTitle` = '%s',
                    `alertDescription` = '%s',
                    `alertSolution` = '%s',
                    `alertDate` = CURRENT_TIMESTAMP()
                    WHERE alertId = %d;",
                    $type, $severity, $actionName, $title, $description, $solution,$result);
            }
            //#! record not found. insert query
            else {
                $query = $wpdb->prepare("INSERT INTO $table
                (`alertType`,
                `alertSeverity`,
                `alertActionName`,
                `alertTitle`,
                `alertDescription`,
                `alertSolution`,
                `alertDate`,
                `alertFirstSeen`)
                VALUES
                (%d,
                 %d,
                 '%s',
                 '%s',
                 '%s',
                 '%s',
                 CURRENT_TIMESTAMP(),
                 CURRENT_TIMESTAMP()
                );",
                    $type, $severity, $actionName, $title, $description, $solution);
            }
        }
        $result = $wpdb->query($query);
        if($result === false){
            //#! MySQL error
            return false;
        }
        return true;
    }

    public static function getTableName($tableName = WpsSettings::ALERTS_TABLE_NAME){
        return wpsGetBasePrefix().$tableName;
    }

    /**
     * Get all alerts grouped by alertActionName
     * @return array
     */
    public static function getAlerts()
    {
        global $wpdb;
        $columns = "`alertId`,`alertType`,`alertSeverity`,`alertActionName`,`alertTitle`,`alertDescription`,`alertSolution`,`alertDate`,`alertFirstSeen`";
        return $wpdb->get_results("SELECT $columns FROM ".self::getTableName(WpsSettings::ALERTS_TABLE_NAME)." GROUP BY `alertActionName`;");
    }

    // filter alerts by input
    public static function getAlertsBy($alertSeverity)
    {
        global $wpdb;
        $columns = "`alertId`,`alertType`,`alertSeverity`,`alertActionName`,`alertTitle`,`alertDescription`,`alertSolution`,`alertDate`,`alertFirstSeen`";
        return $wpdb->get_results("SELECT $columns FROM ".self::getTableName(WpsSettings::ALERTS_TABLE_NAME)." WHERE `alertSeverity` = '$alertSeverity' GROUP BY `alertActionName`;");
    }

    public static function getChildAlerts($alertId, $alertType)
    {
        global $wpdb;
        $columns = "`alertId`,`alertType`,`alertSeverity`,`alertActionName`,`alertTitle`,`alertDescription`,`alertSolution`,`alertDate`,`alertFirstSeen`";
        return $wpdb->get_results("SELECT $columns FROM ".self::getTableName()." WHERE (alertId <> $alertId AND alertType = '$alertType') ORDER BY `alertDate` DESC");
    }

    /**
     * Retrieve the settings from database. This method will extract all methods found in the WsdSecurity class and provide them as
     * settings in the settings page. It will also auto update itself in case new methods are added to the class or if
     * some of them were removed.
     * @return array
     */
    public static function getSettings()
    {
        $className = 'WsdSecurity';
        if(! class_exists($className)){
            return array();
        }

        if(wpsIsMultisite()){
            $settings = get_blog_option(1, WpsSettings::PLUGIN_SETTINGS_OPTION_NAME);
        }
        else { $settings = WpsOption::getOption(WpsSettings::PLUGIN_SETTINGS_OPTION_NAME); }

        $methods = WpsSettings::getSettingsList();
        $useReflection = false;

        if(empty($settings))
        {
            $settings = array();
            foreach($methods as $method)
            {
                $settings[$method['name']] = array(
                    'name' => $method['name'],
                    'value' => 0, // 0 or 1 ; whether or not the option will show as selected by default in the plugin's settings page
                    'desc' => $method['text']
                );
            }
        }
        else
        {
            // Check to see whether or not new methods were added or subtracted
            $numSettings = count($settings);
            $numMethods = count($methods);
            if($numMethods <> $numSettings)
            {
                // add new methods
                $_temp = array();
                foreach($methods as $method)
                {
                    if(! isset($settings[$method['name']])){
                        $settings[$method['name']] = array(
                            'name' => $method['name'],
                            'value' => 0,
                            'desc' => $method['text']
                        );
                    }
                    array_push($_temp, $method['name']);
                }
                // remove missing methods
                foreach($settings as $k => &$entry){
                    if(! in_array($entry['name'], $_temp)){
                        unset($settings[$k]);
                    }
                }
            }
        }
        WpsOption::addOption(WpsSettings::PLUGIN_SETTINGS_OPTION_NAME, $settings);
        return $settings;
    }

    /**
     * Check to see whether or not the provided setting is enabled (as the settings are configurable the user might chose to turn some of them off)
     * @param string $name The name of the setting to look for in the settings array
     * @return bool
     */
    public static function isSettingEnabled($name)
    {
        $settings = self::getSettings();
        return (isset($settings[$name]) ? $settings[$name]['value'] : false);
    }


    static function networkActivate()
    {
        wssLog(__METHOD__.'() executed');
        global $wpdb;
        $charset_collate = '';

        if ( ! empty($wpdb->charset) ){$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";}
        if ( ! empty($wpdb->collate) ){$charset_collate .= " COLLATE $wpdb->collate";}

        // MUST HAVE "CREATE" RIGHTS if a table is not found and needs to be created
        $rights = WsdInfoServer::getDatabaseUserAccessRights();
        $hasCreateRight = in_array('CREATE', $rights['rightsHaving']);
        // Must have alter right for updating table
        $hasAlterRight = in_array('CREATE', $rights['rightsHaving']);
        $table1 = self::getTableName(WpsSettings::ALERTS_TABLE_NAME);
        $table2 = self::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME);
        $table3 = self::getTableName(WpsSettings::SCAN_TABLE_NAME);
        $table4 = self::getTableName(WpsSettings::SCANS_TABLE_NAME);

        $notices = get_blog_option(1, WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());

        if(! WsdUtil::tableExists($table1)){
            wssLog("table not found: $table1");
            if(! $hasCreateRight){
                $notices[] = '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>CREATE</strong>' right in order to install this plugin.";
                update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $query1 = "CREATE TABLE IF NOT EXISTS $table1 (
                          `alertId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                          `alertType` TINYINT NOT NULL DEFAULT 0 ,
                          `alertSeverity` INT NOT NULL DEFAULT 0 ,
                          `alertActionName` VARCHAR (255) NOT NULL,
                          `alertTitle` VARCHAR(255) NOT NULL ,
                          `alertDescription` TEXT NOT NULL ,
                          `alertSolution` TEXT NOT NULL ,
                          `alertDate` DATETIME NOT NULL default '0000-00-00 00:00:00',
                          `alertFirstSeen` DATETIME NOT NULL default '0000-00-00 00:00:00',
                          PRIMARY KEY (`alertId`) ,
                          UNIQUE INDEX `alertId_UNIQUE` (`alertId` ASC) ) $charset_collate;";
            $result = @$wpdb->query($query1);
            if($result === false){
                //#! MySQL error
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query1</pre></strong>.";
                update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            wssLog("table created: $table1");
        }

        $alterCheck = true;
        if(! WsdUtil::tableExists($table2)){
            wssLog("table not found: $table2");
            if(! $hasCreateRight){
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>CREATE</strong>' right in order to install this plugin.";
                update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $query2 = "CREATE TABLE IF NOT EXISTS $table2 (
                         `entryId` bigint(20) unsigned NOT NULL auto_increment,
                         `entryTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                         `entryIp` text,
                         `entryReferrer` text,
                         `entryUA` text,
                         `entryRequestedUrl` text,
                         `entryCountry` varchar(125) not null,
                         `entryCity` varchar(125) not null,
                         `blogId` INT(10) NOT NULL DEFAULT 1,
                         PRIMARY KEY (entryId)) $charset_collate;";
            $result = @$wpdb->query($query2);
            if($result === false){
                //#! MySQL error
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query2</pre></strong>.";
                update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $alterCheck = false;
            wssLog("table created: $table2.");
        }

        if($alterCheck)
        {
            wssLog("Alter check needed for $table2.");
            if(! $hasAlterRight){
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>ALTER</strong>' right in order to install this plugin.";
                update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }

            // Get columns
            $query = "SHOW COLUMNS FROM $table2";
            $cols = $wpdb->get_results($query, ARRAY_A);
            $columns = array();
            if(empty($cols)){
                wssLog("Could not retrieve columns from table: $table2");
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query</pre></strong>. Please inform the plugin author about this error.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            foreach($cols as $i => $values){
                if(isset($values['Field']) && !empty($values['Field'])){
                    array_push($columns, $values['Field']);
                }
            }
            $entryCountryExists = $entryCityExists = $blogIdExists = false;
            if(in_array('entryCountry', $columns)){ $entryCountryExists = true; }
            if(in_array('entryCity', $columns)){ $entryCityExists = true; }
            if(in_array('blogId', $columns)){ $blogIdExists = true; }

            //## Check for column: entryCountry
            wssLog("Checking for column: entryCountry");
            if(!$entryCountryExists)
            {
                // alter table
                $q = "ALTER TABLE $table2 ADD COLUMN `entryCountry` VARCHAR(125) NOT NULL DEFAULT '' AFTER `entryRequestedUrl`;";
                $result = @$wpdb->query($q);
                if($result === false){
                    //#! MySQL error
                    $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$q</pre></strong>.";
                    update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                    return false;
                }
            }

            //## Check for column: entryCity
            wssLog("Checking for column: entryCity");
            if(!$entryCityExists)
            {
                $q = "ALTER TABLE $table2 ADD COLUMN `entryCity` VARCHAR(125) NOT NULL DEFAULT '' AFTER `entryCountry`;";
                $result = @$wpdb->query($q);
                if($result === false){
                    //#! MySQL error
                    $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$q</pre></strong>.";
                    update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                    return false;
                }
            }

            //## Check for column: blogId
            wssLog("Checking for column: blogid");
            if(!$blogIdExists)
            {
                $q = "ALTER TABLE $table2 ADD COLUMN `blogId` INT(10) NOT NULL DEFAULT 1 AFTER `entryCity`;";
                $result = @$wpdb->query($q);
                if($result === false){
                    //#! MySQL error
                    $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$q</pre></strong>.";
                    update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                    return false;
                }
            }
            wssLog("$table2 updated successfully");
        }

        if(! WsdUtil::tableExists($table3)){
            wssLog("table not found: $table3");
            if(! $hasCreateRight){
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>CREATE</strong>' right in order to install this plugin.";
                update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $query3 = "CREATE TABLE IF NOT EXISTS $table3 (
                        `entryId` BIGINT NOT NULL AUTO_INCREMENT ,
                        `scanId` INT NOT NULL ,
                        `filePath` VARCHAR(1000) NOT NULL ,
                        `dateModified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
                        `fileNotFound` TINYINT NOT NULL DEFAULT 0,
                        PRIMARY KEY (`entryId`) ,
                        UNIQUE INDEX `entryId_UNIQUE` (`entryId` ASC) ) $charset_collate;";
            $result = @$wpdb->query($query3);
            if($result === false){
                //#! MySQL error
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query3</pre></strong>.";
                update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
        }

        if(! WsdUtil::tableExists($table4)){
            wssLog("table not found: $table4");
            if(! $hasCreateRight){
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>CREATE</strong>' right in order to install this plugin.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $query4 = "CREATE  TABLE $table4 (
                        `scanId` INT NOT NULL AUTO_INCREMENT ,
                        `scanStartDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `scanEndDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `scanResult` INT NOT NULL DEFAULT 0,
                        `failReason` VARCHAR(5000) NOT NULL DEFAULT '',
                        `scanType` int(11) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`scanId`) ) $charset_collate;";
            $result = @$wpdb->query($query4);
            if($result === false){
                //#! MySQL error
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query4</pre></strong>.";
                update_site_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
        }
        add_blog_option($wpdb->blogid, WpsSettings::CAN_RUN_TASKS_OPTION_NAME, 1);
        return true;
    }

    static function activate()
    {
        wssLog(__METHOD__.'() executed');
        global $wpdb;
        $charset_collate = '';

        if ( ! empty($wpdb->charset) ){$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";}
        if ( ! empty($wpdb->collate) ){$charset_collate .= " COLLATE $wpdb->collate";}

        // MUST HAVE "CREATE" RIGHTS if a table is not found and needs to be created
        $rights = WsdInfoServer::getDatabaseUserAccessRights();
        wssLog('USER RIGHTS', $rights);
        $hasCreateRight = in_array('CREATE', $rights['rightsHaving']);
        // Must have alter right for updating table
        $hasAlterRight = in_array('CREATE', $rights['rightsHaving']);
        $table1 = self::getTableName(WpsSettings::ALERTS_TABLE_NAME);
        $table2 = self::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME);
        $table3 = self::getTableName(WpsSettings::SCAN_TABLE_NAME);
        $table4 = self::getTableName(WpsSettings::SCANS_TABLE_NAME);

        if(! WsdUtil::tableExists($table1)){
            wssLog("table not found: $table1");
            if(! $hasCreateRight){
                wssLog("user has no create right. cannot create table: $table1");
                $notices = WpsOption::getOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>CREATE</strong>' right in order to install this plugin.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $query1 = "CREATE TABLE IF NOT EXISTS $table1 (
                          `alertId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                          `alertType` TINYINT NOT NULL DEFAULT 0 ,
                          `alertSeverity` INT NOT NULL DEFAULT 0 ,
                          `alertActionName` VARCHAR (255) NOT NULL,
                          `alertTitle` VARCHAR(255) NOT NULL ,
                          `alertDescription` TEXT NOT NULL ,
                          `alertSolution` TEXT NOT NULL ,
                          `alertDate` DATETIME NOT NULL default '0000-00-00 00:00:00',
                          `alertFirstSeen` DATETIME NOT NULL default '0000-00-00 00:00:00',
                          PRIMARY KEY (`alertId`) ,
                          UNIQUE INDEX `alertId_UNIQUE` (`alertId` ASC) ) $charset_collate;";
            $result = @$wpdb->query($query1);
            if($result === false){
                //#! MySQL error
                $notices= WpsOption::getOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query1</pre></strong>.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            wssLog("table created: $table1");
        }

        $alterCheck = true;
        if(! WsdUtil::tableExists($table2)){
            wssLog("table not found: $table2");
            if(! $hasCreateRight){
                $notices= WpsOption::getOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>CREATE</strong>' right in order to install this plugin.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $query2 = "CREATE TABLE IF NOT EXISTS $table2 (
                         `entryId` bigint(20) unsigned NOT NULL auto_increment,
                         `entryTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                         `entryIp` text,
                         `entryReferrer` text,
                         `entryUA` text,
                         `entryRequestedUrl` text,
                         `entryCountry` varchar(125) not null,
                         `entryCity` varchar(125) not null,
                         `blogId` INT(10) NOT NULL DEFAULT 1,
                         PRIMARY KEY (entryId)) $charset_collate;";
            $result = @$wpdb->query($query2);
            if($result === false){
                //#! MySQL error
                $notices= WpsOption::getOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query2</pre></strong>.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $alterCheck = false;
            wssLog("table created: $table2.");
        }

        if($alterCheck)
        {
            wssLog("Alter check needed for $table2.");
            if(! $hasAlterRight){
                wssLog('NO ALTER RIGHT');
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>ALTER</strong>' right in order to install this plugin.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }

            // Get columns
            $query = "SHOW COLUMNS FROM $table2";
            $cols = $wpdb->get_results($query, ARRAY_A);
            $columns = array();
            if(empty($cols)){
                wssLog("Could not retrieve columns from table: $table2");
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query</pre></strong>. Please inform the plugin author about this error.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            foreach($cols as $i => $values){
                if(isset($values['Field']) && !empty($values['Field'])){
                    array_push($columns, $values['Field']);
                }
            }
            $entryCountryExists = $entryCityExists = $blogIdExists = false;
            if(in_array('entryCountry', $columns)){ $entryCountryExists = true; }
            if(in_array('entryCity', $columns)){ $entryCityExists = true; }
            if(in_array('blogId', $columns)){ $blogIdExists = true; }

//## Check for column: entryCountry
            wssLog("Checking for column: entryCountry");
            if(!$entryCountryExists)
            {
                // alter table
                $q = "ALTER TABLE $table2 ADD COLUMN `entryCountry` VARCHAR(125) NOT NULL DEFAULT '' AFTER `entryRequestedUrl`;";
                $result = @$wpdb->query($q);
                if($result === false){
                    wssLog('MySql error: '.mysql_error());
                    wssLog("Error running query: $q");
                    //#! MySQL error
                    $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$q</pre></strong>.";
                    WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                    return false;
                }
                wssLog("Column: entryCountry not found in table $table2. but was added.");
            }
            else { wssLog("column already exists: entryCountry");}

//## Check for column: entryCity
            wssLog("Checking for column: entryCity");
            if(!$entryCityExists)
            {
                $q = "ALTER TABLE $table2 ADD COLUMN `entryCity` VARCHAR(125) NOT NULL DEFAULT '' AFTER `entryCountry`;";
                $result = @$wpdb->query($q);
                if($result === false){
                    //#! MySQL error
                    $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$q</pre></strong>.";
                    WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                    return false;
                }
                wssLog("Column: entryCity not found in table $table2. but was added.");
            }
            else { wssLog("column already exists: entryCity");}

//## Check for column: blogId
            wssLog("Checking for column: blogId");
            if(!$blogIdExists)
            {
                $q = "ALTER TABLE $table2 ADD COLUMN `blogId` INT(10) NOT NULL DEFAULT 1 AFTER `entryCity`;";
                $result = @$wpdb->query($q);
                if($result === false){
                    //#! MySQL error
                    $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$q</pre></strong>.";
                    WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                    return false;
                }
                wssLog("Column: blogId not found in table $table2. but was added.");
            }
            else { wssLog("column already exists: blogid");}
            wssLog("$table2 updated successfully");
        }

        if(! WsdUtil::tableExists($table3)){
            wssLog("table not found: $table3");
            if(! $hasCreateRight){
                $notices= WpsOption::getOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>CREATE</strong>' right in order to install this plugin.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $query3 = "CREATE TABLE IF NOT EXISTS $table3 (
                        `entryId` BIGINT NOT NULL AUTO_INCREMENT ,
                        `scanId` INT NOT NULL ,
                        `filePath` VARCHAR(1000) NOT NULL ,
                        `dateModified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
                        `fileNotFound` TINYINT NOT NULL DEFAULT 0,
                        PRIMARY KEY (`entryId`) ,
                        UNIQUE INDEX `entryId_UNIQUE` (`entryId` ASC) ) $charset_collate;";
            $result = @$wpdb->query($query3);
            if($result === false){
                //#! MySQL error
                $notices= WpsOption::getOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query3</pre></strong>.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            wssLog("table created: $table3.");
        }

        if(! WsdUtil::tableExists($table4)){
            wssLog("table not found: $table4");
            if(! $hasCreateRight){
                $notices= WpsOption::getOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>: The database user needs the '<strong>CREATE</strong>' right in order to install this plugin.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
            $query4 = "CREATE  TABLE $table4 (
                        `scanId` INT NOT NULL AUTO_INCREMENT ,
                        `scanStartDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `scanEndDate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `scanResult` INT NOT NULL DEFAULT 0,
                        `failReason` VARCHAR(5000) NOT NULL DEFAULT '',
                        `scanType` int(11) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`scanId`) ) $charset_collate;";
            $result = @$wpdb->query($query4);
            if($result === false){
                //#! MySQL error
                $notices= WpsOption::getOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, array());
                $notices[]= '<strong>'.WPS_PLUGIN_NAME."</strong>. Error running query: <strong><pre>$query4</pre></strong>.";
                WpsOption::updateOption(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION, $notices);
                return false;
            }
        }
        WpsOption::addOption(WpsSettings::CAN_RUN_TASKS_OPTION_NAME, 1);
        return true;
    }
    /**@deprecated*/
    public static function deactivate($blogId=1)
    {
        WsdScheduler::unregisterCronTasks();
        delete_option(WpsSettings::WP_FILE_SCAN_OPTION_NAME);
        delete_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION);
        delete_option(WpsSettings::PLUGIN_SETTINGS_OPTION_NAME);
        delete_option(WpsSettings::PLUGIN_ERROR_NOTICE_OPTION);
        delete_option(WpsSettings::CAN_RUN_TASKS_OPTION_NAME);
    }
    public static function uninstall()
    {
        WpsOption::deleteOption(WpsSettings::LIVE_TRAFFIC_ENTRIES);
        WpsOption::deleteOption('WPS_KEEP_NUM_ENTRIES_LT');
        WpsOption::deleteOption('WPS_REFRESH_RATE_AJAX_LT');
        WpsOption::deleteOption(WpsSettings::PLUGIN_SETTINGS_OPTION_NAME);
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS ".WsdPlugin::getTableName(WpsSettings::SCAN_TABLE_NAME));
        $wpdb->query("DROP TABLE IF EXISTS ".WsdPlugin::getTableName(WpsSettings::SCANS_TABLE_NAME));
        $wpdb->query("DROP TABLE IF EXISTS ".WsdPlugin::getTableName(WpsSettings::ALERTS_TABLE_NAME));
        $wpdb->query("DROP TABLE IF EXISTS ".WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME));
    }

    /**
     * Check to see whether or not the Secure WordPress plugin is installed
     * @return bool
     */
    public static function swpPluginInstalled()
    {
        $pluginPath = trailingslashit(ABSPATH).'wp-content/plugins/secure-wordpress';
        return (is_dir($pluginPath) ? true : false);
    }
}