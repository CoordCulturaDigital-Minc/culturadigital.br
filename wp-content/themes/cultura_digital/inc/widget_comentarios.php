<?php
/*
Plugin Name: Widget: Comentários
Description: Mostra os últimos comentários
Version: 0.1
Author: WebDF
Author URI: http://webdf.com.br/
*/

class widget_comentarios
{
  // ATRIBUTOS ////////////////////////////////////////////////////////////////////////////////////
  
  // METODOS //////////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function display_comentarios($args)
  {
    global $wpdb;
    
    // Extraindo as variáveis dos widgets
    extract($args);
    
    // Comentários
    $comments = $wpdb->get_results("
      SELECT ID, post_title, comment_ID, comment_post_ID, comment_author, comment_content 
      FROM {$wpdb->comments} 
      INNER JOIN {$wpdb->posts} ON (ID = comment_post_ID) 
      WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' 
      ORDER BY comment_ID DESC 
      LIMIT 5
    ");
    
    ?>
    
    <?php if(!empty($comments)) : ?>
      <?php print $before_widget; ?>
        <?php print $before_title . 'Comentários' . $after_title; ?>
        <ul>
          <?php foreach($comments as $comment) : ?>
            <li>
              <p><strong>por <?php print $comment->comment_author; ?></strong></p>
      				<p><?php limit_chars($comment->comment_content, 200); ?></p>
    	  			<p>em <a href="<?php print get_permalink($comment->ID); ?>"><?php print $comment->post_title; ?></a></p>
    	  	  </li>
          <?php endforeach; ?>
        </ul>
      <?php print $after_widget; ?>
    <?php endif; ?>
    
    <?php
  }
  
  // CONSTRUTOR ///////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function widget_comentarios()
  {
    // Registrando o widget
    register_sidebar_widget('Comentarios', array(&$this, 'display_comentarios'));
  }
  
  // DESTRUTOR ////////////////////////////////////////////////////////////////////////////////////
  
}

$widget_comentarios = new widget_comentarios();

?>
