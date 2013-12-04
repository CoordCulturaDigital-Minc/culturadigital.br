<?php
/*
Template Name: Links
*/
?>
<?php get_header(); ?>
<div id="content">
	<div id="content-main">
			<div class="post">
				<h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="post-info"><?php the_time('M jS, Y') ?></p>
				<div class="entry">
					<ul>
						<?php get_links_list('name');?>
					</ul>
				</div>
			</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>