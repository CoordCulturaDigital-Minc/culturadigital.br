<?php
	if( mobile_switch( 'page' ) )
	{
		return;
	}
	
	get_header();
?>
            <div class="middle">
                <h2 class="title"><a href="<?php bloginfo('url'); ?>" title="<?php _e('Homepage', 'evoeTheme'); ?>"><span><?php bloginfo('name'); ?></span></a></h2>
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
                                            <span class="edit"><?php edit_post_link(__('Edit', 'evoeTheme'), '', ' &raquo;'); ?></span>
                                            <p class="author"><?php echo __('by', 'evoeTheme'). ': '; the_author_link(); echo ', '. __('in|category', 'evoeTheme') . ' '; the_category(', '); echo ' '. __('on|date', 'evoeTheme') .' '; the_time(__('d/m/Y', 'evoeTheme')) ?></p>
                                            <div class="postContent">
                                                <p><?php the_content() ?></p>
                                                <p class="tags"><?php the_tags(__('Tags: ', 'evoeTheme'),', '); ?></p>
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
                                    <h1 class="bigTitle"><?php _e('Not found', 'evoeTheme'); ?></h1>
                                    <p><?php _e('Please, try different keywords.', 'evoeTheme'); ?></p>
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
