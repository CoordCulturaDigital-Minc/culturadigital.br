			<div class="azulBox">
				
				<img src="<?php bloginfo('stylesheet_directory'); ?>/images/links-extra.png" usemap="#Map" />

				<map name="Map" id="Map"><area shape="rect" coords="13,31,118,67" href="http://culturadigital.br/sobre/" />
				<area shape="rect" coords="13,70,184,108" href="http://culturadigital.br/o-programa/" />
				<area shape="rect" coords="14,111,261,147" href="http://culturadigital.br/blog/2009/09/26/baixe-o-livro-culturadigital-br/" />
				<area shape="rect" coords="14,153,162,186" href="http://culturadigital.br/quem-faz/" />
				<area shape="rect" coords="14,191,207,228" href="http://culturadigital.br/termos-de-uso/" />
				<area shape="rect" coords="14,231,106,267" href="http://culturadigital.br/faqs/" />
				</map>
				
</div>

		<div id="search">
			<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
			<fieldset>
				<input type="text" size="14" value="<?php the_search_query(); ?>" name="s" id="s" />
				<input type="submit" id="searchsubmit" value="Buscar" />
			</fieldset>
			</form>
		</div>
		
		<div class="aduity"><?php mopr_ad('bottom'); ?></div>

		<div id="footerwrap">
			
			<div id="footer">
				
				<p><a href="?nomobile">Ver website em versão normal</a></p>
				
			</div>
			
		</div>
	
	</body>
</html>