<?php if(! WsdUtil::canLoad()) { return; } ?>
<?php if(! WsdUtil::isAdministrator()) { return; } ?>
<?php
    $scanID = (isset($_GET['scan']) ? intval($_GET['scan']) : 0);
    if(empty($scanID)){
        if(! headers_sent()){
            header("Location: admin.php?page=".WPS_PLUGIN_PREFIX.'wpscan');
            exit;
        }
        echo '<script type="text/javascript">window.location.href = "admin.php?page='.WPS_PLUGIN_PREFIX.'wpscan";</script>';
        return;
    }
    else
    {
        $isValidScan = WsdWpScanner::isValidScan($scanID);
        if($isValidScan)
        {
            $scanInfo = WsdWpScanner::getScanInfo($scanID);
            $scanStartDate = $scanInfo->scanStartDate;
            $scanEndDate = $scanInfo->scanEndDate;
            $scanFailed = $scanInfo->scanResult;
            $failReason = $scanInfo->failReason;
            $entries = WsdWpScanner::getFailedEntries($scanID);
            $scanType = $scanInfo->scanType;
            $h24 = 24 * 60 * 60;
            $since = 0;
            if($scanType == 0)    { $since = strtotime($scanStartDate); }
            elseif($scanType == 1){ $since = strtotime($scanStartDate) - $h24; }
            elseif($scanType == 2){ $since = strtotime($scanStartDate) - 2*$h24; }
            elseif($scanType == 3){ $since = strtotime($scanStartDate) - 3*$h24; }
            elseif($scanType == 4){ $since = strtotime($scanStartDate) - 4*$h24; }
            elseif($scanType == 5){ $since = strtotime($scanStartDate) - 5*$h24; }
            elseif($scanType == 6){ $since = strtotime($scanStartDate) - 6*$h24; }
            elseif($scanType == 7){ $since = strtotime($scanStartDate) - 7*$h24; }
            elseif($scanType == 8){ $since = strtotime("-1 months") - $h24 - strtotime($scanStartDate); }
        }
        // not a valid scan
        else {
            $scanID = 0;
            echo '<script type="text/javascript">window.location.href = "admin.php?page='.WPS_PLUGIN_PREFIX.'wpscan";</script>';
        }
    }
?>
<?php
$modifiedFiles = array();
$filesNotFound = array();
if(! empty($entries)){
    foreach($entries as $entry){
        if($entry->fileNotFound){
            array_push($filesNotFound, $entry);
        }
        else { array_push($modifiedFiles, $entry); }
    }
}
$numModified = count($modifiedFiles);
$num404 = count($filesNotFound);
?>
<div id="wpCoreScanReportWrapper">
    <div id="scanReportWrapper">
        <div class="wsdplugin_alert_section_title wsdplugin_alert_section_title_category">
            <h3 style="margin: 8px 0 8px 8px;"><?php echo __('Scan report',WpsSettings::TEXT_DOMAIN);?></h3>
        </div>
        <div id="scanReportContent">
            <div id="scanReportInsideWrapper">
            <?php
                // load the requested scan report if valid ID
                if($isValidScan):
                ?>
                <table class="table table-condensed" cellspacing="0" cellpadding="0">
                    <thead>
                        <th style="width:25px"><?php echo __('ID',WpsSettings::TEXT_DOMAIN);?></th>
                        <th style="width:140px"><?php echo __('Start date',WpsSettings::TEXT_DOMAIN);?></th>
                        <th style="width:140px"><?php echo __('End date',WpsSettings::TEXT_DOMAIN);?></th>
                        <th style="width:70px; text-align: center;"><?php echo __('Scan Status',WpsSettings::TEXT_DOMAIN);?></th>
                        <th style="width:60px; text-align: center;"><?php echo __('Alerts',WpsSettings::TEXT_DOMAIN);?></th>
                        <th><?php echo __('Fail reason',WpsSettings::TEXT_DOMAIN);?></th>
                    </thead>
                    <tbody>
                        <tr class="alt">
                            <td><?php echo $scanID;?></td>
                            <td><?php echo $scanStartDate;?></td>
                            <td><?php echo $scanEndDate;?></td>
                            <?php
                                $cssClass = 'wsdplugin_alert_indicator wsdplugin_alert_indicator_';
                                $title = '';
                                if($scanFailed==0){ $cssClass .= 'critical'; $title = __('Scan failed',WpsSettings::TEXT_DOMAIN); }
                                else { $cssClass .= 'info'; $title = __('Scan completed',WpsSettings::TEXT_DOMAIN); }
                            ?>
                            <td class="<?php echo $cssClass;?>" title="<?php echo $title;?>"></td>
                            <td style="text-align: center;"><?php echo ($num404 + $numModified); ?></td>
                            <td><?php echo $failReason;?></td>
                        </tr>
                    </tbody>
                </table>
                <p><?php echo __('Scan type: Check for modified files since',WpsSettings::TEXT_DOMAIN);?> <strong><?php echo ($scanType==0) ? date('M d, Y',$since).' 00:00:00' : date('Y-m-d H:i:s',$since);?></strong></p>
                <?php /*[[ IF FILES MODIFIED ]]*/ ?>
                    <div class="wsdplugin_alert_section_title wsdplugin_alert_section_title_category" style="margin-top: 20px;">
                    <h3 style="margin: 8px 0 8px 8px;"><?php echo __('Modified files',WpsSettings::TEXT_DOMAIN);?> (<?php echo $numModified;?>)</h3>
                </div>
                <?php if(!empty($modifiedFiles)) : ?>
                    <table class="table table-condensed" cellspacing="0" cellpadding="0" style="margin: 0 11px;">
                    <thead>
                        <th style="width: 80%; text-align: left;"><?php echo __('File Path',WpsSettings::TEXT_DOMAIN);?></th>
                        <th style="text-align: left;"><?php echo __('Last modified',WpsSettings::TEXT_DOMAIN);?></th>
                    </thead>
                    <tbody>
                    <?php foreach ($modifiedFiles as $entry) : ?>
                        <tr class="alt">
                            <td><?php echo $entry->filePath;?></td>
                            <td><?php echo $entry->dateModified;?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                <?php else : ?>
                    <p style="padding-left: 10px; font-weight: 800; font-size: 13px; color: #006400;"><?php echo __('No files were modified.',WpsSettings::TEXT_DOMAIN);?></p>
                <?php endif; /*#! !empty($modifiedFiles) */?>

                <?php /*[[ IF FILES NOT FOUND ]]*/ ?>
                <div class="wsdplugin_alert_section_title wsdplugin_alert_section_title_category" style="margin-top: 20px;">
                    <h3 style="margin: 8px 0 8px 8px;"><?php echo __('Files not found',WpsSettings::TEXT_DOMAIN);?> (<?php echo $num404;?>)</h3>
                </div>
                <?php if(!empty($filesNotFound)) : ?>
                    <table class="table table-condensed" cellspacing="0" cellpadding="0" style="margin: 0 11px;">
                        <thead>
                            <th style="width: 80%; text-align: left;"><?php echo __('File Path',WpsSettings::TEXT_DOMAIN);?></th>
                        </thead>
                        <tbody>
                        <?php foreach ($filesNotFound as $entry) : ?>
                            <tr class="alt">
                                <td><?php echo $entry->filePath;?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p style="padding-left: 10px; font-weight: 800; font-size: 13px; color: #006400;"><?php echo __('No missing files detected.',WpsSettings::TEXT_DOMAIN);?></p>
                <?php endif; /*#! !empty($modifiedFiles) */?>

                <?php /*[[ SHOW THE DELETE SCAN BUTTON ]]*/ ?>
                    <p style="margin-top: 40px;"><a href="#" class="button button-primary" id="wssDeleteScanButton"><?php echo __('Delete Scan Report',WpsSettings::TEXT_DOMAIN);?></a></p>
                    <script type="text/javascript">
                        jQuery(document).ready(function($){
                            //#!++ Append the input and submit the form
                            var form = $("#wpScanFormDelete");
                            $('#wssDeleteScanButton').on('click', function(){
                                form.append('<input name="deleteScan" type="hidden" value="<?php echo $scanID;?>"/>');
                                form.submit();
                            });
                        });
                    </script>
                <?php
                    else : echo '<p style="color: #d00000;">'. __("Invalid scan ID provided.",WpsSettings::TEXT_DOMAIN).'</p>';
                endif; //#! $isValidScan
                ?>
            </div>
        </div>
    </div>
</div>