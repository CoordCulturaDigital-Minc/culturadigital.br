<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<!--<div class="post-sep"></div> -->

        <div class="postWrapper">

                   

			<div class="post" id="post-<?php the_ID(); ?>">
				<h1><?php the_title(); ?></h1>

				<div class="entry">
					<?php the_content('Leia mais &raquo;'); ?>
				</div>
				
				<!-- META -->
				<div class="postmetadata">
					<p class="meta-date">
						<span class="date-day"><?php the_time('j') ?></span>
						<span class="date-month"><?php the_time('M') ?></span>
						<span class="date-year"><?php the_time('Y') ?></span>
					</p>
					<p class="meta-author">Por <?php the_author() ?></p>
					<?php edit_post_link('<p class="meta-edit">Editar</p>', '', ''); ?>
					<?php comments_number('<p class="meta-comments"><a href="#comments">0 Comentários</a></p>', '<p class="meta-comments"><a href="#comments">1 Comentário &#187;</a></p>', '<p class="meta-comments"><a href="#comments">% Comentários &#187;</a></p>'); ?>
					<p class="meta-categories"><?php the_category(', ') ?></p>
					<?php the_tags('<p class="meta-tags">',', ','</p>'); ?>
				</div>        
            </div>
        </div>

        <?php comments_template('', true); ?>

	<?php endwhile; else: ?>

	<p>Desculpe, Houve um erro.</p>

<?php endif; ?>

</div> <!-- / content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
