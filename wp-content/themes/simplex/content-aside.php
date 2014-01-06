<?php
/**
 * The template for displaying posts in the Aside Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package WordPress
 * @subpackage simpleX
 * @since simpleX 2.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php do_action( 'simplex_before_title' ); ?>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'simplex' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php do_action( 'simplex_after_title' ); ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for search pages ?>
	<div class="entry-summary">
		<?php do_action( 'simplex_before_content' ); ?>
		<?php the_content(); ?>
		<?php do_action( 'simplex_after_content' ); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php do_action( 'simplex_before_content' ); ?>
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'simplex' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'simplex' ), 'after' => '</div>' ) ); ?>
		<?php do_action( 'simplex_after_content' ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php do_action( 'simplex_before_meta' ); ?>
		<?php simplex_posted_on(); ?>
		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<span class="sep"> / </span>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'simplex' ), __( '1 Comment', 'simplex' ), __( '% Comments', 'simplex' ) ); ?></span>
		<?php endif; ?>
		<?php edit_post_link( __( 'Edit', 'simplex' ), '<span class="sep"> / </span><span class="edit-link">', '</span>' ); ?>
		<?php do_action( 'simplex_after_meta' ); ?>
	</footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
