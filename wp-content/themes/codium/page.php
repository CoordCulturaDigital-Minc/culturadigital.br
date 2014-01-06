<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<div class="linebreak clear"></div>
				<div class="entry-content">
<?php the_content() ?>

<?php wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'codium'), "</div>\n", 'number'); ?>

<div class="clear"></div>
<?php edit_post_link(__('Edit', 'codium'),'<span class="edit-link">','</span>') ?>

				</div>
			</div><!-- .post -->

<?php comments_template(); ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>