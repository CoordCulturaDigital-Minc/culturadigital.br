<?php 
global $more; 
$template_url = get_bloginfo('template_url');
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry_header">
        <p class="entry-date"><?php the_time('M y') ?><br /><span class="date"><?php the_time('j')?></span></p>
        <?php echo (is_home()) ? '<h2 class="home">' : '<h1>'?><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> <?php edit_post_link('Edit', '<span class="editpost">', '</span>'); ?><?php echo (is_home()) ? '</h2>' : '</h1>'?>
        <?php if (FALSE && $post->comment_status != 'closed'):?>
        	<div class="comment-bubble"><span><?php comments_popup_link('<span class="nocomment">No Comment</span>', '&nbsp;1 Comment', '% Comments'); ?></span></div>
        <?php endif;?>
        <div class="recover"></div>
    </div>

				
    <div class="entry_content">
        <?php $more = 0;
		the_content('<br /><br /><span class="readmore-icon">Read the rest of '. get_the_title('', '', false). '</span>', FALSE);
		wp_link_pages();
		?>
        
        <div class="postedinfo"><?php the_tags('<span class="tag-meta">Tags: ', ', ', '</span><br />'); ?> <?php _e('Posted in')?> <span class="categories"><?php the_category(' ') ?></span> <?php _e('by')?> <span class="usr-meta"><?php the_author() ?></span>.
        <span class="comment-icon"><?php comments_popup_link('No Comments', '1 Comment', '% Comments')?></span>
        <?php if (isset($options['tags'])) : ?><span class="tags"><?php the_tags('', ', ', ''); ?></span><?php endif; ?></div>
    </div>
</div>