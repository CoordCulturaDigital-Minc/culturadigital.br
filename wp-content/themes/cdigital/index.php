<?php get_header() ?>

    <div id="content" class="clear">

		<?php if(function_exists('dynamic_sidebar')) : ?>
		<div class="middle widgets">

		    <div class="main">


		        <div class="left-column-wrap">
	                <div class="left-column tabs">
		                <?php dynamic_sidebar('left-column'); ?>
                	</div>
                </div>

<!--                <div class="center-column-wrap">
		            <div class="center-column tabs">
		                <?php dynamic_sidebar('center-column'); ?>
		            </div>
                </div> -->




	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="96%" height="90" title=" ">
		<param name="movie" value="<?php bloginfo( stylesheet_directory ); ?>/img/700x90_banner_petro.swf" />
		<param name="quality" value="high" />
		<param name="wmode" value="transparent" />
		<embed src="<?php bloginfo( stylesheet_directory ); ?>/img/700x90_banner_petro.swf" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="96%" height="90"></embed>
	</object>
&nbsp;<br /><br />


				<div class="left-center-column tabs">
					<?php dynamic_sidebar('ultimas-blogadas'); ?>
				</div>



		        <div class="left-center-column tabs">
		            <?php dynamic_sidebar('left-center-column'); ?>
		        </div>

		        <div class="left-bottom-column tabs">
		            <?php dynamic_sidebar('left-bottom-column'); ?>
		        </div>

		    </div>

            <?php locate_template( array( 'sidebar.php' ), true ) ?>
            <div class="clear"></div>


		</div>

		<?php endif; ?>

    </div>

<?php get_footer() ?>
