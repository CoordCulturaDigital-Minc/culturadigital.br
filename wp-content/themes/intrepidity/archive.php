<?php get_header(); ?>
<div class="page">
		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h1 class="catheader catcenter"><?php _e('Archive for the')?> &#8216;<?php single_cat_title(); ?>&#8217; <?php _e('Category')?></h1>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h1 class="catheader catcenter"><?php _e('Posts Tagged')?> &#8216;<?php single_tag_title(); ?>&#8217;</h1>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h1 class="catheader catcenter"><?php _e('Archive for')?> <?php the_time('F jS, Y'); ?></h1>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h1 class="catheader catcenter"><?php _e('Archive for')?> <?php the_time('F, Y'); ?></h1>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h1 class="catheader catcenter"><?php _e('Archive for')?> <?php the_time('Y'); ?></h1>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h1 class="catheader catcenter"><?php _e('Author Archive')?></h1>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1 class="catheader catcenter"><?php _e('Blog Archives')?></h1>
 	  <?php } ?>

		<?php while (have_posts()) : the_post(); ?>
		<div class="posts">
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

				<div class="page-content">
                	<small><?php the_time(get_option('date_format')) ?></small><br />
					<?php the_excerpt(); ?>
				</div>

				<p class="meta"><span class="tag-icon"><?php the_tags('Tags: ', ', ', '<br />'); ?></span> <?php _e('Posted in')?> <span class="categories"><?php the_category(', ') ?></span> <span class="editpost"><?php edit_post_link('Edit', '', ' '); ?></span> <span class="comment-icon"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></span></p>

			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries') ?></div>
		</div>
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
</div>
<?php get_footer(); ?>