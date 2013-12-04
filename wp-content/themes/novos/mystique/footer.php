<?php /* Mystique/digitalnature */ ?>

 <?php do_action('mystique_after_main'); ?>

 <!-- footer -->
  <div id="footer">

 <?php
  $jquery = get_mystique_option('jquery');
  // at least 1st footer area must have widgets
  if (is_sidebar_active('footer-1')):
    $sidebar2 = is_sidebar_active('footer-2');
    $sidebar3 = is_sidebar_active('footer-3');
    $sidebar4 = is_sidebar_active('footer-4');
   ?>

   <!-- blocks + slider -->
   <div id="footer-blocks" class="page-content <?php if(($sidebar2 || $sidebar3 || $sidebar4) && $jquery) echo 'withSlider'; ?>">

    <?php if(($sidebar2 || $sidebar3 || $sidebar4) && $jquery): ?>
    <!-- block navigation -->
    <div class="slide-navigation">
     <a href="#" class="previous"><span>previous</span></a>
     <a href="#" class="next"><span>next</span></a>
    </div>
    <!-- /block navigation -->
    <?php endif; ?>

    <!-- block container -->
	<div class="slide-container clear-block">
		<ul class="slides">

          <!-- slide (100%) -->
          <li class="slide slide-1 page-content">
            <div class="slide-content">
              <ul class="blocks widgetcount-<?php  $sidebars_widgets = wp_get_sidebars_widgets(); echo count($sidebars_widgets['footer-1']); ?>">
               <?php dynamic_sidebar('footer-1'); ?>
              </ul>
            </div>
          </li>
          <!-- /slide -->

          <?php if($sidebar2): ?>
          <!-- slide (100%) -->
          <li class="slide slide-2 page-content">
            <div class="slide-content">
              <ul class="blocks widgetcount-<?php  $sidebars_widgets = wp_get_sidebars_widgets(); echo count($sidebars_widgets['footer-2']); ?>">
               <?php dynamic_sidebar('footer-2'); ?>
              </ul>
            </div>
          </li>
          <!-- /slide -->
          <?php endif; ?>


          <?php if($sidebar3): ?>
          <!-- slide (100%) -->
          <li class="slide slide-3 page-content">
            <div class="slide-content">
              <ul class="blocks widgetcount-<?php  $sidebars_widgets = wp_get_sidebars_widgets(); echo count($sidebars_widgets['footer-3']); ?>">
               <?php dynamic_sidebar('footer-3'); ?>
              </ul>
            </div>
          </li>
          <!-- /slide -->
          <?php endif; ?>

          <?php if($sidebar4): ?>
          <!-- slide (100%) -->
          <li class="slide slide-4 page-content">
            <div class="slide-content">
              <ul class="blocks widgetcount-<?php  $sidebars_widgets = wp_get_sidebars_widgets(); echo count($sidebars_widgets['footer-4']); ?>">
               <?php dynamic_sidebar('footer-4'); ?>
              </ul>
            </div>
          </li>
          <!-- /slide -->
          <?php endif; ?>

		</ul>
	</div>
    <!-- /block container -->

    <?php if($sidebar2 || $sidebar3 || $sidebar4): ?>
    <div class="leftFade"></div>
    <div class="rightFade"></div>
    <?php endif; ?>

   </div>
   <!-- /blocks + slider -->
 <?php endif; ?>

    <div class="page-content">
    <div id="copyright">

     <?php echo do_shortcode(stripslashes(get_mystique_option('footer_content'))); ?>

     <!--[if lte IE 6]> <script type="text/javascript"> isIE6 = true; isIE = true; </script> <![endif]-->
     <!--[if gte IE 7]> <script type="text/javascript"> isIE = true; </script> <![endif]-->

     <?php wp_footer(); ?>

    </div>

   </div>
  </div>
  <!-- /footer -->

 </div>
</div>
<!-- /shadow -->

  <?php if($jquery): ?>
  <!-- page controls -->
  <div id="pageControls"></div>
  <!-- /page controls -->
  <?php endif; ?>

  <!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->

 </div>
</body>
</html>
