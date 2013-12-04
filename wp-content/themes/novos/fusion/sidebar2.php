<!-- 2nd sidebar -->
<div id="sidebar2">
 <div id="sidebar2-wrap">
  <ul id="sidelist2">
    <?php
     if (function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>
      <li><div class="warning">You enabled the 2nd sidebar. Add some widgets here to remove this notice</div></li>
    <?php endif; ?>
  </ul>
 </div>
</div>
<!-- /2nd sidebar -->
