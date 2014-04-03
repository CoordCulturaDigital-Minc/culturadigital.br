<?php

/*
$Id: header.php 179317 2009-12-03 19:55:03Z jamesgpearce $

$URL: http://plugins.svn.wordpress.org/wordpress-mobile-pack/trunk/themes/mobile_pack_base/header.php $

Copyright (c) 2009 James Pearce & friends, portions mTLD Top Level Domain Limited, ribot, Forum Nokia

Online support: http://wordpress.org/extend/plugins/wordpress-mobile-pack/

This file is part of the WordPress Mobile Pack.

The WordPress Mobile Pack is Licensed under the Apache License, Version 2.0
(the "License"); you may not use this file except in compliance with the
License.

You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed
under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
CONDITIONS OF ANY KIND, either express or implied. See the License for the
specific language governing permissions and limitations under the License.
*/

while (have_posts()) {
  the_post();
  print '<div class="post" id="post-' . get_the_ID() . '">';
  if(is_single() || is_page()) {
    print '<h1>' . get_the_title() . '</h1>';
    wpmp_theme_post_single();
  } else {
    print '<ul class="list"><li><a href="'; the_permalink(); print '" rel="bookmark" title="' . __('Link to', 'wpmp') . ' ' . get_the_title() . '">' . get_the_title() . '</a></li></ul>';
    wpmp_theme_post_summary();
  }
}
if(!is_single() && !is_page()) {
  print '<p class="navigation">';
  next_posts_link(__('Older', 'wpmp'));
  print ' ';
  previous_posts_link(__('Newer', 'wpmp'));
  print '</p>';
}


?>