<div id="postlist">
<ul class="spy">
<?php $my_query = new WP_Query('orderby=rand'); ?>

	<?php while ($my_query->have_posts()) : $my_query->the_post();?>
	<li>
	<?php the_thumb('thumbnail', 'width="100" height="60"'); ?> 
	<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	<div class="fcats"><?php the_category(', '); ?> </div> 
	<div class="auth"> Postado por: <?php the_author(); ?> </div> 
	</li>
	<?php endwhile; ?>
</ul>
</div>

