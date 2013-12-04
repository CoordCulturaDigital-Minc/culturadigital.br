<?php if(! WsdUtil::canLoad()) { return; } ?>
<?php if(! WsdUtil::isAdministrator()) { return; } ?>

<h3 style="margin-bottom: 2px;">Scan State: Waiting</h3>
<hr style="height:1px; color: #eee; margin-top: 0; width: 75%;"/>

<p>The scan will start in approximately <strong>1 minute</strong>.</p>
<p>Scan ID: <strong><?php echo $scanID;?></strong></p>
