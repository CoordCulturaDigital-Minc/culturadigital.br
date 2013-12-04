<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>

<!-- Meta -->
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="Generator" content="WordPress" />
    <meta name="Description" content="<?php bloginfo('description'); ?>" />
    <meta name="Keywords" content="<?php wp_title('&raquo;', true, 'right'); ?> <?php bloginfo('name'); ?>" />
    <meta name="Robots" content="ALL" />
    <meta name="Distribution" content="Global" />
    <meta name="Author" content="Equipe Web MinC - http://xemele.cultura.gov.br" />
    <meta name="Resource-Type" content="Document" />
    
    <!-- Title -->
    <title><?php wp_title('&raquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    
    <!-- Pingback -->
    <link href="<?php bloginfo('pingback_url'); ?>" rel="pingback" />
    
    <!-- Icon -->
    <link type="image/x-icon" href="<?php bloginfo('stylesheet_directory'); ?>/img/icon/favicon.ico" rel="shortcut icon" />
    
    <!-- RSS -->
    <link type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="feeds de <?php bloginfo('name'); ?>" rel="alternate" />
    <?php if(is_category()) : ?><link type="application/rss+xml" href="<?php print get_category_feed_link($cat, 'rss2'); ?>" title="feeds de <?php single_cat_title(); ?>" rel="alternate" /><?php endif; ?>


 	<!-- CSS -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

	<!-- JavaScript -->
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory');?>/top.js"></script>


	<?php wp_head(); ?>

</head>
<body id="section-index">
<div id="header">
  <h1><a href="<?php bloginfo('siteurl');?>/" title="<?php bloginfo('name');?>">
    <?php bloginfo('name');?>
    </a></h1>
  <h2>
    <?php bloginfo('description');?>
  </h2>
  <div id="search-tool-div">
    <script type="text/javascript">
	function clearDefault(el) {
	  if (el.defaultValue==el.value) el.value = ""
	}
	</script>
    <form style="margin:0px;" method="get" id="searchform" action="<?php echo $_SERVER['../first-try/PHP_SELF']; ?>">
      <input style="margin:0px;" value="Busque aqui" onfocus="clearDefault(this)" name="s" type="text" class="search-top" id="s" size="25" />
      <input style="margin-left:5px;" type="submit" id="sidebarsubmit" value="Ok" class="submit-search" />
    </form>
  </div>
</div>
<div id="navigation">
  <ul>
    <li <?php if(is_home()){echo 'class="current_page_item"';}?>><a href="<?php bloginfo('siteurl'); ?>/" title="Home">Home</a></li>
    <?php wp_list_pages('&title_li=&depth=1&sort_column=menu_order')?>
  </ul>
</div>
<!--<div class="breadcrumb">
<span>
  <?php
		if (class_exists('breadcrumb_navigation_xt')) {
		// Display a prefix
		echo '*';
		// new breadcrumb object
		$mybreadcrumb = new breadcrumb_navigation_xt;
		// Display the breadcrumb
		$mybreadcrumb->display();
		}
	?>
</span>
</div> -->
<div id="container">
<div id="feedarea">
  <dl>
    <dt style="padding-left:10px;"><strong>RSS Feeds:</strong></dt>
    <dd><a href="<?php bloginfo('rss2_url'); ?>">Posts</a></dd>
    <dd><a href="<?php bloginfo('comments_rss2_url'); ?>">Coment&aacute;rios</a></dd>
  </dl>
</div>
