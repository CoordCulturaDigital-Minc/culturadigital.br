<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>

			<h2 class="page-title"><a href="<?php echo get_permalink($post->post_parent) ?>" rev="attachment"><?php printf(__('Attachment : ', 'codium' )) ?><?php echo get_the_title($post->post_parent) ?></a></h2>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h3 class="entry-title"><?php the_title() ?></h3>
				<div class="entry-content">
<p style="text-align: center;"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></p>
<?php the_content(''.__('Read More <span class="meta-nav">&raquo;</span>', 'codium').''); ?>

<?php wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'codium'), "</div>\n", 'number'); ?>
<?php printf(__('Back to post : ', 'codium')) ?><a href="<?php echo get_permalink($post->post_parent) ?>" rev="attachment"><?php echo get_the_title($post->post_parent) ?></a>	
				</div>
<div class="dp100">
<div class="dp50">
<div class="postthumbimg-ds">
<?php previous_image_link('thumbnail') ?>
</div>
</div>
<div class="dp50">
<div class="postthumbimg-dsr">
<?php next_image_link('thumbnail') ?>
</div>
</div>
</div>
<div class="clear"></div>

				<div class="entry-meta">
					<?php codium_posted_on(); ?>
					<?php codium_posted_in(); ?>	
					<?php edit_post_link(__('Edit', 'codium'), "\n\t\t\t\t\t<span class=\"edit-link\">", "</span>"); ?>
				</div>
			</div><!-- .post -->

<?php comments_template(); ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>