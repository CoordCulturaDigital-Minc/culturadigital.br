<?php
/*
Template Name: Archives
*/
?>
<?php get_header();?>
<div id="content">
<div id="content-main">
<div class="post">
				<h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="post-info"><?php edit_post_link(); ?></p>
				<div class="entry">
				<h2><?php _e('por Categorias'); ?></h2>
					<ul>
						<?php wp_list_cats('optioncount=1');    ?>
					</ul>						
					<h2><?php _e('por Mês'); ?></h2>
					<ul><?php wp_get_archives('type=monthly'); ?></ul>
					<h2>Last 50 Entries</h2>
					<ul>
					<?php $posts = query_posts('showposts=50');?>			
					<?php if ($posts) : foreach ($posts as $post) : start_wp(); ?>
						<li><h4><em><?php the_time('d M Y'); ?></em><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h4></li>
					<?php endforeach; else: ?>
					<p><?php _e('Desculpe, nenhum post encontrado.'); ?></p>
					<?php endif; ?>		
					</ul>					
				</div>
			</div>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer(); ?>