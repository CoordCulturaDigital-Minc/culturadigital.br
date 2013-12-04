<?php
	if( mobile_switch( 'archive' ) )
	{
		return;
	}
	
	get_header();
?>
            <div class="middle">
                <h1 class="title"><a href="<?php bloginfo('url'); ?>" title="<?php _e('Homepage','evoeTheme'); ?>"><span><?php bloginfo('name'); ?></span></a></h1>
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
                        <h2><span></span><?php printf( __('Category %s posts', 'evoeTheme'), single_cat_title()) ?></h2>
                    </div>
		        <?php } elseif(is_tag()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php printf( __('Posts tagged as %s', 'evoeTheme'), single_tag_title()) ?></h2>
                    </div>
		        <?php } elseif(is_day()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php echo __('Posts from', 'evoeTheme') .' '; the_time(__('d, m, Y', 'evoeTheme')) ?></h2>
                    </div>
					<?php } elseif(is_month()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php echo __('Posts from', 'evoeTheme') .' '; the_time(__('F, Y', 'evoeTheme')) ?></h2>
                    </div>
		        <?php } elseif(is_year()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php echo __('Posts from', 'evoeTheme') .' '; the_time(__('Y', 'evoeTheme')) ?></h2>
                    </div>
		        <?php } elseif(is_author()){ ?>
                	<div class="bigTitle">
                        <div class="previewPattern"></div>
                        <h2><span></span><?php printf( __('%s&#8217;s posts', 'evoeTheme'), the_author_link()) ?></h2>
                    </div>
		        <?php } ?>
		                <ul class="posts">
		                    <?php while (have_posts()) : the_post(); ?>
		                    <li class="post">
		                        <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute() ?>"><?php the_title() ?></a></h3>
		                        <a href="<?php the_permalink() ?>#comment" title="<?php comments_number(__('No comments', 'evoeTheme'),__('1 comment', 'evoeTheme'),__('% comments', 'evoeTheme')); ?>" class="comment"><strong><?php comments_number('0','1','%'); ?></strong> <span><?php comments_number(__('comments', 'evoeTheme'),__('comment', 'evoeTheme'),__('comments', 'evoeTheme')); ?></span></a>
		                        <p class="author"><?php echo __('by', 'evoeTheme') .': '; the_author_link(); echo ', '. __('in|category', 'evoeTheme') .' '; the_category(', '); echo ' '. __('on|date', 'evoeTheme') .' '; the_time(__('d/m/Y', 'evoeTheme')) ?></p>
		                        <div class="postContent">
		                            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" class="thumb"><?php the_thumb($post->ID, 'thumbnail') ?></a>
		                            <p><?php limit_chars($post->post_content, $length = 500); ?></p>
		                            <p class="tags archive"><?php the_tags(__('Tags: ', 'evoeTheme'),', '); ?></p>
		                            <a href="<?php the_permalink() ?>" title="<?php _e('Continue reading', 'evoeTheme'); ?>" class="more"><?php _e('Continue reading', 'evoeTheme'); ?></a> 
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
				        <h1><?php _e('Not found', 'evoeTheme'); ?></h1>
				        <p><?php _e('Please, try different keywords.', 'evoeTheme'); ?>.</p>
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
