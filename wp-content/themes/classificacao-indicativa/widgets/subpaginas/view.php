<div class="widget-subpaginas">
	<span class="widget-titulo"><?php echo $instance['titulo']; ?></span>
	<?php wp_list_pages(array('child_of' => $referencia_mae, 'title_li' => '')); ?>
</div>