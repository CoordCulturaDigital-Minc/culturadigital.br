<?php get_header(); ?>
		
		<div id="contentwrap">
		
			<div id="infoblock">
			
				<h2>Últimas Postagens</h2>
			
			</div>
			
			<?php $access_key = 1; ?>
			<?php if (have_posts()): while (have_posts()): the_post(); ?>
			
			<div class="post">
				<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" accesskey="<?php echo $access_key; $access_key++; ?>"><?php the_title(); ?></a></h2>
				<p class="subtitle"><?php the_time('d'); ?> de <?php the_time('F'); ?> de <?php the_time('Y'); ?> <a href="<?php the_permalink(); ?><?php mopr_check_permalink(); ?>comments=true"><?php comments_number('Adicionar comentário', 'Um comentário', '% comentários' ); ?></a></p>
			</div>
			
			<?php endwhile; else: ?>
			
			<h2>Página não encontrada.</h2>
			<p>Desculpe, a página que você estava buscando não foi encontrada.</p>
			
			<?php endif; ?>
			
			<?php if (mopr_check_pagination()): ?>
			
			<div id="indexpostfoot">
				<p><?php posts_nav_link(' &#183; ', 'Página anterior', 'Próxima página'); ?></p>
			</div>
			
			<?php endif; ?>
			
			<div id="pageblock">
			
				<h2>Páginas</h2>
			
			</div>
			
			<div class="page">
				
				<ol id="pages">
					<?php wp_list_pages('title_li='); ?>
				</ol>
				
			</div>
			
		</div>
		
<?php get_footer(); ?>
