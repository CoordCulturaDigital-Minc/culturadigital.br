<link href="<?php echo $this->plugin_url;?>css/share-buttons-like.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $this->plugin_url;?>js/jquery-ui-1.8.1.min.js"></script> 
<script type="text/javascript" src="<?php echo $this->plugin_url;?>js/jquery.corner.js"></script>
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?34"></script>

<script type="text/javascript">
  VK.init({apiId: <?php echo $this->vkontakte_like_api; ?>, onlyWidgets: true});
</script>

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


	url = "<?php echo $this->plugin_url;?>";

	locale = '<?php echo get_locale();?>';

	domain = "<?php echo $_SERVER['HTTP_HOST']; ?>";

	$("#mailru_like_type, #mail_like_verb, #odkl_like_verb, #mailru_like_counter_btn, #mailru_like_text_btn").change(function(){
		var src = $("#mailru iframe").attr('src');
		type = $('#mailru_like_type:checked').val();
		mail_verb = $("#mail_like_verb option:selected").val();
		odkl_verb = $("#odkl_like_verb option:selected").val();
		mailru_text = $("#mailru_like_text_btn:checked").val();
		mailru_counter = $("#mailru_like_counter_btn:checked").val();
		if(mailru_text==1) { mailru_text='&text=true'; } else { mailru_text='&nt=1'; }
		if(mailru_counter==1) { mailru_counter='&counter=true'; } else { mailru_counter='&nc=1'; }
		var tmp = "http://connect.mail.ru/share_button?uber-share=1&type="+type+"&caption-mm="+mail_verb+"&caption-ok="+odkl_verb+mailru_counter+mailru_text+"&width=450&domain="+domain+"&url=http%3A%2F%2Fsbuttons.ru&buttonID=3744650&faces_count=10&height=21&caption=%D0%9D%D1%80%D0%B0%D0%B2%D0%B8%D1%82%D1%81%D1%8F&wid=1783021&app_id=-1&host=http%3A%2F%2F"+domain+"";
		$("#mailru iframe").attr("src", tmp);
	});

	$("#facebook_like_layout, #facebook_like_verb, #facebook_like_send, #facebook_like_color").change(function(){
		var src = $("#fb iframe").attr('src');
		layout = $("#facebook_like_layout option:selected").val();

		send = $("#facebook_like_send:checked").val();

		width = $("#facebook_like_width").val();
		faces = $("#facebook_like_faces:checked").val();
		verb = $("#facebook_like_verb option:selected").val();
		color = $("#facebook_like_color option:selected").val();

		if (!send==true) {send='false'; }
		if (!faces==true) {faces='false'; }

		$("#fb iframe").height(29);
		$("#fb iframe").width(450);

		if(layout=="box_count") { 
			$("#fb iframe").height(62);
			$("#fb iframe").width(97);
		}

		if((layout=="box_count") && (send=='true')) { 
			$("#fb iframe").height(92);
			$("#fb iframe").width(97);
		}

//		var tmp = "https://www.facebook.com/plugins/like.php?action="+verb+"&api_key=297700096922437&channel_url=https%3A%2F%2Fs-static.ak.fbcdn.net%2Fconnect%2Fxd_proxy.php%3Fversion%3D3%23cb%3Df285d94ab9bc%26origin%3Dhttp%253A%252F%252F"+domain+"%252Ff95784dbc08bf2%26relation%3Dparent.parent%26transport%3Dpostmessage&colorscheme="+color+"&extended_social_context=false&href=http%3A%2F%2Fsbuttons.ru&layout="+layout+"&locale="+locale+"&node_type=link&sdk=joey&send="+send+"&show_faces="+faces+"&width="+width+"";
		var tmp = "https://www.facebook.com/plugins/like.php?action="+verb+"&api_key=297700096922437&channel_url=https%3A%2F%2Fs-static.ak.fbcdn.net%2Fconnect%2Fxd_proxy.php%3Fversion%3D3%23cb%3Dfd5a4c170f915a%26origin%3Dhttp%253A%252F%252F"+domain1+"%252Ff27bf41b03f0c26%26relation%3Dparent.parent%26transport%3Dpostmessage&colorscheme="+color+"&extended_social_context=false&href=http%3A%2F%2F"+domain1+"&layout="+layout+"&locale="+locale+"&node_type=link&sdk=joey&send="+send+"&session_key=2.AQDz-7FZTm81ERIv.3600.1318442400.1-100000778853302&show_faces="+faces+"&width="+width+"";

		$("#fb iframe").attr("src", tmp);
	});

	$("#vkontakte_like_verb, #vkontakte_like_type").change(function(){
		var src = $("#vk iframe").attr('src');
		type = $('#vkontakte_like_type:checked').val();
		verb = $("#vkontakte_like_verb option:selected").val();
		$("#vk iframe").height(23); $("#vk iframe").width(350); $("#vk").height(23); $("#vk").width(350);
		if(type=="vertical") { $("#vk iframe").height(51); $("#vk iframe").width(41); $("#vk").height(51); $("#vk").width(41); }
		if(type=="mini") { $("#vk iframe").height(22); $("#vk iframe").width(100); $("#vk").height(22); $("#vk").width(100); }
		var tmp = "http://vkontakte.ru/widget_like.php?app=2640922&width=100%&page=0&url=http%3A%2F%2F"+domain+"&type="+type+"&verb="+verb+"&title=Social%20Share%20Buttons%20for%20Wordpress&description=&image=";

		$("#vk iframe").attr("src", tmp);

	});


	$('.note').corner();

	$('#msg').corner("3px");

	$('#sortable-list li').corner("5px");

});
</script>
<div>
	<div class="wrap">
		<div>
			<h2><?php _e('Share Buttons', $this->plugin_domain) ?> → <?php _e('Like Settings', $this->plugin_domain); ?></h2>
			<div class="donate"><a href=<?php if(get_locale()=='ru_RU') { ?>"http://sbuttons.ru/ru/donate-ru/"<?php } else { ?>"http://sbuttons.ru/en/donate-en/" <?php } ?> title="Donate"><img src="<?php echo $this->plugin_url;?>/images/other/donate.png" alt="Donation link" border="0"/ ></a></div>
			<div class="clear"></div>
				<fieldset class="fieldset_sort">
					<legend><?php _e('Sort Like Buttons', $this->plugin_domain) ?></legend>
					<div class="body_sort">
						<?php
							global $wpdb;
							$order = array();
							$j=0;
						?>
						<ul id="sortable-list">
						<?php
							$this->initKeyed();
							$like_btnsort = $this->like_btnsort;
							foreach($like_btnsort as $key=>$value) {
								$valsort[]=$value;
								$keysort[]=$key;
							}
							$style_like_social_name = array('Facebook', 'Mail.ru', 'Вконтакте');

							$duplic_valsort = $valsort;
							asort($duplic_valsort);
							$duplic_valsort=array_keys($duplic_valsort);
							for($i=0;$i<count($like_btnsort);$i++) {
								$namesort[$style_like_social_name[$i]]=$duplic_valsort[$i];
								asort($namesort);

							}
							$namesort=array_keys($namesort);
							for($j=0;$j<count($like_btnsort);$j++) {
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
								$query = "UPDATE $wpdb->options SET option_value = '$ids' WHERE option_name ='".$this->pluginPrefix."like_buttons_sort'";
								$result = mysql_query($query) or die(mysql_error().': '.$query);
								/* now what? */
								if($_POST['byajax']) { die(); } else { $message = 'Sortation has been saved.'; }
							}
						?>
					</div>
				</fieldset>

			<form method="post" action="options.php">
			<?php settings_fields($this->pluginPrefix . 'settings_like'); ?>

				<fieldset class="fieldset_enable">
					<legend><?php _e('Enable/Disable Share buttons', $this->plugin_domain);?></legend>
					<div class="body_enable">
						<?php
							$like_show=get_option($this->pluginPrefix . 'like_buttons_show');
							if(@array_key_exists(0, $like_show)==false) {
								$like_show = explode(',',$like_show);
							}
							$like_soc_name = array('Facebook', 'Mail.ru', 'Вконтакте');
						?>
						
							<label for="facebook_like_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>like_buttons_show[]" type="checkbox" id="like_buttons_show" value="facebook" <?php checked(TRUE, in_array('facebook',$like_show)); ?> />
								<?php _e('Facebook', $this->plugin_domain) ?>
							</label>
							<br/>
							<label for="mailru_like_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>like_buttons_show[]" type="checkbox" id="like_buttons_show" value="mailru" <?php checked(TRUE, in_array('mailru',$like_show)); ?> />
								<?php _e('Mail.ru', $this->plugin_domain) ?>
							</label>
							<br/>
							<label for="vkontakte_like_button_show">
								<input name="<?php echo $this->pluginPrefix; ?>like_buttons_show[]" type="checkbox" id="like_buttons_show" value="vkontakte" <?php checked(TRUE, in_array('vkontakte',$like_show)); ?> />
								<?php _e('Vkontakte', $this->plugin_domain) ?>
							</label>
							<br/>
					</div>
				</fieldset>
				<div class="clear"></div>

			<div class="button_submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes', $this->plugin_domain) ?>" />
			</div>

			<fieldset class="fieldset_social">
				<legend><?php _e('Mailru Like Button', $this->plugin_domain) ?></legend>
				<!-- Mail.ru Like Button -->
				<div class="body_social" id="odkl_like_button">
					<div id="mailru">
<?php
	$counter="";
	$text = "";
	if($this->mailru_like_counter_btn==1) { $counter="'counter' : 'true'"; } else {$counter="'nc' : '1'";}
	if($this->mailru_like_text_btn==1) { $text="'text' : 'true'"; } else {$text="'nt' : '1'";}
?>
						<a target="_blank" class="mrc__plugin_uber_like_button" href="http://connect.mail.ru/share?share_url=http%3A%2F%2Fsbuttons.ru" data-mrc-config="{'type' : '<?php echo $this->mailru_like_type; ?>', 'caption-mm' : '<?php echo $this->mail_like_verb;?>', 'caption-ok' : '<?php echo $this->odkl_like_verb; ?>', <?php echo $counter ?>, <?php echo $text; ?>, 'width' : '450', 'title' : 'Social Share Buttons for Wordpress'}">Нравится</a>
						<script src="http://cdn.connect.mail.ru/js/loader.js" type="text/javascript" charset="UTF-8"></script>

					</div>
					<br />
					<div class="GroupHead"><?php _e('Type Button', $this->plugin_domain) ?></div>
					<div class="GroupBody">
						<div style="float: left; width:25px;"><input type="radio" name="<?php echo $this->pluginPrefix; ?>mailru_like_type" id="mailru_like_type" value="button" <?php echo (get_option($this->pluginPrefix . 'mailru_like_type') == 'button' ? 'checked' : ''); ?> /></div>
						<div style="float: left;"><?php _e('Button', $this->plugin_domain);?></div>
						<div class="clear"></div>

						<div style="float: left; width:25px;"><input type="radio" name="<?php echo $this->pluginPrefix; ?>mailru_like_type" id="mailru_like_type" value="small" <?php echo (get_option($this->pluginPrefix . 'mailru_like_type') == 'small' ? 'checked' : ''); ?> /></div>
						<div style="float: left;"><?php _e('Icon',$this->plugin_domain);?></div>
						<div class="clear"></div>
					</div>
					<br />

					<div class="GroupHead"><?php _e('Verb to display for Mailru', $this->plugin_domain) ?></div>
					<div>
						<select name="<?php echo $this->pluginPrefix; ?>mail_like_verb" id="mail_like_verb" value="<?php echo $this->mail_like_verb; ?>">
							<option <?php if($this->mail_like_verb == 1) echo("selected=\"selected\""); ?> value="1"><?php _e('Like', $this->plugin_domain) ?></option>
							<option <?php if($this->mail_like_verb == 2) echo("selected=\"selected\""); ?> value="2"><?php _e('Share', $this->plugin_domain) ?></option>
							<option <?php if($this->mail_like_verb == 3) echo("selected=\"selected\""); ?> value="3"><?php _e('Recommend', $this->plugin_domain) ?></option>
						</select>
					</div>
					<br />

					<div class="GroupHead"><?php _e('Verb to display for Odnoklassniki', $this->plugin_domain) ?></div>
					<div class="GroupBody">
						<select name="<?php echo $this->pluginPrefix; ?>odkl_like_verb" id="odkl_like_verb" value="<?php echo $this->odkl_like_verb; ?>">
							<option <?php if($this->odkl_like_verb == 1) echo("selected=\"selected\""); ?> value="1"><?php _e('Klass', $this->plugin_domain) ?></option>
							<option <?php if($this->odkl_like_verb == 2) echo("selected=\"selected\""); ?> value="2"><?php _e('Share', $this->plugin_domain) ?></option>
							<option <?php if($this->odkl_like_verb == 3) echo("selected=\"selected\""); ?> value="3"><?php _e('Like', $this->plugin_domain) ?></option>
						</select>
					</div>
					<br />

					<div class="GroupHead"><?php _e('Display counter in buttons');?></div>
					<div class="GroupBody">
						<input name="<?php echo $this->pluginPrefix; ?>mailru_like_counter_btn" type="checkbox" id="mailru_like_counter_btn" value="1" <?php checked(TRUE, $this->mailru_like_counter_btn); ?> />
						<?php _e('Counter in buttons', $this->plugin_domain) ?>
					</div>    
					<br />

					<div class="GroupHead"><?php _e('Display text in buttons');?></div>
					<div class="GroupBody">
						<input name="<?php echo $this->pluginPrefix; ?>mailru_like_text_btn" type="checkbox" id="mailru_like_text_btn" value="1" <?php checked(TRUE, $this->mailru_like_text_btn); ?> />
						<?php _e('Text in buttons', $this->plugin_domain) ?>
					</div>    
					<br />

					<div class="GroupHead"><?php _e('Width container of Buttons', $this->plugin_domain); ?></div>
					<div class="GroupBody"><input type="text" name="<?php echo $this->pluginPrefix; ?>mailru_like_width" id="mailru_like_width" value="<?php echo esc_attr($this->mailru_like_width);?>" class="regular-text" /></div>
					<br />
				</div>

			</fieldset>
			<br />

			<fieldset class="fieldset_social">
				<legend><?php _e('Facebook Like Buttons', $this->plugin_domain) ?></legend>
				<!-- Facebook.com Like Button -->
				<div class="body_social" id="facebook_like_button">
					<?php if(empty($this->facebook_like_api)) { ?>
					<div id="msg"><?php _e('Please, register API ID for use Facebook button and live example, otherwise will not work', $this->plugin_domain); ?></div>
					<?php } ?>

				<?php
					$locale = get_locale();
					if ($this->facebook_like_send=='true') {$send='true';} else { $send='false'; }
					if ($this->facebook_like_faces=='true'){$face='true';} else { $faces='false'; }
				?>
					<div id="fb">
						<div id="fb-root"></div>
						<script>(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) {return;}
							js = d.createElement(s); js.id = id;
							js.src = "//connect.facebook.net/<?php echo $locale;?>/all.js#xfbml=1&appId=<?php echo $this->facebook_like_api;?>";
							fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>

						<div class="fb-like" data-send="<?php echo $send; ?>" data-layout="<?php echo $this->facebook_like_layout; ?>" data-width="<?php echo $this->facebook_like_width; ?>" data-show-faces="<?php echo $faces; ?>" data-action="<?php echo $this->facebook_like_verb;?>" data-colorscheme="<?php echo $this->facebook_like_color; ?>"></div>
					</div>
					<br />

					<div class="GroupHead"><?php _e('<b>Facebook API ID:</b>', $this->plugin_domain); ?>&nbsp;<span><?php _e('You can register your <b>"api_id"</b> on this <b><a href="https://developers.facebook.com/apps">link</a></b>',$this->plugin_domain);?></span></div>
					<div><input type="text" name="<?php echo $this->pluginPrefix; ?>facebook_like_api" value="<?php echo esc_attr($this->facebook_like_api);?>" class="regular-text" />&nbsp;<span style="color: red;"><?php _e('<b>Required Field</b>', $this->plugin_domain);?></span></div>
					<br />

					<div class="GroupHead"><?php _e('Layout style', $this->plugin_domain) ?></div>
					<div class="GroupBody">
						<select name="<?php echo $this->pluginPrefix; ?>facebook_like_layout" id="facebook_like_layout" value="<?php echo $this->facebook_like_layout; ?>">
							<option <?php if($this->facebook_like_layout == 'standart') echo("selected=\"selected\""); ?> value="standart"><?php _e('standart', $this->plugin_domain) ?></option>
							<option <?php if($this->facebook_like_layout == 'button_count') echo("selected=\"selected\""); ?> value="button_count"><?php _e('button_count', $this->plugin_domain) ?></option>
							<option <?php if($this->facebook_like_layout == 'box_count') echo("selected=\"selected\""); ?> value="box_count"><?php _e('box_count', $this->plugin_domain) ?></option>
						</select>
					</div>
					<br />

					<div class="GroupHead"><?php _e('Include a Send button');?></div>
					<div class="GroupBody">
						<input name="<?php echo $this->pluginPrefix; ?>facebook_like_send" type="checkbox" id="facebook_like_send" value="true" <?php echo (get_option($this->pluginPrefix . 'facebook_like_send') == 'true' ? 'checked' : ''); ?> />
						<?php _e('Send Button', $this->plugin_domain) ?>
					</div>
					<br />

					<div class="GroupHead"><?php _e('Show profile pictures below the button');?></div>
					<div class="GroupBody">
						<input name="<?php echo $this->pluginPrefix; ?>facebook_like_faces" type="checkbox" id="facebook_like_faces" value="true" <?php echo (get_option($this->pluginPrefix . 'facebook_like_faces') == 'true' ? 'checked' : ''); ?> />
						<?php _e('Show faces', $this->plugin_domain) ?>
					</div>    
					<br />

					<div class="GroupHead"><?php _e('Width container of Buttons', $this->plugin_domain); ?></div>
					<div class="GroupBody"><input type="text" name="<?php echo $this->pluginPrefix; ?>facebook_like_width" id="facebook_like_width" value="<?php echo esc_attr($this->facebook_like_width);?>" class="regular-text" /></div>
					<br />

					<div class="GroupHead"><?php _e('The verb to display in the button. Currently only Like and Recommend are supported', $this->plugin_domain) ?></div>
					<div class="GroupBody">
						<select name="<?php echo $this->pluginPrefix; ?>facebook_like_verb" id="facebook_like_verb" value="<?php echo $this->facebook_like_verb; ?>">
							<option <?php if($this->facebook_like_verb == 'like') echo("selected=\"selected\""); ?> value="like"><?php _e('like', $this->plugin_domain) ?></option>
							<option <?php if($this->facebook_like_verb == 'recommend') echo("selected=\"selected\""); ?> value="recommend"><?php _e('recommend', $this->plugin_domain) ?></option>
						</select>
					</div>
					<br />

					<div class="GroupHead"><?php _e('The color scheme of the plugin', $this->plugin_domain) ?></div>
					<div class="GroupBody">
						<select name="<?php echo $this->pluginPrefix; ?>facebook_like_color" id="facebook_like_color" value="<?php echo $this->facebook_like_color; ?>">
							<option <?php if($this->facebook_like_color == 'light') echo("selected=\"selected\""); ?> value="light"><?php _e('light', $this->plugin_domain) ?></option>
							<option <?php if($this->facebook_like_color == 'dark') echo("selected=\"selected\""); ?> value="dark"><?php _e('dark', $this->plugin_domain) ?></option>
						</select>
					</div>
					<br />
				</div>
			</fieldset>
			<br />

			<fieldset class="fieldset_social">
				<legend><?php _e('Vkontakte Like Buttons', $this->plugin_domain) ?></legend>
				<div class="body_social" id="vkontakte_like_sample_buttons">
					<?php if(empty($this->vkontakte_like_api)) { ?>
					<div id="msg"><?php _e('Please, register API ID for use Vkontakte button and live example, otherwise will not work', $this->plugin_domain); ?></div>
					<?php } ?>
					<div id="vk">
						<div id="vk_like"></div>
						<script type="text/javascript">
							VK.Widgets.Like("vk_like", {type: <?php echo '"'.$this->vkontakte_like_type.'"';?>, pageTitle: 'Social Share Buttons for Wordpress', pageUrl:'http://sbutonss.ru', verb: <?php echo $this->vkontakte_like_verb;?>});
						</script>
					</div>
					<br />

					<div class="GroupHead"><?php _e('<b>API ID:</b>', $this->plugin_domain); ?>&nbsp;<span><?php _e('You can register your <b>"api_id"</b> on this <b><a href="http://vkontakte.ru/apps.php?act=add&site=1">link</a></b>',$this->plugin_domain);?></span></div>
					<div><input type="text" name="<?php echo $this->pluginPrefix; ?>vkontakte_like_api" value="<?php echo esc_attr($this->vkontakte_like_api);?>" class="regular-text" />&nbsp;<span style="color: red;"><?php _e('<b>Required Field</b>', $this->plugin_domain);?></span></div>
					<br />

					<div class="GroupHead"><?php _e('Button type', $this->plugin_domain); ?></div>
					<div class="GroupBody">
						<div style="float: left; width:25px;"><input type="radio" name="<?php echo $this->pluginPrefix; ?>vkontakte_like_type" id="vkontakte_like_type" value="full" <?php echo (get_option($this->pluginPrefix . 'vkontakte_like_type') == 'full' ? 'checked' : ''); ?> /></div>
						<div style="float: left;"><?php _e('Button with textable counter', $this->plugin_domain);?></div>
						<div class="clear"></div>

						<div style="float: left; width:25px;"><input type="radio" name="<?php echo $this->pluginPrefix; ?>vkontakte_like_type" id="vkontakte_like_type" value="button" <?php echo (get_option($this->pluginPrefix . 'vkontakte_like_type') == 'button' ? 'checked' : ''); ?> /></div>
						<div style="float: left;"><?php _e('Button with mini counter',$this->plugin_domain);?></div>
						<div class="clear"></div>

						<div style="float: left; width:25px;"><input type="radio" name="<?php echo $this->pluginPrefix; ?>vkontakte_like_type" id="vkontakte_like_type" value="mini" <?php echo (get_option($this->pluginPrefix . 'vkontakte_like_type') == 'mini' ? 'checked' : ''); ?> /></div>
						<div style="float: left;"><?php _e('Mini button',$this->plugin_domain);?></div>
						<div class="clear"></div>

						<div style="float: left; width:25px;"><input type="radio" name="<?php echo $this->pluginPrefix; ?>vkontakte_like_type" id="vkontakte_like_type" value="vertical" <?php echo (get_option($this->pluginPrefix . 'vkontakte_like_type') == 'vertical' ? 'checked' : ''); ?> /></div>
						<div style="float: left;"><?php _e('Mini button and counter bottom',$this->plugin_domain);?></div>
						<div class="clear"></div>
					</div>
					<br />

					<div class="GroupHead"><?php _e('Verb to display', $this->plugin_domain) ?></div>
					<div class="GroupBody">
						<select name="<?php echo $this->pluginPrefix; ?>vkontakte_like_verb" id="vkontakte_like_verb" value="<?php echo $this->vkontakte_like_verb; ?>">
							<option <?php if($this->vkontakte_like_verb == '0') echo("selected=\"selected\""); ?> value="0"><?php _e('Like', $this->plugin_domain) ?></option>
							<option <?php if($this->vkontakte_like_verb == '1') echo("selected=\"selected\""); ?> value="1"><?php _e('Interesting', $this->plugin_domain) ?></option>
						</select>
					</div>
				</div>
			</fieldset>

			<div class="button_submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes', $this->plugin_domain) ?>" />
			</div>
			</form>
		</div>
	</div>
</div>