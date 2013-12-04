<link href="<?php echo $this->plugin_url;?>upload/css/styles.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo $this->plugin_url;?>css/share-buttons-settings.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $this->plugin_url;?>upload/scripts/ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo $this->plugin_url;?>js/jquery-ui-1.8.1.min.js"></script> 
<script type="text/javascript" src="<?php echo $this->plugin_url;?>js/jquery.corner.js"></script>
<script>
jQuery(document).ready(function($){
	$('.note').corner();
	$('#sortable-list li').corner("5px");

});
</script>

<div>
	<div class="wrap">
		<div>
			<h2><?php _e('Share Buttons', $this->plugin_domain) ?> → <?php _e('Main Settings', $this->plugin_domain); ?></h2>
			<div class="donate"><a href=<?php if(get_locale()=='ru_RU') { ?>"http://sbuttons.ru/ru/donate-ru/"<?php } else { ?>"http://sbuttons.ru/en/donate-en/" <?php } ?> title="Donate"><img src="<?php echo $this->plugin_url;?>/images/other/donate.png" alt="Donation link" border="0"/ ></a></div>
			<div class="clear"></div>
			<div id="container">
				<br />
				<fieldset class="fieldset_image">
					<legend><?php _e('Upload picture for your site-logo', $this->plugin_domain) ?></legend>
					<div id="left_col">
						<form action="<?php echo $this->plugin_url;?>upload/scripts/ajaxupload.php" method="post" name="sleeker" id="sleeker" enctype="multipart/form-data">
							<input type="hidden" name="maxSize" value="7291456" />
							<input type="hidden" name="maxW" value="150" />
							<input type="hidden" name="fullPath" value="<?php echo $this->plugin_url;?>upload/uploads/" />
							<input type="hidden" name="relPath" value="<?php echo dirname(__FILE__);?>/upload/uploads/" />
							<input type="hidden" name="colorR" value="255" />
							<input type="hidden" name="colorG" value="255" />
							<input type="hidden" name="colorB" value="255" />
							<input type="hidden" name="maxH" value="150" />
							<input type="hidden" name="filename" value="filename" />
							<input type="file"  size="40" id="file_input" name="filename" onchange="ajaxUpload(this.form,'<?php echo $this->plugin_url;?>upload/scripts/ajaxupload.php?filename=name&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=<?php echo $this->plugin_url;?>upload/uploads/&amp;relPath=../uploads/&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','upload_area','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'<?php echo $this->plugin_url;?>upload/images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'upload/images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;" />
						</form>
						<div style="width: 300px;"><small><?php _e('Files must be <b>.jpg, .gif, .png</b> extension, the desired size of <b>100x100 pixels</b>.',$this->plugin_domain);?></small></div>
					</div>
					<div id="right_col">
						<?php if(file_exists(dirname(__FILE__).'/upload/uploads/logo.png')) { ?>
							<div id="upload_area"><img src="<?php echo $this->plugin_url;?>upload/uploads/logo.png" /></div>
						<?php } else { ?>
							<div id="upload_area"><img src="<?php echo $this->plugin_url;?>images/other/trans.png" width="150px" height="150px"/></div>
						<?php } ?>
					</div>
					<div class="clear"></div>
					<br />
				</fieldset>
			</div>

			<form method="post" action="options.php">
			<?php settings_fields($this->pluginPrefix . 'settings'); ?>

			<fieldset class="fieldset_position">
				<legend><?php _e('Header text before social buttons', $this->plugin_domain);?></legend>
				<div class="body_position">
					<div class="GroupHead"><?php _e('Your text', $this->plugin_domain);?>:</div>
					<div class="GroupBody"><input type="text" name="<?php echo $this->pluginPrefix; ?>header_text" size="100" value="<?php echo esc_attr($this->header_text);?>" class="regular-text" /></div>
				</div>
			</fieldset>
			<br />

			<fieldset class="fieldset_position">
				<legend><?php _e('Generate meta data', $this->plugin_domain);?></legend>
				<div class="body_position">
					<div class="GroupHead"><?php _e('Generate meta data: description, title', $this->plugin_domain);?>:</div>
					<div class="GroupBody">
						<label for="generate_meta">
							<input name="<?php echo $this->pluginPrefix; ?>generate_meta" type="checkbox" id="generate_meta" value="1" <?php checked(TRUE, $this->generate_meta); ?> />
							<?php _e('On/Off', $this->plugin_domain) ?>
						</label>
					</div>
				</div>
			</fieldset>
			<br />

			<fieldset class="fieldset_position">
				<legend><?php _e('Position Share Buttons', $this->plugin_domain);?></legend>
				<div class="body_position">
					<div class="GroupHead"><label for="share_buttons_position"><?php _e('Button horizontal position', $this->plugin_domain) ?></label></div>
					<div class="GroupBody">
						<select name="<?php echo $this->pluginPrefix; ?>position" id="share_buttons_position" value="<?php echo $sb_pos; ?>">
							<option <?php if($this->position == 'right') echo("selected=\"selected\""); ?> value="right"><?php _e('Right', $this->plugin_domain) ?></option>
							<option <?php if($this->position == 'left') echo("selected=\"selected\""); ?> value="left"><?php _e('Left', $this->plugin_domain) ?></option>
							<option <?php if($this->position == 'center') echo("selected=\"selected\""); ?> value="center"><?php _e('Center', $this->plugin_domain) ?></option>
						</select>
					</div>
					<br />

					<div class="GroupHead"><label for="share_buttons_vposition"><?php _e('Button vertical position', $this->plugin_domain) ?></label></div>
					<div class="GroupBody">
						<select name="<?php echo $this->pluginPrefix; ?>vposition" id="share_buttons_vposition" value="<?php echo $sb_vpos; ?>">
							<option <?php if($this->vposition == 'top') echo("selected=\"selected\""); ?> value="top"><?php _e('On top of post', $this->plugin_domain) ?></option>
							<option <?php if($this->vposition == 'bottom') echo("selected=\"selected\""); ?> value="bottom"><?php _e('On bottom of post', $this->plugin_domain) ?></option>
						</select>
					</div>
					<br />

					<div class="GroupHead"><?php _e('The Button displays on', $this->plugin_domain) ?></div>
					<div class="GroupBody">
							<label for="share_buttons_show_on_posts">
								<input name="<?php echo $this->pluginPrefix; ?>show_on_posts" type="checkbox" id="share_buttons_show_on_posts" value="1" <?php checked(TRUE, $this->show_on_post); ?> />
								<?php _e('Posts', $this->plugin_domain) ?>
							</label>
							<br />

							<label for="share_buttons_show_on_pages">
								<input name="<?php echo $this->pluginPrefix; ?>show_on_pages" type="checkbox" id="share_buttons_show_on_pages" value="1" <?php checked(TRUE, $this->show_on_page); ?> />
								<?php _e('Pages', $this->plugin_domain) ?>
							</label>
							<br />
							<label for="share_buttons_show_on_home">
								<input name="<?php echo $this->pluginPrefix; ?>show_on_home" type="checkbox" id="share_buttons_show_on_home" value="1" <?php checked(TRUE, $this->show_on_home); ?> />
								<?php _e('Home', $this->plugin_domain) ?>
							</label>
							<br />
					</div>
					<br />

					<div class="GroupHead"><?php _e('Exclude pages and posts with IDs', $this->plugin_domain) ?></label></div>
					<div class="GroupBody"><input type="text" name="<?php echo $this->pluginPrefix; ?>exclude" value="<?php echo esc_attr($this->exclude); ?>" class="regular-text" /></div>
				</div>
			</fieldset>
			<br />

			<fieldset class="fieldset_position">
				<legend><?php _e('Other Settings', $this->plugin_domain);?></legend>
				<div class="body_position">
					<div class="GroupHead"><?php _e('Twitter via', $this->plugin_domain); ?>&nbsp;<span class="description">(<?php _e('Your Nickname without "@"', $this->plugin_domain);?>)</span></div>
					<div class="GroupBody"><input type="text" name="<?php echo $this->pluginPrefix; ?>twitter_via" value="<?php echo esc_attr($this->twitter_via);?>" class="regular-text" /></div>
				</div>
			</fieldset>

			<div class="button_submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes', $this->plugin_domain) ?>" />
			</div>
			</form>
		</div>
	</div>
</div>