<?php get_header(); ?>
<div id="banner" class="container">
		<div class="row">			
			<div class="banner span12">
				<div id="header">								
					<a class="logo img-replacement" href="<?php echo home_url(); ?>">
						<?php bloginfo('name'); ?>
					</a>
								
					<div class="direita">
						<div class="menu-principal">
							<ul>
								<?php wp_nav_menu(array('theme_location' => 'principal', 'items_wrap' => '%3$s', 'container' => '')); ?>
								<li>
									<a href="#" class="bt-busca img-replacement">
										<?php _e('Busca'); ?>
									</a>
								</li>
							</ul>
						</div>
						
						<div class="form-busca">
							<form action="<?php echo home_url(); ?>" method="get">
								<div class="campo">
									<input type="text" name="s" value="digite sua busca" onFocus="this.value=''" />
									<input type="submit" value="ok" class="bt-enviar" />
								</div>
							</form>
						</div>
					</div>
					
				</div>
			
				<div class="imagem-bg">
					
				</div>
				
				<div class="texto">
					<p class="principal">
						<?php echo get_option('_ethymos_destaque_1'); ?>	
					</p>
					
					<div class="divisor">
						<div class="ornamento">
								
						</div>
					</div>
					
					<p class="secundario">
						<?php echo get_option('_ethymos_destaque_2'); ?>
						<br />
						<strong><?php echo get_option('_ethymos_destaque_3'); ?></strong>
					</p>
				</div>
				<div class="seta-botoes">
					
				</div>
				
				<div class="botoes">
					<ul>
						<li><a class="bt-classificacao-l" href="<?php echo (get_option('_ethymos_link_botao_livre')) ? get_option('_ethymos_link_botao_livre') : '#'; ?>">L</a></li>
						<li><a class="bt-classificacao-10" href="<?php echo (get_option('_ethymos_link_botao_10')) ? get_option('_ethymos_link_botao_10') : '#'; ?>">10</a></li>
						<li><a class="bt-classificacao-12" href="<?php echo (get_option('_ethymos_link_botao_12')) ? get_option('_ethymos_link_botao_12') : '#'; ?>">12</a></li>
						<li><a class="bt-classificacao-14" href="<?php echo (get_option('_ethymos_link_botao_14')) ? get_option('_ethymos_link_botao_14') : '#'; ?>">14</a></li>
						<li><a class="bt-classificacao-16" href="<?php echo (get_option('_ethymos_link_botao_16')) ? get_option('_ethymos_link_botao_16') : '#'; ?>">16</a></li>
						<li><a class="bt-classificacao-18" href="<?php echo (get_option('_ethymos_link_botao_18')) ? get_option('_ethymos_link_botao_18') : '#'; ?>">18</a></li>
					</ul>
				</div>
			</div>
		</div>
</div>

<div id="content" class="container">
	<div class="row">
		<div class="widgets-destaques">
			<?php dynamic_sidebar('blocos-home'); ?>
		</div>
	
		<?php $destaques = $ethymos->query->destaques(3); ?>
		<?php  if($destaques->have_posts()) :
			function classind_excerpt_length( $length )
			{
				return 22;
			}
			add_filter( 'excerpt_length', 'classind_excerpt_length', 999 );
		?>
		<div class="destaques-foto">			
			<?php while($destaques->have_posts()) : $destaques->the_post(); ?>
			<div class="span4 destaque">
				<div class="bg-2">
					<div class="imagem">
						<?php the_post_thumbnail('destaques-home'); ?>
						<div class="titulo"><?php the_title(); ?></div>
					</div>
					
					<div class="resumo">
					<?php
						the_excerpt();
					?>
					</div>
					
					<a href="<?php the_permalink(); ?>" class="bt-leia-mais bt-azul"><?php _e('Leia mais', 'classificacao-indicativa'); ?></a>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
		<?php endif; ?>
		
		<div class="span8 bloco-blog">
			<div class="titulo-bloco">
				<?php _e('Blog', 'classificacao'); ?>
				<a href="<?php echo get_category_link(get_category_by_slug('blog')->term_id); ?>" class="todos-os-posts"><?php _e('&raquo; ver todos os posts', 'classificacao-indicativa'); ?></a>
			</div>
			
			<div class="row">
				<?php $blog = $ethymos->query->blog(2); ?>
				<?php if($blog->have_posts()) : while($blog->have_posts()) : $blog->the_post(); ?>
				<div class="post span4">
					<div class="data"><?php the_time('d \d\e F \d\e Y'); ?></div>
					<div class="titulo"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
					<div class="resumo"><?php the_excerpt(); ?></div>
					<a href="<?php the_permalink(); ?>" class="leia-mais"><?php _e('Leia mais &raquo;', 'classificacao-indicativa'); ?></a>
				</div>
				<?php endwhile; else : ?>
				
				<div class="post span4">
					<?php _e('Nenhum post encontrado', 'classificacao-indicativa'); ?>
				</div>
				
				<?php endif; ?>				
			</div>
		</div>
		
		<div class="span4 bloco-links-uteis">
			
			<div class="titulo-bloco">
				<?php _e('Links úteis'); ?>
			</div>
			<ul>
				<?php
				if(function_exists('wp_get_links')){
					get_links(0, '<li>', '</li>', ' - ', false);
				}
				?>
			</ul>
		</div>
		
		
	</div>
</div>








<?php get_footer(); ?>