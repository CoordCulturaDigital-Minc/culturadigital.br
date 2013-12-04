<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<p class="entry-date"><?php the_time('M y') ?><br /><span class="date"><?php the_time('j')?></span></p>
			<div class="entry_header">
                <h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> <?php edit_post_link('Edit', '<span class="editpost">', '</span>'); ?></h1>
                <?php if ($post->comment_status != 'closed'):?>
                    <div class="comment-bubble"><?php comments_popup_link('<span class="nocomment">Leave a comment &#187;</span>', '1 Comment', '% Comments'); ?></div>
                <?php endif;?>
                <div class="recover"></div>
            </div>
			
			<div class="entry">
				<?php 
				the_content();
				wp_link_pages();
				?>

				<?php the_tags( '<p class="tags">Tags: ', ', ', '</p>'); ?>

				<p class="postmetadata alt">
					<small>
						This entry was posted
						on <?php the_time(get_option('date_format')) ?> at <?php the_time() ?>. You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.

						<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>
							You can <a href="#respond" rel="nofollow">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>
							Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Comments are open, Pings are not ?>
							You can skip to the end and leave a response. Pinging is currently not allowed.

						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Neither Comments, nor Pings are open ?>
							Both comments and pings are currently closed.

						<?php }; ?>

					</small>
				</p>

			<p class="postmetacat"><?php _e('Posted in')?> <span class="categories"><?php the_category(' ') ?></span> <?php _e('by')?> <span class="usr-meta"><?php the_author() ?></span> <span class="comment-icon"><?php comments_popup_link('No Comments Yet', '1 Comment', '% Comments')?></span><?php if (isset($options['tags'])) : ?><span class="tags"><?php the_tags('', ', ', ''); ?></span><?php endif; ?></p>
			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria.')?></p>

<?php endif; ?>

<?php get_footer(); ?>