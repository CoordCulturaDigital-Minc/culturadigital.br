<?php global $traction; ?>
<div id="sidebar">
	<?php if ($traction->sideboxState() != 'true' ) {?>
		<div id="sidebox">
			<?php if ($traction->sideboxCustom() == 'true' ) : ?>
				<?php echo $traction->sideboxCode(); ?>
			<?php else : ?>
				<a href="/"><img src="<?php bloginfo( 'template_url' ); ?>/images/sidebar/sidebox.jpg" width="250" height="200" alt="Traction WordPress Theme" /></a>
			<?php endif; ?>
		</div><!--end sidebox-->
	<?php } ?>
	<?php if ($traction->adboxState() == 'true' ) { ?>
		<div id="adbox" class="clear">
			<a href="<?php if ($traction->adboxUrl1() != '' ) echo $traction->adboxUrl1(); else echo "#"; ?>"><img class="alignleft" src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/ads/<?php if ($traction->adboxImage1() != '' ) echo $traction->adboxImage1(); else echo "125_ad.png"; ?>" width="125" height="125" alt="<?php if ($traction->adboxAlt1() != '' ) echo $traction->adboxAlt1(); else echo bloginfo( 'name' ); ?>" /></a>
			<a href="<?php if ($traction->adboxUrl2() != '' ) echo $traction->adboxUrl2(); else echo "#"; ?>"><img class="alignright" src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/ads/<?php if ($traction->adboxImage2() != '' ) echo $traction->adboxImage2(); else echo "125_ad.png"; ?>" width="125" height="125" alt="<?php if ($traction->adboxAlt2() != '' ) echo $traction->adboxAlt2(); else echo bloginfo( 'name' ); ?>" /></a>
		</div><!--end adbox-->
	<?php } ?>
	<?php if ($traction->news() == 'true' ) { ?>
		<div id="newsletter">
			<h3><?php if ($traction->newsTitle() != '' ) echo $traction->newsTitle(); else echo _e( 'Sign up for our free newsletter', 'traction' ); ?></h3>
			<form class="newsletter" action="http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $traction->newsName(); ?>" method="post">
				<div>
					<input id="news-email" class="text" name="email" type="text" value="<?php _e( 'Email address', 'traction' ); ?>" onfocus="if (this.value == '<?php _e( 'Email address', 'traction' ); ?>' ) {this.value = '';}" onblur="if (this.value == '' ) {this.value = '<?php _e( 'Email address', 'traction' ); ?>';}"	/>
					<input id="news-button" type="image" src="<?php bloginfo( 'template_url' ); ?>/images/newsletter-go.png" alt="<?php _e( 'Go', 'traction' ); ?>" class="button" value="Go" />
				</div>
			</form>
		</div><!--end newsletter-->
	<?php } ?>
	<?php if ($traction->twitterState() == 'true' ) { ?>
		<div class="widget twitter">
			<h2 class="widgettitle"><?php _e( 'Twitter', 'traction' ); ?> <a href="http://twitter.com/<?php echo $traction->twitter(); ?>"><?php echo $traction->twitter(); ?></a></h2>
			<div class="tweet">
				<?php twitter_messages($traction->twitter(), $traction->twitterUpdates(), true, true, false, true, true, true); ;?>
			</div>
		</div><!--end twitter-->
	<?php } ?>
	<?php if ( is_active_sidebar( 'normal_sidebar' ) ) echo "<ul>" ?>
		<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'normal_sidebar' ) ) : ?>
			<ul>
				<li class="widget widget_recent_entries">
					<h2 class="widgettitle"><?php _e( 'Recent Articles', 'traction' ); ?></h2>
					<ul>
						<?php $side_posts = get_posts( 'numberposts=10' ); foreach($side_posts as $post) : ?>
						<li><a href= "<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</li>
				<li class="widget widget_meta">
					<h2 class="widgettitle"><?php _e( 'Archives', 'traction' ); ?></h2>
					<ul>
						<?php wp_get_archives(); ?>
					</ul>
				</li>
			</ul>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'normal_sidebar' ) ) echo "</ul>" ?>
</div><!--end sidebar-->