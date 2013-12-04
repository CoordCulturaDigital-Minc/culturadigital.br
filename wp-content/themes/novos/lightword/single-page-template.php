<?php
/*
Template Name: No sidebar (works only with original layout)
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
<title><?php wp_title('&laquo;', true, 'right'); ?><?php bloginfo('name'); ?></title>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
<?php global $lw_layout_settings; if($lw_layout_settings == "Wider") : ?>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/wider.css" type="text/css" />
<?php else: ?>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/original.css" type="text/css" />
<?php endif; ?>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/single-page-template.css" type="text/css" />
<link rel="shortcut icon" href="<?php bloginfo('url'); ?>/favicon.ico" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_enqueue_script('jquery'); ?>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
<!--[if IE 6]><style type="text/css">/*<![CDATA[*/#header{background-image: none; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/images/single-page-template/content_top.png',sizingMethod='scale'); } /*]]>*/</style><![endif]-->
</head>

<body>
<div id="wrapper">
<?php lw_header_image(); ?>
<div id="header">
<?php lw_rss_feed(); ?>
<?php global $lw_remove_rss; if($lw_remove_rss == "true") {  ?>
<?php echo "<style type=\"text/css\">/*<![CDATA[*/ #header{background:transparent url(".get_bloginfo('template_directory')."/images/single-page-template/content_top_no_rss.png) no-repeat; } #content-body,x:-moz-any-link{float:left;margin-right:28px;}#content-body, x:-moz-any-link, x:default{float:none;margin-right:25px;}/*]]>*/</style>"; } ?>

<div id="top_bar">
<ul id="front_menu"<?php echo lw_expmenu(); ?>>
<?php echo lw_homebtn(__('Home','lightword')); ?>
<?php echo lw_wp_list_pages();  ?>
</ul>
<?php echo lw_searchbox(); ?>
</div>

</div>
<div id="content">

<div id="content-body">
<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div <?php if (function_exists("post_class")) post_class(); else print 'class="post"'; ?> id="post-<?php the_ID(); ?>">
<h2><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
<?php edit_post_link(__('Edit this page','lightword'), '', ''); ?>

<?php the_content(''); ?>
<?php if(function_exists('wp_print')) { print_link(); } ?>
<?php wp_link_pages('before=<div class="nav_link">'.__('PAGES','lightword').': &after=</div>&next_or_number=number&pagelink=<span class="page_number">%</span>'); ?>

</div>
<?php if ( comments_open() && $lw_disable_comments == "false" ) : comments_template(); endif; ?>
<?php endwhile; else: ?>

<h2><?php _e('Not Found','lightword'); ?></h2>
<p><?php  _e("Sorry, but you are looking for something that isn't here.","lightword"); ?></p>

<?php endif; ?>
</div>
<?php get_footer(); ?>