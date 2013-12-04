<?php if(! WsdUtil::canLoad()) { return; } ?>
<ul class="acx-common-list">
    <?php
        /*[ display wp info ]*/
        echo '<li><p>'.WsdInfo::getCurrentVersionInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getDatabasePrefixInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getWpVersionStatusInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getPhpStartupErrorStatusInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getAdminUsernameInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getWpAdminHtaccessInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getDatabaseUserAccessRightsInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getWpContentIndexInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getWpContentPluginsIndexInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getWpContentThemesIndexInfo().'</p></li>';
        echo '<li><p>'.WsdInfo::getWpContentUploadsIndexInfo().'</p></li>';
    ?>
</ul>
