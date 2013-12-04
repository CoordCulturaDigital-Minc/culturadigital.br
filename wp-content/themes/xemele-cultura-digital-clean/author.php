<?php get_header();?>
<div id="content">
<div id="content-main">
<div class="post">
<?php
	global $wp_query;
	$curauth = $wp_query->get_queried_object();
?>
<h2>Nick: <?php echo $curauth->nickname; ?></h2>
<dl>
<dt>Nome completo</dt>
<dd><?php echo $curauth->first_name. ' ' . $curauth->last_name ;?></dd>
<dt>Site</dt>
<dd><a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></dd>
<dt>Detalhes</dt>
<dd><?php echo $curauth->description; ?></dd>
</dl>

			<h2>Posts por <?php echo $curauth->nickname; ?>:</h2>
			<ul class="authorposts">
			<!-- The Loop -->
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<li>
				<h4>
				<em><?php the_time('d M Y'); ?></em>
				<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a>
				</h4>
			</li>
			<?php endwhile; else: ?>
			<p><?php _e('Nenhum post deste autor.'); ?></p>

			<?php endif; ?>
			<!-- End Loop -->			
		</ul>
		<p align="center"><?php posts_nav_link(' - ','&#171; Anterior','Pr&oacute;ximo &#187;') ?></p>
	</div>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer(); ?>