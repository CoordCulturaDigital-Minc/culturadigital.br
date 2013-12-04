<?php get_header();?>
<div class="container" id="content">
	<div class="row">		
		<div class="span8">
			<div class="post-content">
				
					<div class="row">
					
						<div class="titulo-pagina">
							<h1>
								<?php _e('Resultados da busca para:', 'classificacao-indicativa'); ?> <?php echo wp_strip_all_tags($s); ?>
							</h1>
						</div>
					
					</div>
					
					<div class="lista-posts">
						
					<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
						
						<div class="post">
							<div class="data">
								<?php the_time("d \d\e F \d\e Y"); ?>
							</div>
							
							<div class="titulo">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</div>
							
							<div class="resumo">
								<?php the_excerpt(); ?>
							</div>

							<a href="<?php the_permalink(); ?>" class="leia-mais"><?php _e('Leia mais', 'classificao-indicativa'); ?></a>
						</div>
						
					<?php endwhile; else : ?>
					
						<h2><?php _e('Nenhum post encontrado', 'classificacao-indicativa'); ?></h2>
					
					<?php endif; ?>
					
					</div>
					
					<div class="pagenav">
						<?php 
							if ( function_exists( 'wp_pagenavi' ) ){
								wp_pagenavi(array('query' => $wp_query));
							} else {
								previous_posts_link();
								echo " | ";
								next_posts_link();
							}
						?>
					</div>
			</div>
		</div>
		<div class="span4 sidebar">
			<div class="row">
				<?php dynamic_sidebar('paginas-internas'); ?>
			</div>
		</div>

	</div>
</div>
<?php
	get_footer();
?>
