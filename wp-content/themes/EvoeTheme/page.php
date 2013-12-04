<?php
	if( mobile_switch( 'page' ) )
	{
		return;
	}
	
	get_header();
?>
            <div class="middle">
                <h2 class="title"><a href="<?php bloginfo('url'); ?>" title="<?php _e('Página Inicial', 'evoeTheme'); ?>"><span><?php bloginfo('name'); ?></span></a></h2>
                <a href="<?php bloginfo('rss2_url'); ?>" title="RSS" class="rss">RSS</a>
            </div>
        </div>
        
        <div id="content">
            <div class="middle">
                <div class="main">

                    <?php if (have_posts()) : ?>
                    <div class="wrapper bot">
                    	<div class="outer">
                            <div class="inner">
                                <div class="posts bg">
                                    <ul class="posts">
                                        <?php while (have_posts()) : the_post(); ?>
                                        <li class="post">
                                            <h1 class="big"><?php the_title() ?></h1>
                                            <span class="edit"><?php edit_post_link(__('Editar', 'evoeTheme'), '', ' &raquo;'); ?></span>
                                            <p class="author"><?php echo __('por', 'evoeTheme'). ': '; the_author_link(); echo ', '. __('em', 'evoeTheme') . ' '; the_category(', '); echo ' '. __('no dia', 'evoeTheme') .' '; the_time('d/m/Y') ?></p>
                                            <div class="postContent">
                                                <p><?php the_content() ?></p>
                                                <p class="tags"><?php the_tags('Tags: ',', '); ?></p>
                                            </div>
                                        </li>
                                        <?php endwhile ?>
                                    </ul>
				    				<?php comments_template('/comments.php', true); ?>
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
                                    <h1 class="bigTitle"><?php _e('Não Encontrado', 'evoeTheme'); ?></h1>
                                    <p><?php _e('Tente novamente com outros termos.', 'evoeTheme'); ?></p>
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
