<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */

get_header();
?>

	<?php if (have_posts()) : ?>

	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	<?php /* If this is a category archive */ if (is_category()) { ?>
	<div class="mainTitle"><h3>Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h3></div>
	<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
	<div class="mainTitle"><h3>Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h3></div>
	<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
	<div class="mainTitle"><h3>Archive for <?php the_time('F jS, Y'); ?></h3></div>
	<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
	<div class="mainTitle"><h3>Archive for <?php the_time('F, Y'); ?></h3></div>
	<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
	<div class="mainTitle"><h3>Archive for <?php the_time('Y'); ?></h3></div>
	<?php /* If this is an author archive */ } elseif (is_author()) { ?>
	<div class="mainTitle"><h3>Author Archive</h3></div>
	<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
	<div class="mainTitle"><h3>Blog Archives</h3></div>
	<?php } ?>



		<?php while (have_posts()) : the_post(); ?>
			
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">			
			<div class="entry">
				<h3 class="result"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
			</div>
			<div class="postMeta postCategory">
				<p class="postCategory-title"><strong>Category:</strong></p>
				<p class="postCategory-elements"><?php the_category(', '); ?></p>
			</div>
			<div class="postMeta postTags">
				<p><strong>Tagged with:</strong></p>
				<?php the_tags('<ul><li>','</li><li>','</li></ul>'); ?>
			</div>
			<div class="postMeta postShare">
				<p><strong>Share it:</strong></p>
				<ul>
					<li class="share-Email"><a rel="nofollow" href="mailto:?subject=An%20interesting%20post%20on%<?php bloginfo('name'); ?>&amp;body=Check%20out%20%22<?php the_title(); ?>%22%20from%20<?php bloginfo('name'); ?>: <?php the_permalink(); ?>" title="Send a link to this post by email">Share this post by E-mail</a></li>
					<li class="share-Delicious"><a rel="nofollow" href="http://del.icio.us/post?url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>" title="Bookmark this post on Delicious" target="_blank">Share this post on Delicious</a></li>
					<li class="share-Digg"><a rel="nofollow" href="http://digg.com/submit?phase=2&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" title="Share this post on Digg" target="_blank">Share this post on Digg</a></li>
					<li class="share-Facebook"><a rel="nofollow" href="http://www.facebook.com/share.php?u=<?php the_permalink() ?>" title="Share this post on Facebook" target="_blank">Share this post on Facebook</a></li>
					<li class="share-Myspace"><a rel="nofollow" href="http://www.myspace.com/Modules/PostTo/Pages/?l=3&amp;u=<?php the_permalink() ?>" title="Share this post on Mysace" target="_blank">Share this post on Myspace</a></li>
					<li class="share-Google"><a rel="nofollow" href="http://www.google.co.uk/bookmarks/mark?op=edit&amp;bkmk=<?php the_permalink() ?>" title="Bookmark this post on Google" target="_blank">Share this post on Google</a></li>
					<li class="share-Linkedin"><a rel="nofollow" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>&amp;summary=&amp;source=<?php bloginfo('name'); ?>">Share this post on LinkedIn</a></li>
					<li class="share-Twitter"><a rel="nofollow" href="http://twitter.com/home?status=<?php the_title(); ?>+<?php the_permalink() ?>" title="Share this post on Twitter" target="_blank">Share this post on Twitter</a></li>
					<li class="share-Reddit"><a rel="nofollow" href="http://reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php echo urlencode(get_the_title($id)); ?>" title="Share this post on Reddit" target="_blank">Share this post on Reddit</a></li>
					<li class="share-Stumbleupon"><a rel="nofollow" href="http://www.stumbleupon.com/submit?url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>" title="Share this post on Stumbleupon" target="_blank">Share this post on Stumbleupon</a></li>
					<li class="share-Newsvine"><a rel="nofollow" href="http://www.newsvine.com/_tools/seed&amp;save?u=<?php the_permalink() ?>&amp;h=<?php the_title(); ?>" title="Share this post on Newsvine" target="_blank">Share this post on Newsvine</a></li>
					<li class="share-Technoratti"><a rel="nofollow" href="http://technorati.com/faves?add=<?php the_permalink() ?>" title="Share this post on Technorati" target="_blank">Share this post on Technorati</a></li>
				</ul>
			</div>
		</div>

		<?php endwhile; ?>

	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<div class='mainTitle'><h3>Sorry, but there aren't any posts in the %s category yet.</h3></div>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<div class='mainTitle'><h3>Sorry, but there aren't any posts with this date.</h3></div>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<div class='mainTitle'><h3>Sorry, but there aren't any posts by %s yet.</h3></div>", $userdata->display_name);
		} else {
			echo("<div class='mainTitle'><h3>No posts found.</h3></div>");
		}

	endif;
?>

</div>

<?php get_sidebar(); ?>

<div id="navigation">
	<p id="prevPage"><?php next_posts_link('Previous Posts') ?></p>
	<p id="nextPage"><?php previous_posts_link('Next Posts') ?></p>
</div>

<?php get_footer(); ?>