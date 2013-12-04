<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
  <head>
    <title><?php bloginfo('name'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php wp_enqueue_style('blog_reset-min', get_bloginfo('stylesheet_directory').'/global/css/reset-min.css'); ?>
    <?php wp_enqueue_style('blog_principal', get_bloginfo('stylesheet_directory').'/global/css/principal.css'); ?>
    <?php wp_enqueue_style('blog_lightbox', get_bloginfo('stylesheet_directory').'/global/css/jquery.lightbox-0.5.css'); ?>
    <?php wp_enqueue_script('jquery'); ?>
    <?php wp_enqueue_script( 'lightbox', get_bloginfo('stylesheet_directory').'/global/js/jquery.lightbox-0.5.min.js', 'jquery'); ?>
    <?php wp_enqueue_script( 'funcoes', get_bloginfo('stylesheet_directory').'/global/js/funcoes.js', 'jquery'); ?>
    <!--[if IE 6]>
      <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory').'/global/css/ie6.css'; ?>" />
    <![endif]-->
    <?php wp_head() ?>
  </head>

  <body>
    
    <div id="principal">

      <?php locate_template( array( 'sidebar.php' ), true ) ?>

