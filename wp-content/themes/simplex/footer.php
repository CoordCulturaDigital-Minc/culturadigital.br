<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage simpleX
 * @since simpleX 2.0
 */
 
 $url = "http://wpshoppe.com";
?>

	</div><!-- #main -->
	
	<?php do_action( 'simplex_before_footer' ); ?>

	<footer id="colophon" role="contentinfo">
		<div id="site-generator">
			<?php do_action( 'simplex_credits' ); ?>
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'simplex' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'simplex' ); ?>" rel="generator"><?php printf( __( 'Powered by %s', 'simplex' ), 'WordPress' ); ?></a> &amp;	<a href="<?php echo esc_url( __( 'http://wpshoppe.com/', 'simplex' ) ); ?>" title="<?php esc_attr_e( 'Minimalist WordPress Themes', 'simplex' ); ?>" rel="generator">simpleX</a>.
					
		</div>
	</footer><!-- #colophon -->
	
	<?php do_action( 'simplex_after_footer' ); ?>
	
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>