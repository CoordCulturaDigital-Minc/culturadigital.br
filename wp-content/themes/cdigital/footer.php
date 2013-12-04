
</div>

<div class="realizacaoParceiros"><img src="<?php bloginfo('template_directory'); ?>/img/realizacao-parceiros-apoio.png" alt="" /></div>


<div id="footer">
    <div class="middle">
        <ul class="menu">
            <li><a href="<?php bloginfo('url'); ?>">Início</a></li>
            <?php // wp_list_pages( 'title_li=&depth=1&include=1896,1919,1921' ); ?>
			
			<li><a href="/termos-de-uso/">Termo de Uso</a></li>
			<li><a href="/quem-faz-2/">Quem Faz</a></li>
			<li><a href="/parceria-mincrnp/">Parceria MinC/RNP</a></li>
			<li><a href="/faqs/">FAQs</a></li>			
			
        </ul>

        <p class="text"><a href="http://www.cultura.gov.br/">Ministério da Cultura</a> e <a href="http://www.rnp.br/">RNP</a> - Alguns direitos reservados.</p>

        <p class="links"><a href="http://www.w3.org/" title="www.w3.org"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/graph_w3c.png" alt="www.w3.org" /></a> <a href="http://creativecommons.org/licenses/by/3.0/br/" title="www.creativecommons.org"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/graph_cc.png" alt="www.creativecommons.org" /></a> <a href="http://www.wordpress.org/" title="www.wordpress.org"><img src="<?php bloginfo('template_directory'); ?>/global/img/graph/graph_wp.png" alt="www.wordpress.org" /></a></p>
        <?php do_action( 'bp_footer' ) ?>
        <div class="clear"></div>
    </div>
</div>

<?php wp_footer(); ?>

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
	try {
		_uacct = "UA-9810322-1";
		urchinTracker();
	} catch(err) {}
</script>

</body>
</html>