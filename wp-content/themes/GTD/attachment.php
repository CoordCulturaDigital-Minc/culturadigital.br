<?php get_header( ); ?>
<div id="postpage">
	<div class="sleeve_main">
<div id="main">

<?php 
if( have_posts() ):
	while( have_posts( ) ):
		the_post();
?>

<div <?php post_class('post'); ?> id="post-<?php the_ID( ); ?>">
	<h2><?php the_title( ); ?></h2>
	<div class="entry">
		<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
		
		<p><?php echo wp_get_attachment_image( $post->ID, array($content_width - 8, 700)); ?></p>
		
		<?php the_content('<p class="serif">'.__('Read the rest of this page &rarr;', 'p2').'</p>'); ?>
<div class="navigation">
					<div class="alignleft"><?php previous_image_link() ?></div>
					<div class="alignright"><?php next_image_link() ?></div>
				</div>
				
		<?php if ( comments_open() ) comments_template(); ?>

		<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:', 'p2').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

	</div> <!-- // entry -->
</div> <!-- post-<?php the_ID( ); ?> -->

<?php
	endwhile; // have_posts

endif; // have_posts
?>

	</ul>
</div> <!-- // main -->
</div>
</div>
<?php
get_footer( );
