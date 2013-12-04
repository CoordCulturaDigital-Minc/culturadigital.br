<?php get_header();?>
<div id="content">
<div id="content-main">
<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>
				
			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="posttitle">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="post-info"><?php the_time('d') ?>/<?php the_time('m') ?>/<?php the_time('Y') ?> - postado por <?php the_author_posts_link() ?> <?php edit_post_link('Editar', '', ' | '); ?> </p>
				</div>
				
				<div class="entry">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
				</div>
		
				<p class="postmetadata">Categoria(s) <?php the_category(', ') ?> | <?php comments_number('Nenhum Coment&aacute;rio &#187;', '1 Coment&aacute;rio &#187;', '% Coment&aacute;rios &#187;'); ?></p>				
			
				
			</div>

			<?php comments_template(); ?>	
		<?php endwhile; ?>

		<p align="center"><?php posts_nav_link(' - ','&#171; Anterior','Pr&oacute;ximo &#187;') ?></p>
		
	<?php else : ?>

		  <h2 class="center">N&atilde;o encontrado </h2>
		  <p class="center">Desculpe, mas o que voc&ecirc; estava procurando n&atilde;o est&aacute; aqui.</p>

	<?php endif; ?>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer(); ?>