<?php get_header(); ?>

	<?php if(is_home()) : ?>
	<div class="destaque">
		<?php if(function_exists('dynamic_sidebar')) dynamic_sidebar('video-home'); ?>
		<?php if(function_exists('dynamic_sidebar')) dynamic_sidebar('destaques-home'); ?>
		
	</div>
	<br clear="all" />
	<?php endif; ?>
		
<div <?php print (is_page() || is_single()) ? 'class="boxContentPage"' : 'class="boxContent1"'; ?>>
	<?php if(is_home()) : ?>
	
		<div class="box1-1">
			<ul>
			</ul>
			
			<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('left-column')) : ?>
				Nenhum Widget.
			<?php endif; ?>
		</div>
		
		<div class="box1-2">
			<ul>
			</ul>

			<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('bottom-column')) : ?>
				Nenhum Widget.
			<?php endif; ?>

		</div>
		
	<?php elseif (is_page(1901)) : ?>
	<a href="javascript:window.history.go(-1)" class="bold">&laquo; voltar</a>
	<h1 class="titulo"><?php the_title(); ?></h1>
		<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('lifestream-interna')) : ?>
			Nenhum Widget.
		<?php endif; ?>
		
		
	<?php else : ?>
		<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('left-column-interna')) : ?>
			Nenhum Widget.
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php get_sidebar(); ?>

<?php if(!is_page() && !is_single()) get_sidebar('slim'); ?>

<?php get_footer(); ?>
