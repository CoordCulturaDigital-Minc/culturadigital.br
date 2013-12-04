<?php get_header(); ?>
<div class="container" id="content">
	<div class="row">
		<div class="span8">
			<div class="post-content">
				<?php if(have_posts()) : the_post(); ?>
					<div class="row">
					
						<div class="titulo-pagina">
							<h1>
								<?php the_title(); ?>
							</h1>
						</div>
					
					</div>
					
					<div class="texto">
						<?php the_content(); ?>
						
						<div class="atualizado-em">
							Atualizado em <?php the_modified_time("d \d\\e F \d\\e Y"); ?>
						</div>
					</div>
						
				<?php else : ?>
				
					<h1><?php _e('Página não encontrada', 'classificacao-indicativa'); ?></h1>
					
					<p>
						<?php _e('Desculpe, mas a página que você procura não foi encontrada', 'classificacao-indicativa'); ?>
					</p>
				
				<?php endif; ?>
			</div>
		</div>
		<div class="span4 sidebar">
			<div class="row">
				<?php dynamic_sidebar('paginas-estaticas'); ?>
			</div>
		</div>
	</div>
</div>


<?php get_footer(); ?>