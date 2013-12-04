<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
<title><?php bloginfo('name'); wp_title();?></title>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<?php add_theme_support('automatic-feed-links'); ?>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css' />
<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
<link rel="shortcut icon" href="/favicon.ico" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head() ?>

</head>

<body class="<?php codium_body_class() ?>">

<div id="wrapperpub" class="hfeed">
	<div id="header">
		<div class="dp100">	
			<h1 id="blog-title" class="blogtitle"><a href="<?php echo bloginfo('url') ?>/" title="<?php bloginfo('name') ?>"><?php bloginfo('name') ?></a></h1>
			<div class="description"><?php bloginfo('description'); ?> </div>
		</div><!-- dp100 -->		
	</div><!--  #header -->		

		<div id="access">
			<div class="menu">
				<ul>
					<li>
						<a href="<?php echo bloginfo('url') ?>/" title="home" class="top_parent"><?php printf(__('HOME', 'codium')) ?></a>
					</li>
				</ul>
			</div>
					<?php wp_nav_menu(array( 'container_class' => 'menu-header', 'theme_location' => 'primary-menu',)); ?>			
		</div><!--  #access -->	
	
</div><!--  #wrapperpub -->	
<div id="wrapper">	