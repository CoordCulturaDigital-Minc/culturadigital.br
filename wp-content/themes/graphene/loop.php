<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class('clearfix post'); ?>>
            
            	<?php /* Posts navigation for single post pages, but not for Page post */ ?>
                <?php if (is_single() && !is_page()) : ?>
            	<div class="post-nav clearfix">
                    <p id="previous"><?php previous_post_link() ?></p>
                    <p id="next-post"><?php next_post_link() ?></p>
                    <?php do_action('graphene_post_nav'); ?>
                </div>
                <?php endif; ?>
                
                <?php /* Post date is not shown if this is a Page post */ ?>
                <?php if (!is_page() && (get_option('graphene_hide_post_date') != true)) : ?>
                <div class="date">
                    <p><?php the_time('M'); ?><br /><span><?php the_time('d') ?></span></p>
                    <?php do_action('graphene_post_date'); ?>
                </div>
                <?php endif; ?>
                
                <div class="entry clearfix<?php if (get_option('graphene_hide_post_date') == true) {echo ' nodate';} ?>">
                
                	<?php /* Post title */ ?>
                    <h2>
                    	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(esc_attr__('Permalink Link to %s', 'graphene'), the_title_attribute('echo=0')); ?>"><?php if (get_the_title() == '') {_e('(No title)','graphene');} else {the_title();} ?></a>
                    <?php do_action('graphene_post_title'); ?>
                    </h2>
                    
					<?php /* Post meta */ ?>
                    <div class="post-meta clearfix">
                    	
						<?php /* Post category, not shown if this is a Page post or if admin decides to hide it */ ?>
                        <?php if (!is_page() && (get_option('graphene_hide_post_cat') != true)) : ?>
                        <ul class="meta_categories">
                            <li><?php the_category(",</li>\n<li>") ?></li>
                        </ul>
                        <?php endif; ?>
                        
                        <?php /* Post author, not not shown if this is a Page post or if admin decides to hide it */ ?>
                        <?php if (get_option('graphene_hide_post_author') != true) : ?>
                        <p class="post-author">
							<?php
								if (!is_page()) {
									/* translators: this is for the author byline, such as 'by John Doe' */
									_e('by','graphene'); echo ' '; the_author_posts_link();
								}
								edit_post_link(__('Edit post','graphene'), ' (', ')');
								
								/* Show the post author's gravatar if enabled */
								if (get_option('graphene_show_post_avatar')) {
									echo get_avatar(get_the_author_meta('user_email'), 40);
								}
							?>
                        </p>
                        <?php endif; ?>
                        
                        <?php do_action('graphene_post_meta'); ?>
                    </div>
                    
                    <?php /* Post content */ ?>
                    <div class="entry-content clearfix">
                    	<?php do_action('graphene_before_post_content'); ?>
                        
                    	<?php if (!is_search() && !is_archive()) : ?>
                        <?php the_content(__('Read the rest of this entry &raquo;','graphene')); ?>
                        <?php else : ?>
                        	<?php the_excerpt(); ?>
                        <?php endif; ?>
                        <?php wp_link_pages(array('before' => __('<p><strong>Pages:</strong> ','graphene'), 'after' => '</p>', 'next_or_number' => 'number')); ?>
                        
                        <?php do_action('graphene_after_post_content'); ?>
                        
                    </div>
                    
                    <?php /* Post footer */ ?>
                    <div class="entry-footer clearfix">
                    	<?php /* Display the post's tags, if there is any */ ?>
                        <?php if (!is_page() && (get_option('graphene_hide_post_tags') != true)) : ?>
                        <p class="post-tags"><?php if (has_tag()) {_e('Tags:','graphene'); the_tags(' ', ', ', '');} else {_e('This post has no tag','graphene');} ?></p>
                        <?php endif; ?>
                        
						<?php 
							/**
							 * Display AddThis social sharing button if single post page, comments popup link otherwise.
							 * See the graphene_addthis() function in functions.php
							*/ 
						?>
                        <?php if (is_single() || is_page()) : ?>
                            <?php graphene_addthis(); ?>
                        <?php elseif (get_option('graphene_hide_post_commentcount') != true) : ?>
                        	<p class="comment-link"><?php comments_popup_link(__('Leave comment','graphene'), __('1 comment','graphene'), __("% comments",'graphene')); ?></p>
                        <?php endif; ?>
                        
                        <?php do_action('graphene_post_footer'); ?>
                    </div>
                </div>
            </div>
            
            <?php 
			/**
			 * Display Adsense advertising for single post pages 
			 * See graphene_adsense() function in functions.php
			*/ 
			?>
            <?php if (is_single() || is_page() || get_option('graphene_adsense_show_frontpage')) {graphene_adsense();} ?>
            
            <?php /* Get the comments template for single post pages */ ?>
            <?php if (is_single() || is_page()) {comments_template();} ?>
            
	<?php endwhile; ?>
    
    <?php /* Display posts navigation if this is not a single post page */ ?>
    <?php if (!is_single()) : ?>
        <?php /* Posts navigation. See functions.php for the function definition */ ?>
    	<?php graphene_posts_nav(); ?>
    <?php endif; ?>
    
<?php /* If there is no post, display message and search form */ ?>
<?php else : ?>
    <div class="post">
        <h2><?php _e('Not found','graphene'); ?></h2>
        <div class="entry-content">
            <p>
			<?php 
				if (!is_search())
					_e("Sorry, but you are looking for something that isn't here. Wanna try a search?","graphene"); 
				else
					_e("Sorry, but no results were found for that keyword. Wanna try an alternative keyword search?","graphene"); 
			?>
                
            </p>
            <?php get_search_form(); ?>
        </div>
    </div>
    
    <?php do_action('graphene_not_found'); ?>
<?php endif; ?>