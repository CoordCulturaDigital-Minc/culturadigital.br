<?php get_header( ); ?>
<div id="postpage" class="sleeve_main">
<div id="main">

<?php 
if( have_posts() ):
	while( have_posts( ) ):
		the_post();
?>

<div <?php post_class('post'); ?> id="post-<?php the_ID( ); ?>">
	<?php prologue_the_title('<h2>','</h2>' ); ?>
	<div class="entry">
		<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
		<?php the_content('<p class="serif">'.__('Read the rest of this page &rarr;', 'p2').'</p>'); ?>

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
<?php
get_footer( );
