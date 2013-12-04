<?php get_header();?>
<div id="content">
<div id="content-main">
<?php if (have_posts()) : ?>
	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
    
    <?php /* If this is a category archive */ if (is_category()) { ?>				
		<h2 class="pagetitle" style="color:#333333;  margin-left:5px;">Arquivos da categoria '<?php echo single_cat_title(); ?>'</h2>
		
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle" style="color:#333333;  margin-left:5px;">Arquivos em <?php the_time('F jS, Y'); ?></h2>
		
	 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle" style="color:#333333;  margin-left:5px;">Arquivos em <?php the_time('F, Y'); ?></h2>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle" style="color:#333333;  margin-left:5px;">Arquivos em <?php the_time('Y'); ?></h2>
		
	  <?php /* If this is a search */ } elseif (is_search()) { ?>
		<h2 class="pagetitle" style="color:#333333;  margin-left:5px;">Resultados da busca</h2>
		
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle" style="color:#333333; margin-left:5px;">Arquivos do autor</h2>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Arquivos do Blog</h2>

		<?php } ?>
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
				<?php comments_template(); ?>
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