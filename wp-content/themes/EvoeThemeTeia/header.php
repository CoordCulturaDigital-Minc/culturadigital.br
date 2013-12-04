<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
   	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory') ?>/global/css/master.css" media="all" />
   	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory') ?>/global/inc/custom_theme_stylesheet.css.php" media="all" />
    <link rel="icon" href="<?php bloginfo('stylesheet_directory') ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory') ?>/favicon.ico" type="image/x-icon">
    <?php if ( is_single() or is_page() ) : ?>
    	<link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/global/css/single.css" media="all" />
    <?php endif; ?>
    <?php wp_enqueue_script( 'jquery' ); ?>
    <?php wp_enqueue_script( 'resetDefault', get_bloginfo('template_directory') .'/global/js/resetDefault.js', 'jquery' ); ?>
    <?php wp_enqueue_script( 'lavaLamp', get_bloginfo('template_directory') .'/global/js/jquery.lavalamp.js', 'jquery' ); ?>
    <?php wp_enqueue_script( 'easing', get_bloginfo('template_directory') .'/global/js/easing.js', 'jquery' ); ?>
    <?php wp_enqueue_script( 'jquery.cookie', get_bloginfo('template_directory') .'/global/js/jquery.cookie.js', 'jquery' ); ?>
    <?php
    if ( is_home() and !is_paged() )
      wp_enqueue_script( 'stepCarousel', get_bloginfo('template_directory') .'/global/js/stepCarousel.js', 'jquery' );
    ?>
    <?php wp_enqueue_script( 'functions', get_bloginfo('template_directory') .'/global/js/functions.js', 'jquery' ); ?>
    <?php
    if ( is_singular() and comments_open() and (get_option('thread_comments') == 1) or is_page() and comments_open() and (get_option('thread_comments') == 1) )
      wp_enqueue_script( 'comment-reply' );
    ?>
<?php wp_head(); ?>
    <!--[if IE 6]> 
        <script src="<?php bloginfo('template_directory'); ?>/global/js/DD_belatedPNG.js"></script>
        <script type="text/javascript">
            DD_belatedPNG.fix( '#header h2 a, #header div.nav div.bg, span.tab, #content span.twitt, #header .rss, #content, #content div.wrapper, #content div.outer, #content div.inner, #content .bigTitle, #content .thumb, #content div.main div.posts ul.posts li .comment, #footer' );
        </script>
    <![endif]-->
<?php
	$ctheme = get_option('custom_theme');
	$hlCategory = $ctheme['general']['hlCategory'];
	$my_query1 = new WP_Query('cat='. $hlCategory .'&posts_per_page=10');
    if ( $my_query1->have_posts() && is_home() and !is_paged() ) : ?>
    <script type="text/javascript">
	// steop carousel
	stepcarousel.setup({
		galleryid: 'mygallery', //id of carousel DIV
		beltclass: 'gallery', //class of inner "belt" DIV containing all the panel DIVs
		panelclass: 'panel', //class of panel DIVs each holding content
		panelbehavior: {speed:400, wraparound:true, persist:true},
		defaultbuttons: {enable: true, moveby: 1, leftnav: ['<?php bloginfo("template_directory") ?>/global/img/bt/bt_prev.png', 15, 100], rightnav: ['<?php bloginfo("template_directory") ?>/global/img/bt/bt_next.png', -30, 100]},
		statusvars: ['statusA', 'statusB', 'statusC'], //register 3 variables that contain current panel (start), current panel (last), and total panels
		contenttype: ['inline'] //content setting ['inline'] or ['external', 'path_to_external_file']
	})
	</script>
<?php endif ?>
</head>

<body <?php body_class() ?>>
    <div id="general">
        <div id="header">
            <div class="nav">
            	<div class="bg"></div>
                <div class="middle">
                    <ul class="nav">
                        <li><a href="<?php bloginfo('url'); ?>" title=""><?php _e('Home', 'evoeTheme'); ?></a></li>
                        <?php wp_list_pages('title_li='); ?>
                    </ul>
                </div>
            </div>
            
            <div class="skip">
                <a href="#content" title="<?php _e('Pular para o conteúdo', 'evoeTheme'); ?>"><?php _e('Pular para o conteúdo', 'evoeTheme'); ?></a>
            </div>
