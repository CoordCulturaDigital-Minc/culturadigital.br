<div class="boxContent2">

	<div class="box2-1">
 
		<ul>
			<!--<li><a href="#comentarios"><span>Comentários</span></a></li>-->
			<!--<li><a href="#rssComentarios"><span>rss</span></a></li>-->
		</ul>
		
		<?php if(!is_page() && !is_single() && !is_category()) : ?>
			<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('center-column')) : ?>
				Nenhum Widget.
			<?php endif; ?>
		
		<?php else : ?>
			<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('right-column-interna')) : ?>
				Nenhum Widget.
			<?php endif; ?>
		<?php endif; ?>
	
	</div>

</div>
