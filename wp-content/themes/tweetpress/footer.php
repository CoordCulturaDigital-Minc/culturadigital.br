<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */
?>
<?php if(!is_404()) : ?>
	</tr>
</table>
<?php endif; ?>
<div id="footer" role="contentinfo" class="round">
	<ul>
		<li class="first">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></li>
		<li><a href="http://twitter.com/">Twitter</a></li>
		<li><a href="http://gabrieljones.com/tweetpress/">TweetPress</a></li>
		<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
		<?php wp_list_pages('title_li='); ?>
		<li><a href="<?php bloginfo('rss2_url'); ?>">Subscribe</a></li>
		<li><a href="javascript:void(0);" id="back-top">Top</a></li>
	</ul>
</div>
</div>
	<?php if(google_analytics_key()) : ?>
	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		try {
		var pageTracker = _gat._getTracker("<?php echo google_analytics_key()?>");
		pageTracker._trackPageview();
		} catch(err) {}
	</script>
	<?php endif; ?>
	<?php wp_footer(); ?>
</body>
</html>
