<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : ?>

        <div id="intro">
 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Posts da Categoria: <span>&#8216;<?php single_cat_title(); ?>&#8217;</span></h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Posts com a Tag: <span>&#8216;<?php single_tag_title(); ?>&#8217;</span></h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Posts de <span><?php the_time('F jS, Y'); ?></span></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Posts de <span><?php the_time('F, Y'); ?></span></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Posts de <span><?php the_time('Y'); ?></span></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Posts do Autor</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Posts do Blog</h2>
 	  <?php } ?>
        </div>

		<?php while (have_posts()) : the_post(); ?>

        <div class="postWrapper">

           

			<div class="post" id="post-<?php the_ID(); ?>">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

				<div class="entry">
					<?php the_excerpt(); ?>
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><span class="more">Mais &raquo;</span></a>
				</div>
				
				 <!-- META -->
				<div class="postmetadata">
					<p class="meta-date">
						<span class="date-day"><?php the_time('j') ?></span>
						<span class="date-month"><?php the_time('M') ?></span>
						<span class="date-year"><?php the_time('Y') ?></span>
					</p>
					<p class="meta-author">Por <?php the_author() ?></p>
					<?php edit_post_link('<p class="meta-edit">Editar</p>', '', ''); ?>
					<?php comments_popup_link('<p class="meta-comments">0 Comentários</p>', '<p class="meta-comments">1 Comentário &#187;</p>', '<p class="meta-comments">% Comentários &#187;</p>'); ?>
					<p class="meta-categories"><?php the_category(', ') ?></p>
					<?php the_tags('<p class="meta-tags">',', ','</p>'); ?>
				</div>
            </div>
        </div>

		<?php endwhile; ?>

		<div class="nav nav-border-bottom">
			<div class="alignleft"><?php next_posts_link('&laquo; Posts mais antigos') ?>&nbsp;</div>
			<div class="alignright">&nbsp;<?php previous_posts_link('Posts mais novos &raquo;') ?></div>
		</div>
	<?php else : ?>
        <div id="intro">
      		<?php
                  if ( is_category() ) { // If this is a category archive
          			printf("<h2>Desculpe, não ha posts na categoria %s.</h2>", single_cat_title('',false));
          		} else if ( is_date() ) { // If this is a date archive
          			echo("<h2>Desculpe, nao ha posts dessa data.</h2>");
          		} else if ( is_author() ) { // If this is a category archive
          			$userdata = get_userdatabylogin(get_query_var('author_name'));
          			printf("<h2>Desculpe, nao ha posts do autor %s.</h2>", $userdata->display_name);
          		} else {
          			echo("<h2>No posts found.</h2>");
          		}
            ?>
        </div>
        <div class="postWrapper">
            <div class="post">
    			<div class="entry">
                    <?php include (TEMPLATEPATH . '/links.php'); ?>
    			</div>
    		</div>
        </div>
	<?php endif; ?>

	</div><!-- /content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
