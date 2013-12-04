<?php

// more icons need to added to this extension...

function mystique_xtranav_css(){
 $i = THEME_URL.'/images'; ?>
#header a.nav-extra.rss{background:transparent url(<?php echo $i; ?>/nav-icons.png) no-repeat right top;}
#header a.twitter{background:transparent url(<?php echo $i; ?>/nav-icons.png) no-repeat left top;}
<?php
}

function mystique_xtranav_admin(){ ?>
   <tr>
    <th scope="row"><p><?php _e("Extra navigation items","mystique"); ?><span><?php _e("(Leave blank to disable)","mystique"); ?></span></p></th>
    <td class="clearfix">

     <div class="alignleft">
      <label for="opt_xtranav_twitter"><?php _e("Twitter ID", "mystique"); ?></label><br />
      <input name="xtranav_twitter" id="opt_xtranav_twitter" type="text" value="<?php echo wp_specialchars(get_mystique_option('xtranav_twitter')); ?>" /><br />
     </div>

     <div class="alignleft">
      <label for="opt_xtranav_rss"><?php _e("RSS URL", "mystique"); ?></label><br />
      <input name="xtranav_rss" id="opt_xtranav_rss" type="text" value="<?php echo wp_specialchars(get_mystique_option('xtranav_rss')); ?>" /><br />
     </div>

    </td>
   </tr> <?php
}

function mystique_xtranav_settings($defaults){
  $defaults['xtranav_twitter'] = 'Wordpress';
  $defaults['xtranav_rss'] = get_bloginfo('rss2_url'); // wp default rss feed
  return $defaults;
}

function mystique_xtranav_icons($nav_extra){
  if(get_mystique_option('xtranav_twitter'))
   $nav_extra .= '<a href="http://twitter.com/'.wp_specialchars(get_mystique_option('xtranav_twitter')).'" class="nav-extra twitter" title="'.__("Follow me on Twitter","mystique").'"><span>'.__("Follow me on Twitter","mystique").'</span></a>';

  if(get_mystique_option('xtranav_rss'))
   $nav_extra .= '<a href="'.wp_specialchars(get_mystique_option('xtranav_rss')).'" class="nav-extra rss" title="'.__("RSS Feeds","mystique").'"><span>'.__("RSS Feeds","mystique").'</span></a>';
  return $nav_extra;
}

add_action('mystique_css','mystique_xtranav_css');

add_filter('mystique_default_settings','mystique_xtranav_settings');
add_action('mystique_admin_navigation','mystique_xtranav_admin');
add_action('mystique_navigation_extra', 'mystique_xtranav_icons')

?>