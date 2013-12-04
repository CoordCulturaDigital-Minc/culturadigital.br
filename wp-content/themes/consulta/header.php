<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged, $pageTitle;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie.css" />
<![endif]-->
<!--[if lt IE 9]>
	<script src="<?php bloginfo('stylesheet_directory'); ?>/js/iepp.1-6-2.js"></script>
<![endif]-->

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div id="overlay"></div>
<div class="container">
<header id="main-header" class="clearfix">
	<div class="span-17 clearfix">
		<div id="login">
            <?php if (is_user_logged_in()): ?>
                <?php global $current_user; ?>
                Olá,

                <div id="logged-user-name">
                    <a href="<?php echo admin_url( 'profile.php' ); ?>">
                        <?php echo substr($current_user->display_name, 0, 38); ?>
                    </a>
                </div>
                |
                <a href="<?php echo wp_logout_url(get_bloginfo('url')) ; ?>">
                    <?php _e("Sair", "tnb"); ?>
                </a>
            <?php else: ?>
                <a href="<?php echo site_url('wp-login.php'); ?>">Log in</a> | <a href="<?php echo site_url('cadastro'); ?>">Cadastro</a>
            <?php endif; ?>




        </div>

        <?php

        $datafinal = strtotime(get_theme_option('data_encerramento'));
        //$datafinal = strtotime('2011-08-12');


        if ($datafinal) {

            if ($datafinal > time()) {

                $intervalo = $datafinal - time();
                $dias = $intervalo / 60 / 60 / 24;
                $dias = (int) $dias + 1;

            } else {
                $dias = -1;
            }

        }

        ?>

		<p id="cronometro">
            A consulta
            <?php if ($dias > 0): ?>
                 se encerra em <span><?php echo $dias; ?></span> dia<?php if ($dias > 1) echo 's'; ?>
            <?php elseif (get_theme_option('data_encerramento') == date('Y-m-d')): ?>
                 se encerra hoje
            <?php else: ?>
                está encerrada
            <?php endif; ?>
        </p>
	</div>
	<form id="busca" class="span-6" role="search" method="get" action="<?php echo home_url( '/' ); ?>">
		<input id="s" type="search" value="buscar" name="s" onfocus="if (this.value == 'buscar') this.value = '';" onblur="if (this.value == '') {this.value = 'buscar';}" />
	</form>
	<a id="feed-link" class="span-1 last" href="<?php bloginfo('rss_url'); ?>" title="RSS Feed">rss</a>

    <div id="branding" class="clear">

        <?php if ( 'blank' == get_header_textcolor() ) : ?>
			<a id="header-image-link" href="<?php bloginfo( 'url' ); ?>" title="<?php bloginfo( 'name' ); ?>"></a>
        <?php else: ?>
			<h1><a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<p id="description" class="col-12"><?php bloginfo( 'description' ); ?></p>
		<?php endif; ?>

    </div>


    <nav id="main-nav" class="span-22 prepend-2 last clearfix">
		<?php wp_nav_menu( array( 'theme_location' => 'principal', 'container' => '', 'menu_id' => 'main-menu', 'menu_class' => 'clearfix', 'fallback_cb' => 'consulta_default_menu') ); ?>
	</nav>

</header>
<!-- #main-header -->
