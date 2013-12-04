<?php
$load = '../../../../wp-config.php';
if (file_exists($load)){  //if it's >WP-2.6
  require_once($load);
  }
  else {
 wp_die('Error: Config file not found');
 }

   $destination_path = ABSPATH . "wp-content/themes/".get_option( 'template' )."/attachments/";
   if (!file_exists($destination_path)){
	   $upload_path = ABSPATH . "wp-content/themes/".get_option( 'template' )."/attachments/";
	  if (!file_exists($upload_path)){
		mkdir($upload_path, 0777);
	  }
    }
   $result = 0;
   $name = $_FILES['myfile']['name'];
   $name = strtolower($name);
   	$target_path = $destination_path . $name;
	//$user_path = get_option( 'siteurl' ) ."/wp-content/uploads/".$imagepath."/".$name;
	$user_path = $name;
   if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
      $result = 1;
   }
   sleep(1);
   $imgNumb = "image".$_GET['img'];
?>
<script language="javascript" type="text/javascript">window.parent.window.noUpload(<?php echo $result.", '".$user_path."', '".$_GET['img']."'"; ?>);</script>