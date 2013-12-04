<?php
ob_start();
require_once(TEMPLATEPATH.'/php/simplepie.inc');
require_once(TEMPLATEPATH.'/php/newsblocks.inc.php');
//header('Content-type:text/html; charset=utf-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--[if lt IE 7]>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/pngfix.js"></script>
<![endif]-->
<?php wp_head(); ?>
</head>
<body>
<div id="header">
  <div id="header-in" class="clearfix">
    
     <div class="logo"> <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"> AGGREGATOR</a></div>
    
    
    <p class="description"><?php bloginfo('description'); ?></p>
  </div>
  <!-- header in #end -->
</div>
<!-- header #end -->



<div id="navbg">
  <div id="nav-menu" class="clearfix">
    <ul id="nav" >
      	  <?php wp_list_pages('title_li=&depth=0&exclude=' . get_inc_pages("pag_exclude_") .'&sort_column=menu_order'); ?>
          <?php wp_list_categories ('title_li=&depth=0&include=' . get_option('ptthemes_categories_id') . '&sort_column=menu_order'); ?>
    </ul>
  </div>
  <!-- navigation #end -->
</div>
<!-- navbg #end -->