<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */

get_header(); ?>

	<td id="content" class="column round-left" role="main">
		<div class="wrapper">
			<div class="section">
				<h2 class="pagetitle">Search Results</h2>
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
										<a target="_blank" href="<?php echo retweet_url(get_permalink(),the_title_attribute('echo=0')) ?>" title="Retweet">Retweet</a>
									</span>
								</li>
							</ul>
						</li>
					<?php endwhile; ?>
					</ol>
				<?php else : ?>
					<h2 class="center">No posts found. Try a different search?</h2>
				<?php endif; ?>
				<div id="pagination">
					<a href="javascript:void(0);" id="more" class="round more">more</a>
				</div>
			</div>
		</div>
	</td>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
