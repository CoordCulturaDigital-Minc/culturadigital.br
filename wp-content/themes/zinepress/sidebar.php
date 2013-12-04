<div id="sidebarbump">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('SideAd') ) : ?><?php endif; ?>
	<div id="sidebar">
		<ul>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
			<li><h2>Arquivos</h2>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>
			<?php endif; ?>
		</ul>
	</div>
</div>

