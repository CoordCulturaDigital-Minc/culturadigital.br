<?php
global $options;
foreach ($options as $value) {
    if (get_option( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; }
    else { $$value['id'] = get_option( $value['id'] ); }
    }
?>
    </div><!-- #main -->
    
<?php thematic_abovefooter(); ?>    

	<div id="footer">
        <?php get_sidebar('subsidiary'); ?>
        <div id="siteinfo">        
    		<?php /* footer text set in theme options */ echo do_shortcode(__(stripslashes(thematic_footertext($thm_footertext)), 'thematic')); ?>
		</div><!-- #siteinfo -->
	</div><!-- #footer -->
	
<?php thematic_belowfooter(); ?>  

</div><!-- #wrapper .hfeed -->

<?php wp_footer(); ?>

<?php thematic_after(); ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9810322-3");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>