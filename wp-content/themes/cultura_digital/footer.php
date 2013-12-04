    </div>
	
      <div id="rodape">

        <div class="links centralizar">
          <ul>
            <li><a href="<?php bloginfo('url'); ?>" title="Home">Home</a></li>
            <li><a href="<?php print get_page_link(1919); ?>" title="Quem faz">Quem faz</a></li>
            <li><a href="<?php print get_page_link(1896); ?>" title="FAQs">FAQs</a></li>
            <li><a href="<?php print get_page_link(1921); ?>" title="Termo de uso">Termo de uso</a></li>
            <li><a href="http://xemele.cultura.gov.br/" title="Xemelê" target="_blank">Xemelê</a></li>
          </ul>
          
          <div class="twitter"><a href="http://twitter.com/CulturaGovBr" target="_blank" title="twitter">twitter</a></div>
          <div class="rss"><a href="<?php bloginfo('rss2_url'); ?>" title="RSS">RSS</a></div>
          <div class="top"><a href="#linksHeader" title="Topo">Voltar ao Topo</a></div>
        </div>
        
<!--		<div class="centralizar">
        <div class="box">
          <h2 class="institucional">Institucional</h2>
          <ul>
            <?php wp_list_pages('title_li=&depth=1&child_of=20'); ?>
          </ul>
        </div>
        
        <?php if(function_exists('groups_get_popular')) $groups = groups_get_popular(8, 1); ?>
        <?php if($groups['groups']) : ?>
          <div class="box">
            <h2 class="grupos">Grupos</h2>
            <ul>
              <?php foreach($groups['groups'] as $group) : ?>
                <?php $group = new BP_Groups_Group($group->group_id, false); //print_r($group); ?>
                <li><a href="<?php print bp_group_permalink($group); ?>" title="<?php print $group->name ?>"><?php print $group->name ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        
        <div class="box">
          <h2 class="linksDiretos">Links</h2>
          <ul>
            <?php wp_list_bookmarks('title_li=&categorize=0'); ?>
          </ul>
        </div>
        
        <div class="box">
          <h2 class="buscaRodape">Busca</h2>
          <form action="<?php bloginfo('url'); ?>" method="get">
            <input type="text" name="s" /><button type="submit"></button>
          </form>
          <h3><?php if(function_exists('post_recommend')) post_recommend(); ?></h3>
        </div>
        </div>-->
		
		<div class="centralizar">
        <div class="linha"></div>
        <div class="direitos"><a href="http://www.cultura.gov.br/" title="Ministério da Cultura" target="_blank">Ministério da Cultura</a> e <a href="http://www.rnp.br/" title="Rede Nacional de Ensino e Pesquisa" target="_blank">RNP</a> - Alguns Direitos Reservados</div>
        <div class="w3c"><a href="http://www.w3.org/" title="The World Wide Web Consortium" target="_blank">W3C</a></div>
        <div class="cc"><a href="http://creativecommons.org/licenses/by-nc-sa/2.5/br/" title="Creative Commons" target="_blank">Creative Commons</a></div>
        <div class="wordpress"><a href="http://www.wordpress.org/" title="WordPress">WordPress.org</a></div>
      </div>
	  </div> 
    </div>

    <?php wp_footer(); ?>

	<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
	</script>
	<script type="text/javascript">
	try {
	_uacct = "UA-9810322-1";
	urchinTracker();
	} catch(err) {}</script>
		
  </body>
</html>
