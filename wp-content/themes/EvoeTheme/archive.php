<?php
	if( mobile_switch( 'archive' ) )
	{
		return;
	}
	
	get_header();
?>
            <div class="middle">
                <h1 class="title"><a href="<?php bloginfo('url'); ?>" title="<?php _e('Página Inicial','evoeTheme'); ?>"><span><?php bloginfo('name'); ?></span></a></h1>
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
		        <?php if(is_category()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php echo __('Posts da categoria', 'evoeTheme') ." '"; single_cat_title() ?>'</h2>
                    </div>
		        <?php } elseif(is_tag()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php echo __('Posts taggeados', 'evoeTheme') ." '"; single_tag_title() ?>'</h2>
                    </div>
		        <?php } elseif(is_day()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php echo __('Posts de', 'evoeTheme') .' '; the_time('d, m, Y') ?></h2>
                    </div>
					<?php } elseif(is_month()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php echo __('Posts de', 'evoeTheme') .' '; the_time('F, Y') ?></h2>
                    </div>
		        <?php } elseif(is_year()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php echo __('Posts de', 'evoeTheme') .' '; the_time('Y') ?></h2>
                    </div>
		        <?php } elseif(is_author()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php echo __('Posts de', 'evoeTheme') .' '; the_author_link() ?></h2>
                    </div>
		        <?php } ?>
		                <ul class="posts">
		                    <?php while (have_posts()) : the_post(); ?>
		                    <li class="post">
		                        <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute() ?>"><?php the_title() ?></a></h3>
		                        <a href="<?php the_permalink() ?>#comment" title="<?php comments_number(__('Nenhum comentário', 'evoeTheme'),__('1 comentário', 'evoeTheme'),__('% comentários', 'evoeTheme')); ?>" class="comment"><strong><?php comments_number('0','1','%'); ?></strong> <span><?php comments_number(__('comentários', 'evoeTheme'),__('comentário', 'evoeTheme'),__('comentários', 'evoeTheme')); ?></span></a>
		                        <p class="author"><?php echo __('por', 'evoeTheme') .': '; the_author_link(); echo ', '. __('em', 'evoeTheme') .' '; the_category(', '); echo ' '. __('no dia', 'evoeTheme') .' '; the_time('d/m/Y') ?></p>
		                        <div class="postContent">
		                            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" class="thumb"><?php the_thumb($post->ID, 'thumbnail') ?></a>
		                            <p><?php limit_chars($post->post_content, $length = 500); ?></p>
		                            <p class="tags archive"><?php the_tags('Tags: ',', '); ?></p>
		                            <a href="<?php the_permalink() ?>" title="<?php _e('Continuar lendo', 'evoeTheme'); ?>" class="more"><?php _e('Continuar lendo', 'evoeTheme'); ?></a> 
		                        </div>
		                    </li>
		                    <?php endwhile ?>
		                </ul>
                        <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
		                <?php get_footer(); ?>
		            </div>
		        </div>
		    </div>
		</div>
		    <?php else : ?>
		    <div class="wrapper">
		        <div class="outer">
			    <div class="inner">
				    <div class="posts bg">
				        <h1><?php _e('Não Encontrado', 'evoeTheme'); ?></h1>
				        <p><?php _e('Tente novamente com outros termos', 'evoeTheme'); ?>.</p>
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
