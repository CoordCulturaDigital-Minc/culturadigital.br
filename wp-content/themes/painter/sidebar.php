<div id="sidebar">
	<?php
		// load specific sidebar
		if( is_home() )
		{
			if( !dynamic_sidebar( 'home' ) ) dynamic_sidebar( 'index' );
		}
		elseif( is_single() )
		{
			if( !dynamic_sidebar( 'single' ) ) dynamic_sidebar( 'index' );
		}
		elseif( is_page() )
		{
			if( !dynamic_sidebar( 'page' ) ) dynamic_sidebar( 'index' );
		}
		else
		{
			dynamic_sidebar( 'index' );
		}
	?>
</div>