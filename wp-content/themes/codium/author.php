<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>

			<h2 class="page-title author"><?php printf(__('Author Archives: <span class="vcard">%s</span>', 'codium'), "<a class='url fn n' href='$authordata->user_url' title='$authordata->display_name' rel='me'>$authordata->display_name</a>") ?></h2>

			<?php if ( get_the_author_meta( 'description' ) ) : ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'codium', 60 ) ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description" class="entry-content">
							<h3><?php printf( __( '%s', 'codium' ), get_the_author() ); ?></h3>
							<div class="clear"></div>
							<p><?php the_author_meta( 'description' ); ?></p>
						</div><!-- #author-description	-->
					</div><!-- #entry-author-info -->
					<div class="linebreak clear"></div>
			<?php endif; ?>

<?php rewind_posts(); while (have_posts()) : the_post(); ?>

			<!-- Begin post -->
			<div id="post-<?php the_ID() ?>" class="<?php codium_post_class() ?>">
				<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Link to %s', 'codium'), esc_html(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h2>
				<div class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s &#8211; %2$s', 'codium'), the_date('', '', '', false), get_the_time()) ?></abbr></div>
									
					<?php if ( has_post_thumbnail() ) { ?>
						<div class="dp40">
							<div class="postthumbimg-ds">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail(); ?>
					        	</a>
					    	</div>
							</div><!-- End Thumb Container -->
						
						<div class="entry-content dp60">
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
						<div class="entry-meta">
							<?php the_tags(__('<span class="tag-links">Tags ', 'codium'), ", ", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n") ?>
							<?php edit_post_link(__('Edit', 'codium'), "\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n"); ?>
							<span class="comments-link"><?php comments_popup_link(__('Comment (0)', 'codium'), __('Comment (1)', 'codium'), __('Comments (%)', 'codium')) ?></span>
						</div>
					<?php }?> 
						
			</div>
			<!-- End post -->
			
			<div class="linebreak clear"></div>


<?php endwhile ?>

<div class="center">			
	<?php if(function_exists('wp_pagenavi')) { 
		wp_pagenavi();
	} else {?>
		<div class="navigation"><p><?php posts_nav_link(); ?></p></div>
		 <?php } ?>  
</div>
		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>