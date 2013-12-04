<?php /*
Example template
Author: mitcho (Michael Yoshitaka Erlewine)
*/
?><h3><?php _e("Related Posts","fusion"); ?></h3>
<?php if ($related_query->have_posts()):?>
<ol>
	<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
	<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a><!-- (<?php the_score(); ?>)--></li>
	<?php endwhile; ?>
</ol>
<?php else: ?>
<p><?php _e("No related posts.","fusion"); ?></p>
<?php endif; ?>
