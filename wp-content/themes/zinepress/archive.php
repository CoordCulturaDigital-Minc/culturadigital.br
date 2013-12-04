<?php get_header(); ?>

	<div id="content" class="narrowcolumn">
<?php is_tag(); ?>
		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Arquivos para &#8216;<?php single_cat_title(); ?>&#8217; Categoria</h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Artigos tag &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Arquivos para <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Arquivos para <?php the_time('F, Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Arquivos para <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Arquivos do autor</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Arquivo geral</h2>
 	  <?php } ?>

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
					<?php edit_post_link('Edit', '<span class="editthispost">', '</span>'); ?>
					<span class="thecomments"><?php comments_popup_link('Adicionar comentário &#187;', '1 comentário &#187;', '% comentários &#187;'); ?></span>
					<div class="readmore">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="Continue lendo este artigo &#187;">Leia Mais</a>
					</div>
				</div>
			</div>
		<?php endwhile; ?>

			<div class="navigation">
				<div class="navleft"><?php next_posts_link('Página anterior') ?></div>
				<div class="navright"><?php previous_posts_link('Próxima página') ?></div>
			</div>

	<?php else : ?>

		<h2 class="center">Não encontrado!</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
