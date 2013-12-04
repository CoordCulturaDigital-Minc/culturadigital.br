<?php /*/#! Check for install errors */ if(!wpsCanDisplayPage()){ return; } ?>
<?php
$liveTrafficToolEnabled = WpsOption::getOption(WpsSettings::ENABLE_LIVE_TRAFFIC);

if($liveTrafficToolEnabled)
{
    $refreshRates = array(0, 5, 10, 15, 20, 25, 30);

    $settings = array(
        WpsOption::getOption('WPS_KEEP_NUM_ENTRIES_LT'),
        WpsOption::getOption('WPS_REFRESH_RATE_AJAX_LT')
    );

    $rm = strtoupper($_SERVER['REQUEST_METHOD']);
    if('POST' == $rm)
    {
        // check nonce
        if(isset($_POST['wsdplugin_update_settings_field'])){
            if(!wp_verify_nonce($_POST['wsdplugin_update_settings_field'],'wsdplugin_update_settings')){
                exit('Invalid request.');
            }
        }
        else {exit('Invalid request.');}

        function wpsPluginValidateSettingsForm($refreshRates)
        {
            if(isset($_POST['max_number_live_traffic']) && isset($_POST['refreshRateOption']))
            {
                // validate input $_POST['max_number_live_traffic']
                $keepNumEntriesLiveTraffic = intval($_POST['max_number_live_traffic']);
                if($keepNumEntriesLiveTraffic == 0){
                    $keepNumEntriesLiveTraffic = 0;
                }
                elseif(! preg_match("/[0-9]{1,5}/", $keepNumEntriesLiveTraffic)){
                    $keepNumEntriesLiveTraffic = 500;
                }
                // validate input $_POST['refreshRateOption']
                $liveTrafficRefreshRateAjax = intval($_POST['refreshRateOption']);
                if(! in_array($liveTrafficRefreshRateAjax, $refreshRates)){
                    $liveTrafficRefreshRateAjax = 10;
                }
                elseif($_POST['refreshRateOption'] == 0){
                    $liveTrafficRefreshRateAjax = 0;
                }
                elseif(! preg_match("/[0-9]{1,2}/", $liveTrafficRefreshRateAjax)){
                    $liveTrafficRefreshRateAjax = 10;
                }
                // update settings
                WpsOption::updateOption('WPS_KEEP_NUM_ENTRIES_LT',$keepNumEntriesLiveTraffic);
                WpsOption::updateOption('WPS_REFRESH_RATE_AJAX_LT',$liveTrafficRefreshRateAjax);
                return array($keepNumEntriesLiveTraffic, $liveTrafficRefreshRateAjax);
            }
            else { exit('Invalid request.');  }
        }

        // check form
        if(isset($_POST['updateSettingsButton']))
        {
            if(isset($_POST['max_number_live_traffic']) && isset($_POST['refreshRateOption']))
            {
                $settings = wpsPluginValidateSettingsForm($refreshRates);
            }
            else { exit('Invalid request.');  }
        }
        elseif(isset($_POST['deleteEntriesButton'])){
            global $wpdb;
            $query = "TRUNCATE ".WsdPlugin::getTableName(WpsSettings::LIVE_TRAFFIC_TABLE_NAME);
            $res = $wpdb->query($query);
            if($res !== false){
                WpsOption::updateOption(WpsSettings::LIVE_TRAFFIC_ENTRIES, 0);
            }
            $settings = wpsPluginValidateSettingsForm($refreshRates);
        }
        else { exit('Invalid request.');  }
    }

    $keepNumEntriesLiveTraffic = $settings[0];
    $liveTrafficRefreshRateAjax = $settings[1];
}
?>
<?php if($liveTrafficToolEnabled) : ?>
<div class="wrap wsdplugin_content">
    <h2><?php echo WPS_PLUGIN_NAME.' - '. __('Live Traffic');?></h2>

    <p class="clear"></p>

    <?php if(wpsIsMainSite()):?>
    <div style="padding: 0 0;">
        <form method="post">
            <?php wp_nonce_field('wsdplugin_update_settings','wsdplugin_update_settings_field'); ?>
            <fieldset class="wsdPluginFieldsetSettingsExpanded">
                <legend id="settingsLegend">Settings</legend>
                <p id="settingsContent">
                    <label for="max_number_live_traffic">Maximum number of entries to store for Live Traffic:</label>
                    <input type="text" name="max_number_live_traffic" id="max_number_live_traffic" value="<?php echo $keepNumEntriesLiveTraffic;?>" maxlength="5"/>

                    <label for="refreshRateOption" style="margin-left: 20px;">Refresh rate</label>
                    <select name="refreshRateOption" id="refreshRateOption">
                        <?php
                        foreach($refreshRates as $rate){
                            $selected = ($rate == $liveTrafficRefreshRateAjax ? 'selected="selected"' : '');
                            if($rate == 0){
                                echo '<option value="'.$rate.'" '.$selected.'>Never</option>';
                            }
                            else {  echo '<option value="'.$rate.'" '.$selected.'>'.$rate.' seconds</option>'; }
                        }
                        ?>
                    </select>
                    <input type="submit" value="Update settings" class="btn button-primary" name="updateSettingsButton" style="margin-left: 20px;"/>
                    <?php if($liveTrafficRefreshRateAjax == 0) :?>
                        <input type="button" value="Refresh" class="btn btn-inverse" style="margin-left: 20px;" onclick="javascript: window.location.href=document.URL;"/>
                    <?php endif;?>
                    <input type="submit" value="Delete all" class="btn btn-danger" name="deleteEntriesButton" style="margin-left: 20px;"/>
                </p>
            </fieldset>
        </form>
    </div>
    <?php endif;?>

    <p class="clear"></p>
    <table id="wsdTrafficScanTable" cellspacing="0"
           class="wp-list-table widefat wsdTrafficScan"
           data-nonce="<?php echo wp_create_nonce("wpsTrafficScan_nonce");?>"
           data-lastid="<?php echo WsdLiveTraffic::getLastID();?>">
        <thead style="height: 20px;">
        <tr>
            <th class="manage-column column-cb" scope="col">
                <span style="display: block; float: left; font-weight: 800;"><?php echo __('Live activity');?></span>
                <p id="loaderWrapper"></p>
            </th>
        </tr>
        </thead>
        <tbody id="the-list"></tbody>
    </table>
</div>
<script src="<?php echo WsdUtil::jsUrl('live-traffic-queue.js') ;?>"></script>
<script type="text/javascript">
if(liveTrafficQueue != undefined)
{
    jQuery(document).ready(function($)
    {
        var maxEntries = ((<?php echo $keepNumEntriesLiveTraffic;?> > 100) ? 100 : <?php echo $keepNumEntriesLiveTraffic;?>);
        var queue = new liveTrafficQueue(
            $
            ,"<?php echo admin_url( 'admin-ajax.php' );?>"
            ,"ajaxGetTrafficData"
            ,"<?php echo WsdUtil::imageUrl('ajax-loader.gif') ?>"
            ,maxEntries);

        queue.retrieveData();

        <?php /*[ enable autoload only if refresh rate > 0 ]*/ ?>
        <?php if($liveTrafficRefreshRateAjax > 0) : ?> window.setInterval(function(){ queue.retrieveData(); }, <?php echo $liveTrafficRefreshRateAjax * 1000 ?>);
        <?php endif; ?>

        // settings
        var settingsLegend = $('#settingsLegend');
        var settingsContent = $('#settingsContent');
        settingsLegend.toggle(
            function(){
                settingsContent.hide();
                settingsLegend.parent().removeClass('wsdPluginFieldsetSettingsExpanded').addClass('wsdPluginFieldsetSettingsCollapsed');
            },
            function(){
                settingsLegend.parent().removeClass('wsdPluginFieldsetSettingsCollapsed').addClass('wsdPluginFieldsetSettingsExpanded');
                settingsContent.show();
            }
        );
    });
}
</script>
<?php else : ?>
    <div class="wrap wsdplugin_content">
        <h2><?php echo WPS_PLUGIN_NAME.' - '. __('Live Traffic');?></h2>
        <p class="clear"></p>
        <div class="error">
            <p><?php echo __('The Live traffic tool has been disabled. To enable it, go to <a href="admin.php?page=wps_settings">settings</a> page and tick the <strong>"Enable Live Traffic tool"</strong> option.');?></p>
        </div>
    </div>
<?php endif;?>
