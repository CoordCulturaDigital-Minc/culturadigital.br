<?php
	if( mobile_switch( 'search' ) )
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
		<?php if (have_posts()) : ?>
		<div class="wrapper">
		    <div class="outer">
		        <div class="inner">
		            <div class="posts bg">
		            <div class="bigTitle"><div class="previewPattern"></div><h2><?php echo __('Resultado de pesquisa', 'evoeTheme'). ' "'; the_search_query() ?>"</h2></div>
		                <ul class="posts">
		                    <?php while (have_posts()) : the_post(); ?>
		                    <li class="post">
		                        <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute() ?>"><?php the_title() ?></a></h3>
		                        <a href="<?php the_permalink() ?>#comment" title="<?php comments_number(__('Nenhum comentário', 'evoeTheme'),__('1 comentário', 'evoeTheme'),__('% comentários', 'evoeTheme')); ?>" class="comment"><strong><?php comments_number('0','1','%'); ?></strong> <span><?php comments_number(__('comentários', 'evoeTheme'),__('comentário', 'evoeTheme'),__('comentários', 'evoeTheme')); ?></span></a>
		                        <p class="author"><?php __('por', 'evoeTheme'). ': '; the_author_link(); echo ', '. __('em', 'evoeTheme') .' '; the_category(', '); echo ' '. __('no dia', 'evoeTheme') .' '; the_time('d/m/Y') ?></p>
		                        <div class="postContent">
		                            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" class="thumb"><?php the_thumb($post->ID, 'thumbnail') ?></a>
		                            <p><?php limit_chars($post->post_content, $length = 500); ?></p>
		                            <p class="tags archive"><?php the_tags('Tags: ',', '); ?></p>
		                            <a href="<?php the_permalink() ?>" title="<?php __('Continuar lendo', 'evoeTheme'); ?>" class="more"><?php _e('Continuar lendo', 'evoeTheme'); ?></a> 
		                        </div>
		                    </li>
		                    <?php endwhile ?>
		                </ul>
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
				        <p class="bottom_"><?php _e('Tente novamente com outros termos.', 'evoeTheme'); ?></p>
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
