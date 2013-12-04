<div class="subscribe">
	<?php if ($traction->followDisable() != 'true' ) { ?>
		<h5><?php _e( 'Subscribe', 'traction' ); ?></h5>
		<ul>
			<?php if ($traction->facebookToggle() == 'true' ) { ?>
				<li>
					<a href="<?php echo $traction->facebook(); ?>"><img src="<?php bloginfo( 'template_url' ); ?>/images/flw-facebook.png" alt="<?php _e( 'Facebook', 'traction' ); ?>"/></a>
				</li>
			<?php } ?>
			<?php if ($traction->flickrToggle() == 'true' ) { ?>
				<li>
					<a href="<?php echo $traction->flickr(); ?>"><img src="<?php bloginfo( 'template_url' ); ?>/images/flw-flickr.png" alt="<?php _e( 'Flickr', 'traction' ); ?>"/></a>
				</li>
			<?php } ?>
			<?php if ($traction->twitterToggle() == 'true' ) { ?>
				<li>
				 <a href="http://twitter.com/<?php echo $traction->twitter(); ?>"><img src="<?php bloginfo( 'template_url' ); ?>/images/flw-twitter.png" alt="<?php _e( 'Twitter', 'traction' ); ?>"/></a>
				</li>
			<?php } ?>
			<li>
				<a href="<?php bloginfo( 'rss2_url' ); ?>"><img src="<?php bloginfo( 'template_url' ); ?>/images/flw-rss.png" alt="<?php _e( 'RSS Feed', 'traction' ); ?>"/></a>
			</li>
		</ul>
	<?php } ?>
</div>