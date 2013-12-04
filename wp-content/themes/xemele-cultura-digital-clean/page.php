<?php get_header();?>
<div id="content">
<div id="content-main">
<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>
				
			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="posttitle">
					<h2><a style="color:0033666;"<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="post-info"><?php edit_post_link('Edit', '', ' | '); ?> </p>
				</div>
				
				<div class="entry">
					<?php the_content(); ?>	
					<?php wp_link_pages(); ?>				
					<?php $sub_pages = wp_list_pages( 'sort_column=menu_order&depth=1&title_li=&echo=0&child_of=' . $id );?>
					<?php if ($sub_pages <> "" ){?>
						<p class="post-info">This page has the following sub pages.</p>
						<ul><?php echo $sub_pages; ?></ul>
					<?php }?>											
				</div>
		
			</div>
	
		<?php endwhile; ?>

		<p align="center"><?php posts_nav_link(' - ','&#171; Anterior','Pr&oacute;ximo &#187;') ?></p>
		
	<?php else : ?>

		  <h2 class="center">N&atilde;o encontrado </h2>
		  <p class="center">Desculpe, mas o que voc&ecirc; estava procurando n&atilde;o est&aacute; aqui.</p>
		

	<?php endif; ?>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer(); ?>