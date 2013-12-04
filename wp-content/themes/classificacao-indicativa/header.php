<!doctype html>
<!--[if IE 6]>
<html id="ie6" lang="pt-BR">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" lang="pt-BR">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" lang="pt-BR">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8) ]><!-->
<html lang="pt-BR">
<!--<![endif]-->

  <head>
    <title>	    
	    <?php
	    if(is_home()){
			echo get_bloginfo('name') . ' - ' . get_bloginfo('description');
	    } else {
		    wp_title('&raquo;', true, 'right');
		    bloginfo('name');
	    }
	    ?>	    
    </title>
    <meta charset="utf-8" />

    <!-- meta/rss/trackacks -->
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <!-- wp-head -->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php if(!is_home()) : ?>

<div id="header" class="container">								
	
	<a class="logo img-replacement" href="<?php echo home_url(); ?>">
		<?php bloginfo('name'); ?>
	</a>
						
	<div class="direita">
		<div class="menu-principal">
			<ul>
				<?php wp_nav_menu(array('theme_location' => 'principal', 'items_wrap' => '%3$s', 'container' => '')); ?>
				<li>
					<a href="#" class="bt-busca img-replacement">
						<?php _e('Busca'); ?>
					</a>
				</li>
			</ul>
		</div>
		
		<div class="form-busca">
			<form action="<?php echo home_url(); ?>" method="get">
				<div class="campo">
					<input type="text" name="s" value="digite sua busca" onFocus="this.value=''" />
					<input type="submit" value="ok" class="bt-enviar" />
				</div>
			</form>
		</div>

	</div>
	
</div>

<?php endif; ?>