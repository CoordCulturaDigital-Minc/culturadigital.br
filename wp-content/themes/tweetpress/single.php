<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */

get_header();
?>
	<td id="content" class="column round-left" role="main">
		<div class="wrapper">
			<div class="section">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<h2 class="pagetitle"><?php the_title(); ?></h2>
			<span class="timestamp"><?php echo format_time_ago(get_the_time('g:i A M jS Y')); ?> from web <?php the_tags('under ', ', ', ''); ?></span>
			<div class="entry-meta">
					<span class="alignleft">
						<!--<span><?php the_tags( '<p>Tags: ', ', ', '</p>'); ?></span>-->
						<?php $rtcnt = get_post_meta($post->ID,'retweet_count',true); if($rtcnt=='')$rtcnt=0; ?>
						<span>Retweeted by <?php echo $rtcnt; ?> people</span>
					</span>
					<ul class="actions-hover">
						<li>
							<span class="reply">
								<span class="reply-icon icon"></span>
								<a title="reply to <?php the_title_attribute(); ?>" href="javascript:void(0);">Reply</a>
							</span>
						</li>
						<li>
							<span class="retweet-link">
								<span class="retweet-icon icon"></span>
								<a target="_blank" href="<?php bloginfo('home'); ?>?retweet_url=<?php echo retweet_url(get_permalink(),the_title_attribute('echo=0')); ?>&post_id=<?php echo $post->ID; ?>" title="Retweet">Retweet</a>
							</span>
						</li>
					</ul>
					<div class="clear"></div>
				</div>
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				
				
				
				<!--
				<p class="postmetadata alt">
					<small>
						This entry was posted
						on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
						and is filed under <?php the_category(', ') ?>.
						You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.

						<?php if ( comments_open() && pings_open() ) {
							?>
							You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

						<?php } elseif ( !comments_open() && pings_open() ) {
							 ?>
							Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

						<?php } elseif ( comments_open() && !pings_open() ) {
							 ?>
							You can skip to the end and leave a response. Pinging is currently not allowed.

						<?php } elseif ( !comments_open() && !pings_open() ) {
							 ?>
							Both comments and pings are currently closed.

						<?php } edit_post_link('Edit this entry','','.'); ?>

					</small>
				</p>
				-->

			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>
			</div>
		</div>
	</td>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
