<?php get_header();?>
<div id="content">
<div id="content-main">
<?php if (have_posts()) : ?>
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
    
		<h2 class="pagetitle"  style="margin-left:5px;">Resultado da busca por <?php echo "'".$s."'";?></h2>
		<?php while (have_posts()) : the_post(); ?>
				
			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="posttitle">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="post-info">
						Postado na categoria <?php the_category(', ') ?>  em <?php the_time('M jS, Y') ?></p>
				</div>
				
				<div class="entry">
					<?php the_excerpt(); ?>
					<p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">Leia mais &#187;</a></p>
				</div>
			</div>
	
		<?php endwhile; ?>
		<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
		<!-- <p align="center"><?php posts_nav_link(' - ','&#171; Prev','Next &#187;') ?></p> -->
		
	<?php else : ?>

		  <h2 class="center">N&atilde;o encontrado </h2>
		  <p class="center">Desculpe, mas o que voc&ecirc; estava procurando n&atilde;o est&aacute; aqui.</p>
	<?php endif; ?>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer(); ?>