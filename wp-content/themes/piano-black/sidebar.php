<div id="right-col">

 <?php $options = get_option('pb_options'); ?>
 <?php if ($options['show_information']) : ?>
 <h3 class="side-title" id="information-title"><?php if($options['information_title']) { echo ($options['information_title']); } else { _e('INFORMATION','piano-black'); } ?></h3>
 <div class="information-contents">
 <?php if($options['information_contents']) { echo ($options['information_contents']); } else { _e('Change this sentence and title from admin Theme option page.','piano-black'); } ?>
 </div>
 <?php endif; ?>

<?php if(is_active_sidebar('top')||is_active_sidebar('bottom')||is_active_sidebar('left')||is_active_sidebar('right')){ ?>

 <div id="side-top">
  <?php dynamic_sidebar('top'); ?>
 </div>
 <div id="side_middle" class="clearfix">
  <div id="side-left">
   <?php dynamic_sidebar('left'); ?>
  </div>
  <div id="side-right">
   <?php dynamic_sidebar('right'); ?>
  </div>
 </div>
 <div id="side-bottom">
  <?php dynamic_sidebar('bottom'); ?>
 </div>

<?php } else { ?>

 <div id="side-top">
  <div class="side-box">
   <h3 class="side-title"><?php _e('RECENT ENTRY','piano-black'); ?></h3>
   <ul>
    <?php $myposts = get_posts('numberposts=5'); foreach($myposts as $post) : ?>
    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
    <?php endforeach; ?>
   </ul>
  </div>
 </div>
 <div id="side_middle" class="clearfix">
  <div id="side-left-ex">
   <div class="side-box-short">
    <h3 class="side-title"><?php _e('CATEGORY','piano-black'); ?></h3>
    <ul>
     <?php wp_list_cats('sort_column=name'); ?>
    </ul>
   </div>
  </div>
  <div id="side-right-ex">
   <div class="side-box-short">
    <h3 class="side-title"><?php _e('ARCHIVES','piano-black'); ?></h3>
    <ul>
     <?php wp_get_archives('type=monthly'); ?>
    </ul>
   </div>
  </div>
 </div>
 <div id="side-bottom-ex">
  <div class="side-box">
   <h3 class="side-title"><?php _e('LINKS','piano-black'); ?></h3>
   <ul>
    <?php wp_list_bookmarks('title_li=&categorize=0'); ?>
   </ul>
  </div>
 </div>


 <?php }; ?>

 <div class="side-box">
  <ul id="copyrights">
   <li>
      <?php
           global $wpdb;
           $post_datetimes = $wpdb->get_row($wpdb->prepare("SELECT YEAR(min(post_date_gmt)) AS firstyear, YEAR(max(post_date_gmt)) AS lastyear FROM $wpdb->posts WHERE post_date_gmt > 1970"));
           if ($post_datetimes) {
             $firstpost_year = $post_datetimes->firstyear;
             $lastpost_year = $post_datetimes->lastyear;
             $copyright = __('Copyright &copy;&nbsp; ', 'piano-black') . $firstpost_year;
             if($firstpost_year != $lastpost_year) {
               $copyright .= '-'. $lastpost_year;
             }
             $copyright .= ' ';
             echo $copyright;
           }
       ?>
    &nbsp;<a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></li>
   <li><?php _e('Theme designed by <a href="http://www.mono-lab.net/">mono-lab</a>','piano-black'); ?></li>
   <li id="wp"><?php _e('Powered by <a href="http://wordpress.org/">WordPress</a>','piano-black'); ?></li>
  </ul>
 </div>

</div><!-- #left-col end -->