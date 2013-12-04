<?php get_header(); ?>

<div id="content">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="postWrapper">

			<div class="post" id="post-<?php the_ID(); ?>">
				<h1><?php the_title(); ?></h1>

				<div class="entry">
    				<?php the_content('<p class="serif">Leia o resto dessa página &raquo;</p>'); ?>

    				<?php wp_link_pages(array('before' => '<p><strong>Páginas:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				</div>
				
				<!-- META -->
				<div class="postmetadata">
					<p class="meta-date">
						<span class="date-day"><?php the_time('j') ?></span>
						<span class="date-month"><?php the_time('M') ?></span>
						<span class="date-year"><?php the_time('Y') ?></span>
					</p>
					<p class="meta-author">Por <?php the_author() ?></p>
					<?php edit_post_link('<p class="meta-edit">Edit</p>', '', ''); ?>
					<?php comments_number('<p class="meta-comments"><a href="#comments">0 Comentários</a></p>', '<p class="meta-comments"><a href="#comments">1 Comentário &#187;</a></p>', '<p class="meta-comments"><a href="#comments">% Comentários &#187;</a></p>'); ?>
				</div>
            </div>
        </div>

        <?php comments_template('', true); ?>

	<?php endwhile; endif; ?>

</div> <!-- / content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
