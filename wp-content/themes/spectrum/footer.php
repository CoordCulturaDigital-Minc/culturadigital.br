<?php
/**
 * @package WordPress
 * @subpackage Spectrum
 */
?>

			<div id="tagCloud">
				<div class="subTitle">
					<h4><strong>Tag Cloud</strong></h4>
				</div>
				<ul>
					<?php
						$tags = get_tags( array('orderby' => 'count', 'order' => 'DESC') );
						foreach ( (array) $tags as $tag ) {
						?>
						<?php echo '<li><a href="' . get_tag_link ($tag->term_id) . '" rel="tag">' . $tag->name . '</a></li>';	?>
					<?php }?>
				</ul>
			</div>
		</div>
	</div>
	<div id="footer">
		<div class="pageList">
			<ul>
				<?php wp_list_pages('title_li='); ?>
			</ul>
		</div>
		<div id="copywright">
			<p id="about">
				<?php bloginfo('name'); ?> is proudly powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a> &amp; <a href="http://spectrum-theme.com/" target="_blank">Spectrum Theme</a> by <a href="http://www.ignacioricci.com" target="_blank">Ignacio Ricci</a>
			</p>
			<p id="codeIsPoetry">Code is poetry</p>
			<?php wp_footer(); ?>
		</div>
	</div>

</body>
</html>