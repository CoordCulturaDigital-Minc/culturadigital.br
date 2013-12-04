<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Arquivo <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lt IE 7]>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/unitpngfix/unitpngfix.js"></script>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" type="text/css" media="screen" />
<![endif]-->

<?php wp_head(); ?>
</head>

<body>
	<div id="wrapper">
		<div id="page">
			<div id="header">
				<div id="headerimg">
					<h1><a href="<?php echo get_option('home'); ?>/" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
				</div>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('TopAd') ) : ?><?php endif; ?>
				<div id="rss" title="Subscibe To Our RSS Feed">
					<a href="<?php bloginfo('rss2_url'); ?>">RSS</a>
				</div>
				<div id="menu">
					<ul>
						<li<?php if ( is_home() ) { ?> class="current_page_item"<?php } ?> id="homelink"><a href="<?php echo get_option('home'); ?>/" title="<?php bloginfo('name'); ?>">Home</a></li>
						<?php wp_list_pages('title_li='); ?>
						<li><a href="<?php bloginfo('rss2_url'); ?>" title="RSS">RSS</a></li>
					</ul>
				<div id="searchetc">
					<?php include (TEMPLATEPATH . '/searchformtop.php'); ?>
				</div>
			</div>
		</div>
	<div id="contentwrapper">