<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
	<div id="searchbox">
		<input type="text" value="Pesquisar..." onfocus="if (this.value == 'Pesquisar...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Pesquisar...';}" name="s" id="s" />
		<input type="image" src="<?php bloginfo('stylesheet_directory'); ?>/images/searchgo.gif" id="searchsubmit" alt="Pesquisar" title="Pesquisar" />
	</div>
</form>
