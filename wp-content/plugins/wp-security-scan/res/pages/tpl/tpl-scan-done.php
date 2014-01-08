<?php if(! WsdUtil::canLoad()) { return; } ?>
<?php if(! WsdUtil::isAdministrator()) { return; } ?>
<?php
    // get last scan info
    $scanID = WsdWpScanner::getLastScanID_table();
    $scanInfo = WsdWpScanner::getScanInfo($scanID);
    $scanId = $scanInfo->scanId;
    $scanStartDate = $scanInfo->scanStartDate;
    $scanEndDate = $scanInfo->scanEndDate;
    $scanFailed = ($scanInfo->scanResult == 0);
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
?>
<h3 style="margin-bottom: 2px;">Scan complete</h3>
<hr style="height:1px; color: #eee; margin-top: 0; width: 75%;"/>
<p>Scan ID: <strong><?php echo $scanId;?></strong></p>
<p>Start date: <strong><?php echo $scanStartDate;?></strong></p>
<p>End date: <strong><?php echo $scanEndDate;?></strong></p>
<p>Type: Check for modified files since <strong><?php echo ($scanType==0) ? date('Y-m-d',$since).' 00:00:00' : date('Y-m-d H:i:s',$since);?></strong></p>
<p>Success: <strong><?php echo $scanFailed ? 'No' : 'Yes';?></strong></p>
<?php
if($scanFailed){ echo '<p>Error: '.$scanInfo->failReason.'</p>'; }
?>
<p><a href="admin.php?page=<?php echo WPS_PLUGIN_PREFIX.'wpscan'?>&scan=<?php echo $scanID;?>">View Report</a></p>
