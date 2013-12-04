<?php get_header(); ?>
		
		<div id="contentwap">
		
		<?php
			if (isset($_GET['comments'])) {
			
				if (have_posts()): while (have_posts()): the_post();
					comments_template();
				endwhile; endif;
				
			}
			elseif (isset($_GET['postcomment'])) {
			
				if (have_posts()): while (have_posts()): the_post();
					comments_template($file = '/postcomment.php');
				endwhile; endif;
			
			}
			else {
		?>
			
			<?php if (have_posts()): while (have_posts()): the_post(); ?>
			
			<div id="infoblock">
			
				<h2><?php the_title(); ?></h2>
			
			</div>
			
			<div class="post">
				<?php the_content(); ?>
				<?php wp_link_pages('before=<p>&after=</p>&next_or_number=number&pagelink=Page %'); ?>
			</div>
			
			<div id="postfoot">
				<p><?php the_time('d F Y'); ?> Publicado em <?php the_category(', ') ?>.</p>
			</div>
			
			<div id="comments">
				<div id="respond">
					<p><a href="<?php the_permalink(); ?><?php mopr_check_permalink(); ?>comments=true">Ver comentários</a> ou <a href="<?php the_permalink() ?><?php mopr_check_permalink(); ?>postcomment=true">Comentar</a>.</p>
				</div>
			</div>
			
			<?php endwhile; else: ?>
			
			<h2>Página não encontrada.</h2>
			<p>Desculpe, a página que você estava buscando não foi encontrada.</p>
			
			<?php endif; ?>
		
		<?php
		}
		?>
		
		</div>
		
<?php get_footer(); ?>
