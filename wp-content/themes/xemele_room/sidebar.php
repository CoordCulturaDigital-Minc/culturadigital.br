	<div id="sidebar">
	
		<ul>
			<li class="nobackground">
				<?php get_search_form(); ?>
			</li>
					
			<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.  -->
			<!--<li><h2>Twitter</h2>
            <p><img class="alignleft" src="<?php bloginfo('template_url'); ?>/images/about.jpg" alt="About Me" />Siga o nosso twitter e saiba em primeira m&atilde;o as novidades do Minist&eacute;rio da Cultura: <small><a href="http://www.twitter.com/CulturaGovBr">http://www.twitter.com/CulturaGovBr</a></small></p>
			</li>
			
			<li><h2>Not&iacute;cias rand&ocirc;micas</h2></li>
			<?php include (TEMPLATEPATH . '/featlist.php'); ?>
			

			<li class="ads clearfix"><h2>Nossos Parceiros</h2>
				<a class="ad-left" href="#"><img src="<?php bloginfo('template_url'); ?>/images/ad.jpg" alt="" /></a>
				<a class="ad-right" href="#"><img src="<?php bloginfo('template_url'); ?>/images/ad.jpg" alt="" /></a>
			</li> -->
			
	
			
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
  
			<?php wp_list_categories('show_count=1&title_li=<h2>Categorias</h2>'); ?>
            
            <li><h2>Últimos Posts</h2>
                <ul>
                    <?php get_archives('postbypost', 10); ?>
                </ul>
            </li>

			<li><h2>Arquivos</h2>
				<ul>
				    <?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

			<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
				<?php wp_list_bookmarks(); ?>

				<li><h2>Meta</h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
					<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
					<?php wp_meta(); ?>
				</ul>
				</li>
			<?php } ?>

			<?php endif; ?>	

		</ul>
	</div>

