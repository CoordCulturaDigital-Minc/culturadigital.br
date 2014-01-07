<?php if(! WsdUtil::canLoad()) { return; } ?>
<?php if(! WsdUtil::isAdministrator()) { return; } ?>

<h3 style="margin-bottom: 2px;">Scan State: None</h3>
<hr style="height:1px; color: #eee; margin-top: 0; width: 75%;"/>
<p>This tool will try to identify your WordPress version and it will check all files against the official released
    version of WordPress to see if they were modified in the selected time interval.</p>
<p>Select the time interval to check if any of the WordPress core files were modified.</p>
