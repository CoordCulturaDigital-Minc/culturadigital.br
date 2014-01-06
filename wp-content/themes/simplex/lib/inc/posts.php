<?php

/*
 * These functions display define Post loops
 * @package WordPress
 * @subpackage simpleX
 * @since simpleX 2.0
 */

if ( ! function_exists( 'simplex_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own simplex_posted_on to override in a child theme
 *
 */
function simplex_posted_on() {
	printf( __( '<a href="%1$s" title="%2$s" rel="bookmark" class="entry-date"><time datetime="%3$s" pubdate>%4$s</time></a>', 'simplex' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'simplex' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}
endif;