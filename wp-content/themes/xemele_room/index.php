<?php get_header(); ?>

<div id="content">
	
	<?php if(is_home() && !is_paged()) : ?>
	<div class="post">
		<h1>Em Destaque</h1>
		<?php include (TEMPLATEPATH . '/slide.php'); ?>
		
	</div>
	<?php endif; ?>
	
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="postWrapper">

   			<div class="post" id="post-<?php the_ID(); ?>">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

				<div class="entry">
					<?php the_content('<span class="more">Mais &raquo;</span>'); ?>
				</div>
				
				<!-- META -->
				<div class="postmetadata">
					<p class="meta-date">
						<span class="date-day"><?php the_time('j') ?></span>
						<span class="date-month"><?php the_time('M') ?></span>
						<span class="date-year"><?php the_time('Y') ?></span>
					</p>
					<p class="meta-author">por <?php the_author() ?></p>
					<?php edit_post_link('<p class="meta-edit">Edit</p>', '', ''); ?>
					<?php comments_popup_link('<p class="meta-comments">0 Comentários</p>', '<p class="meta-comments">1 Comentário &#187;</p>', '<p class="meta-comments">% Comentários &#187;</p>'); ?>
					<p class="meta-categories"><?php the_category(', ') ?></p>
					<?php the_tags('<p class="meta-tags">',', ','</p>'); ?>
				</div>
            </div>
        </div>

    <?php endwhile; ?>

		<div class="nav nav-border-bottom">
			<div class="alignleft"><?php next_posts_link('&laquo; Posts Mais Antigos') ?>&nbsp;</div>
			<div class="alignright">&nbsp;<?php previous_posts_link('Posts Mais novos &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>

	<?php endif; ?>

</div> <!-- / content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
