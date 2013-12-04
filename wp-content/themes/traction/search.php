<?php // Setup read more url variable
	$template_url = get_bloginfo( 'template_url' );
	$read_more = "<img src=\"$template_url/images/entry-more.png\" alt=\"Read more\"/>";
?>
<?php get_header(); ?>
	<div id="main-top">
		<h4><?php printf(__ ("Search results for '%s'", "traction"), attribute_escape(get_search_query())); ?></h4>
		<?php if (is_file(STYLESHEETPATH . '/subscribe.php' )) include(STYLESHEETPATH . '/subscribe.php' ); else include(TEMPLATEPATH . '/subscribe.php' ); ?>
	</div>
	<div id="main" class="clear">
		<div id="content">
			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail( 'index-thumb', array( 'class' => 'index-post-thm alignleft border' ) ); ?></a>
					<div class="entry <?php if ( !has_post_thumbnail() ) echo 'nothumb'; ?>">
						<h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<?php the_content(__('Read more', 'traction') . $read_more); ?>
						<?php edit_post_link(__( 'Edit', 'traction' )); ?>
					</div><!--end entry-->
				</div><!--end post-->
			<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
				<div class="navigation index">
					<?php if (function_exists( 'wp_pagenavi' )) : wp_pagenavi(); ?>
					<?php else : ?>
						<div class="alignleft"><?php next_posts_link(__ ( '&laquo; Older Entries', 'traction' )); ?></div>
						<div class="alignright"><?php previous_posts_link(__ ( 'Newer Entries &raquo;', 'traction' )); ?></div>
					<?php endif; ?>
				</div><!--end navigation-->
			<?php else : ?>
				<div class="entry single">
					<p><?php printf(__ ( 'Sorry your search for "%s" did not turn up any results. Please try again.', 'traction' ), attribute_escape(get_search_query())); ?></p>
					<?php if (is_file(STYLESHEETPATH . '/searchform.php' )) include (STYLESHEETPATH . '/searchform.php' ); else include(TEMPLATEPATH . '/searchform.php' ); ?>
				</div>
			<?php endif; ?>
		</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>