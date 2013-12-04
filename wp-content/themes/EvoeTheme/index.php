<?php
	if( mobile_switch( 'index' ) )
	{
		return;
	}
	
	get_header(); 
?>
            <div class="middle">
                <h1 class="title"><a href="<?php bloginfo('url'); ?>" title="<?php _e('Página Inicial', 'evoeTheme'); ?>"><span><?php bloginfo('name'); ?></span></a></h1>
                <a href="<?php bloginfo('rss2_url'); ?>" title="RSS" class="rss">RSS</a>
            </div>
        </div>
        
        <div id="content">
            <div class="middle">
                <div class="main">
                <!-- INICIO DESTAQUE -->
					<?php
						$hlCategory = get_option('custom_theme');
						$hlCategory = $hlCategory['general']['hlCategory'];
						$query1 = new WP_Query('cat='. $hlCategory .'&showposts=10' );
						$ids = array();
						if ($query1->have_posts() && is_home() and !is_paged()) :
					?>
                    <div class="wrapper">
                    	<div class="outer">
                        	<div class="inner">
                                <div id="mygallery" class="splash bg">
                                    <div class="gallery">
                                        <?php while ($query1->have_posts()) : $query1->the_post(); $ids[] = get_the_ID(); ?>
                                            <div class="panel">
                                                <a href="<?php the_permalink() ?>" class="thumb"><span><?php the_thumb($post->ID, 'medium', 'width="237" height="166"') ?></span></a>
                                                <h2><a href="<?php the_permalink() ?>"><?php limit_chars(get_the_title(), $length = 50); ?></a></h2>
                                                <div class="excerpt">
                                                	<p><?php limit_chars(get_the_excerpt(), $length = 210); ?></p>
                                                </div>
                                            </div>
                                        <?php endwhile ?>
                                    </div>
                                </div>
                        	</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                 <!-- INICIO POSTS -->
                    <?php 
						parse_str($query_string, $query_array);
						$query_array[] = array('post__not_in' => $ids);
						$query2 = new WP_Query($query_array);
						if ($query2->have_posts()) :
					?>
                    <div class="wrapper bot">
                    	<div class="outer">
                        	<div class="inner">
                                <div class="posts bg">
                                    <div class="bigTitle">
                                        <div class="previewPattern"></div>
                                        <h2><span></span><?php _e('Últimos Posts', 'evoeTheme'); ?></h2>
                                    </div>
                                    <ul class="posts">
                                        <?php while ($query2->have_posts()) : $query2->the_post(); ?>
                                        <li id="post-<?php echo $post->ID; ?>" class="post">
                                            <h3><a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute() ?>"><?php the_title() ?></a></h3>
                                            <a href="<?php the_permalink() ?>#comment" title="<?php comments_number(__('Nenhum comentário', 'evoeTheme'),__('1 comentário', 'evoeTheme'),__('% comentários', 'evoeTheme')); ?>" class="comment"><strong><?php comments_number('0','1','%'); ?></strong> <span><?php comments_number(__('comentários', 'evoeTheme'),__('comentário', 'evoeTheme'),__('comentários', 'evoeTheme')); ?></span></a>
                                            <p class="author"><?php echo __('por', 'evoeTheme') .': '; the_author_link(); echo ', '. __('em', 'evoeTheme') .' '; the_category(', '); echo ' '. __('no dia', 'evoeTheme') .' '; the_time('d/m/Y') ?><span class="edit"><?php edit_post_link(__('Editar post', 'evoeTheme'), '( ', ' )'); ?></span></p>
                                            <div class="postContent">
                                                <p><?php the_content(__('Continuar lendo...', 'evoeTheme')) ?></p>
                                                <p class="tags"><?php the_tags('Tags: ',', '); ?></p>
                                            </div>
                                        </li>
                                        <?php endwhile ?>
                                    </ul>
                                    <div class="pagenavi">
                                    	<div class="prev">
											<?php echo get_previous_posts_link( __('&laquo; Anterior', 'evoeTheme') ); ?>
                                        </div>
                                        <div class="next">
											<?php echo get_next_posts_link( __('Próximo &raquo;', 'evoeTheme') ); ?>
                                        </div>
                                    </div>
                               		<?php get_footer(); ?>
                                </div>
                    		</div>
                    	</div>
                    </div>
					<?php else : ?>
                    <div class="wrapper">
                    	<div class="outer">
                        	<div class="inner">
                                <div class="posts bg search">
                                    <h1><?php _e('Não Encontrado', 'evoeTheme'); ?></h1>
                                    <p><?php _e('Desculpe, tente novamente.', 'evoeTheme'); ?></p>
                                    <?php get_footer(); ?>
                                </div>
                    		</div>
                    	</div>
                    </div>
                    <?php endif; ?>
                    
                </div>
                
                <?php get_sidebar(); ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</body>
</html>
