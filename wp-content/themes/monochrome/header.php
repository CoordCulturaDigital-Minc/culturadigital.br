<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?php wp_title(''); if (function_exists('is_tag') and is_tag()) { ?><?php } if (is_archive()) { ?><?php } elseif (is_search()) { ?><?php echo $s; } if ( !(is_404()) and (is_search()) or (is_single()) or (is_page()) or (function_exists('is_tag') and is_tag()) or (is_archive()) ) { ?><?php _e(' | '); ?><?php } ?><?php bloginfo('name'); ?></title>
<meta name="description" content="<?php if (is_home()): echo bloginfo('description'); else: echo the_title(); endif; ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" /> 
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/comment-style.css" type="text/css" />
<?php if (strtoupper(get_locale()) == 'JA') ://to fix the font-size for japanese font ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/japanese.css" type="text/css" />
<?php endif; ?>
<!--[if lt IE 7]>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie6.css" type="text/css" />
<![endif]--> 
<?php $options = get_option('mc_options'); if($options['site_width'] == '930') { ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/width_930px.css" type="text/css" />
<?php } elseif($options['site_width'] == '1100') { ; ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/width_1100px.css" type="text/css" />
<?php }; ?>
<?php if($options['image_style']) { ?>
<style type="text/css">
.post img, .post a img { border:1px solid #ccc; padding:5px; margin:0 10px 0 0;  background:#f2f2f2; }
.post a:hover img { border:1px solid #38a1e5; background:#9cd1e1; }
.post img.wp-smiley { border:0px; padding:0px; margin:0px; background:none; }
</style>
<?php }; ?>

<?php wp_enqueue_script( 'jquery' ); ?>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?> 
<?php wp_head(); ?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/scroll.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jscript.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/comment.js"></script>
</head>

<body>
<div id="wrapper">

 <div id="header">

  <div id="header_top">

   <div id="logo">
   <?php if ($options['use_logo']): ?>
    <h1><a href="<?php echo get_option('home'); ?>/" id="logo_image"><img src="<?php bloginfo('template_url'); ?>/img/<?php echo $options['logo_name']; ?>" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>" /></a></h1>
   <?php else: ?>
    <a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a>
    <h1><?php bloginfo('description'); ?></h1>
   <?php endif; ?>
   </div>

   <?php if($options['use_wp_nav_menu']) { ?>
   <?php if (function_exists('wp_nav_menu')) { wp_nav_menu( array( 'sort_column' => 'menu_order', 'container_class' => 'header_menu' ) ); }; ?>
   <?php } else { ?>
   <div class="header_menu">
    <ul class="menu">
     <li class="<?php if (!is_paged() && is_home()) { ?>current_page_item<?php } else { ?>page_item<?php } ?>"><a href="<?php echo get_settings('home'); ?>/"><?php _e('HOME','monochrome'); ?></a></li>
     <?php
         if($options['header_menu_type'] == 'pages') {
         wp_list_pages('sort_column=menu_order&depth=0&title_li=&exclude=' . $options['exclude_pages']);
         } else {
         wp_list_categories('depth=0&title_li=&exclude=' . $options['exclude_category']);
         }
     ?>
    </ul>
   </div>
   <?php }; ?>

  </div>

  </div><!-- #header end -->