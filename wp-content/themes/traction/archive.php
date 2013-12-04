<?php // Setup read more url variable
	$template_url = get_bloginfo( 'template_url' );
	$read_more = "<img src=\"$template_url/images/entry-more.png\" alt=\"Read more\"/>";
?>
<?php get_header(); ?>
	<div id="main-top">
		<?php /* If this is a category archive */ if (is_category()) { ?>
			<h4><?php printf(__ ( 'Posts from the	&#8216;%s&#8217; Category', 'traction' ), single_cat_title('', false)); ?></h4>
		<?php /* If this is a tag archive */ } elseif ( is_tag() ) { ?>
			<h4><?php printf(__ ( 'Posts tagged &#8216;%s&#8217;', 'traction' ), single_tag_title('', false)); ?></h4>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h4><?php _e( 'Archive for', 'traction' ); ?> <?php the_time(__ ( 'F jS, Y', 'traction' )); ?></h4>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h4><?php _e( 'Archive for', 'traction' ); ?> <?php the_time(__ ( 'F, Y', 'traction' )); ?></h4>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h4><?php _e( 'Archive for', 'traction' ); ?> <?php the_time(__ ( 'Y', 'traction' )); ?></h4>
		<?php /* If this is an author archive */ } elseif (is_author()) { if (isset($_GET['author_name'])) $current_author = get_userdatabylogin($author_name); else $current_author = get_userdata(intval($author));?>
			<h4><?php printf(__ ( 'Posts by %s', 'traction' ), $current_author->nickname); ?></h4>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h4><?php _e( 'Blog Archives', 'traction' ); ?></h4>
		<?php } ?>
		<?php if (is_file(STYLESHEETPATH . '/subscribe.php' )) include(STYLESHEETPATH . '/subscribe.php' ); else include(TEMPLATEPATH . '/subscribe.php' ); ?>
	</div>
	<div id="main" class="clear">
		<div id="content">
			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
					<div class="date">
						<div class="day"><?php the_time(__( 'j' )); ?></div>
						<div class="month"><?php the_time(__ ( 'M', 'traction' )); ?></div>
					</div>
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
			<?php endif; ?>
		</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>