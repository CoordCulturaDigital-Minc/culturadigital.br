<?php /* Mystique/digitalnature */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php //language_attributes('xhtml'); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php mystique_title(); ?></title>

<?php mystique_meta_description(); ?>
<meta name="designer" content="digitalnature.ro" />

<?php if(WP_VERSION < 3.0): ?>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<?php endif; ?>

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" />

<?php wp_head(); ?>
</head>
<body class="<?php mystique_body_class() ?>">
 <div id="page">


  <div class="page-content header-wrapper">


    <div id="header" class="bubbleTrigger">
    
      <?php do_action('mystique_header_start'); ?>          

      <div id="site-title" class="clear-block">

        <?php mystique_logo(); ?>
        <?php if(get_bloginfo('description')): ?><p class="headline"><?php bloginfo('description'); ?></p><?php endif; ?>

        <?php do_action('mystique_header'); ?>

      </div>

      <?php mystique_navigation(); ?>

      <?php do_action('mystique_header_end'); ?>      

    </div>

  </div>

  <!-- left+right bottom shadow -->
  <div class="shadow-left page-content main-wrapper">
   <div class="shadow-right">

     <?php do_action('mystique_before_main'); ?>