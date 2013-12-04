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
				<?php if (have_posts()) : ?>
					<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
					<?php /* If this is a category archive */ if (is_category()) { ?>
					<h2 class="pagetitle">Archive for <?php single_cat_title(); ?></h2>
					<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
					<h2 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
					<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
					<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
					<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
					<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
					<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
					<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
					<?php /* If this is an author archive */ } elseif (is_author()) { ?>
					<h2 class="pagetitle">Author Archive</h2>
					<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
					<h2 class="pagetitle">Blog Archives</h2>
				<?php } ?>
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
	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='center'>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2>Sorry, but there aren't any posts with this date.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='center'>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='center'>No posts found.</h2>");
		}
	endif;
?>
				<div id="pagination">
					<a href="javascript:void(0);" id="more" class="round more">more</a>
				</div>
			</div>
		</div>
	</td>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
