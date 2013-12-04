<div class="clr"></div>
<div id="contentbottom"></div>
</div>

<div id="subfooter">
	<div id="subfooterwrapper">
		<ul>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footerbar') ) : ?>
				<li><h2 class="widgettitle">Change This Footer</h2>
				<p class="textwidget">This Footer is easily and completely editable with widgets.</p>  <p class="textwidget">Log into your admin panel, click on "Design" followed by "Widgets".  From there you can arrange this sidebar by draging the options into their respective places on this sidebar.</p>  <p class="textwidget">More information on using widgets can be found <a href="http://automattic.com/code/widgets/use/">here</a>.</p><p class="textwidget">(This note will not be displayed once you have widget-ized this sidebar)</p>
				</li>
			<?php endif; ?>
		</ul>
	</div><!--subfooterwrapper-->
	
	<div class="clr"></div>
</div>