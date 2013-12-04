<?php if(! WsdUtil::canLoad()) { return; } ?>
<?php if(! WsdUtil::isAdministrator()) { return; } ?>
<?php
// retrieve the list of all previous scans
    $scans = WsdWpScanner::getScans();
    $numScans = count($scans);
    if($numScans < 1){
        echo '<p>There are no finished scans yet.</p>';
    }
    else {
        if(!$showScanForm){
            echo '<form method="post" id="wpScanFormDelete">';
            echo (function_exists('wp_nonce_field')) ? wp_nonce_field('wss-wp-scan-nonce') : '';
        }
        echo '<ul id="wssLastScansList">';
        foreach($scans as $scan){
            $scanFailed = $scan->scanResult == 0;
            echo '<li>';
                echo '<a class="scanReportItem-js" id="e-'.$scan->scanId.'" href="admin.php?page='.WPS_PLUGIN_PREFIX.'wpscan&scan='.$scan->scanId.'" title="Click to view the scan report">'.$scan->scanEndDate.'</a>';
                if($scanFailed){ echo '<i class="icon-exclamation-sign" title="Scan Failed"></i>'; }
                else { echo '<i class="icon-ok" title="Scan Completed"></i>'; }
                echo '  <a href="#" title="Delete scan report" class="wss-delete-scan-js" data-scan-id="'.$scan->scanId.'"><i class="icon-remove"></i></a>';
            echo '</li>';
        }
        echo '</ul>';
        if(!$showScanForm){
            echo '</form>';
        }
    }
?>
<?php if(! in_array($scanState, array(WsdWPScanSettings::SCAN_STATE_IN_PROGRESS, WsdWPScanSettings::SCAN_STATE_WAITING))) : ?>
    <?php if($numScans>0):?>
    <p style="text-align: right; margin-top: 10px;">
        <input type="button" id="deleteScansButton" value="Delete all reports" class="button button-primary"/>
    </p>
    <?php endif;?>
<?php endif;?>
<script type="text/javascript">
jQuery(document).ready(function($){
    //#!++ Mark the selected scan
    var element = $('#e-<?php echo (isset($_GET['scan']) ? intval($_GET['scan']) : 0);?>');
    if(element.length > 0){
        element.css({'color':'#006400'});
        element.addClass('js-report-active');
        element.attr('title','');
    }
    //#! No need to have this link enabled as we're currently viewing the report it links to
    $('a.scanReportItem-js').on('click', function(){
        if($(this).hasClass('js-report-active')){
            return false;
        }
    });
    //#!++ Append the input and submit the form
    <?php
        if(!$showScanForm){
            echo 'var form = $("#wpScanFormDelete");';
        }
        else { echo 'var form = $("#wpScanForm");'; }
    ?>
    $('.wss-delete-scan-js').on('click', function(){
        var value = parseInt($(this).attr('data-scan-id'));
        form.append('<input name="deleteScan" type="hidden" value="'+value+'"/>');
        form.submit();
    });
    //#!++
    <?php if($numScans>0):?>
        $('#deleteScansButton').on('click',function(){ form.append('<input name="deleteAllScans" type="hidden" value="1"/>').submit(); });
    <?php endif;?>
});
</script>