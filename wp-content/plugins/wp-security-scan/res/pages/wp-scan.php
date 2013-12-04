<?php /*/#! Check for install errors */ if(!wpsCanDisplayPage()){ return; } ?>
<?php
    $enableSubmit = true;
    $eInfo = array(/* 'error' =>'', 'message' => '' */);

$rm = strtoupper($_SERVER['REQUEST_METHOD']);
if('POST' == $rm)
{
    if (function_exists('wp_nonce_field')) {
        check_admin_referer('wss-wp-scan-nonce');
    }

    $scanSettings = WsdWPScanSettings::getSettings();
    $scanProgress = $scanSettings['SCAN_PROGRESS'];
    $scanState = $scanSettings['SCAN_STATE'];
    $scanType = $scanSettings['SCAN_TYPE'];
    $scanID = $scanSettings['SCAN_ID'];

    //#! Check if this is a request to delete scans
    if(isset($_POST['deleteScan']))
    {
        $scanID = intval($_POST['deleteScan']);
        wssLog('Receiving delete scan command for scan ID: ',$scanID);
        if(WsdWpScanner::isValidScan($scanID))
        {
            if(!WsdWpScanner::deleteScan($scanID)){
                echo '<script type="text/javascript">alert("Error: Could not delete scan. Scan ID = "+'.$scanID.');</script>';
            }
        }
        else { echo '<script type="text/javascript">alert("Invalid scan id provided: "+'.$scanID.');</script>'; }
    }
    //#! Delete all scans
    elseif(isset($_POST['deleteAllScans'])){
        wssLog('Receiving command: delete all scans.');
        if(WsdWpScanner::deleteAllScans()){
            $eInfo['message'] = 'All scans have been deleted.';
        }
        else { $eInfo['error'] = 'An error occurred while deleting the scans. Please try again in a few moments.'; }
    }
    //#! Normal scan registration request
    else
    {
        // scanType
        if(isset($_POST['scanType']))
        {
            $type = intval($_POST['scanType']);
            $eInfo['message'] = $type;
            $scanSettings = WsdWPScanSettings::getSettings();
            $scanProgress = $scanSettings['SCAN_PROGRESS'];
            $scanState = $scanSettings['SCAN_STATE'];
            $scanType = $scanSettings['SCAN_TYPE'];

            $result = WsdWpScanner::registerScan($type);
            if(-1 == $result){
                $eInfo['error'] = "A scan is already pending to start. You must wait for it to finish to register another one.";
                $enableSubmit = false;
            }
            elseif(0 == $result){
                $eInfo['error'] = "A scan is already in progress. You must wait for it to finish to register another one.";
                $enableSubmit = false;
            }
            elseif(1 == $result){
                $eInfo['error'] = "Invalid form. Please provide a valid scan type.";
            }
            elseif(2 == $result){
                $eInfo['error'] = "Internal Error: could not retrieve the ID for the last added scan.";
            }
            elseif(3 === $result){
                $eInfo['message'] = "Scan successfully registered. It should start in approximately 1 minute. You can come back later on to check on its status.";
                $scanSettings = WsdWPScanSettings::getSettings();
                $scanProgress = $scanSettings['SCAN_PROGRESS'];
                $scanState = $scanSettings['SCAN_STATE'];
                $scanType = $scanSettings['SCAN_TYPE'];
                $enableSubmit = false;
            }
        }
        else { $eInfo['error'] = "Invalid form."; }
    }
}
//#! GET
else {
    $scanSettings = WsdWPScanSettings::getSettings();
    $scanProgress = $scanSettings['SCAN_PROGRESS'];
    $scanState = $scanSettings['SCAN_STATE'];
    $scanType = $scanSettings['SCAN_TYPE'];
    $enableSubmit = (in_array($scanState, array(WsdWPScanSettings::SCAN_STATE_DONE, WsdWPScanSettings::SCAN_STATE_NONE)) ? true : false);
}

//#! Whether or not to show the scan form
$showScanForm = !((isset($_GET) && !empty($_GET['scan'])));
?>
<style type="text/css">
    .wsdplugin_alert_section_title{ float: none; clear: both; padding: 0 0 !important; }
    .wsdplugin_alert_section_title p { font-size: 14px; margin: 10px 7px; line-height: normal; }
    #wpCoreScanWrapper, #wpCoreScanReportWrapper { margin: 0 0; display: block; clear: both; }
    #wpScanForm { margin-top: 25px;}
    #scanStateWrapper {margin-top: 35px;}
    [class^="icon-"], [class*=" icon-"] { margin-top: 0 !important;}
    ul#wssLastScansList { margin: 0 0; padding 0 0; }
    ul#wssLastScansList li { padding: 0 0; margin: 0 0;}
    ul#wssLastScansList li a { margin: 1px 8px 0 0;}
    ul#wssLastScansList li a + i{ margin: 1px 5px 0 0; }

</style>
<div class="wrap wsdplugin_content">
    <h2><?php echo WPS_PLUGIN_NAME.' - WordPress File Scan';?></h2>
    <div id="page-wp-check" class="wrap wsdplugin_content">

        <div class="wsdplugin_alert_section_title wsdplugin_alert_section_title_category">
            <p>This tool helps you identify if, and what WordPress core files (depending on the version installed) have been modified in the specified time range.</p>
        </div>

        <div id="content" style="overflow: hidden;">
            <!-- LEFT -->
            <div style="float: left; width: 82%;">
                <?php if($showScanForm): ?>
                <div id="wpCoreScanWrapper">

                    <?php
                        if(isset($eInfo['error'])){ echo '<div class="error" style="clear:both;margin-right: 10px;"><p">'.$eInfo['error'].'</p></div>'; }
                        elseif(isset($eInfo['message'])){ echo '<div class="updated" style="clear:both;margin-right: 10px;"><p>'.$eInfo['message'].'</p></div>'; }
                    ?>

                    <form method="post" id="wpScanForm" class="form-inline">
                        <?php if (function_exists('wp_nonce_field')) { wp_nonce_field('wss-wp-scan-nonce'); } ?>
                        <label for="dateScan">Scan for modified files since: </label>
                        <select id="dateScan" name="scanType" <?php echo ($enableSubmit ? '' : 'disabled="disabled"'); ?>>
                            <?php
                                function __checkSelected($scanTypeSettings, $scanTypeOption){
                                    if($scanTypeOption == $scanTypeSettings){ return 'selected = "selected"'; }
                                    return '';
                                }
                            ?>
                            <option <?php echo __checkSelected($scanType,0);?> value="0"><?php echo WpsSettings::$scanTypes[0];?></option>
                            <option <?php echo __checkSelected($scanType,1);?> value="1"><?php echo WpsSettings::$scanTypes[1];?></option>
                            <option <?php echo __checkSelected($scanType,2);?> value="2"><?php echo WpsSettings::$scanTypes[2];?></option>
                            <option <?php echo __checkSelected($scanType,3);?> value="3"><?php echo WpsSettings::$scanTypes[3];?></option>
                            <option <?php echo __checkSelected($scanType,4);?> value="4"><?php echo WpsSettings::$scanTypes[4];?></option>
                            <option <?php echo __checkSelected($scanType,5);?> value="5"><?php echo WpsSettings::$scanTypes[5];?></option>
                            <option <?php echo __checkSelected($scanType,6);?> value="6"><?php echo WpsSettings::$scanTypes[6];?></option>
                            <option <?php echo __checkSelected($scanType,7);?> value="7"><?php echo WpsSettings::$scanTypes[7];?></option>
                            <option <?php echo __checkSelected($scanType,8);?> value="8"><?php echo WpsSettings::$scanTypes[8];?></option>
                        </select>
                        <?php if($enableSubmit) :?><input type="button" id="inputFormScan" class="button button-primary" value="Scan" /><?php endif;?>
                    </form>

                    <div id="scanStateWrapper">
                        <?php /**[[ check the state ]]*/ ?>
                        <?php
                        if($scanState == WsdWPScanSettings::SCAN_STATE_NONE)
                        {
                            //#! Check if there is a previous scan completed
                            $scanID = WsdWpScanner::getLastScanID_table();
                            if(! empty($scanID)){
                                echo WsdUtil::loadTemplate('tpl-scan-done');
                            }
                            //#! load the default template
                            else { echo WsdUtil::loadTemplate('tpl-scan-none'); }
                        }
                        elseif($scanState == WsdWPScanSettings::SCAN_STATE_WAITING)
                        {
                            echo WsdUtil::loadTemplate('tpl-scan-waiting', array('scanID'=>$scanSettings['SCAN_ID']));
                        }
                        elseif($scanState == WsdWPScanSettings::SCAN_STATE_DONE)
                        {
                            echo WsdUtil::loadTemplate('tpl-scan-done');
                        }
                        elseif($scanState == WsdWPScanSettings::SCAN_STATE_IN_PROGRESS)
                        {
                            echo WsdUtil::loadTemplate('tpl-scan-progress', array('scanID'=>$scanSettings['SCAN_ID']));
                        }
                        ?>
                    </div>
                </div>

                <?php else : ?>
                    <?php echo WsdUtil::loadTemplate('tpl-last-scan-report-view'); ?>
                <?php endif;?>

            </div>
            <!-- RIGHT -->
            <div style="float: right; width: 17%;">
                <?php
                /*
                 * Display last scans
                 * ================================================================
                 */
                ?>
                <div id="lastScansWrapper">
                    <div class="wsdplugin_alert_section_title wsdplugin_alert_section_title_category"><p>Previous Scans</p></div>
                    <div class="inside" style="margin-left: 5px;">
                        <?php echo WsdUtil::loadTemplate('tpl-last-scans-list', array('showScanForm' => $showScanForm, 'scanState'=>$scanState)); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="<?php echo WsdUtil::jsUrl('wsdplugin-wp-scan.js') ;?>" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
    //#!++ Set the default dateScan
        var dateScan = 0; // default
        $('#dateScan').on('change', function(){ dateScan = $(this).val(); });
    //#!--
    <?php if($enableSubmit) : ?>
        $('#inputFormScan').on('click', function(){ $('#wpScanForm').submit(); });
    <?php endif; ?>
});
</script>