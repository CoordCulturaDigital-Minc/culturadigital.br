<?php
/**
 * @package WordPress
 * @subpackage simpleX
 */
?>

<?php do_action( 'simplex_before_article' ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php do_action( 'simplex_before_title' ); ?>
		<div class="entry-meta">
			<?php simplex_posted_on(); ?> by <?php the_author(); ?>
		</div><!-- .entry-meta -->
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'simplex' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

		<?php if ( 'post' == get_post_type() ) : ?>
		
		<?php endif; ?>
		<?php do_action( 'simplex_after_title' ); ?>
	</header><!-- .entry-header -->
	

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php do_action( 'simplex_before_content' ); ?>
		<?php the_post_thumbnail('thumbnail');  ?>
		<?php the_excerpt(); ?>
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
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'simplex' ) );
				if ( $categories_list  ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'simplex' ), $categories_list ); ?>
			</span>
			<span class="sep"> / </span>			
			<?php endif; // End if categories ?>
			
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'simplex' ) );
				if ( $tags_list ) :
			?>
			<span class="tag-links">
				<?php printf( __( 'Tagged %1$s', 'simplex' ), $tags_list ); ?>
			</span>
			<span class="sep"> / </span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>
		
		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'simplex' ), __( '1 Comment', 'simplex' ), __( '% Comments', 'simplex' ) ); ?></span>
		<span class="sep"> / </span>
		<?php endif; ?>
		
		<?php edit_post_link( __( 'Edit', 'simplex' ), '<span class="edit-link">', '</span>' ); ?>
		<?php do_action( 'simplex_after_meta' ); ?>
	</footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
<?php do_action( 'simplex_after_article' ); ?>
