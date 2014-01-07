<?php
/**
 * @package WordPress
 * @subpackage simpleX
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php do_action( 'simplex_before_title' ); ?>
		<h2 class="entry-title"><?php the_title(); ?></h2>
		<?php do_action( 'simplex_after_title' ); ?>

		<div class="entry-meta">
			<?php simplex_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php do_action( 'simplex_before_content' ); ?>
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'simplex' ), 'after' => '</div>' ) ); ?>
		<?php do_action( 'simplex_after_content' ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php do_action( 'simplex_before_meta' ); ?>
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'simplex' ) );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', ', ' );
			
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'simplex' );
				} else {
					$meta_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'simplex' );
				}
				
						
			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink(),
				the_title_attribute( 'echo=0' )
			);
		?>

		<?php edit_post_link( __( 'Edit', 'simplex' ), '<span class="edit-link">', '</span>' ); ?>
		<?php do_action( 'simplex_after_meta' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
