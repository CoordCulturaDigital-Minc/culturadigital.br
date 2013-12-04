<?php
/*
Plugin Name: cultTube
Plugin URI: http://culturadigital.br/
Description: cultTube provides a video player based on flowplayer or html5 if supported, to display your videos on wordpress.
Version: 1
Author: Marcos Lopes
Author URI: http://marcoslop.es/
*/

/** Setup the plugin **
_____________________*/
	
add_action('activate_cultTube/cultTube.php', 'activate');
add_filter('media_buttons_context', 'media_buttons_context');
add_action('media_upload_culttube-upload', 'media_upload_culttube');
	
// init the plugin
function activate() {
	// init goes here
}

/** End setup **
______________*/
	
	
	/** Admin functions **
	____________________*/
	
	function media_buttons_context($context) {
		global $post_ID, $temp_ID;
		//$dir = dirname(__FILE__);

		$image_btn = get_option('siteurl').'/wp-content/plugins/cultTube/img/cultTube.png';
		$image_title = 'Cult Tube';

		$uploading_iframe_ID = (int) ($post_ID == 0 ? $temp_ID : $post_ID);

		$media_upload_iframe_src = "media-upload.php?post_id=$uploading_iframe_ID";
		$out = ' <a href="'.$media_upload_iframe_src.'&tab=culttube-upload&TB_iframe=true&height=500&width=640" class="thickbox" title="'.$image_title.'"><img src="'.$image_btn.'" alt="'.$image_title.'" /></a>';
		return $context.$out;
	}

	function media_upload_culttube() {		
		cultTube_iframe('upload_tab_new');
	}
	
	function cultTube_iframe( $path ) {

		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
		<head>
			<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
			<title><?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e('WordPress'); ?></title>
			<link rel="stylesheet" href="<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/css/default.css' ?>" />
			<script type="text/javascript" src="<?php echo get_bloginfo('url').'/wp-includes/js/jquery/jquery.js?ver=1.4.2' ?>"></script>
			<script type="text/javascript" src="<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/js/swfupload.js' ?>"></script>
			<script type="text/javascript" src="<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/js/swfupload.queue.js' ?>"></script>
			<script type="text/javascript" src="<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/js/fileprogress.js' ?>"></script>
			<script type="text/javascript" src="<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/js/handlers.js' ?>"></script>
			<script type="text/javascript" src="<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/js/ajaxupload.3.6.js' ?>"></script>
			<script type="text/javascript">
				//<![CDATA[
				addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
				var userSettings = {'url':'<?php echo SITECOOKIEPATH; ?>','uid':'<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>','time':'<?php echo time(); ?>'};
				var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>', pagenow = 'media-upload-popup', adminpage = 'media-upload-popup';
				//]]>
			</script>
			<script type="text/javascript">
				var upload1;
			
				jQuery(function() {					
					upload1 = new SWFUpload({
						// Backend Settings
						upload_url: "<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/'; ?>upload.php",
						post_params: {"post_id" : "<?php echo $_REQUEST['post_id']; ?>"},

						// File Upload Settings
						file_size_limit : "1147483647",	// 1TB
						file_types : "*.mp4; *.ogg; *.webm",
						file_types_description : "Video Files",
						file_upload_limit : 3,
						file_queue_limit : 0,

						// Event Handler Settings (all my handlers are in the Handler.js file)
						swfupload_preload_handler : preLoad,
						swfupload_load_failed_handler : loadFailed,
						file_dialog_start_handler : fileDialogStart,
						file_queued_handler : fileQueued,
						file_queue_error_handler : fileQueueError,
						file_dialog_complete_handler : fileDialogComplete,
						upload_start_handler : uploadStart,
						upload_progress_handler : uploadProgress,
						upload_error_handler : uploadError,
						upload_success_handler : uploadSuccess,
						upload_complete_handler : uploadComplete,

						// Button Settings
						button_image_url : "<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/'; ?>img/XPButtonUploadText_61x22.png",
						button_placeholder_id : "spanButtonPlaceholder1",
						button_width: 61,
						button_height: 22,

						// Flash Settings
						flash_url : "<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/'; ?>swf/swfupload.swf",
						flash9_url : "<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/'; ?>swf/swfupload_fp9.swf",


						custom_settings : {
							progressTarget : "fsUploadProgress1",
							cancelButtonId : "btnCancel1"
						},

						// Debug Settings
						debug: false
					});
                    
                    new AjaxUpload('upload_button', {
                      // Location of the server-side upload script
                      // NOTE: You are not allowed to upload files to another domain
                      action: '<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/'; ?>upload.php',
                      // File upload name
                      name: 'Filedata',
                      // Additional data to send
                      data: {
                        post_id : "<?php echo $_REQUEST['post_id']; ?>"
                      },
                      // Submit file after selection
                      autoSubmit: true,
                      // The type of data that you're expecting back from the server.
                      // HTML (text) and XML are detected automatically.
                      // Useful when you are using JSON data as a response, set to "json" in that case.
                      // Also set server response type to text/html, otherwise it will not work in IE6
                      responseType: false,
                      // Fired after the file is selected
                      // Useful when autoSubmit is disabled
                      // You can return false to cancel upload
                      // @param file basename of uploaded file
                      // @param extension of that file
                      onChange: function(file, extension){},
                      // Fired before the file is uploaded
                      // You can return false to cancel upload
                      // @param file basename of uploaded file
                      // @param extension of that file
                      onSubmit: function(file, extension) {},
                      // Fired when file upload is completed
                      // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
                      // @param file basename of uploaded file
                      // @param response server response
                      onComplete: function(file, response) {
                        alert('Sucesso! url do arquivo: '+response);
                        
                            if( response.indexOf('.mp4') != -1 ) {
                                document.getElementById('mp4').value = response;
                            }
                            if( response.indexOf('.ogg') != -1 ) {
                                document.getElementById('ogg').value = response;
                            }
                            if( response.indexOf('.webm') != -1 ) {
                                document.getElementById('webm').value = response;
                            }
                      }
                    });
			     });
			</script>
		</head>
		<body>
			<div id="header">
				<h1>CultTube</h1>
			</div>
			<?php call_user_func($path); ?>
		</body>
		</html>		
		<?php
	}
	
	function upload_tab_new() {
		include_once( ABSPATH.'wp-content/plugins/cultTube/html/form.php' );
	}
	
	function ctube_shortcode($atts) {
		extract($atts);
		
		if(empty($mp4) && empty($webm) && empty($ogg))
			return;
			
		$url = get_bloginfo('url').'/wp-content/plugins/cultTube/html/videoplayer.php?mp4='.$mp4.'&webm='.$webm.'&ogg='.$ogg;
		
		$width = get_option('cultTube_player_width');
		$width = empty($width) ? 510 : $width + 10;
		$height = get_option('cultTube_player_height');
		$height = empty($height) ? 310 : $height + 10;

		$out  = '<iframe id="videoPlayer" frameborder="0" width="'.$width.'" height="'.$height.'" rel="'.$width.'x'.$height.'" style="overflow:hidden" src="'.$url.'" hspace="0"></iframe>';
		
		return $out;
	}
	
	add_shortcode('ctube', 'ctube_shortcode');
	
	function add_some_mimes($mimes) {
		$ourMimes = array(
			"webm" => "video/webm",
			"mp4" => "video/mp4",
			"ogg" => "video/ogg",
			"odf" => "application/vnd.oasis.opendocument.formula",
			"odt" => "application/vnd.oasis.opendocument.text",
			"ods" => "application/vnd.oasis.opendocument.spreadsheet",
			"xls" => "application/vnd.ms-excel",
			"doc" => "application/msword"
		);
		
		return array_merge($mimes, $ourMimes);
	}
	add_filter("upload_mimes","add_some_mimes");