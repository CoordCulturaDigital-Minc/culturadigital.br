<?php
/*
* admin.php - This file have the admin page template
*
* Copyright (C) 2010  Marcos Maia Lopes <marcosmlopes01@gmail.com>
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/* hl_validate()
 * Description: This function validates the form against malicious users.
 */
function hl_validate( $arr ) {
  if( !is_array($arr) || empty($arr) ) {
    return new WP_Error('error 001', __('Undefined error', 'hl-sticky-posts'));
  }

  // Other validation goes here.
}

/* hl_sticky_post_order()
 * Description: This function change sticky posts menu_order on wp_posts table.
 */
function hl_sticky_post_order( $arr ) {
  global $wpdb;

  $validate = hl_validate( $arr );

  if( !is_wp_error($validate) ) {
    for( $i = 0; $i < count($arr); $i++ ) {
      $id = $wpdb->prepare('%d', $arr[$i]);

      $wpdb->update( $wpdb->posts, array('menu_order' => $i), array('ID' => $id) );
    }

    return '<p class="updated">'. __('Sticky posts order updated', 'hl-sticky-posts') .'!</p>';
  } else {
    $out = '<p class="error">'.$validate->get_error_code().' - '. $validate->get_error_message() .'</p>';
    return $out;
  }
}

?>
<div class="wrap">
  <?php screen_icon(); ?>
  <h2>Highlight sticky posts</h2>
  <p><?php _e('How to use? see', 'hl-sticky-posts') ?> <a href="<?php echo PLUGINPATH.'/readme.txt'; ?>">README</a></p>

  <?php
    if( isset($_POST['save-post-order']) ) {
      $arr = $_POST['hl-sticky-posts'];

      echo hl_sticky_post_order( $arr );
    }
  ?>

  <div class="sticky-posts">
    <?php
      $sticky = get_option('sticky_posts');

      $obj = new WP_Query(
        array(
          'post__in' => $sticky,
          'orderby' => 'menu_order',
          'order' => 'ASC'
        )
      );
    ?>

    <?php if( !empty($sticky) && $obj->have_posts() ) : ?>

      <ul class="tabs">
        <li class="selected"><span><?php _e('All', 'hl-sticky-posts') ?></span></li>
      </ul>

      <div id="all" class="tab-content">
        <form action="" method="post">
          <div class="sticky-category">
            <p class="helper"><?php _e('Here you can drag`n drop these posts to change sticky order', 'hl-sticky-posts') ?></p>
          </div>

          <ul class="posts">
            <?php while( $obj->have_posts() ) : $obj->the_post() ?>
              <li class="item">
                <input type="hidden" name="hl-sticky-posts[]" value="<?php echo $obj->post->ID ?>" />
                <h3><?php the_title(); ?></h3>
              </li>
            <?php endwhile ?>
          </ul>
          
          <div class="bt">
            <input type="submit" name="save-post-order" value="Salvar" class="hl-save" />
          </div>
        </form>
      </div>

    <?php else : ?>
      <p><strong><?php _e('No sticky posts found', 'hl-sticky-posts'); ?></strong></p>
    <?php endif ?>
  </div>
</div>