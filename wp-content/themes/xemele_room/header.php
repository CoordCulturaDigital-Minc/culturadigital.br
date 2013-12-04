<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
    <!-- Meta -->
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="Generator" content="WordPress" />
    <meta name="Description" content="<?php bloginfo('description'); ?>" />
    <meta name="Keywords" content="<?php wp_title('&raquo;', true, 'right'); ?> <?php bloginfo('name'); ?>" />
    <meta name="Robots" content="ALL" />
    <meta name="Distribution" content="Global" />
    <meta name="Author" content="Equipe Web MinC - http://xemele.cultura.gov.br" />
    <meta name="Resource-Type" content="Document" />

	<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery-1.2.6.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/spy.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/scroll.js"></script>
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/featlist.css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" />


<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>


<?php wp_head(); ?>
</head>
<body id="top">
<div id="container">
<!-- Barra do Governo -->
<!--<div id="govbar">
	<a href="http://www.cultura.gov.br/" title="Minist&eacute;rio da Cultura" class="minc">Minist&eacute;rio da Cultura</a>
	<a href="http://www.brasil.gov.br/" title="Brasil - um pa&iacute;s de todos" class="brasil">Brasil - um pa&iacute;s de todos</a>	
	<select name="destaque">
		<option>Destaques do governo</option>
		<option value="http://www.brasil.gov.br">Portal do Governo Federal</option>
	</select>	
</div> -->
    <div id="wrapper">

<div id="header">

			<div id="linksHeader">
				<div class="dia"><?php print __(date('l')).", ".date('j')." de ".__(date('F'))." de ".date('Y'); ?></div> 
				<div class="outrasFuncoes">
				<div class="twitter"><a href="http://twitter.com/CulturaGovBr" target="_blank" title="twitter">Twitter</a></div>
				<div class="rss"><a href="<?php bloginfo('rss2_url'); ?>" title="RSS">RSS</a></div>
				</div>
			</div>	
			
						
            <div id="logo">
        	    <h1 class="logo"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
            </div>        

	</div>
		
		<div id="menucontainer">

			<div id="menu">
					
					<ul>
						<li <?php if(is_home()){echo 'class="current_page_item"';}?>><a href="<?php bloginfo('siteurl'); ?>/" title="Home">Home</a></li>
 						<?php wp_list_pages('&title_li=&depth=1&sort_column=menu_order')?>
					</ul>
			</div>	
				
		</div>

        <div id="pageWrapper">
