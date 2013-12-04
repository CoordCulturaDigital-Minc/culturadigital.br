<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.1//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php if (is_home()) { bloginfo('name'); } ?><?php if (is_month()) { the_time('F Y'); } ?><?php if (is_category()) { single_cat_title(); } ?><?php if (is_single()) { the_title(); } ?><?php if (is_page()) { the_title(); } ?><?php if (is_tag()) { single_tag_title(); } ?><?php if (is_404()) { echo "Página não encontrada"; } ?></title>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
		<meta name="generator" content="WordPress e MobilePress" />
		<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<script type="application/x-javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.min.js"></script>	
	</head>

	<body>
	
		<div id="headerwrap">
			
			<div id="header">
				<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
				<p><?php bloginfo('description'); ?></p>
			</div>
			
		</div>
		
		<div class="aduity"><?php mopr_ad('top'); ?></div>