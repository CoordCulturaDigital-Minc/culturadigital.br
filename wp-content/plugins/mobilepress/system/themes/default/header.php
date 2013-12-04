<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.1//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php if (is_home()) { bloginfo('name'); } ?><?php if (is_month()) { the_time('F Y'); } ?><?php if (is_category()) { single_cat_title(); } ?><?php if (is_single()) { the_title(); } ?><?php if (is_page()) { the_title(); } ?><?php if (is_tag()) { single_tag_title(); } ?><?php if (is_404()) { echo "Página não encontrada!"; } ?></title>
		<meta name="generator" content="WordPress e MobilePress" />
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<style type="text/css">
/* Simple Reset */

body, html, div, span, h1, h2, h3, h4, h5, h6, p, ul, ol, li, blockquote, form, input, fieldset { margin: 0; padding: 0;}

img, fieldset { border: none; outline: none; }

	img { height: auto; max-height: 300px; max-width: 300px; width: auto; }

ul, ol { list-style: none; }

/* General Styles */

body { background: #aaa; font: 62.5% Verdana, Arial, Trebuchet MS; }

/* Structure */

#headerwrap { width: 100%; background:#0076a4 url(images/bg-header.png) repeat-x; }

	#header { background: url(images/logo-cdbr.png) no-repeat; border-bottom: 1px solid #ccc; padding: 0px; height:81px; text-indent:-5000px; }
	
	#header a{ width:320px; height:81px; float:left; text-indent:-5000px; }

#infoblock, #pageblock { background: #03374f; border-bottom: 1px solid #c6d9e9; color: #fff; }

.aduity { background: #f5f5f5; border-bottom: 1px solid #c6d9e9; text-align: center; }

#contentwrap { width: 100%; }

	.post { background: #f5f5f5; border-bottom: 1px solid #c6d9e9; padding: 8px; }
	
	.page { background: #f5f5f5; padding: 8px; }

#postfoot { background: #e4f2fd; color: #333; padding: 8px; }

#indexpostfoot { background: #f89517; color: #fff; padding: 8px; }

#indexpostfoot a { color: #fff !important; font-weight:bold; margin:0 0 10px 0; }

#loading { background: #fff url(images/wait.gif) center no-repeat; clear: both; display: none; font-size: 12px; height: 30px; padding: 5px 0px; text-align: center;	}

#comments { background: #e4f2fd; border-top: 1px solid #c6d9e9; color: #333; padding: 8px; }

	.comments { display: none; }

#searchwrap { width: 100%; }

	#search { background: #03374f; border-top: 0px solid #777; padding: 8px; }

#footerwrap { width: 100%; }

	#footer { background: #000; padding: 8px; color:#f89517; }
	
	#footer a{ color:#f89517 !important; }

/* Headings */

h1 { font-size: 1.4em; line-height: 1.4em; margin: 0 0 10px 0; }

	#header h1 { color: #ccc; margin: 0; }
	
		#header h1 a { color: #ccc; text-decoration: none; }
		
h2 { font-size: 1.2em; line-height: 1.4em; margin: 0 0 10px 0; }

	h2 a { color: #2583ad; }
	
	h2.title { margin: 0; }
	
	#infoblock h2 { padding: 8px; margin: 0; }
	
	#pageblock h2 { padding: 8px; margin: 0; }
	
h3 { font-size: 1.2em; line-height: 1.4em; margin: 0 0 10px 0; }

/* Typography */

.aduity a { color: #d54e21; font-size: 1.2em; line-height: 1.4em; }

p { color: #333; font-size: 1.2em; line-height: 1.4em; margin: 0 0 10px 0; }

	p a { color: #d54e21; }
	
	#header p { color: #888; font-size: 1.1em; margin: 0; }
	
		#header p a { color: #888; }

	.post p.subtitle { font-size: 1.1em; margin: 0; }
	
		.post p.singleline { margin: 0; }
		
		.post p.commentfoot { font-size: 1.1em; }
	
	.oneline p { margin: 0; }
	
	#postfoot p, #indexpostfoot p { font-size: 1.1em; margin: 0; }
	
		#postfoot p a { color: #d54e21; }
		
		#indexpostfoot p a { color: #ccc; }
	
	#comments p { font-size: 1.1em; margin: 0; }
	
		#comments p a { color: #d54e21; }
	
	#footer p { color: #666; font-size: 1.1em; margin: 0; }
	
		#footer p a { color: #666; }

ul { list-style: disc; margin: 0 0 10px 25px; }

ol { list-style: decimal; margin: 0 0 10px 25px; }

	ul li, ol li { color: #333; font-size: 1.2em; padding: 2px; }
	
	ol#pages { margin: 0 0 0 20px; }
	
		ol#pages li { color: #333; font-size: 1.1em; padding-left: 0; }
		
			ol#pages li a { color: #d54e21; }

blockquote { border-left: 4px solid #ccc; margin: 8px; padding-left: 4px; }

.azulBox{ background:#0076a4; width:100%; }		
	
	</style>
	</head>
	
	<body>
	
		<div id="headerwrap">
			
			<div id="header">
				<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
				<p><?php bloginfo('description'); ?></p>
			</div>
			
		</div>
		
		<div class="aduity"><?php mopr_ad('top'); ?></div>