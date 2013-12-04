<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<!--[if IE 6]>
		<style type="text/css" media="screen">
			#footer p.rss { background-image: none; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('stylesheet_directory'); ?>/images/ico-rss-2.png', sizingMethod='image'); }
			#main-bot { height: 150px; }
			.comment { height: 90px; }
		</style>
	<![endif]-->
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>
	<script src="<?php bloginfo('stylesheet_directory'); ?>/js/fn.js" type="text/javascript" charset="utf-8"></script>
	<!-- Theme -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/themes/<?php echo choco_get_theme_path(); ?>/style.css" type="text/css" media="all" />
	<!--[if IE 6]>
		<style type="text/css" media="screen">
			#rss-link { background-image: none; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('stylesheet_directory'); ?>/themes/<?php echo choco_get_theme_path(); ?>/images/ico-rss.png', sizingMethod='image'); }
			.post .date .bg { background-image: none; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('stylesheet_directory'); ?>/themes/<?php echo choco_get_theme_path(); ?>/images/date.png', sizingMethod='image'); }
			#nav ul li.current_page_item a,
			#nav ul li.current_page_item a span { background-image: none; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('stylesheet_directory'); ?>/themes/<?php echo choco_get_theme_path(); ?>/images/nav-active.png', sizingMethod='scale'); }
		</style>
	<![endif]-->
</head>
<body <?php body_class(); ?> style="<?php echo choco_get_body_style() ?>">
<!-- Page -->
<div id="page">
	<!-- Header -->
	<div id="header">
		<!-- Logo -->
		<div id="logo">
			<h1><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
			<div class="description"><?php bloginfo('description'); ?></div>
		</div>
		<!-- END Logo -->
		<!-- Main Navigation -->
		<div id="nav">
			<ul>
				<?php choco_print_header() ?>
			</ul>
		</div>
		<script type="text/javascript" charset="utf-8">
			
		</script>
		<!-- END Main Navigation -->
		<div class="cl">&nbsp;</div>
	</div>
	<!-- END Header -->
	<!-- Main Block -->
	<div id="main">
		<!-- RSS icon -->
		<a href="<?php bloginfo('rss_url'); ?>" id="rss-link">RSS</a>
		<div id="main-top">
			<div id="main-bot">
				<div class="cl">&nbsp;</div>
				<!-- Content -->
				<div id="content">