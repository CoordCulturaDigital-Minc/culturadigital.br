<?php global $painter; ?>

<li class="post" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post_entry entry">
		<?php the_content(); ?>
		<?php wp_link_pages( 'before=<p>&after=</p>&next_or_number=number&pagelink=' . __( 'part %', 'painter' ) ); ?>
	</div>
	<div class="clear"></div>
	<?php if( $painter->options[ 'index_author' ] ) : ?><div class="post_meta"><span class="fa fa-user"></span> <?php the_author_posts_link(); ?></div><?php endif; ?>
	<?php if( $painter->options[ 'index_date' ] ) : ?><div class="post_meta"><span class="fa fa-calendar"></span> <a href="<?php echo get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ); ?>"><time datetime="<?php the_time( 'U' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time></a></div><?php endif; ?>
	<?php if( $painter->options[ 'index_modified_date' ] ) : ?><div class="post_meta"><span class="fa fa-repeat"></span> <a href="<?php echo get_day_link( get_the_modified_time( 'Y' ), get_the_modified_time( 'm' ), get_the_modified_time( 'd' ) ); ?>"><time datetime="<?php the_modified_time( 'U' ); ?>"><?php the_modified_time( get_option( 'date_format' ) ); ?></time></a></div><?php endif; ?>
	<?php if( is_single() ) : ?>
		<?php if( $painter->options[ 'index_category' ] ) : ?><div class="post_meta"><span class="fa fa-folder-open"></span> <?php the_category( ', ' ); ?></div><?php endif; ?>
		<?php if( $painter->options[ 'index_tag' ] ) : ?><div class="post_meta"><span class="fa fa-tag"></span> <?php the_tags( ' ', ', ' ); ?>&nbsp;</div><?php endif; ?>
	<?php endif; ?>
	<?php if( $painter->options[ 'index_comments' ] ) : ?><div class="post_meta"><span class="fa fa-comment"></span> <?php comments_popup_link( __( 'Leave a Reply!', 'painter' ), __( '1 Comment', 'painter' ), __( '% Comments', 'painter' ) ); ?></div><?php endif; ?>
	<?php edit_post_link( 'Editar', '<div class="post_meta"><span class="fa fa-pencil"></span> ', '</div>' ); ?>
</li>