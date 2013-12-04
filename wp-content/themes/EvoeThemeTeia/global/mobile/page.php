<?php get_mobile_header(); ?>
    <body <?php body_class() ?>>
        <div id="general">
            <div id="header">
            	<div class="nav">
                </div>
                <h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
				<form id="searchform" action="<?php bloginfo('url'); ?>" method="get">
                	<ul>
                    	<li><input type="text" name="s" id="s" /></li>
                        <li><button type="submit" class="searchsubmit"><?php _e('Search'); ?></button></li>
                    </ul>
                </form>
            </div>
            
            <div id="content">
                <ul class="tabs">
                	<li class="active"><a href="">Últimos posts</a></li>
                    <li><a href="">Mais lidos</a></li>
                </ul>
                <ul class="posts">
					<?php
                        if( have_posts() ) :
                        while( have_posts() ) : 
                        the_post();
                    ?>
                	<li>
                    	<?php the_category(', '); ?>
                        <h2><a href="<?php the_permalink() ?>"><?php limit_chars(get_the_title(), $length = 50); ?></h2>
                    </li>
					<?php endwhile; ?>
                </ul>
                <?php
					endif;
				?>
            </div>
            
            <?php get_mobile_footer(); ?>
        </div>
    </body>
</html>
