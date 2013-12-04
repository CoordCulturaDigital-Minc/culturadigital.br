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

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/comment-style.css" type="text/css" media="screen" />
<?php if (strtoupper(get_locale()) == 'JA') : ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/japanese.css" type="text/css" media="screen" />
<?php elseif (strtoupper(get_locale()) == 'HE_IL' || strtoupper(get_locale()) == 'FA_IR') : ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/rtl.css" type="text/css" media="screen" />
<?php endif; ?>

<!--[if lt IE 7]>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/iepngfix.js"></script>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie6.css" type="text/css" media="screen" />
<![endif]--> 

<?php $options = get_option('pb_options'); if($options['image_style']) { ?>
<style type="text/css">
.post img, .post a img { border:1px solid #222; padding:5px; margin:0;  background:#555; }
.post a:hover img { border:1px solid #849ca0; background:#59847d; }
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
 <div id="contents">

 <div class="header-menu-wrapper clearfix">
 <div id="pngfix-right"></div>
  <?php if($options['use_wp_nav_menu']) { ?>
  <?php if (function_exists('wp_nav_menu')) { wp_nav_menu( array( 'sort_column' => 'menu_order') ); }; ?>
  <?php } else { ?>
  <ul class="menu">
   <li class="<?php if (!is_paged() && is_home()) { ?>current_page_item<?php } else { ?>page_item<?php } ?>"><a href="<?php echo get_settings('home'); ?>/"><?php _e('HOME','piano-black'); ?></a></li>
   <?php 
         if($options['header_menu_type'] == 'pages') {
         wp_list_pages('sort_column=menu_order&depth=0&title_li=&exclude=' . $options['exclude_pages']);
         } else {
         wp_list_categories('depth=0&title_li=&exclude=' . $options['exclude_category']);
         }
   ?>
  </ul>
  <?php }; ?>
  <div id="pngfix-left"></div>
  </div>

  <div id="header">

   <?php if ($options['use_logo']) : ?>
   <div id="logo_image">
    <h1><a href="<?php echo get_option('home'); ?>/"><img src="<?php bloginfo('template_url'); ?>/img/<?php echo $options['logo_name']; ?>" title="<?php bloginfo('name'); ?>" alt="<?php bloginfo('name'); ?>" /></a></h1>
   </div>
   <?php else : ?>
   <div id="logo">
    <a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a>
    <h1><?php bloginfo('description'); ?></h1>
   </div>
   <?php endif; ?>

   <div id="header_meta">

    <?php if ($options['header_search']) : ?>
    <div id="search-area"<?php if (!$options['header_rss']&&!$options['header_twitter']) : echo ' style="margin-right:0;"'; endif; ?>>
     <?php if ($options['use_google_search']) : ?>
     <form action="http://www.google.com/cse" method="get" id="searchform">
      <div><input type="text" value="<?php _e('Google Search','piano-black'); ?>" name="q" id="search-input" onfocus="this.value=''; changefc('white');" /></div>
      <div>
       <input type="image" src="<?php bloginfo('template_url'); ?>/img/search-button.gif" name="sa" alt="<?php _e('Search from this blog.','piano-black'); ?>" title="<?php _e('Search from this blog.','piano-black'); ?>" id="search-button" />
       <input type="hidden" name="cx" value="<?php echo $options['custom_search_id']; ?>" />
       <input type="hidden" name="ie" value="UTF-8" />
      </div>
     </form>
     <?php else: ?>
      <form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
       <div><input type="text" value="<?php _e('Search','piano-black'); ?>" name="s" id="search-input" onfocus="this.value=''; changefc('white');" /></div>
       <div><input type="image" src="<?php bloginfo('template_url'); ?>/img/search-button.gif" alt="<?php _e('Search from this blog.','piano-black'); ?>" title="<?php _e('Search from this blog.','piano-black'); ?>" id="search-button" /></div>
      </form>
     <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($options['header_rss']) : ?>
    <a href="<?php bloginfo('rss2_url'); ?>" id="rss-feed" title="<?php _e('Entries RSS','piano-black'); ?>" ><?php _e('RSS','piano-black'); ?></a>
    <?php endif; ?>
    <?php if ($options['header_twitter']) : ?>
    <a href="<?php echo $options['twitter_url']; ?>" id="twitter" title="<?php _e('TWITTER','piano-black'); ?>" ><?php _e('Twitter','piano-black'); ?></a>
    <?php endif; ?>

   </div><!-- #header_meta end -->

  </div><!-- #header end -->