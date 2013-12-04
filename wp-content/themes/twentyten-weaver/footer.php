<?php
/**
 * The template used to display the footer
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets
 *
 * @package WordPress
 * @subpackage Twenty Ten
 * @since 3.0.0
 */
?>
	</div><!-- #main -->
	<div id="footer">
		<div id="colophon">
<?php 	get_sidebar( 'footer' );
	echo(do_shortcode(str_replace("\\", "", ttw_getopt('ttw_footer_opts'))));	/* here is where the footer options get inserted */
	do_action('ttwx_extended_footer');			/* anything in the extended footer */
	$date = getdate();
	$year = $date['year'];
?>
<table id='ttw_ftable'><tr>
 <td id='ttw_ftdl'><div id="site-info">
<?php $cp = ttw_getadminopt('ttw_copyright');
	if (strlen($cp) > 0) echo(str_replace("\\", "", ttw_getadminopt('ttw_copyright')));
	else { ?>
 &copy; <?php echo($year); ?> - <a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
 </div></td> <?php }
	if (! ttw_getadminopt('ttw_hide_poweredby')) { ?> 
 <td id='ttw_ftdr'><div id="site-generator">
 <?php do_action('twentyten_credits' ); ?>
 <a href="http://wordpress.org/" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentyten' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s.', 'twentyten' ), 'WordPress' ); ?></a>
 <?php echo(TTW_THEMENAME); ?> by WPWeaver.info
 </div></td> <?php } ?>
</tr></table>
		</div><!-- #colophon -->
	</div><!-- #footer -->

</div><!-- #wrapper -->
<?php echo(str_replace("\\", "", ttw_getadminopt('ttw_end_opts')."\n")); /* and this is the end options insertion */
      wp_footer();  ?>
</body>
</html>