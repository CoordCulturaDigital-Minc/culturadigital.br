<?php if(! defined('WPS_PLUGIN_PREFIX')) return;
/**
 * Class WsdLiveTraffic
 */
class WsdLiveTraffic
{
    private function __construct(){}
    private function __clone(){}

    final public static function clearEvents()
    {
        wssLog(__METHOD__."() triggered.");

        global $wpdb;
        $settings = WsdPlugin::getSettings();
        $keepMaxEntries = (int)WpsOption::getOption('WPS_KEEP_NUM_ENTRIES_LT');

        if($keepMaxEntries < 1){
            $query = "TRUNCATE ".WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME);
            $result = $wpdb->query($query);
            wssLog("Deleting live traffic entries.", array('query'=>$query, 'result'=>$result));
            return;
        }

        $optData = WpsOption::getOption(WpsSettings::LIVE_TRAFFIC_ENTRIES);
        if(empty($optData)){
            wssLog("Option (".WpsSettings::LIVE_TRAFFIC_ENTRIES.") not found.");
            return;
        }

        $numEntries = $wpdb->get_var("SELECT COUNT(entryId) FROM ".WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME));

        if($numEntries <> $keepMaxEntries){
            WpsOption::updateOption(WpsSettings::LIVE_TRAFFIC_ENTRIES, $numEntries);
        }

        if(intval($optData) <= $keepMaxEntries){
            return;
        }

        $tableName = WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME);

        $querySelect = "SELECT min(t.entryTime)
                            FROM
                            (
                                SELECT
                                    entryTime
                                FROM
                                    ".$tableName."
                                ORDER BY
                                    entryTime DESC
                                LIMIT ".$keepMaxEntries."
                            ) AS t";


        $deleteFromTime = $wpdb->get_var($querySelect);

        $queryDelete = "DELETE FROM ".$tableName." WHERE entryTime < %s";
        $result = $wpdb->query($wpdb->prepare($queryDelete,$deleteFromTime));

        wssLog("Deleting live traffic entries.", array('query'=>"DELETE FROM $tableName WHERE entryTime < $deleteFromTime", 'deleted'=>$result));

        if(false === $result){
            return;
        }
        // update option
        $numEntries = $wpdb->get_var("SELECT COUNT(entryId) FROM ".WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME));
        WpsOption::updateOption(WpsSettings::LIVE_TRAFFIC_ENTRIES, $numEntries);
    }

    final public static function registerHit()
    {
        // check if live traffic tool is enabled
        $liveTrafficToolEnabled = WpsOption::getOption(WpsSettings::ENABLE_LIVE_TRAFFIC);
        if(! $liveTrafficToolEnabled){
            return;
        }

        if(is_admin()){ return; }

        global $wpdb;

        $blogID = $wpdb->blogid;
        $url = self::getRequestedUrl();

        if(self::isUrlExcluded($url)){ return; }

        $ip = self::getIP();
        $referrer = self::getReferrer();
        $ua = self::getUserAgent();

        $geoIpInfo = self::_getGeoIpInfo($ip);
        $country = $geoIpInfo['country'];
        $city = $geoIpInfo['city'];

        $query = $wpdb->prepare("INSERT INTO ".WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME)." (entryTime, entryIp, entryReferrer, entryUA, entryRequestedUrl, entryCountry, entryCity, blogId)
                            VALUES(CURRENT_TIMESTAMP, %s, %s, %s, %s, %s, %s, %d)", $ip, $referrer, $ua, $url, $country, $city, $blogID);
        if(false === @$wpdb->query($query)){
            return;
        }

        $numEvents = 0;
        $optData = WpsOption::getOption(WpsSettings::LIVE_TRAFFIC_ENTRIES);
        if(empty($optData)){
            WpsOption::addOption(WpsSettings::LIVE_TRAFFIC_ENTRIES, $numEvents);
        }
        else { $numEvents = intval($optData); }

        WpsOption::updateOption(WpsSettings::LIVE_TRAFFIC_ENTRIES, $numEvents + 1);
    }

    final public static function getIP()
    {
        $ip = null;
        if ( isset($_SERVER["REMOTE_ADDR"]) ) { $ip = $_SERVER["REMOTE_ADDR"]; }
        else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ) { $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; }
        else if ( isset($_SERVER["HTTP_CLIENT_IP"]) ) { $ip = $_SERVER["HTTP_CLIENT_IP"]; }
        if(! is_null($ip) && self::isValidIp($ip)){ return $ip; }
        return 'unknown';
    }

    final public static function getReferrer() { return (empty($_SERVER['HTTP_REFERER']) ? '' : htmlentities($_SERVER['HTTP_REFERER'],ENT_QUOTES)); }

    final public static function getUserAgent() { return (empty($_SERVER['HTTP_USER_AGENT']) ? '' : htmlentities($_SERVER['HTTP_USER_AGENT'],ENT_QUOTES)); }

    final public static function isValidIp($ip){
        if(preg_match('/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/', $ip, $m)){
            if(
                $m[0] >= 0 && $m[0] <= 255 &&
                $m[1] >= 0 && $m[1] <= 255 &&
                $m[2] >= 0 && $m[2] <= 255 &&
                $m[3] >= 0 && $m[3] <= 255
            ){
                return true;
            }
        }
        return false;
    }
    final public static function getRequestedUrl(){
        if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']){
            $host = $_SERVER['HTTP_HOST']; }
        else {
            $host = $_SERVER['SERVER_NAME'];
        }
        $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $host . $_SERVER['REQUEST_URI'];
        return esc_url($url);
    }

    /**
     * @param int $maxEntries If $maxEntries is 0 it means to load all entries, otherwise it will limit the select to that number
     * @return mixed
     */
    final public static function getTrafficData($maxEntries = 0)
    {
        global $wpdb;
        if(empty($maxEntries)){
            return $wpdb->get_results("SELECT entryId,entryTime,entryIp,entryReferrer,entryUA,entryRequestedUrl,entryCountry,entryCity FROM ".WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME)." ORDER BY entryId DESC");
        }
        else { return $wpdb->get_results("SELECT entryId,entryTime,entryIp,entryReferrer,entryUA,entryRequestedUrl,entryCountry,entryCity FROM ".WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME)." ORDER BY entryId DESC LIMIT 0, ".$maxEntries);}
    }

    final public static function getLastID()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT entryId FROM ".WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME)." ORDER BY entryId DESC");
    }

    final public static function ajaxGetTrafficData()
    {
        if(! isset($_REQUEST['nonce'])){ exit('Invalid request'); }
        if ( !wp_verify_nonce( $_REQUEST['nonce'], "wpsTrafficScan_nonce")) { exit('Invalid request'); }

        if ( !isset( $_REQUEST['getFrom'])) { exit(__('Invalid request',WpsSettings::TEXT_DOMAIN)); }
        if ( !isset( $_REQUEST['maxEntries'])) { exit(__('Invalid request',WpsSettings::TEXT_DOMAIN)); }

        $lastID  = intval($_REQUEST['getFrom']);
        $maxEntries = intval($_REQUEST['maxEntries']);

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            echo self::_ajaxGetFrom($maxEntries, $lastID);
            exit;
        }
        exit('Invalid request!');
    }

    /**
     * @internal
     * @param $maxEntries
     * @param int $lastID
     * @return mixed|string|void
     */
    final public static function _ajaxGetFrom($maxEntries, $lastID = 0)
    {
        $result = array(
            'type' => 'success',
            'data' => array()
        );

        // get the last ID from db
        $dbLastID = self::getLastID();

        if(empty($dbLastID)){
            $result['data'][] = '<tr data-id="0"><td><p style="margin: 5px 5px; font-weight: bold; color: #cc0000">'.__('No data yet.',WpsSettings::TEXT_DOMAIN).'</p></td></tr>';
            return json_encode($result);
        }

        // no change - nothing to display
        if($lastID == $dbLastID)
        {
            return json_encode($result);
        }

        if($lastID == 0){
            $getFrom = $maxEntries;
        }
        else {
            $getFrom = $dbLastID - $lastID;
            if ($getFrom < 1){
                return json_encode($result);
            }
        }

        $data = self::getTrafficData($getFrom);
        if(empty($data)){ $result['data'][] = '<tr data-id="0"><td><p style="margin: 5px 5px; font-weight: bold; color: #cc0000">'.__('No data yet.',WpsSettings::TEXT_DOMAIN).'</p></td></tr>'; }
        else {
            $data = array_reverse($data);
            foreach($data as $entry)
            {
                $req = trim($entry->entryRequestedUrl);
                $e = '<tr data-id="'.$entry->entryId.'"><td class="wsd-scan-entry">';
                $e .= '<div>';
                if(empty($entry->entryReferrer)){
                    $ref = '';
                }
                else {
                    // Ignore page refresh
                    $ref = trim($entry->entryReferrer);
                    if(strcasecmp($req,$ref)==0){
                        continue;
                    }
                    $url = strip_tags(urldecode($req));
                    $url = esc_html($url,ENT_QUOTES);
                    $ref = ' '.__('coming from',WpsSettings::TEXT_DOMAIN).' <span class="w-entry"><span>'. $url . '</span></span>';
                }

                // add geo-location + flag
                $country = '';
                $city = '';
                $flag = '';

                if(!empty($entry->entryCountry)){
                    $country = $entry->entryCountry;
                    $pos = strpos($country,',');
                    if(false !== $pos){
                        $code = substr($country, $pos+1);
                        $flag = WsdUtil::imageUrl('flags/'.strtolower($code).'.png');
                        $country = substr($country, 0, $pos);
                    }
                }
                if(!empty($entry->entryCity)){ $city = $entry->entryCity; }
                if(!empty($flag))
                {
                    $flag = trim($flag);
                    if(false !== ($pos = strpos($flag, ' republic of,kr.png'))){
                        $flag = WsdUtil::imageUrl('flags/kr.png');
                    }
                    $flag = '<img src="'.$flag.'" alt="'.$flag.'" title="'.$flag.'"/>';
                }

                $ipInfoUrl = "http://dnsquery.org/ipwhois/{$entry->entryIp}";
                $ipInfoTitle = __('Lookup this IP. Opens in a new window/tab',WpsSettings::TEXT_DOMAIN);

                $geoInfo = '<span>';
                if(! empty($country)){
                    if(! empty($flag)){ $geoInfo .= $flag; }
                    if(! empty($city)) { $geoInfo .=  ' '.$city.',';}
                    if(! empty($country)) { $geoInfo .= ' '.$country; }
                    $geoInfo .= ' (<span class="w-ip"><a href="'.$ipInfoUrl.'" title="'.$ipInfoTitle.'" target="_blank">'.$entry->entryIp.'</a></span>)';
                }
                else { $geoInfo = __('Unknown location',WpsSettings::TEXT_DOMAIN).' (<span class="w-ip"><a href="'.$ipInfoUrl.'" title="'.$ipInfoTitle.'" target="_blank">'.$entry->entryIp.'</a></span>)'; }
                $geoInfo .= '</span>';

                $reqUrl = strip_tags(urldecode($req));
                $reqUrl = esc_html($reqUrl,ENT_QUOTES);
                $e .= '<p style="margin-bottom: 1px;">'.$geoInfo;
                $e .= $ref.' '.__('requested',WpsSettings::TEXT_DOMAIN).' <span class="w-entry"><span>'.$reqUrl.'</span></span></p>';
                $e .= '<p style="margin-bottom: 1px;"><strong>'.__('Date',WpsSettings::TEXT_DOMAIN).'</strong>: <span class="w-date">'.$entry->entryTime.'</span></p>';
                $e .= '<p style="margin-bottom: 1px;"><strong>'.__('Agent',WpsSettings::TEXT_DOMAIN).'</strong>: <span class="w-ua">'.htmlentities($entry->entryUA,ENT_QUOTES).'</span></p>';
                $e .= '</div>';
                $e .= '</td></tr>';
                $result['data'][] = $e;
            }
        }
        return json_encode($result);
    }

    /**
     * @param $url
     * @return bool
     * Exclude urls
     */
    private static function isUrlExcluded($url)
    {
        if(false !==(strpos($url, 'wp-cron.php?doing_wp_cron'))) { return true; }
        return false;
    }

    private static function _getGeoIpInfo($ip)
    {
        $data = array(
            'country' => '',
            'city' => ''
        );
        if($ip == '0.0.0.0'){
            return $data;
        }

        $infoUrl = 'http://www.geoplugin.net/json.gp?ip='.$ip;
        $result = @file_get_contents($infoUrl);
        if(empty($result)){
            return $data;
        }
        $result = json_decode($result);
        $data['country'] = $result->geoplugin_countryName. (empty($result->geoplugin_countryCode) ? '' : sanitize_text_field(','.$result->geoplugin_countryCode));
        $data['city'] = (empty($result->geoplugin_city) ? '' : sanitize_text_field($result->geoplugin_city));
        return $data;
    }

}
