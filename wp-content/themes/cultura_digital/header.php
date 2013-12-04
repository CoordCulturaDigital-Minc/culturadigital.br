<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<!-- Meta -->
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="Description" content="<?php bloginfo('description'); ?>" />
		<meta name="Keywords" content="<?php bloginfo('description'); ?>" />
		<meta name="Distribution" content="Global" />
		<meta name="Author" content="Equipe Web MinC - http://xemele.cultura.gov.br/" />
		<meta name="Resource-Type" content="Document" />
		
		<!-- Title -->
		<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
		
		<!-- Pingback -->
		<link href="<?php bloginfo('pingback_url'); ?>" rel="pingback" />
		
		<!-- Icon -->
		<link type="image/x-icon" href="<?php bloginfo('stylesheet_directory'); ?>/img/icon/favicon.ico" rel="shortcut icon" />
		
		<!-- RSS -->
		<link type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="feeds de <?php bloginfo('name'); ?>" rel="alternate" />
		<?php if(is_category()) : ?><link type="application/rss+xml" href="<?php print get_category_feed_link($cat, 'rss2'); ?>" title="feeds de <?php single_cat_title(); ?>" rel="alternate" /><?php endif; ?>
		
		<!-- CSS -->
		<link type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/home.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/carousel.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/jquery.tabs.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/jquery.tooltip.css" rel="stylesheet" media="screen" />
		<?php wp_deregister_style('thickbox'); ?>
		<link type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/jquery.thickbox.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/destaque.css" rel="stylesheet" media="screen" />
		
		<!--[if lte IE 7]>
		<link type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/jquery.tabs-ie.css" rel="stylesheet" media="screen" />
		<![endif]-->
		
		<!-- JavaScript -->
		<?php wp_enqueue_script('jquery'); ?>
		<?php wp_enqueue_script('thickbox'); ?>
		
		<?php wp_enqueue_script('jquery-easyTooltip', get_bloginfo('stylesheet_directory') . '/js/jquery.tooltip.js', array('jquery')); ?>
		<?php wp_enqueue_script('back-to-top', get_bloginfo('stylesheet_directory') . '/js/backtotop.js'); ?>
		<?php wp_enqueue_script('jquery-droppy', get_bloginfo('stylesheet_directory') . '/js/jquery.droppy.js', array('jquery')); ?>
		<?php wp_enqueue_script('jquery-tabs', get_bloginfo('stylesheet_directory') . '/js/jquery.tabs.js', array('jquery')); ?>
		<?php wp_enqueue_script('cultura-digital', get_bloginfo('stylesheet_directory') . '/js/script.js', array('jquery', 'back-to-top', 'jquery-droppy', 'jquery-tabs')); ?>
		
		<?php wp_head(); ?>
	</head>
	<body>
	
	<div class="centralizar">
		<div id="linksHeader">
			<div class="dia"><?php print __(date('l')).", ".date('j')." de ".__(date('F'))." de ".date('Y'); ?></div> 
			<div class="outrasFuncoes">
			<div class="twitter"><a href="http://twitter.com/CulturaGovBr" target="_blank" title="twitter">Twitter</a></div>
			<div class="rss"><a href="<?php bloginfo('rss2_url'); ?>" title="RSS">RSS</a></div>
			</div>
		</div>
	</div>	 
		
	<div class="centralizar">
			<div id="cabecalho">
		 		 <h1 class="logo"><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
			
				<div class="estruturaHeader">
			
					<div class="busca">
						<!--
						<form action="<?php bloginfo('url'); ?>" method="get">
							<input type="text" name="s" /><button type="submit"></button>
						</form>
						-->
						
						<form action="http://www.culturadigital.br" id="cse-search-box">

								<input type="hidden" name="s" value="busca" />
								<input type="hidden" name="cx" value="002806229775407539401:8n7iq7nch10" />
								<input type="hidden" name="cof" value="FORID:10" />
								<input type="hidden" name="ie" value="UTF-8" />
								<input type="text" name="q" size="31" />
								<button type="submit" name="sa"></button>

						</form>

						<script type="text/javascript" src="http://www.google.com/jsapi"></script>
						<script type="text/javascript">google.load("elements", "1", {packages: "transliteration"});</script>
						<script type="text/javascript" src="http://www.google.com/coop/cse/t13n?form=cse-search-box&t13n_langs=pt"></script>
						<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=pt"></script>
					</div>
	
					<div class="menu">
						<div>
						<ul id='nav'>
							<li class="btHome"><a href="<?php bloginfo('url'); ?>" title="home">home</a></li>
							<li class="btForum">
								<div><a href="<?php print get_page_link(1898); ?>" title="O Fórum"></a></div>
								<ul><?php wp_list_pages('title_li=&depth=1&child_of=1898'); ?></ul>
							</li>
							<li class="btPrograma">
								<div><a href="<?php print get_page_link(1909); ?>" title="O Programa"></a></div>
								<ul><?php wp_list_pages('title_li=&depth=1&child_of=1909'); ?></ul>
							</li>
							<li class="btLifeStream"><div><a href="<?php print get_page_link(1901); ?>" title="LiveStream"></a></div></li>
							<li class="btLabblog"><a href="<?php print get_category_link(158); ?>" title="labblog">LabBlog</a></li>
						</ul>
						</div>
				</div>
			</div>
		</div>	
	</div>
		<?php if(function_exists('yoast_breadcrumb')) : ?><div class="centralizar"><div id="breadcrumb"><?php yoast_breadcrumb(); ?></div></div><?php endif; ?>
		
		<?php if(is_home()) : ?>

			<div id="comunidade">
				<div id="boxComunidade">
					<div class="infos">
						<h2>Um novo jeito de fazer Política Pública</h2>	
						<p>Bem-vindo à rede social da <strong>Cultura Digital Brasileira</strong>, espaço público e aberto voltado para a formulação e a construção democrática de uma política pública de cultura digital, integrando cidadãos e insituições governamentais, estatais, da sociedade civil e do mercado.</p>
						
						<div class="botoes">
							<div class="integrantes"><a href="<?php bloginfo('url'); ?>/members/">integrantes</a></div>
							<div class="grupos"><a href="<?php bloginfo('url'); ?>/groups/">grupos</a></div>
							<!--<div class="organizacoes"><a href="#">organizacoes</a></div> -->
							<div class="agenda"><a href="http://www.culturadigital.br/agenda/" target="_blank">agenda</a></div>
						</div>
						
						<?php global $user_ID; ?>
						<?php if(empty($user_ID)) : ?>
						<br /><br /><br /><br /><br /><br />
							<!--<h3>Cadastre-se e participe deste debate.</h3> -->
							<h3>Login</h3>
							<div class="login">
								<form name="loginform" id="loginform" action="<?php bloginfo('url'); ?>/wp-login.php" method="post">
									<input type="text" name="log" onfocus="if (this.value == 'Usuário') this.value = '';" onblur="if (this.value == '') {this.value = 'Usuário';}" value="Usuário" tabindex="10" />
									<input type="password" name="pwd" onfocus="if (this.value == 'Senha') this.value = '';" onblur="if (this.value == '') {this.value = 'Senha';}" value="Senha" tabindex="20" />
									<button type="submit" name="wp-submit" value="Login" /></button>
									<input type="hidden" name="redirect_to" value="<?php bloginfo('url'); ?>" />
									<input type="hidden" name="testcookie" value="1" />
								</form>
							</div>
							
							
							<div class="semRegistro"><strong>Ainda não tem registro?</strong> É super simples criar uma conta. <span class="cadastreSe"> &raquo; <a href="<?php bp_signup_page(); ?>">Registre-se</a></span></div>
							
							
						<?php else : ?><p></p><p></p>
						<h3>Painel de Controle</h3>
						<div class="login">
							<div class="linksAdmin">
							<a href="<?php print bp_core_get_userurl($user_ID); ?>">Meu perfil</a> <!--| <a href="<?php print bp_core_get_userurl($user_ID); ?>profile/edit/group/6">TEIA 2010 - Inscreva-se</a> --> | <strong><?php wp_loginout(); ?></strong> 
							</div>
						</div>
							
						<?php endif; ?>
						
					</div>
					
					<div class="ajax">
						<h2>A rede no mapa</h2>
						<iframe src="<?php bloginfo('stylesheet_directory'); ?>/map.php" width="471" height="245" frameborder="0"></iframe>
						<!--<div class="mapaCompleto"><a href="#">Mapa completo</a></div> -->
					</div>
				</div>
			</div>

		<?php endif; ?>

		<div class="conteudo centralizar">
