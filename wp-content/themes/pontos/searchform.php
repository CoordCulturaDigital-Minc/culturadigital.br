<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div id="search">
<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" class="field" style="width:125px;" />
<input type="image" src="<?php bloginfo('template_url'); ?>/images/loupe.gif" style="width:15px;height:14px;"/>
</div>
</form>