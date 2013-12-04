<?php if(! WsdUtil::canLoad()) { return; } ?>
<?php
/*
 * DISPLAY AVAILABLE DOWNLOADS
 *======================================================
 */
?>
<?php
$files = WsdUtil::getAvailableBackupFiles();

    if (empty($files))
    {
        echo '<p>No backups files found.</p>';
    }
    else {
        echo '<div class="acx-section-box">';
            echo '<ul id="bck-list" data-nonce="'.wp_create_nonce("wpsBackupFileDelete_nonce").'">';
            foreach($files as $fileName) {
                echo '<li style="overflow: hidden;">';
                    echo '<a href="#" title="Delete this backup" class="acx-delete-bck" style="margin-top: 3px; margin-right: 7px; float: left;"><img src="'.WsdUtil::imageUrl('minus.gif').'"/></a>';
                    echo '<a href="',WPS_PLUGIN_URL.'res/backups/',$fileName,'" title="',__('Click to download'),'" style="float: left;">',$fileName,'</a>';
                echo '</li>';
            }
            echo '</ul>';
        echo '</div>';
    }
?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        function deleteBackupFile($,adminPostUrl, serverMethod, nonce, fileName, $item)
        {
            $.ajax({
                type : "post",
                dataType : "json",
                cache: false,
                url : adminPostUrl,
                data : {'action': serverMethod, 'nonce': nonce, 'file': fileName},
                success: function(response) {
                    if(response)
                    {
                        if(response.type == "success") {
                            $item.parent('li').remove();

                            // check if any elements left
                            var numElements = $item.parent('ul').find('li').length;
                            if(numElements == 0){
                                $item.parent('ul').remove();
                                $('#box-available-backups').append('<p>No backups files found.</p>');
                            }

                            alert(response.data);
                        }
                        else if(response == 'error'){ alert(response.data); }
                        response = null;
                    }
                    else { alert("An error has occurred while trying to delete the backup file. Please try again in a few seconds."); }
                }
            });
            return false;
        }

        var items = $('.acx-delete-bck');
        if(items.length > 0){
            $.each(items, function(i,v){
                var item = $(this), fileName = item.next('a').text();
                item.on('click', function(){ return deleteBackupFile($, "<?php echo admin_url( 'admin-ajax.php' );?>", "ajaxDeleteBackupFile", $('#bck-list').attr('data-nonce'), fileName, item); });
            });
        }
    });
</script>