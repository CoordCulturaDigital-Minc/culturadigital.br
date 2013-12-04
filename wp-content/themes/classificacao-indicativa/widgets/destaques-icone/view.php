<div class="span4 widget widget-destaque-<?php echo $instance['modelo']; ?>">
	<img class="icone" src="<?php echo get_stylesheet_directory_uri(); ?>/imagens/icones-widget/<?php echo $icone; ?>.png" alt="" />
	<div class="info">
		<span class="titulo"><a href="<?php echo $instance['link']; ?>"><?php echo $instance['titulo']; ?></a></span>
		<?php echo $instance['descricao']; ?>
	</div>
</div>