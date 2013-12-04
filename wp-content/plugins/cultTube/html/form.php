<div id="content">
	<div class="videoUpload">	
		<form id="form1" action="<?php bloginfo('url') ?>/wp-content/plugins/cultTube/upload.php" method="post" enctype="multipart/form-data">
			<p style="color:#666; margin-bottom:15px">Formats supported: mp4, webm, ogg, avi</p>
			<div class="swfupload">
				<div class="fieldset flash" id="fsUploadProgress1">
					<span class="legend">Upload you video:</span>
				</div>
				<div style="padding-left: 5px;">
					<span id="spanButtonPlaceholder1"></span>
					<input id="btnCancel1" type="button" value="Cancel Uploads" onclick="cancelQueue(upload1);" disabled="disabled" style="margin-left: 2px; height: 22px; font-size: 8pt;" />
					<br />
					<a href="" class="bt_normalForm">Usar html para upload de arquivos</a>
					<div id="upload_button" style="display:none">Upload</div>
                    
                    <script type="text/javascript">
                        jQuery('.bt_normalForm').click(function() {
                            jQuery(this).hide();
                            jQuery('#upload_button').show();
                            
                            return false;
                        });
                    </script>
				</div>
			</div>
		</form>
	</div>
	
	<div class="videoPlayer">
		<form method="post" action="<?php bloginfo('url') ?>/wp-content/plugins/cultTube/upload.php">
			<div class="fieldset flash">
				<span class="legend">Video player:</span>
				<label for="mp4">Mp4:</label>
				<input type="text" id="mp4" name="mp4" />
				<label for="ogg">Ogg:</label>
				<input type="text" id="ogg" name="ogg" />
				<label for="webm">Webm:</label>
				<input type="text" id="webm" name="webm" />
			</div>
			<input type="submit" name="attachToPost" id="attachToPost" value="Insert into post" />
		</form>
	</div>

</div>