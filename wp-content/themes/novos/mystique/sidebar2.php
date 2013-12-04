<!-- 2nd sidebar -->
<div id="sidebar2">
 <ul class="blocks">
   <?php do_action('mystique_sidebar2_start'); ?>
   <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('sidebar-2')) : else : ?>
    <li class="block block-info">
      <p class="empty-sidebar"><?php _e("You activated the 2nd sidebar. Add widgets here from the Dashboard to remove this message","mystique"); ?></p>
    </li>
   <?php endif; ?>
   <?php do_action('mystique_sidebar2_end'); ?>
 </ul>  
</div>
<!-- /2nd sidebar -->