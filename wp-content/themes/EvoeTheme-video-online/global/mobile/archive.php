<?php get_mobile_header(); ?>
    <body <?php body_class() ?>>
        <div id="general">
            <div id="header">
            	<div class="nav mid">
                	<a href="#menu" title="Menu"><?php _e('Menu'); ?></a>
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
									?><?php printf( __('Category %s posts', 'evoeTheme'), single_cat_title()) ?>
								<?php }
								elseif(is_tag())
								{
									?><?php printf( __('Posts tagged as %s', 'evoeTheme'), single_tag_title()) ?>
								<?php }
								elseif(is_day())
								{
									?><?php echo __('Posts from', 'evoeTheme') .' '; the_time(__('d, m, Y', 'evoeTheme')) ?>
								<?php }
								elseif(is_month())
								{
									?><?php echo __('Posts from', 'evoeTheme') .' '; the_time(__('F, Y', 'evoeTheme')) ?>
								<?php }
								elseif(is_year())
								{
									?><?php echo __('Posts from', 'evoeTheme') .' '; the_time(__('Y', 'evoeTheme')) ?>
								<?php }
								elseif(is_author())
								{
									?><?php printf( __('%s&#8217;s posts', 'evoeTheme'), the_author_link()) ?>
								<?php }
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
                    	<h2><a href=""><?php _e('No posts found!', 'evoeTheme')?></a></h2>
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
