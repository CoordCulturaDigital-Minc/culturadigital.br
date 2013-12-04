<?php include '../../../../wp-config.php' ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>HTML5 Video Player</title>

  <!-- Include the jQuery Framework -->
  <script type="text/javascript" src="<?php bloginfo('url') ?>/wp-includes/js/jquery/jquery.js"></script>

  <!-- Include the VideoJS Library -->
  <script src="<?php bloginfo('url'); ?>/wp-content/plugins/cultTube/js/video.js" type="text/javascript" charset="utf-8"></script>

  <script type="text/javascript" charset="utf-8">
    jQuery(function(){
       VideoJS.setup();
    })
  </script>

  <link rel="stylesheet" href="<?php bloginfo('url'); ?>/wp-content/plugins/cultTube/css/videojs.css" type="text/css" media="screen" title="Video JS" charset="utf-8">
</head>

<body id="body">

  <!-- Begin VideoJS -->
  <?php
  $mp4 = $_REQUEST['mp4'];
  $webm = $_REQUEST['webm'];
  $ogg = $_REQUEST['ogg'];
  $thumbnail = $_REQUEST['thumbnail'];
  $width = get_option('cultTube_player_width');
  $width = empty($width) ? 500 : $width;
  $height = get_option('cultTube_player_height');
  $height = empty($height) ? 300 : $height;
  ?>
  <div class="video-js-box">

    <!-- Using the Video for Everybody Embed Code http://camendesign.com/code/video_for_everybody -->
      <video class="video-js" width="<?php echo $width ?>" height="<?php echo $height ?>" <?php if( !empty($thumbnail) ) { ?>poster="<?php echo $thumbnail; ?>"<?php } ?> controls>
      <?php if( !empty($webm) ) { ?>
      <source src="<?php echo $webm; ?>" type='video/webm; codecs="vp8, vorbis"'>
      <?php } if( !empty($ogg) ) { ?>
      <source src="<?php echo $ogg; ?>" type='video/ogg; codecs="theora, vorbis"'>
      <?php } if( !empty($mp4) ) { ?>
      <source src="<?php echo $mp4; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
      <!-- Flash Fallback. Use any flash video player here. Make sure to keep the vjs-flash-fallback class. -->
      <object class="vjs-flash-fallback" width="500" height="300" type="application/x-shockwave-flash"
        data="<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/swf/flowplayer-3.2.1.swf'; ?>">
        <param name="movie" value="<?php echo get_bloginfo('url').'/wp-content/plugins/cultTube/swf/flowplayer-3.2.1.swf'; ?>" />
        <param name="allowfullscreen" value="true" />
        <param name="flashvars" value='config={"clip":{"url":"<?php echo $mp4 ?>","autoPlay":false,"autoBuffering":true}}' />
	<?php if( !empty($thumbnail) ) : ?>
        <!-- Image Fallback -->
        <img src="<?php echo $thumbnail ?>" width="500" height="300" alt="Poster Image"
	  title="No video playback capabilities." />
	<?php endif; ?>
      </object>
      <?php } ?>
    </video>
    <!-- Download links provided for devices that can't play video in the browser. -->
    <p class="vjs-no-video"><strong>Download Video:</strong>
      <a href="<?php echo $mp4 ?>">MP4</a>,
      <a href="<?php echo $webm ?>">WebM</a>,
      <a href="<?php echo $ogg ?>">Ogg</a><br>
      <!-- Support VideoJS by keeping this link. -->
      <a href="http://videojs.com">HTML5 Video Player</a> by <a href="http://videojs.com">VideoJS</a>
    </p>
  </div>
  <!-- End VideoJS -->

</body>
</html>