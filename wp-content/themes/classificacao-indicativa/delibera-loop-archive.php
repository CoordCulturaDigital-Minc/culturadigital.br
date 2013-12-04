<?php
global $ethymos;
/*
 * O loop padrão do archive.php
 * 
 * Por enquanto, ele está apenas alinhado para funcionar com o Delibera. A ideia é deixa-lo específico
 * o suficiente pra trabalhar com datas, categorias, tags e até taxonomias, antes de, quem sabe, separar
 * os arquivos.
 * 
 */
?>

<div class="row delibera">
	<div class="titulo-pagina span12">
		<?php _e('Participe <span>&raquo; Debata os critérios</span>', 'classisicacao-indicativa'); ?>
	</div>
	
	<div class="span12">
		<div class="row">
			<?php if(is_tax()) : ?>
			<div class="span12 descricao">
				<?php $main_term = get_queried_object(); ?>
				<div class="icone">
					<?php //$ethymos->pauta_exibe_icone($main_term->term_id); ?>
				</div>
				
				<div class="texto">
					<h1 class="titulo"><?php echo $main_term->name; ?></h1>
					<?php echo $main_term->description; ?>
				</div>
				
			</div>	
			<?php endif; ?>			
			<div class="header filtro span12">
				<div class="titulo">
					<?php _e('Selecione uma classificação para exibir seus critérios'); ?>
				</div>
				
				<ul class="lista-classificacao">
					<li><a class="bt-classificacao-l" href="<?php echo get_term_link('classificacao-livre', 'tema'); ?>">L</a></li>
					<li><a class="bt-classificacao-10" href="<?php echo get_term_link('nao-recomendado-para-menores-de-10-anos', 'tema'); ?>">10</a></li>
					<li><a class="bt-classificacao-12" href="<?php echo get_term_link('nao-recomendado-para-menores-de-12-anos', 'tema'); ?>">12</a></li>
					<li><a class="bt-classificacao-14" href="<?php echo get_term_link('nao-recomendado-para-menores-de-14-anos', 'tema'); ?>">14</a></li>
					<li><a class="bt-classificacao-16" href="<?php echo get_term_link('nao-recomendado-para-menores-de-16-anos', 'tema'); ?>">16</a></li>
					<li><a class="bt-classificacao-18" href="<?php echo get_term_link('nao-recomendado-para-menores-de-18-anos', 'tema'); ?>">18</a></li>
				</ul>
			</div>
			
			<div class="lista-pautas span12">
			
				<div class="row">
					<?php $cont = 0; ?>
					<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
					<?php 
						if($cont == 0){
							echo '<div class="lista-pautas-linha">';
							$cont++;
						} elseif($cont == 3){
							echo "</div>";
							echo '<div class="lista-pautas-linha">';
							$cont = 1;
						} else {
							$cont++;
						}
					?>
					<div class="span4 pauta">
						<div class="margem">
							<h3 class="titulo"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="resumo"><?php the_excerpt(); ?></div>
							<a href="<?php the_permalink(); ?>" class="discuta">
								<?php _e('&raquo; Discuta', 'classificacao-indicativa'); ?>
							</a>
							<span class="status">
								<?php
								if ( delibera_get_prazo( $post->ID ) == 0 )
									_e( 'Prazo encerrado', 'classificacao-indicativa' );
								else
									printf( _n( 'Encerra em um dia', 'Encerra em %1$s dias', delibera_get_prazo( $post->ID ), 'direitoamoradia' ), number_format_i18n( delibera_get_prazo( $post->ID ) ) );
								?>
							</span>
						</div>
					</div>
					<?php endwhile; else : ?>
						<div class="span12">
							<div class="erro">
								<h3><?php _e('Nenhum critério encontrado', 'classificacao-indicativa'); ?></h3>
								<p>
									<?php _e('Desculpe, mas nenhum critério foi cadastrado para esta categoria.', 'classificao-indicativa'); ?>
								</p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	
	
</div>
<?php /*
<div class="row">
<?php
	if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
	<div id="post-<?php the_ID(); ?>" <?php post_class('span4'); ?>>
		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>
			<div class="entry-meta">	
				<span class="entry-author author vcard">
					<?php _e( 'Discussão criada por', 'direitoamoradia' ); ?>
					<a class="url fn n" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php printf( __( 'Ver o perfil de %s', 'direitoamoradia' ), get_the_author() ); ?>">
						<?php the_author(); ?>
					</a>
				</span><!-- entry-date -->
				
				<span class="entry-date">
					<?php echo ' ' . __( 'em', 'direitoamoradia' ) . ' '; ?>
					<?php the_date('d/m/Y'); ?>
				</span><!-- .entry-date -->
			
				<span class="entry-prazo">
					<?php
						if ( delibera_get_prazo( $post->ID ) == 0 )
							_e( 'Prazo encerrado', 'direitoamoradia' );
						else
							printf( _n( 'Encerra em um dia', 'Encerra em %1$s dias', delibera_get_prazo( $post->ID ), 'direitoamoradia' ), number_format_i18n( delibera_get_prazo( $post->ID ) ) );
					?>
				</span><!-- .entry-prazo -->
				
			</div><!-- .entry-meta -->

	<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
	<?php else : ?>
			<div class="entry-content">
				<?php the_content( __( 'Continue lendo' ), 'direitoamoradia' ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Páginas:', 'direitoamoradia' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->
	<?php endif; ?>

			<div class="entry-utility">
				<?php if ( count( get_the_category() ) ) : ?>
					<span class="cat-links">
						<?php printf( __( 'Arquivado em', 'direitoamoradia' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
					</span>
					<span class="meta-sep">|</span>
				<?php endif; ?>
				
				<?php
				if(comments_open(get_the_ID()) && is_user_logged_in())
				{
				?>
					<span class="comments-link">
						<a href="<?php echo delibera_get_comments_link(); ?>">
						<?php
						_e( 'Discuta', 'direitoamoradia' );
						comments_number( '', ' ('. __( 'Um comentário', 'direitoamoradia' ) . ')', ' ('. __( '% comentários', 'direitoamoradia' ) . ')' );
						?>
						</a>
					</span>
				<?php
				}
				elseif(delibera_comments_is_open(get_the_ID()) && !is_user_logged_in())
				{
				?>
					<span class="comments-link">
						<a href="<?php echo wp_login_url( delibera_get_comment_link());?>">
							<?php _e( 'Discuta', 'direitoamoradia' ); ?>
							<?php comments_number( '', '('. __( 'Um comentário', 'direitoamoradia' ) . ')', '('. __( '% comentários', 'direitoamoradia' ) . ')' ); ?> 
						</a>
					</span>
				<?php
				}
				?>
				<span class="archive-situacao">
					<?php echo delibera_get_situacao($post->ID)->name; ?>
				</span>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->

		<?php comments_template( '', true ); ?>


<?php endwhile; endif; ?>
</div>
<!-- /.row -->
*/ ?>