<?php get_header(); ?>
	<div id="content" class="narrowcolumn">
	<?php if (have_posts()) : ?>
		<h2 class="pagetitle">Resultados da pesquisa</h2>
		<?php while (have_posts()) : the_post(); ?>
			<div class="post">
				<div class="postwrapper">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="Continue lendo este artigo &#187;"><img src="<?php $key="thumbnail"; echo get_post_meta($post->ID, $key, true); ?>" alt="thumbnail" class="thumbnail" /></a>
					<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<div class="entry">
						<?php the_excerpt(); ?>
					</div>
				</div>
				<div class="postmetadata">
					<span class="thetime"><?php the_time('F jS, Y') ?></span>
					<span class="thecategory"><?php the_category(', ') ?></span>
					<?php edit_post_link('Editar', '<span class="editthispost">', '</span>'); ?>
					<span class="thecomments"><?php comments_popup_link('Adicionar comentário &#187;', '1 comentário &#187;', '% comentários &#187;'); ?></span>
					<div class="readmore">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="Continue lendo este artigo &#187;">Leia mais</a>
					</div>
				</div>
			</div>
		<?php endwhile; ?>

			<div class="navigation">
				<div class="navleft"><?php next_posts_link('Página anterior') ?></div>
				<div class="navright"><?php previous_posts_link('Próxima página') ?></div>
			</div>

	<?php else : ?>

		<h2 class="center">Nenhum post encontrado. Utilize a busca novamente.</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>