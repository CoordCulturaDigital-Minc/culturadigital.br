<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */
if(isset($_GET['retweet_url']) && isset($_GET['post_id'])) {
	$c = get_post_meta($_GET['post_id'], 'retweet_count',true);
	if($c=='') $c = 0;
	update_post_meta($_GET['post_id'], 'retweet_count', ($c+1));
	echo "<span style='font-weight:bold;background:#f6f6f6;border:1px solid #efefef;padding:2px 8px;'>Redirecting...</span>";
	echo "<script>window.location = '" . $_GET['retweet_url'] ."'</script>";
}
get_header(); ?>
	<?php global $t; ?>
	<td id="content" class="column round-left" role="main">
		<div class="wrapper">
			<?php if(is_user_logged_in()) : ?>
				<div id="status_update_box">
					<form id="status_update_form" class="status-update-form" method="post" action="./">
						<div style="margin:0;padding:0;"></div>
						<fieldset class="common-form standard-form">
							<div class="bar">
								<h3>
									<label class="doing" for="status">What's happening?</label>
								</h3>
								<span id="chars_left_notice" class="numeric">
									<strong id="status-field-char-counter" class="char-counter">140</strong>
								</span>
							</div>
							<div class="info">
								<textarea id="status" tabindex="1" autocomplete="off" accesskey="u" name="status"></textarea>
								<div class="status-btn">
									<input id="update-submit" class="status-btn round-btn" type="submit" tabindex="2" value="update" name="update" />
								</div>
								<div id="update_notifications">
									<strong>Latest:</strong> <span id="latest-status"><?php echo urldecode(urlencode($t->status->text)); ?></span>
								</div>
								<div class="clear"></div>
							</div>
						</fieldset>
					</form>
				</div>
			<?php else : ?>
				<div id="status_<?php echo $t->status->id; ?>" class="latest-status">
					<span class="status-body">
						<?php $replyto = $t->status->in_reply_to_screen_name; ?>
						<?php $retweeted = $t->status->retweeted_status->source; ?>
						<span class="entry-content">
							<?php format_status($t->status->text); ?>
						</span>
						<span class="meta entry-meta">
							<?php echo format_time_ago($t->status->created_at) . ' from ' . (($retweeted) ? $retweeted : $t->status->source); ?>
							<?php if($replyto!='') echo "<a href='http://twitter.com/$replyto/status/".$t->status->in_reply_to_status_id."'>in reply to $replyto</a>"; ?>
						</span>
					</span>
				</div>
			<?php endif; ?>
			
			<div class="section">
				<div id="timeline_heading">
					<h1 id="heading">Home</h1>
				</div>
				<?php if (have_posts()) : ?>
					<ol id="timeline" class="posts">
					<?php while (have_posts()) : the_post(); ?>
						<li id="post-<?php the_ID(); ?>" <?php post_class() ?>>
							<span class="post-body">
								<strong><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></strong>
								<span class="entry-content"><?php echo format_excerpt(get_the_excerpt()); ?></span>
								<span class="meta entry-meta"><?php the_time('g:i A M jS Y') ?> from web</span>
							</span>
							<ul class="actions-hover">
								<li>
									<span class="reply">
										<span class="reply-icon icon"></span>
										<a title="read <?php the_title_attribute(); ?>" href="<?php the_permalink() ?>">Read</a>
									</span>
								</li>
								<li>
									<span class="retweet-link">
										<span class="retweet-icon icon"></span>
										<a target="_blank" href="?retweet_url=<?php echo retweet_url(get_permalink(),the_title_attribute('echo=0')); ?>&post_id=<?php echo $post->ID; ?>" title="Retweet">Retweet</a>
									</span>
								</li>
							</ul>
						</li>
					<?php endwhile; ?>
					</ol>
				<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>
				
				<?php endif; ?>
				<div id="pagination">
					<a href="javascript:void(0);" id="more" class="round more">more</a>
				</div>
			</div>
		</div>
	</td>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
