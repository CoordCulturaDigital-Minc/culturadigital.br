<?php get_header() ?>

	<div id="container">
		<div id="content">
			<h2 class="entry-title"><?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $term->name; ?></h2>
			<div class="linebreak clear"></div>	
			
			<?php if (have_posts()) : ?>  
			<?php while (have_posts()) : the_post(); ?>
			
			<div class="dp100">
			<span class="cat-links <?php post_class(); ?>"><?php printf(__('%s', 'codium'), get_the_category_list(' ')) ?></span>
			</div>
			
			<!-- Begin post -->
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
				<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Link to %s', 'codium'), esc_html(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h2>
				 <div class="entry-date"><?php unset($previousday); printf(__('%1$s &#8211; %2$s', 'codium'), the_date('', '', '', false), get_the_time()) ?></div> 

					<?php if ( has_post_thumbnail() ) { ?>
						<div class="dp40 mobileoff">
							<div class="postthumbimg-ds">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail(); ?>
					        	</a>
					    	</div>
							</div><!-- End Thumb Container -->
						
						<div class="entry-content dp60 mobileon">
							<?php the_excerpt(''.__('read more <span class="meta-nav">&raquo;</span>', 'codium').''); ?>
							<?php wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'codium'), "</div>\n", 'number'); ?>
						</div>
						<div class="clear"></div>				
						
						<div class="entry-meta">
							<?php the_tags(__('<span class="tag-links">Tags ', 'codium'), ", ", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n") ?>
							<?php edit_post_link(__('Edit', 'codium'), "\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n"); ?>
							<span class="comments-link"><?php comments_popup_link(__('Comment (0)', 'codium'), __('Comment (1)', 'codium'), __('Comments (%)', 'codium')) ?></span>
						</div>
						
					<?php } else { ?>
						<div class="entry-content">
					<?php the_excerpt(''.__('read more <span class="meta-nav">&raquo;</span>', 'codium').''); ?>
					<?php wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'codium'), "</div>\n", 'number'); ?>
						</div>
						<div class="clear"></div>
						<div class="entry-meta">
							<?php the_tags(__('<span class="tag-links">Tags ', 'codium'), ", ", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n") ?>
							<?php edit_post_link(__('Edit', 'codium'), "\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n"); ?>
							<span class="comments-link"><?php comments_popup_link(__('Comment (0)', 'codium'), __('Comment (1)', 'codium'), __('Comments (%)', 'codium')) ?></span>
						</div>
					<?php }?> 
						
			</div>
			<!-- End post -->

<div class="linebreak clear"></div>

<?php endwhile; endif ?>

<div class="center">			
	<?php if(function_exists('wp_pagenavi')) { 
		wp_pagenavi();
	} else {?>
		<div class="navigation mobileoff"><p><?php posts_nav_link(); ?></p></div>
		 <?php } ?>  
		<div class="navigation_mobile"><p><?php posts_nav_link(); ?></p></div> 
</div>


		</div><!-- #content -->
	</div><!-- #container -->
	
<?php get_sidebar() ?>
<?php get_footer() ?>