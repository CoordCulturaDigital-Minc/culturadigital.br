<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : ?>

        <div id="intro">
            <h2 class="pagetitle">Resultado da pesquisa por <span>'<?php the_search_query(); ?>'</span></h2>
        </div>

		<?php while (have_posts()) : the_post(); ?>

        <div class="postWrapper">

            
			<div class="post" id="post-<?php the_ID(); ?>">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

				<div class="entry">
					<?php the_excerpt(); ?>
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><span class="more">Mais &raquo;</span></a>
				</div>
				
				
				<!-- META -->
				<div class="postmetadata">
					<p class="meta-date">
						<span class="date-day"><?php the_time('j') ?></span>
						<span class="date-month"><?php the_time('M') ?></span>
						<span class="date-year"><?php the_time('Y') ?></span>
					</p>
					<p class="meta-author">by <?php the_author() ?></p>
					<?php edit_post_link('<p class="meta-edit">Editar</p>', '', ''); ?>
					<?php comments_popup_link('<p class="meta-comments">0 Comentários</p>', '<p class="meta-comments">1 Comentário &#187;</p>', '<p class="meta-comments">% Comentários &#187;</p>'); ?>
					<p class="meta-categories"><?php the_category(', ') ?></p>
					<?php the_tags('<p class="meta-tags">',', ','</p>'); ?>
				</div>

            </div>
        </div>

		<?php endwhile; ?>

		<div class="nav nav-border-bottom">
			<div class="alignleft"><?php next_posts_link('&laquo; Posts antigos') ?>&nbsp;</div>
			<div class="alignright">&nbsp;<?php previous_posts_link('Novos Posts &raquo;') ?></div>
		</div>

	<?php else : ?>
        <div id="intro">
            <h2>Não foram achados posts para <span>'<?php the_search_query(); ?>'</span>.</h2>
            <p>Tente uma busca diferente ou navegue num dos seguintes links.</p>
        </div>
        <div class="postWrapper">
            <div class="post">
    			<div class="entry">
                    <?php include (TEMPLATEPATH . '/links.php'); ?>
    			</div>
    		</div>
        </div>



	<?php endif; ?>

</div> <!-- / content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
