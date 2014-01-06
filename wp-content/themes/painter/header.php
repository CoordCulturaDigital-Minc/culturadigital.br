<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width" />

		<title><?php wp_title( '|', true, 'right' ); ?></title>

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div class="container">
			<div id="head">
				<div class="row">
					<div class="col_full">
						<div id="brand">
							<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo get_bloginfo( 'name' ); ?>"><?php echo get_bloginfo( 'name' ); ?></a></h1>
							<h2><?php echo get_bloginfo( 'description' ); ?></h2>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>

		<div class="container">
			<div id="body">
				<div class="row border_bottom">
					<div class="col_full">
						<div id="navigator">
							<?php wp_nav_menu( 'theme_location=header&depth=2' ); ?>

							<form class="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
								<input type="text" id="s" name="s" placeholder="<?php _e( 'search for', 'painter' ); ?>" />
								<button class="fa fa-search"></button>
							</form>

							<div class="clear"></div>
						</div>
					</div>
					<div class="clear"></div>
				</div>

				<?php if( function_exists( 'yoast_breadcrumb' ) ) : ?>
					<div class="row">
						<div class="col_full">
							<div id="breadcrumb">
								<p><?php yoast_breadcrumb(); ?></p>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				<?php endif; ?>