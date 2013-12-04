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
                <ul class="tabs">
                	<li class="archive">
                    	<a href="">
                        	<?php
                        		if(is_category())
								{
									?>Posts da categoria <?php single_cat_title();
								}
								elseif(is_tag())
								{
									?>Posts taggeados <?php single_tag_title();
								}
								elseif(is_day())
								{
									?>Posts de <?php echo get_the_time('d, m, Y');
								}
								elseif(is_month())
								{
									?>Posts de <?php echo get_the_time('F, Y');
								}
								elseif(is_year())
								{
									?>Posts de <?php echo get_the_time('Y');
								}
								elseif(is_author())
								{
									?>Posts de <?php the_author_link();
								}
							?>
                        </a>
                    </li>
                </ul>
                <ul class="posts">
					<?php
						$cont = 0;
                        if( have_posts() ) :
                        while( have_posts() ) : 
                        the_post();
						$cont++
                    ?>
                	<li <?php echo $cont == 1 ? 'class="first"' : '' ?>>
                    	<div class="cat">
							<?php the_category(', '); ?>
                        </div>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
                <div class="prev">
                    <?php echo get_previous_posts_link( __('&laquo; Previous') ); ?>
                </div>
                <div class="next">
                    <?php echo get_next_posts_link( __('Next &raquo;') ); ?>
                </div>
                <div class="clear"></div>
            </div>
            
            <?php get_mobile_footer(); ?>
        </div>
    </body>
</html>
