<?php
/*
Plugin Name: Widget: Custom Comments
Plugin URI: http://xemele.cultura.gov.br/
Description: Allow the user to load some comments on sidebar
Version: 0.1
Author: Equipe Web MinC
Author URI: http://xemele.cultura.gov.br/
*/

class widget_custom_comments
{
  // ATRIBUTOS ////////////////////////////////////////////////////////////////////////////////////
  
  // METODOS //////////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function display_custom_comments($args)
  {
    // Extraindo as variáveis dos widgets
    extract($args);
    
    // Carregar as opções desse widget
    $options = get_option("custom-comments");
    
    // Valor padrão, caso nada tenha sido informado
    if(empty($options['title'])) $options['title'] = "Posts";
    if(empty($options['showposts'])) $options['showposts'] = 5;
    
    // Posts
    ?>
    
      <?php $custom_posts = new WP_Query("showposts={$options['showposts']}&cat={$options['category']}"); ?>
      <?php if($custom_posts->have_posts()) : ?>
        <?php print $before_widget; ?>
          <?php print $before_title . $options['title'] . $after_title; ?>
          <ul>
            <?php while($custom_posts->have_posts()) : $custom_posts->the_post(); ?>
              <li>
                <?php the_thumb('thumbnail', 'align="left"'); ?>
                <?php if(1 == $options['category']) : ?><p class="category">/ <?php the_category(' '); ?></p><?php endif; ?>
                <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <?php if(1 == $options['excerpt']) : ?><p><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php limit_chars(get_the_excerpt(), 100); ?></a></p><?php endif; ?>
              </li>
            <?php endwhile; ?>
          </ul>
        <?php print $after_widget; ?>
      <?php endif; ?>
    
    <?php
  }
  
  /************************************************************************************************
    
  ************************************************************************************************/
  function admin_custom_comments()
  {
    // Carregar as opções desse widget
    $options = get_option('custom-comments');
    
    // Salvando as opções
    if($_POST['custom-comments'])
    {
      $newoptions['title'] = $_POST['custom-comments-title'];
      
      $newoptions['category'] = (int) $_POST['custom-comments-category'];
      $newoptions['showcomments'] = (int) $_POST['custom-comments-showcomments'];
      
      $newoptions['excerpt'] = (int) $_POST['custom-comments-excerpt'];
      $newoptions['author'] = (int) $_POST['custom-comments-author'];
      $newoptions['date'] = (int) $_POST['custom-comments-date'];
      
      if($options != $newoptions)
      {
        $options = $newoptions;
        update_option('custom-comments', $options);
      }
    }
    
    // Valor padrão, caso nada tenha sido informado
    if(empty($options['title'])) $options['title'] = "Posts";
    if(empty($options['showposts'])) $options['showposts'] = 5;
    
    // Formulário
    ?>
      <input type="hidden" name="custom-comments" value="1" />
      
      <p>
        <label for="custom-comments-title">Título:</label>
        <input type="text" name="custom-comments-title" maxlength="26" value="<?php print $options['title']; ?>" class="widefat" />
      </p>
      
      <p>
        <label for="custom-comments-category">Exibir apenas posts da categoria:</label>
        <input type="text" name="custom-comments-category" size="2" maxlength="2" value="<?php print $options['category']; ?>" />
        <label for="custom-comments-category">Quantidade de posts:</label>
        <input type="text" name="custom-comments-showposts" size="2" maxlength="2" value="<?php print $options['showposts']; ?>" />
      </p>
      
      <p>
        <label for="custom-comments-excerpt">
          <input type="checkbox" name="custom-comments-excerpt" value="1" <?php if($options['excerpt']) print "checked='checked'"; ?> />Resumo dos posts
        </label><br />
        
        <label for="custom-comments-author">
          <input type="checkbox" name="custom-comments-author" value="1" <?php if($options['author']) print "checked='checked'"; ?> />Autor dos posts
        </label><br />
        
        <label for="custom-comments-category">
          <input type="checkbox" name="custom-comments-category" value="1" <?php if($options['category']) print "checked='checked'"; ?> />Categoria dos posts
        </label><br />
        
        <label for="custom-comments-date">
          <input type="checkbox" name="custom-comments-date" value="1" <?php if($options['date']) print "checked='checked'"; ?> />Data dos posts
        </label><br />
      </p>
      
    <?php
  }
  
  // CONSTRUTOR ///////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function widget_custom_comments()
  {
    // Registrando o widget
    register_sidebar_widget('Custom Comments', array(&$this, 'display_custom_comments'));
    
    // Registrando as opções do widget
    register_widget_control('Custom Comments', array(&$this, 'admin_custom_comments'));
  }
  
  // DESTRUTOR ////////////////////////////////////////////////////////////////////////////////////
  
}

$widget_custom_comments = new widget_custom_comments();

?>
