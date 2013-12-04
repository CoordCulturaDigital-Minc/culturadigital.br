<div id="current-theme">
<?php if ( $themes[$current_theme]['Screenshot'] ) : ?>
<img src="<?php echo $themes[$current_theme]['Theme Root URI'] . '/' . $themes[$current_theme]['Stylesheet'] . '/' . $themes[$current_theme]['Screenshot']; ?>" alt="<?php _e('Current theme preview'); ?>" />
<?php endif; ?>
<h4><?php printf(__('%1$s %2$s by %3$s'), $themes[$current_theme]['Title'], $themes[$current_theme]['Version'], $themes[$current_theme]['Author']) ; ?></h4>
<p class="theme-description"><?php echo $themes[$current_theme]['Description']; ?></p>
<?php if ($themes[$current_theme]['Parent Theme']) { ?>
	<p><?php printf(__('The template files are located in <code>%2$s</code>.  The stylesheet files are located in <code>%3$s</code>.  <strong>%4$s</strong> uses templates from <strong>%5$s</strong>.  Changes made to the templates will affect both themes.'), $themes[$current_theme]['Title'], str_replace( WP_CONTENT_DIR, '', $themes[$current_theme]['Template Dir'] ), str_replace( WP_CONTENT_DIR, '', $themes[$current_theme]['Stylesheet Dir'] ), $themes[$current_theme]['Title'], $themes[$current_theme]['Parent Theme']); ?></p>
<?php } else { ?>
	<p><?php printf(__('All of this theme&#8217;s files are located in <code>%2$s</code>.'), $themes[$current_theme]['Title'], str_replace( WP_CONTENT_DIR, '', $themes[$current_theme]['Template Dir'] ), str_replace( WP_CONTENT_DIR, '', $themes[$current_theme]['Stylesheet Dir'] ) ); ?></p>
<?php } ?>
<?php if ( $themes[$current_theme]['Tags'] ) : ?>
<p><?php _e('Tags:'); ?> <?php echo join(', ', $themes[$current_theme]['Tags']); ?></p>
<?php endif; ?>
</div>
<div class="clear"></div>