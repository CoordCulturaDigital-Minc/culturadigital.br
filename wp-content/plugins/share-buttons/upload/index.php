<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>PHP AJAX Image Upload, Truly Web 2.0!</title>
		<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
		<!-- MAKE SURE TO REFERENCE THIS FILE! -->
		<script type="text/javascript" src="scripts/ajaxupload.js"></script>
		<!-- END REQUIRED JS FILES -->
		<!-- THIS CSS MAKES THE IFRAME NOT JUMP -->
		<style type="text/css">
			iframe {
				display:none;
			}
		</style>
		<!-- THIS CSS MAKES THE IFRAME NOT JUMP -->
	</head>
	<body>
		<div id="container">
			<!-- THIS IS THE IMPORTANT STUFF! -->
			<div id="demo_area">
				<div id="left_col">
						<!-- 
							VERY IMPORTANT! Update the form elements below ajaxUpload fields:
							1. form - the form to submit or the ID of a form (ex. this.form or standard_use)
							2. url_action - url to submit the form. like 'action' parameter of forms.
							3. id_element - element that will receive return of upload.
							4. html_show_loading - Text (or image) that will be show while loading
							5. html_error_http - Text (or image) that will be show if HTTP error.

							VARIABLE PASSED BY THE FORM:
							maximum allowed file size in bytes:
							maxSize		= 9999999999
							
							maximum image width in pixels:
							maxW			= 100
							
							maximum image height in pixels:
							maxH			= 100
							
							the full path to the image upload folder:
							fullPath		= http://www.atwebresults.com/php_ajax_image_upload/uploads/
							
							the relative path from scripts/ajaxupload.php -> uploads/ folder
							relPath		= ../uploads/
							
							The next 3 are for cunstom matte color of transparent images (gif,png), use RGB value
							colorR		= 255
							colorG		= 255
							colorB		= 255

							The form name of the file upload script
							filename		= filename
						-->
					<fieldset>
						<legend>Sleeker More "Web 2.0" onChange Use</legend>
						<form action="index.php" method="post" name="sleeker" id="sleeker" enctype="multipart/form-data">
							<input type="hidden" name="maxSize" value="9999999999" />
							<input type="hidden" name="maxW" value="200" />
							<input type="hidden" name="fullPath" value="http://test-wordpress.kg/upload/uploads/" />
							<input type="hidden" name="relPath" value="../uploads/" />
							<input type="hidden" name="colorR" value="255" />
							<input type="hidden" name="colorG" value="255" />
							<input type="hidden" name="colorB" value="255" />
							<input type="hidden" name="maxH" value="300" />
							<input type="hidden" name="filename" value="filename" />
							<p><input type="file" name="filename" onchange="ajaxUpload(this.form,'scripts/ajaxupload.php?filename=name&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=http://test-wordprees.kg/upload/uploads/&amp;relPath=../uploads/&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','upload_area','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;" /></p>
						</form>
					</fieldset>
					<br /><small style="font-weight: bold; font-style:italic;">Supported File Types: gif, jpg, png</small>
				</div>
				<div id="right_col">
<?php

?>
					<div id="upload_area"><img src="uploads/logo.png">
					</div>
				</div>
				<div class="clear"> </div>
			</div>
			<!-- END IMPORTANT STUFF -->
</body>
</html>