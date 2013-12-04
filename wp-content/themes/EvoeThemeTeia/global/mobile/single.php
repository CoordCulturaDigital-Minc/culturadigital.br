<?php get_mobile_header(); ?>
    <body <?php body_class() ?>>
        <div id="general">
            <div id="header">
            	<div class="nav mid">
                	<a href="#menu" title="Menu">Menu</a>
                </div>
                <h1 class="tit mid"><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
				<form class="mid" action="<?php bloginfo('url'); ?>" method="get">
                	<ul>
                    	<li><input type="text" name="s" id="s" /></li>
                        <li><button type="submit" class="searchsubmit"><?php _e('Search'); ?></button></li>
                    </ul>
                    <div class="clear"></div>
                </form>
            </div>
            
            <div id="content" class="mid">
                <ul class="posts">
					<?php
                        if( have_posts() ) :
                        while( have_posts() ) : 
                        the_post();
                    ?>
                	<li class="first">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    	<div class="cat">
							<?php the_category(', '); ?>
                        </div>
                        <div class="postContent">
                            <?php the_content(); ?>
                        </div>
                        <?php comments_template('/comments.php', true); ?>
                        <div class="clear"></div>
                    </li>
					<?php endwhile; ?>
					<?php else : ?>
                    <li>
                    	<h2><a href="">Nenhum post encontrado!</a></h2>
                        <div class="clear"></div>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="clear"></div>
            </div>
            
            <?php get_mobile_footer(); ?>
        </div>
    </body>
</html>
