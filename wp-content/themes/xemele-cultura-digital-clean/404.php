<?php get_header();?>
<div id="content">
<div id="content-main">
			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="posttitle"  style="margin-left:5px;">
					<h2>Ooops...Where did you get such a link ?</h2>
					<p class="post-info">Server cannot locate what you are looking for !</p>
				</div>
				
				<div class="entry">
          <p>The Server tried all of its options before returning this page to you.</p>
          <p><img src="<?php bloginfo('stylesheet_directory') ;?>/img/404.gif" alt="404" class="left" />You are looking for something that is not here now.<br/>
			You can always try doing a <strong>search</strong> or browsing through the <strong>Archives</strong>.<br/>Don't loose your hope just yet.<br style="clear:both" /></p>
				</div>
		
				<p class="postmetadata">Posted as Not Found</p>
				
			</div>	
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer(); ?>