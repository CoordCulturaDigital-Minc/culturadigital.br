<form method="get" id="sidebar_search" class="search" action="<?php echo get_option('home'); ?>/" >
	<input type="text" 
	value="<?php echo attribute_escape(apply_filters('the_search_query', get_search_query())) ?>" 
	name="s" 
	id="sidebar_search_q" 
	class="round-left"/>
	<span title="Search" id="sidebar_search_submit" class="submit round-right">&nbsp;</span>
	<!--<input type="submit" id="searchsubmit" value="Search" />-->
</form>