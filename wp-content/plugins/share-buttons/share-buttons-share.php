<link href="<?php echo $this->plugin_url;?>css/share-buttons-share.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $this->plugin_url;?>upload/scripts/ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo $this->plugin_url;?>js/jquery-ui-1.8.1.min.js"></script> 
<script type="text/javascript" src="<?php echo $this->plugin_url;?>js/jquery.corner.js"></script>

<script>
jQuery(document).ready(function($){
	/* grab important elements */
	var sortInput = jQuery('#sort_order');
	//var submit = jQuery('#autoSubmit');
	var messageBox = jQuery('#message-box');
	var list = jQuery('#sortable-list');
	/* create requesting function to avoid duplicate code */
	var request = function() {
		jQuery.ajax({
			beforeSend: function() {
				messageBox.text('Updating the sort order in the database.');
			},
			complete: function() {
				messageBox.text('Database has been updated.');
			},
			data: 'sort_order=' + sortInput[0].value + '&ajax=' + '&do_submit=1&byajax=1', //need [0]?
			type: 'post',
			url: '<?php echo $_SERVER["REQUEST_URI"]; ?>'
		});
	};
	/* worker function */
	var fnSubmit = function(save) {
		var sortOrder = [];
		list.children('div').each(function(){
			sortOrder.push(jQuery(this).data('id'));
		});
		sortInput.val(sortOrder.join(','));
		//console.log(sortInput.val());
		if(save) {
			request();
		}
	};
	/* store values */
	list.children('div').each(function() {
		var span = jQuery(this);
		span.data('id',span.attr('title')).attr('title','');
	});
	/* sortables */
	list.sortable({
		opacity: 0.7,
		update: function() {
			fnSubmit(true);
		}
	});
	list.disableSelection();
	/* ajax form submission */
	jQuery('#dd-form').bind('submit',function(e) {
		if(e) e.preventDefault();
		fnSubmit(true);
	});

	$('.note').corner();
	$('#sortable-list li').corner("5px");
});
</script>

<div>
	<div class="wrap">
		<div>
			<h2><?php _e('Share Buttons', $this->plugin_domain) ?> → <?php _e('Share Settings', $this->plugin_domain); ?></h2>
			<div class="donate"><a href=<?php if(get_locale()=='ru_RU') { ?>"http://sbuttons.ru/ru/donate-ru/"<?php } else { ?>"http://sbuttons.ru/en/donate-en/" <?php } ?> title="Donate"><img src="<?php echo $this->plugin_url;?>/images/other/donate.png" alt="Donation link" border="0"/ ></a></div>
			<div class="clear"></div>

				<fieldset class="fieldset_sort">
					<legend><?php _e('Sort buttons', $this->plugin_domain) ?></legend>
					<div class="body_sort">
						<?php
							global $wpdb;
							$order = array();
							$j=0;
						?>
						<ul id="sortable-list">
						<?php
							$this->initKeyed();
							$btnsort = $this->btnsort;
							foreach($btnsort as $key=>$value) {
								$valsort[]=$value;
								$keysort[]=$key;
							}
							$style_social_name = array('Facebook', 'Google Buzz', 'Google Plus', 'LiveJournal', 'Mail.ru', 'Одноклассники', 'Twitter', 'Вконтакте','Yandex');

							$duplic_valsort = $valsort;

							asort($duplic_valsort);

							$duplic_valsort=array_keys($duplic_valsort);

							for($i=0;$i<count($btnsort);$i++) {
								$namesort[$style_social_name[$i]]=$duplic_valsort[$i];
								asort($namesort);

							}

							$namesort=array_keys($namesort);

							for($j=0;$j<count($btnsort);$j++) {
								echo '<div title="'.$valsort[$j].'" id="'.$valsort[$j].'">';
									echo '<li>'.$namesort[$j].'</li>';
								echo '</div>';
								$order[] = $valsort[$j];
							}

						?>
						</ul>
						<br />
						<input type="hidden" name="sort_order" id="sort_order" value="<?php echo implode(',',$order); ?>" />
						<?php
							/* on form submission */
							if(isset($_POST['do_submit']))  {
								/* split the value of the sortation */
								$ids = $_POST['sort_order'];
								/* run the update query for each id */
								$query = "UPDATE $wpdb->options SET option_value = '$ids' WHERE option_name ='".$this->pluginPrefix."buttons_sort'";
								$result = mysql_query($query) or die(mysql_error().': '.$query);
								/* now what? */
								if($_POST['byajax']) { die(); } else { $message = 'Sortation has been saved.'; }
							}
						?>
					</div>
				</fieldset>

			<form method="post" action="options.php">
			<?php settings_fields($this->pluginPrefix . 'settings_share'); ?>

				<fieldset class="fieldset_style">
					<legend><?php _e('Choose the display style for your social buttons', $this->plugin_domain);?></legend>
					<div id="customize_type" class="body_style">
						<div class="GroupHead"><input type="radio" name="<?php echo $this->pluginPrefix; ?>opt_customize_type" id="opt_customize_type" value="original_count" <?php echo (get_option($this->pluginPrefix . 'opt_customize_type') == 'original_count' ? 'checked' : ''); ?> />01. <?php _e('Original with count', $this->plugin_domain);?></div>
						<div class="GroupBodyS"><img src="<?php echo $this->plugin_url;?>images/social/original_count.jpg" /></div>
						<br />

						<div class="GroupHead"><input type="radio" name="<?php echo $this->pluginPrefix; ?>opt_customize_type" id="opt_customize_type" value="soft_round" <?php echo (get_option($this->pluginPrefix . 'opt_customize_type') == 'soft_round' ? 'checked' : ''); ?> />01. <?php _e('Soft Round', $this->plugin_domain);?></div>
						<div class="GroupBodyS"><img src="<?php echo $this->plugin_url;?>images/social/soft_round.jpg" /></div>
						<br />

						<div class="GroupHead"><input type="radio" name="<?php echo $this->pluginPrefix; ?>opt_customize_type" id="opt_customize_type" value="soft_rect" <?php echo (get_option($this->pluginPrefix . 'opt_customize_type') == 'soft_rect' ? 'checked' : ''); ?> />02. <?php _e('Soft Rectangle', $this->plugin_domain);?></div>
						<div class="GroupBodyS"><img src="<?php echo $this->plugin_url;?>images/social/soft_rect.jpg" /></div>
						<br />

						<div class="GroupHead"><input type="radio" name="<?php echo $this->pluginPrefix; ?>opt_customize_type" id="opt_customize_type" value="classic" <?php echo (get_option($this->pluginPrefix . 'opt_customize_type') == 'classic' ? 'checked' : ''); ?> />03. <?php _e('Classic', $this->plugin_domain);?></div>
						<div class="GroupBodyS"><img src="<?php echo $this->plugin_url;?>images/social/classic.jpg" /></div>
						<br />

						<div class="GroupHead"><input type="radio" name="<?php echo $this->pluginPrefix; ?>opt_customize_type" id="opt_customize_type" value="mini" <?php echo (get_option($this->pluginPrefix . 'opt_customize_type') == 'mini' ? 'checked' : ''); ?> />04. <?php _e('Classic mini', $this->plugin_domain);?></div>
						<div class="GroupBodyS"><img src="<?php echo $this->plugin_url;?>images/social/mini.jpg" /></div>
						<br />
					</div>
				</fieldset>
			<div style="clear:both;"></div>

				<fieldset class="fieldset_enable">
					<legend><?php _e('Enable/Disable Share buttons', $this->plugin_domain);?></legend>
					<div class="body_enable">
						<?php
							$show=get_option($this->pluginPrefix . 'buttons_show');
							if(@array_key_exists(0, $show)==false) {
								$show = explode(',',$show);
							}
							$soc_name = array('Facebook', 'Google Buzz', 'Google Plus', 'LiveJournal', 'Mail.ru', 'Одноклассники', 'Twitter', 'Вконтакте','Yandex');
						?>
						
						<div style="float:left; width:150px;">
							<label for="facebook_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>buttons_show[]" type="checkbox" id="buttons_show" value="facebook" <?php checked(TRUE, in_array('facebook',$show)); ?> />
								<?php _e('Facebook', $this->plugin_domain) ?>
							</label>
							<br/>
							<label for="googlebuzz_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>buttons_show[]" type="checkbox" id="buttons_show" value="googlebuzz" <?php checked(TRUE, in_array('googlebuzz',$show)); ?> />
								<?php _e('Google Buzz', $this->plugin_domain) ?>
							</label>
							<br/>
							<label for="googleplus_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>buttons_show[]" type="checkbox" id="buttons_show" value="googleplus" <?php checked(TRUE, in_array('googleplus',$show)); ?> />
								<?php _e('Google Plus', $this->plugin_domain) ?>
							</label>
							<br/>
							<label for="livejournal_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>buttons_show[]" type="checkbox" id="buttons_show" value="livejournal" <?php checked(TRUE, in_array('livejournal',$show)); ?> />
								<?php _e('LiveJournal', $this->plugin_domain) ?>
							</label>
						</div>

						<div style="float:left; width:150px;">
							<label for="mailru_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>buttons_show[]" type="checkbox" id="buttons_show" value="mailru" <?php checked(TRUE, in_array('mailru',$show)); ?> />
								<?php _e('Mail.ru', $this->plugin_domain) ?>
							</label>
							<br />

							<label for="odnoklassniki_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>buttons_show[]" type="checkbox" id="buttons_show" value="odnoklassniki" <?php checked(TRUE, in_array('odnoklassniki',$show)); ?> />
								<?php _e('Odnoklassniki', $this->plugin_domain) ?>
							</label>
							<br/>
							<label for="twitter_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>buttons_show[]" type="checkbox" id="buttons_show" value="twitter" <?php checked(TRUE, in_array('twitter',$show)); ?> />
								<?php _e('Twitter', $this->plugin_domain) ?>
							</label>
							<br/>
							<label for="vkontakte_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>buttons_show[]" type="checkbox" id="buttons_show" value="vkontakte" <?php checked(TRUE, in_array('vkontakte',$show)); ?> />
								<?php _e('Vkontakte', $this->plugin_domain) ?>
							</label>
							<br/>
						</div>
						<div style="float:left;">
							<label for="yandex_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>buttons_show[]" type="checkbox" id="buttons_show" value="yandex" <?php checked(TRUE, in_array('yandex',$show)); ?> />
								<?php _e('Yandex', $this->plugin_domain) ?>
							</label>
						</div>
						<div style="clear:both;"></div>
					</div>
				</fieldset>
				<br />
				<fieldset class="fieldset_position">
					<legend><?php _e('Margins top and bottom block buttons', $this->plugin_domain);?></legend>
					<div class="body_position">
						<div class="note"><?php _e('Please, insert only <b>integer</b> and integer without <b>px</b>, for example: 1, 2, 3, 4, 5...10 and e.t.c', $this->plugin_domain);?></div>
						<br/>

						<div class="GroupHead"><?php _e('Margin top', $this->plugin_domain);?>:</div>
						<div class="GroupBody"><input type="text" name="<?php echo $this->pluginPrefix; ?>margin_top" size="35" value="<?php echo esc_attr($this->margin_top);?>" class="regular-text" /></div>
						<br />

						<div class="GroupHead"><?php _e('Margin bottom', $this->plugin_domain);?>:</div>
						<div class="GroupBody"><input type="text" name="<?php echo $this->pluginPrefix; ?>margin_bottom" size="35" value="<?php echo esc_attr($this->margin_bottom);?>" class="regular-text" /></div>
					</div>
				</fieldset>

			<div class="button_submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes', $this->plugin_domain) ?>" />
			</div>
			</form>
		</div>
	</div>
</div>