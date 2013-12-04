<?php if(! WsdUtil::canLoad()) { return; } ?>
<?php if(! WsdUtil::isAdministrator()) { return; } ?>
<?php
    $progress = WsdWPScanSettings::getSetting('SCAN_PROGRESS');
    if($progress == WsdWPScanSettings::SCAN_PROGRESS_NONE){
        $currentlyScanning = 'nothing';
    }
    elseif($progress == WsdWPScanSettings::SCAN_PROGRESS_ROOT){
        $currentlyScanning = '<strong>root</strong> directory';
    }
    elseif($progress == WsdWPScanSettings::SCAN_PROGRESS_ADMIN){
        $currentlyScanning = '<strong>wp-admin</strong> directory';
    }
    elseif($progress == WsdWPScanSettings::SCAN_PROGRESS_CONTENT){
        $currentlyScanning = '<strong>wp-content</strong> directory';
    }
    elseif($progress == WsdWPScanSettings::SCAN_PROGRESS_INCLUDES){
        $currentlyScanning = '<strong>wp-includes</strong> directory';
    }
?>
<style type="text/css">
    #progressBar { width: 400px; padding: 0 0; line-height: normal; }
    #step { display:block; background: #e0e0e0; border: solid 1px #bebebe;padding: 3px 0; margin: 0 0; line-height: normal; text-align: center;}
    #step span { font-weight: 800; color: #000; font-size: 12px; }
</style>

<h3 style="margin-bottom: 2px;">Scan State: In Progress</h3>
<hr style="height:1px; color: #eee; margin-top: 0; width: 75%;"/>

<div id="progressBar"><p id="step"><span></span></p></div>

<script type="text/javascript">
jQuery(document).ready(function($){
    var progressBar = $('#progressBar')
        ,step = $('#step')
        ,progress = <?php echo $progress; ?>
        ,pbWidth = progressBar.width()
        ,pbText = $('span', step)
        ,w = 0;

    if(progress == 0){ w = 0; pbText.html('0%'); }
    else if(progress == 1) { w = parseInt(pbWidth/4); pbText.html('25%'); }
    else if(progress == 2) { w = parseInt(pbWidth/3); pbText.html('50%'); }
    else if(progress == 3) { w = parseInt(pbWidth/2); pbText.html('75%'); }
    else if(progress == 4) { w = parseInt(pbWidth - 15); pbText.html('90%'); }

    step.css({'width' : w});
});
</script>

<p style="margin-top: 15px;"></p>
<p>Scan ID: <strong><?php echo $scanID;?></strong></p>
<p>Currently scanning: <?php echo $currentlyScanning;?></p>