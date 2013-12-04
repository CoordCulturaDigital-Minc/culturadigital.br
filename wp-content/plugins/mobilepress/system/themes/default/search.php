<?php get_header(); ?>
		
		<div id="contentwrap">
		
			<div id="infoblock">
			
				<h2>Busca por "<?php the_search_query(); ?>"</h2>
			
			</div>
			
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<div class="post">
				<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="subtitle"><?php the_time('d F Y'); ?> <a href="<?php the_permalink() ?><?php mopr_check_permalink(); ?>comments=true"><?php comments_number('Adicionar comentário', 'Um comentário', '% comentários' ); ?></a></p>
			</div>
			
			<?php endwhile;  else: ?>
			
			<h2>Página não encontrada.</h2>
			<p>Desculpe, a página que você estava buscando não foi encontrada.</p>
			
			<?php endif; ?>
			
			<?php if (mopr_check_pagination()): ?>
			
			<div id="postfoot">
				<p><?php posts_nav_link(' &#183; ', 'Página anterior', 'Próxima página'); ?></p>
			</div>
			
			<?php endif; ?>
		
		</div>
		
<?php get_footer(); ?>
