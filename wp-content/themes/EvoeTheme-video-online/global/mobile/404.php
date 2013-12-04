<?php get_mobile_header(); ?>
    <body <?php body_class() ?>>
        <div id="general">
            <div id="header">
            	<div class="nav mid">
                	<a href="#menu" title="Menu"><?php _e('Menu'); ?></a>
                </div>
                <h1 class="tit mid"><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
				<form id="searchform" class="mid" action="<?php bloginfo('url'); ?>" method="get">
                	<ul>
                    	<li><input type="text" name="s" id="s" /></li>
                        <li><button type="submit" class="searchsubmit"><?php _e('Search'); ?></button></li>
                    </ul>
                    <div class="clear"></div>
                </form>
            </div>
            <div id="content" class="mid">
                <ul class="posts">
                	<li class="first"><a href="#searchform"><?php _e(404 Error. Page not found!); ?></a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <?php get_mobile_footer(); ?>
        </div>
    </body>
</html>
