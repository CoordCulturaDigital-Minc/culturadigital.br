<?php global $traction; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php if ( is_front_page() ) : ?>
		<title><?php bloginfo( 'name' ); ?></title>
	<?php elseif ( is_404() ) : ?>
		<title><?php _e( 'Page Not Found |', 'traction' ); ?> | <?php bloginfo( 'name' ); ?></title>
	<?php elseif ( is_search() ) : ?>
		<title><?php printf(__ ("Search results for '%s'", "traction"), attribute_escape(get_search_query())); ?> | <?php bloginfo( 'name' ); ?></title>
	<?php else : ?>
		<title><?php wp_title($sep = '' ); ?> | <?php bloginfo( 'name' );?></title>
	<?php endif; ?>

	<!-- Basic Meta Data -->
	<meta name="Copyright" content="Design is copyright <?php echo date( 'Y' ); ?> The Theme Foundry" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<?php if ((is_single() || is_category() || is_page() || is_home()) && (!is_paged())) : else : ?>
		<meta name="robots" content="noindex,follow" />
	<?php endif; ?>

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php bloginfo( 'stylesheet_directory' ); ?>/images/favicon.ico" />

	<!--Stylesheets-->
	<link href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" rel="stylesheet" />
	<!--[if lt IE 8]>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo( 'template_url' ); ?>/stylesheets/ie.css" />
	<![endif]-->

	<!--WordPress-->
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?> RSS Feed" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!--WP Hooks-->
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_enqueue_script( 'jquery' ); ?>
	<?php wp_head(); ?>

	<!--Scripts-->
	<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/javascripts/traction.js"></script>
	<?php if ( is_front_page() && ($traction->sliderState() != '' ) ) { ?>
		<!--Slider-->
		<?php
		 $autoStart = 0;
		 $slidespeed = 300;
		 $fadespeed = 200;
		 if ($traction->sliderSpeed() != '' ) $slidespeed = $traction->sliderSpeed();
		 if ($traction->sliderFade() != '' ) $fadespeed = $traction->sliderFade();
		 if ($traction->sliderStart() == 'true' ) $autoStart = $traction->sliderDelay(); else $autoStart = 0;
		?>
		<script type="text/javascript" charset="utf-8">
			jQuery(function(){
				jQuery("#feature").loopedSlider({
					containerClick: false,
					autoStart: <?php echo $autoStart; ?>,
					slidespeed: <?php echo $slidespeed; ?>,
					fadespeed: <?php echo $fadespeed; ?>,
					autoHeight: 1
				});
			});
		</script>
	<?php } ?>
</head>
<body>
	<div class="skip-content"><a href="#content"><?php _e( 'Skip to content', 'traction' ); ?></a></div>
	<div id="pg-nav-bg">
		<div class="wrapper clear">
			<div id="pg-nav">
				<ul class="nav">
					<li class="page_item <?php if (is_front_page()) echo( 'current_page_item' );?>"><a href="<?php bloginfo( 'url' ); ?>"><?php _e( 'Home', 'traction' ); ?></a></li>
					<?php if ($traction->hidePages() !== 'true' ) : ?>
						<?php wp_list_pages( 'title_li=' ); ?>
					<?php endif; ?>
				</ul>
			</div><!--end page-navigation-->
		</div><!--end wrapper-->
	</div><!--end page-navigation-bg-->
	<div class="wrapper big">
		<div id="header" class="clear">
			<div class="logo">
				<?php if (is_home()) echo( '<h1 id="title">' ); else echo( '<div id="title">' );?><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a><?php if (is_home()) echo( '</h1>' ); else echo( '</div>' );?>
				<div id="description">
					<?php bloginfo( 'description' ); ?>
				</div><!--end description-->
			</div><!--end logo-->
			<div id="cat-nav" class="clear">
				<ul class="nav">
					<?php if ($traction->hideCategories() != 'true' ) : ?>
						<?php wp_list_categories( 'title_li=' ); ?>
					<?php endif; ?>
				</ul>
			</div><!--end navigation-->
		</div><!--end header-->
		<?php if (($traction->sliderState() != '' ) && is_home() && !is_paged() ) { ?>
			<?php if (is_file(STYLESHEETPATH . '/featured.php' )) include(STYLESHEETPATH . '/featured.php' ); else include(TEMPLATEPATH . '/featured.php' ); ?>
		<?php } ?>