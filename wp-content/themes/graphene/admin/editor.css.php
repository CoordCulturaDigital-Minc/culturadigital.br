<?php 
	$content_width = 640;
	if ( isset( $_GET['content_width'] ) && $_GET['content_width'] ) $content_width = $_GET['content_width']; 
?>
.wp-editor {
    width: <?php echo $content_width; ?>px;
}