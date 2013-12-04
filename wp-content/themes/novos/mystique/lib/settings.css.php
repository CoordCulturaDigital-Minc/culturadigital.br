<?php /* Mystique/digitalnature */

   $mystique_options = get_option('mystique');
   $font_styles = font_styles();
   header('Content-type: text/css');
   header("Expires: Mon, 25 Dec 1989 02:00:00 GMT");
   header("Cache-Control: no-cache");
   header("Pragma: no-cache");

   echo '@import "'.THEME_URL.'/color-'.$mystique_options['color_scheme'].'.css";'.PHP_EOL;
   do_action('mystique_css');

   // font styles
   if($mystique_options['font_style'] != 0)
    echo '*{font-family:'.$font_styles[$mystique_options['font_style']]['code'].';}'.PHP_EOL;

   // column dimensions
   $w = $mystique_options['page_width'];
   $unit = ($w == 'fluid') ? '%' : 'px';
   $gs = ($w == 'fluid') ? '100' : '940';
   switch ($mystique_options['layout']):
    case 'col-2-left':
      $s = explode(";", $mystique_options['dimensions'][$w]['col-2-left']);
      echo 'body.'.$w.'.col-2-left #primary-content{width:'.($gs-$s[0]).$unit.';left:'.($s[0]).$unit.';}'.PHP_EOL;
      echo 'body.'.$w.'.col-2-left #sidebar{width:'.$s[0].$unit.';left:'.(-($gs-$s[0])).$unit.';}'.PHP_EOL;
      break;
    case 'col-2-right':
      $s = explode(";", $mystique_options['dimensions'][$w]['col-2-right']);
      echo 'body.'.$w.'.col-2-right #primary-content{width:'.($gs-($gs-$s[0])).$unit.';left:0;}'.PHP_EOL;
      echo 'body.'.$w.'.col-2-right #sidebar{width:'.($gs-$s[0]).$unit.';}'.PHP_EOL;
      break;
    case 'col-3':
      $s = explode(";", $mystique_options['dimensions'][$w]['col-3']);
      echo 'body.'.$w.'.col-3 #primary-content{width:'.($gs-$s[0]-($gs-$s[1])).$unit.';left:'.$s[0].$unit.';}'.PHP_EOL;
      echo 'body.'.$w.'.col-3 #sidebar{width:'.($gs-$s[1]).$unit.';}'.PHP_EOL;
      echo 'body.'.$w.'.col-3 #sidebar2{width:'.$s[0].$unit.';left:'.(-($gs-$s[0]-($gs-$s[1]))).$unit.';}'.PHP_EOL;
      break;
    case 'col-3-left':
      $s = explode(";", $mystique_options['dimensions'][$w]['col-3-left']);
      echo 'body.'.$w.'.col-3-left #primary-content{width:'.($gs-$s[1]).$unit.';left:'.$s[1].$unit.';}'.PHP_EOL;
      echo 'body.'.$w.'.col-3-left #sidebar{width:'.$s[0].$unit.';left:'.(-($gs-$s[0])).$unit.';}'.PHP_EOL;
      echo 'body.'.$w.'.col-3-left #sidebar2{width:'.($s[1]-$s[0]).$unit.';left:'.(-($gs-$s[1])+$s[0]).$unit.';}'.PHP_EOL;
      break;
    case 'col-3-right':
      $s = explode(";", $mystique_options['dimensions'][$w]['col-3-right']);
      echo 'body.'.$w.'.col-3-right #primary-content{width:'.$s[0].$unit.';}'.PHP_EOL;
      echo 'body.'.$w.'.col-3-right #sidebar{width:'.($gs-$s[1]).$unit.';}'.PHP_EOL;
      echo 'body.'.$w.'.col-3-right #sidebar2{width:'.($s[1]-$s[0]).$unit.';}'.PHP_EOL;
      break;
   endswitch;

   if($mystique_options['background']) echo '#page{background-image:none;}'.PHP_EOL.'body{background-image:url("'.$mystique_options['background'].'");background-repeat:no-repeat;background-position:center top;}'.PHP_EOL;
   if(($mystique_options['background_color']) && (strpos($mystique_options['background_color'],'000000') === false)):
    echo 'body{background-color:#'.$mystique_options['background_color'].';}'.PHP_EOL;
    if (!$mystique_options['background']) echo 'body,#page{background-image:none;}'.PHP_EOL;
   endif;

   $thumb_size = explode('x',get_mystique_option('post_thumb'));
   echo '.post-info.with-thumbs{margin-left:'.($thumb_size[0]+30).'px;}'.PHP_EOL;

   if($mystique_options['user_css']) echo $mystique_options['user_css'].PHP_EOL;
   if (is_single() || is_page()):
     global $post;
     $css = get_post_meta($post->ID, 'css', true);
     if (!empty($css)) echo $css.PHP_EOL;
   endif;
?>