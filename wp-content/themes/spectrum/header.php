<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/ie.css" type="text/css" media="screen" />
		<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" />
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery-min.js"></script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/spectrum-min.js"></script>
		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
		<?php wp_head(); ?>
	</head>
	
	<body <?php body_class(); ?>>
		
		<div id="header">
			<div id="logo">
				<h1>
					<a href="<?php echo get_option('home'); ?>/"><img src="<?php bloginfo('template_directory'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" /></a>
				</h1>
			</div>
			<div id="subscribe">
				<a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to <?php bloginfo('name'); ?> RSS">Subscribe to <?php bloginfo('name'); ?> RSS</a>
			</div>
			<div class="pageList">
				<ul>
					<?php wp_list_pages('title_li=&depth=1'); ?>
				</ul>
			</div>
		</div>
		<div id="mainWrap">
			<div id="main">
				<div id="siteDescription">
					<h2>
						<?php bloginfo('description'); ?>
					</h2>
				</div>
				<div id="content">