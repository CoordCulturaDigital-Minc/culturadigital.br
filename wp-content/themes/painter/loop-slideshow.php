<?php global $painter, $useds; ?>

<?php if( $painter->options[ 'slideshow' ] > 0 ) : ?>
	<?php $slideshow = new WP_Query( "cat={$painter->options[ 'slideshow' ]}" ); ?>
	<?php if( $slideshow->have_posts() ) : ?>
		<div class="section section_slideshow">
			<div class="body">
				<ul class="posts">
					<?php while( $slideshow->have_posts() ) : $slideshow->the_post(); ?>
						<?php $useds[] = get_the_ID(); ?>
						<li class="post">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'slideshow', array( 'class' => 'post_thumb' ) ); ?></a>
							<div class="post_description">
								<h2 class="post_title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
								<div class="post_entry hide_tablet hide_mobile"><?php echo painter_limit_chars( get_the_excerpt(), 160 ); ?></div>
							</div>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
			<div class="foot">
				<div class="navigation"></div>
			</div>
		</div>
		<div class="clear"></div>
	<?php endif; ?>
<?php	endif; ?>