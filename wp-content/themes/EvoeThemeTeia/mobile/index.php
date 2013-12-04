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
            <?php if( $_GET['popularposts'] != 1 ) : ?>
            <div id="content" class="mid">
                <ul class="tabs">
                	<li class="active"><a href="<?php bloginfo('url'); ?>">Últimos posts</a></li>
                    <li><a href="<?php bloginfo('url'); ?>?popularposts=1">Mais lidos</a></li>
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
            <?php else : ?>
            <div id="content" class="mid">
                <ul class="tabs">
                	<li><a href="<?php bloginfo('url'); ?>">Últimos posts</a></li>
                    <li class="active popularposts"><a href="<?php bloginfo('url'); ?>?popularposts=1">Mais lidos</a></li>
                </ul>
                <ul class="posts">
					<?php
						global $wpdb;
						$cont = 0;
						$result = $wpdb->get_results("SELECT comment_count,ID,post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 , 10");
						foreach ($result as $post) :
							setup_postdata($post);
							$postid = $post->ID;
							$title = $post->post_title;
							$commentcount = $post->comment_count;
							if ($commentcount != 0) : $cont++
                    ?>
                        <li <?php echo $cont == 1 ? 'class="first"' : '' ?>>
                            <div class="cat">
                                <a href="<?php echo get_permalink($postid); ?>"><?php echo $commentcount; ?> Visualizações.</a>
                            </div>
                            <h2><a href="<?php echo get_permalink($postid); ?>"><?php echo $title ?></a></h2>
                            <div class="clear"></div>
                        </li>
					<?php endif; endforeach; ?>
                </ul>
                <div class="clear"></div>
            </div>
            <?php endif; get_mobile_footer(); ?>
        </div>
    </body>
</html>
