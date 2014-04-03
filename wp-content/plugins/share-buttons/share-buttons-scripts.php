<?php
class ButtonsScripts {

	var $social_name = array('facebook','googlebuzz','googleplus','livejournal','mailru','odnoklassniki','twitter','vkontakte','yandex');
	var $like_social_name = array('facebook', 'mailru', 'vkontakte');
	var $description;
	var $btnsort=array();
	var $btnshow=array();
	var $like_btnsort=array();
	var $like_btnshow=array();


	function getKeyedArray($string, $delimiter = ',', $kv = ':') {
		if ($a = explode($delimiter, $string)) { // create parts
			foreach ($a as $s) { // each part
				if ($s) {
					if ($pos = strpos($s, $kv)) { // key/value delimiter
						$ka[trim(substr($s, 0, $pos))] = trim(substr($s, $pos + strlen($kv)));
					} else { // key delimiter not found
						$ka[] = trim($s);
					}
				}
			}
			return $ka;
		}
	}

	function initKeyed() {
		if(@array_key_exists(0, $this->buttons_sort)==false) {
			$this->btnsort = explode(',', $this->buttons_sort);
		} else {
			$this->btnsort = $this->buttons_sort;
		}

		if(@array_key_exists(0, $this->like_buttons_sort)==false) {
			$this->like_btnsort = explode(',', $this->like_buttons_sort);
		} else {
			$this->like_btnsort = $this->like_buttons_sort;
		}


	}

	function add_head() {
		$this->initKeyed();
		if (!is_admin()) {
			echo '<link rel="stylesheet" href="'.$this->plugin_url.'css/share-buttons-user.css" type="text/css" />';
			echo "\r\n";               
			global $post;

			$thumb = get_post_meta($post->ID, 'Thumbnail', $single = true);
			$your_logo = $this->plugin_url.'upload/uploads/logo.png';
			$absolute_path_logo = dirname(__FILE__).'/upload/uploads/logo.png';

			if(file_exists($absolute_path_logo)) {
				/* if your themes support $thumbs */
				if($thumb!='') {
					echo '<link rel="image_src" href="'.$thumb.'" />';
					echo "\r\n";
				/* else get image that uploaded */
				} else if($thumb=='' && file_exists($absolute_path_logo)) {
					echo '<link rel="image_src" href="'.$your_logo.'" />';
					echo "\r\n";
				}
			}

			if($this->generate_meta==true) {
				if(function_exists('mb_strlen') && function_exists('mb_substr')) {
					$descr = esc_js(mb_substr(strip_shortcodes(strip_tags($post->post_content)), 0, 250, 'UTF-8'));
					if (mb_strlen($post->post_content, 'UTF-8') > 250 && $descr != '') {
						$descr .= '...';
					}
				} else {

					$temp = substr(strip_shortcodes(strip_tags($post->post_content)), 0, 250);
					// Sometimes substr() returns substring with strange symbol in the end which crashes esc_js()
					while (esc_js($temp) == '' && $temp != '')
						$temp = substr($temp, 0, strlen($temp)-1);

					$descr = esc_js($temp);
					if (strlen($post->post_content) > 250 && $descr != '') {
						$descr .= '...';
					}
				}

				$title = esc_js($post->post_title);

				$descr = str_replace('\n',".",$descr);
				$descr = str_replace('\n\n',".",$descr);
				$descr = str_replace('\n\r',".",$descr);
				$descr = strip_tags($descr);

				echo '<meta name="description" content="'.$descr.'" />';
				echo "\r\n";

				if(in_array('facebook',$this->btnsort)) {
					echo '<html xmlns:fb="http://ogp.me/ns/fb#">';
					echo '<meta property="og:title" content="'.$title.'" /> ';
					echo "\r\n";
					echo '<meta property="og:description" content="'.$descr.'" />';
					echo "\r\n";
					if($thumb!='') {
						echo '<meta property="og:image" content="'.$thumb.'" />';
						echo "\r\n";
					} else if($thumb=='' && file_exists($absolute_path_logo)) {
						echo '<meta property="og:image" content="'.$your_logo.'" />';
						echo "\r\n";
					}
				}

			}

			wp_enqueue_script('new_window_api_script', $this->plugin_url.'js/share-buttons.js');

			if(in_array('googleplus',$this->btnsort)) {
				wp_enqueue_script('googleplus_script', 'https://apis.google.com/js/plusone.js');
			}

			//Vkontakte.ru
			if(in_array('vkontakte',$this->btnsort)) {
				wp_enqueue_script('vk_share_button_api_script', 'http://vkontakte.ru/js/api/share.js?11');
			}

			if(in_array('vkontakte',$this->like_btnsort)) {
				wp_enqueue_script('vk_like_button_api_script', 'http://userapi.com/js/api/openapi.js?34');
			}

			//Odnoklassniki.ru
			if(in_array('odnoklassniki',$this->btnsort)) {
				echo '<link href="http://stg.odnoklassniki.ru/share/odkl_share.css" rel="stylesheet">';
				echo "\r\n";
				wp_enqueue_script('odkl_share_button_api_script', 'http://stg.odnoklassniki.ru/share/odkl_share.js');
				wp_enqueue_script('odkl_init_api_script', $this->plugin_url.'js/odkl_init.js');
			}

			//Mail.ru
			if(in_array('mailru',$this->btnsort)) {
				wp_enqueue_script('mailru_share_button_api_script', 'http://cdn.connect.mail.ru/js/share/2/share.js');
			}

			//Facebook.com
			if(in_array('facebook',$this->btnsort)) {
//				wp_enqueue_script('facebook_share_button_api_script', 'http://connect.facebook.net/en_US/all.js');
			}

			//Twitter.com
			if(in_array('twitter',$this->btnsort)) {
				wp_enqueue_script('twitter_button_api_script', 'http://platform.twitter.com/widgets.js');
			}

			if(in_array('googlebuzz',$this->btnsort)) {
				wp_enqueue_script('google_button_api_script', 'http://www.google.com/buzz/api/button.js');
			}
		}
	}

//Including all social share buttons
	function the_button() {

		$mofile = dirname(__FILE__) . '/lang/' . $this->plugin_domain . '-' . get_locale() . '.mo';
		
		load_textdomain($this->plugin_domain, $mofile);
		$button_code = "<!--Start Share Buttons http://sbuttons.ru -->\r\n";

		include('share/share_customize_type.php');

		$button_code .= "<!--End Share Buttons http://sbuttons.ru -->\r\n";

		return $button_code;
	}

	function the_like_button() {
		$mofile = dirname(__FILE__) . '/lang/' . $this->plugin_domain . '-' . get_locale() . '.mo';
		load_textdomain($this->plugin_domain, $mofile);
		include('like/like_customize_type.php');
		return $button_code;
	}
}
?>