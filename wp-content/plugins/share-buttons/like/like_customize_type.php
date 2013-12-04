<?php
	global $post;
	$url = get_permalink($post->ID);
	$title = $post->post_title;
	$facebook_like='';
	$vkontakte_like='';
	$mailru_like='';
	$locale = get_locale();

		$type_vk = get_option($this->pluginPrefix . 'vkontakte_like_type');
		$verb_vk = get_option($this->pluginPrefix . 'vkontakte_like_verb');
		$api_id_vk = get_option($this->pluginPrefix . 'vkontakte_like_api');

		$vkontakte_like .= "<div class=\"vkontakte_like\">\r\n";
		$vkontakte_like .= "<div id=\"vk_like_".$post->ID."\" style=\"margin-bottom:5px;\"></div>\r\n";

		$vkontakte_like .= '<script type="text/javascript">';
		$vkontakte_like .= "\r\n";
/*		$vkontakte_like .= "window.onload = function() {";
		$vkontakte_like .= "\r\n";*/
		$vkontakte_like .= "  VK.init({apiId: $api_id_vk, onlyWidgets: true});";
		$vkontakte_like .= "\r\n";    

		$vkontakte_like .= "  VK.Widgets.Like('vk_like_".$post->ID."', {type: '$type_vk', pageTitle: '$title', pageUrl:'$url', verb: '$verb_vk'}, ".$post->ID.");";
		$vkontakte_like .= "\r\n";
/*		$vkontakte_like .= '}';
		$vkontakte_like .= "\r\n";*/
		$vkontakte_like .= '</script>';
		$vkontakte_like .= "\r\n";
		$vkontakte_like .= "</div>\r\n\r\n";


		$layout_fb = $this->facebook_like_layout;
		if($this->facebook_like_faces=='true') { $faces_fb='true'; } else { $faces_fb='false'; }
		if($this->facebook_like_send=='true') { $send_fb='true'; } else { $send_fb='false'; }

		$width_fb  = $this->facebook_like_width;
		$height_fb = $this->facebook_like_height;
		$color_fb  = $this->facebook_like_color;
		$faces_fb  = $this->facebook_like_faces;

    

		$facebook_like .= "<div class=\"facebook_like\">\r\n";
		$facebook_like .= "<div id=\"fb-root\"></div>\r\n";
		$facebook_like .= "<script>(function(d, s, id) { 
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = \"//connect.facebook.net/".$locale."/all.js#xfbml=1&appId=".$this->facebook_like_api."\";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>\r\n";

		$facebook_like .= '<div class="fb-like" data-href="'.$url.'" data-send="'.$send_fb.'" data-layout="'.$layout_fb.'" data-width="'.$width_fb.'" data-show-faces="'.$faces_fb.'" data-action="'.$verb_fb.'" data-colorscheme="'.$color_fb.'"></div>';
		$facebook_like .= "\r\n</div>\r\n\r\n";


		$mailru_type = $this->mailru_like_type;
		$mail_verb = $this->mail_like_verb;
		$odkl_verb = $this->odkl_like_verb;
		$mailru_width = $this->mailru_like_width;
		$counter="";
		$text = "";
		if($this->mailru_like_counter_btn==1) { $counter="'counter' : 'true'"; } else {$counter="'nc' : '1'";}
		if($this->mailru_like_text_btn==1) { $text="'text' : 'true'"; } else {$text="'nt' : '1'";}

		$mailru_like .= "<div class=\"mailru_like\">";
		$mailru_like .= "<a target=\"_blank\" class=\"mrc__plugin_uber_like_button\" href=\"http://connect.mail.ru/share?share_url=".$url."\" data-mrc-config=\"{'type' : '".$mailru_type."', 'caption-mm' : '".$mail_verb."', 'caption-ok' : '".$odkl_verb."', ".$counter.", ".$text.", 'width' : '".$mailru_width."'}\">РќСЂР°РІРёС‚СЃСЏ</a>\r\n";

		$mailru_like .= "<script src=\"http://cdn.connect.mail.ru/js/loader.js\" type=\"text/javascript\" charset=\"UTF-8\"></script>\r\n";
		$mailru_like .= "</div>\r\n\r\n";

	$array_buttons = array();

	$temp = array();

	$array_buttons = array('facebook'=>$facebook_like, 'mailru'=>$mailru_like, 'vkontakte'=>$vkontakte_like);

	$like_btnsort=0;
	$like_btnsort = get_option($this->pluginPrefix . 'like_buttons_sort');
	$like_btnsort = explode(',',$like_btnsort);

	$like_show = get_option($this->pluginPrefix . 'like_buttons_show');

	if(!empty($like_show)) {
		if(@array_key_exists(0, $like_show)==false) {
			$like_show = explode(',',$like_show);
		}

		asort($like_btnsort);

		$like_btnsort=array_flip($like_btnsort);

		for($i=0;$i<count($this->like_social_name);$i++) {

			if(!in_array($this->like_social_name[$i],$like_show)) {
				unset($array_buttons[$this->like_social_name[$i]]);
				unset($like_btnsort[$this->like_social_name[$i]]);
			}
		}
		$c=array_combine($like_btnsort,$array_buttons);
		ksort($c);

		$button_code.=implode('',$c);
	}



?>