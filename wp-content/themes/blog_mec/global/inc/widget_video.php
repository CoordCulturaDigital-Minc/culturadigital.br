<?php
/*
* widget_video.php - Video widget
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

class widget_video extends WP_Widget
{
  function widget_video() {
    $widget_args = array('classname' => ' widget_video', 'description' => __( 'Wordpress Video widget') );
    parent::WP_Widget('video', __('Video Widget'), $widget_args);
  }

  function widget($args, $instance) {
    extract($args);

    $title = apply_filters('widget_title', empty($instance['title']) ? 'Video' : $instance['title']);
    $video_id = $instance['youtube_video_id'];

    if( !empty($video_id) ) {

      echo '<div id="video_destaque">';
        echo '<h3>V&iacute;deo em destaque</h3>';
        echo '<h4>', $title, '</h4>';
        echo '<div id="box_video">';
        echo '<object type="application/x-shockwave-flash" data="http://www.youtube.com/v/', $video_id, '?rel=1&amp;autoplay=0&amp;loop=0&amp;egm=0&amp;border=0&amp;fs=1&amp;showinfo=0" style="width: 383px; height: 216px;">';
          echo '<param name="wmode" value="transparent">';
          echo '<param name="movie" value="http://www.youtube.com/v/', $video_id, '?rel=1&amp;autoplay=0&amp;loop=0&amp;egm=0&amp;border=0&amp;fs=1&amp;showinfo=0">';
          echo '<param name="allowfullscreen" value="true">';
        echo '</object>';
        echo '<p class="button"><a href="', get_permalink('3'), '">Veja mais v&iacute;deos</a></p>';
      echo '</div>';

      echo $after_widget;

    }
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
		
    if( $instance != $new_instance ) {
      $instance = $new_instance;
    }
		
    return $instance;
  }

  function form($instance) {
    $title = esc_attr( $instance['title'] );
    $video = esc_attr( $instance['youtube_video_id'] );
    
    echo '<p>';
      echo '<label for="', $this->get_field_id('title'), '">Título:</label>';
      echo '<input type="text" id="', $this->get_field_id('title'), '" name="', $this->get_field_name('title'), '" maxlength="26" value="', $title, '" class="widefat" />';
    echo '</p>';

    echo '<p>';
      echo '<label for="', $this->get_field_id('youtube_video_id'), '">Video Id:</label>';
      echo '<input type="text" id="', $this->get_field_id('youtube_video_id'), '" name="', $this->get_field_name('youtube_video_id'), '" maxlength="26" value="', $video, '" class="widefat" />';
    echo '</p>';
  }
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("widget_video");'));