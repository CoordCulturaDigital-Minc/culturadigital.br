<div id="sidebar">
<ul>

<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar()) : ?>
<li class="sidebox">
	<h2><?php _e("Categories") ?></h2>
	<ul>
		 <?php wp_list_categories('show_count=1&title_li=&use_desc_for_title=0'); ?>
	</ul>		
</li>

<li class="sidebox">
      <h2>
        <?php _e('Comments');?>
      </h2>
      <ul class="recent-comments">
        <?php include (TEMPLATEPATH . '/recent_comments.php'); ?>
        <?php mw_recent_comments(5, false, 100, 30, 50, 'all', '<li>%date%: <a href="%permalink%" title="%title%">%title% (<em>%author_name%</em>)</a></li>','d.m.y, H:i'); ?>
      </ul>
</li>

<li class="sidebox"> 
        <h2>
        <?php _e('Links'); ?>
      </h2>
      <ul class="list-blogroll">
        <?php get_links( -1, '<li>', '</li>', '', FALSE, 'id', FALSE, FALSE, -1, FALSE); ?>
      </ul>
    </li>

<li class="sidebox">
	<h2><?php _e("Archives") ?></h2>
	<ul><?php wp_get_archives('type=monthly&show_post_count=true'); ?></ul>
</li>
<?php endif; ?>

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/2.5/br/">
<img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/2.5/br/88x31.png" /></a>
<br />
<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/2.5/br/">Publicado sob licen&ccedil;a Creative Commons.</a></li>

</ul>
</div><!-- end id:sidebar -->
</div><!-- end id:content -->
</div><!-- end id:container -->