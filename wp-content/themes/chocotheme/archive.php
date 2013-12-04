<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<h2 class="pagetitle">
			<?php if (is_category()) { ?>
				Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category
			<?php } elseif( is_tag() ) { ?>
				Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;
			<?php } elseif (is_day()) { ?>
				Archive for <?php the_time('F jS, Y'); ?>
			<?php } elseif (is_month()) { ?>
				Archive for <?php the_time('F, Y'); ?>
			<?php } elseif (is_year()) { ?>
				Archive for <?php the_time('Y'); ?>
			<?php } elseif (is_author()) { ?>
				Author Archive
			<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
				Blog Archives
			<?php } ?>
		</h2>
		<div class="list-page">
			<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class() ?>>
					<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					<div class="date">
						<div class="bg">
							<span class="day"><?php the_time('d') ?></span>
							<span><?php the_time('M') ?></span>
						</div>
					</div>
	
					<div class="entry">
						<?php the_content('Read the rest of this entry &raquo;'); ?>
						<div class="cl">&nbsp;</div>
					</div>
	
					<div class="meta">
						<div class="bg">
							<span class="comments-num"><?php comments_popup_link('No Comments', '1 Comment', '% Comments') ?></span>
							<p>Posted <!-- by <?php the_author_link() ?> --> in <?php the_category(', ') ?></p>
						</div>
						<div class="bot">&nbsp;</div>
					</div>
				</div>
				
			<?php endwhile; ?>
	
			<div class="navigation">
				<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
				<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
			</div>
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
		get_search_form();
		endif;
	?>
	
	<?php get_sidebar(); ?>
<?php get_footer(); ?>