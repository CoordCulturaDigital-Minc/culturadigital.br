<?php
  include_once(TEMPLATEPATH . '/global/inc/the_thumb.php');
  include_once(TEMPLATEPATH . '/global/inc/widget_video.php');

  // my_register_sidebar
  function my_register_sidebar($name)
  {
    register_sidebar(
        array(
        'name' => $name,
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>'
      )
    );
  }

  // cadastrar sidebars
  if(function_exists('register_sidebar'))
  {
    my_register_sidebar('sidebar');
  }

  // Custom comment
  function mytheme_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;

    echo '<div class="infoComment" id="comment-', comment_ID(), '">';
      echo '<div class="avatar">';

      if( get_avatar($comment, $size='48') != '' ) {
          echo get_avatar($comment, $size='48');
      } else {
          echo '<img src="', get_bloginfo('stylesheet_directory'), '/global/imageAvatarAnonimo.jpg" alt="Sem avatar" width="48" height="48" />';
      }

      echo '</div>';

      echo '<div class="userComment">';
        echo '<p class="userName">', get_comment_author_link(), ' ', get_comment_date( 'j' ), ' de ', get_comment_date( 'F' ), '</p>';
        if ($comment->comment_approved == '0') {
          echo '<p>', __('Your comment is awaiting moderation.'), '</p>';
        } else {
          echo '<p>', comment_text(), '</p>';
        }
      echo '</div>';
    echo '</div>';
  }
?>