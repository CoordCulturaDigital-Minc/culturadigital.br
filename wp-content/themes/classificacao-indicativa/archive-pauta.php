<?php get_header();?>
<div class="container" id="content">
	<div class="row">		
		<div class="span12">
			<?php
				// Chama o cabeçalho que apresenta o sistema de discussão
				//get_delibera_header();
				//delibera_filtros_gerar();
				//$args = get_tax_filtro($_REQUEST, array('post_type' => 'pauta'));
			?>
										
			<!-- lista pautas -->
			<?php
				// Chama o loop do arquivo
				//wp_reset_query();
				
				//echo count(query_posts($args));
				load_template(dirname(__FILE__).DIRECTORY_SEPARATOR.'delibera-loop-archive.php', true);
			?>
								
			<div id="nav-below" class="navigation">
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
			<!-- #nav-below -->
			<!-- lista-pautas -->
		</div>
	</div>
</div>
<?php
	get_footer();
?>
