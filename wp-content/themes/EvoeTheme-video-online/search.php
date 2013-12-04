<?php
	if( mobile_switch( 'search' ) )
	{
		return;
	}
	
	get_header();
?>
            <div class="middle">
                <h1 class="title"><a href="<?php bloginfo('url'); ?>" title="<?php _e('Homepage', 'evoeTheme'); ?>"><span><?php bloginfo('name'); ?></span></a></h1>
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
		            <div class="bigTitle"><div class="previewPattern"></div><h2><?php echo __('Search results', 'evoeTheme'). ' "'; the_search_query() ?>"</h2></div>
		                <ul class="posts">
		                    <?php while (have_posts()) : the_post(); ?>
		                    <li class="post">
		                        <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute() ?>"><?php the_title() ?></a></h3>
		                        <a href="<?php the_permalink() ?>#comment" title="<?php comments_number(__('No comments', 'evoeTheme'),__('1 comment', 'evoeTheme'),__('% comments', 'evoeTheme')); ?>" class="comment"><strong><?php comments_number('0','1','%'); ?></strong> <span><?php comments_number(__('comments', 'evoeTheme'),__('comment', 'evoeTheme'),__('comments', 'evoeTheme')); ?></span></a>
		                        <p class="author"><?php __('by', 'evoeTheme'). ': '; the_author_link(); echo ', '. __('in|category', 'evoeTheme') .' '; the_category(', '); echo ' '. __('on|date', 'evoeTheme') .' '; the_time(__('d/m/Y', 	'evoeTheme')) ?></p>
		                        <div class="postContent">
		                            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" class="thumb"><?php the_thumb($post->ID, 'thumbnail') ?></a>
		                            <p><?php limit_chars($post->post_content, $length = 500); ?></p>
		                            <p class="tags archive"><?php the_tags(__('Tags: ', 'evoeTheme'),', '); ?></p>
		                            <a href="<?php the_permalink() ?>" title="<?php __('Continue reading', 'evoeTheme'); ?>" class="more"><?php _e('Continue reading', 'evoeTheme'); ?></a> 
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
				        <h1><?php _e('Not found', 'evoeTheme'); ?></h1>
				        <p class="bottom_"><?php _e('Please, try different keywords.', 'evoeTheme'); ?></p>
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
