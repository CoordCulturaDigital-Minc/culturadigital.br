<?php 
	if( mobile_switch( '404' ) )
	{
		return;
	}
	
	get_header(); 
?>
            <div class="middle">
                <h1 class="title"><a href="<?php bloginfo('url'); ?>" title="<?php _e('Homepage', 'evoeTheme'); ?>"><span><?php bloginfo('name'); ?></span></a></h1>
                <a href="<?php bloginfo('rss2_url'); ?>" title="RSS" class="rss">RSS</a>
            </div>
        </div>
        
        <div id="content">
            <div class="middle">
                <div class="main">
		<div class="wrapper">
		    <div class="outer">
		        <div class="inner">
		            <div class="posts bg">
                        <h2 class="bigTitle"><?php _e('404 error', 'evoeTheme'); ?></h2>
                        <p><?php _e('Page not found.', 'evoeTheme'); ?></p>
						<?php get_footer(); ?>
		            </div>
		        </div>
		    </div>
		</div>
                </div>
                
                <?php get_sidebar(); ?>
                <div class="clear"></div>
            </div>
        </div>

    </div>
</body>
</html>
