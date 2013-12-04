<?php get_header(); ?>
<div class="page">
<h1 class="catheader catcenter"><?php _e('Tag Archive')?></h1>

<div id="tagcloud" class="page-content tag-icon"><?php wp_tag_cloud('smallest=9&largest=14'); ?></div>

<?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
			

    <div class="posts">
    <h2><a href="<?php the_permalink() ?>" title="Click to read <?php the_title(); ?>"><?php the_title(); ?></a></h2>
    <div class="meta">
				<span class="tagmeta"><?php _e('Published on')?> <?php the_time(get_option('date_format')) ?> <?php _e('By')?> <span class="usr-meta"><?php the_author() ?></span></span>
			</div>
	<?php the_excerpt(); ?>
    </div>

	<?php endwhile; ?>
	
	<?php endif; ?>
</div>
<?php get_footer(); ?>