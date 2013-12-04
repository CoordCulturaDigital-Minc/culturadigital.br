<?php
/*
Plugin Name: Widget: Custom Posts
Plugin URI: http://xemele.cultura.gov.br/
Description: Allow the user to load som posts on sidebar
Version: 0.2
Author: Equipe Web MinC
Author URI: http://xemele.cultura.gov.br/
*/

class widget_custom_posts
{
  // ATRIBUTOS ////////////////////////////////////////////////////////////////////////////////////
  
  // METODOS //////////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function display_custom_posts($args)
  {
    // Extraindo as variáveis dos widgets
    extract($args);
    
    // Carregar as opções desse widget
    $options = get_option("custom-posts");
    
    // Valor padrão, caso nada tenha sido informado
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
                <?php if(1 == $options['show-category']) : ?><p class="category">/ <?php the_category(' '); ?></p><?php endif; ?>
                <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <?php if(1 == $options['show-excerpt']) : ?><p><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php limit_chars(get_the_excerpt(), 100); ?></a></p><?php endif; ?>
              </li>
            <?php endwhile; ?>
          </ul>
		  
        <?php print $after_widget; ?>    
		
      <?php endif; ?>

    <?php
  }
  
  /************************************************************************************************
    
  ************************************************************************************************/
  function admin_custom_posts()
  {
    // Carregar as opções desse widget
    $options = get_option('custom-posts');
    
    // Salvando as opções
    if($_POST['custom-posts'])
    {
      $newoptions['title'] = $_POST['custom-posts-title'];
      
      $newoptions['category'] = (int) $_POST['custom-posts-category'];
      $newoptions['showposts'] = (int) $_POST['custom-posts-showposts'];
      
      $newoptions['show-excerpt'] = (int) $_POST['custom-posts-show-excerpt'];
      $newoptions['show-author'] = (int) $_POST['custom-posts-show-author'];
      $newoptions['show-category'] = (int) $_POST['custom-posts-show-category'];
      $newoptions['show-date'] = (int) $_POST['custom-posts-show-date'];
      
      if($options != $newoptions)
      {
        $options = $newoptions;
        update_option('custom-posts', $options);
      }
    }
    
    // Valor padrão, caso nada tenha sido informado
    if(empty($options['title'])) $options['title'] = "Posts";
    if(empty($options['showposts'])) $options['showposts'] = 5;
    
    // Formulário
    ?>
      <input type="hidden" name="custom-posts" value="1" />
      
      <p>
        <label for="custom-posts-title">Título:</label>
        <input type="text" name="custom-posts-title" maxlength="26" value="<?php print $options['title']; ?>" class="widefat" />
      </p>
      
      <p>
        <label for="custom-posts-category">Exibir apenas posts da categoria:</label>
        <input type="text" name="custom-posts-category" maxlength="6" value="<?php print $options['category']; ?>" class="widefat" />
      </p>
      <p>
        <label for="custom-posts-category">Quantidade de posts:</label>
        <input type="text" name="custom-posts-showposts" size="2" maxlength="2" value="<?php print $options['showposts']; ?>" />
      </p>
      
      <p>
        <label for="custom-posts-excerpt">
          <input type="checkbox" name="custom-posts-show-excerpt" value="1" <?php if($options['show-excerpt']) print "checked='checked'"; ?> />Resumo dos posts
        </label><br />
        
        <label for="custom-posts-author">
          <input type="checkbox" name="custom-posts-show-author" value="1" <?php if($options['show-author']) print "checked='checked'"; ?> />Autor dos posts
        </label><br />
        
        <label for="custom-posts-category">
          <input type="checkbox" name="custom-posts-show-category" value="1" <?php if($options['show-category']) print "checked='checked'"; ?> />Categoria dos posts
        </label><br />
        
        <label for="custom-posts-date">
          <input type="checkbox" name="custom-posts-show-date" value="1" <?php if($options['show-date']) print "checked='checked'"; ?> />Data dos posts
        </label><br />
      </p>
      
    <?php
  }
  
  // CONSTRUTOR ///////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function widget_custom_posts()
  {
    // Registrando o widget
    register_sidebar_widget('Custom Posts', array(&$this, 'display_custom_posts'));
    
    // Registrando as opções do widget
    register_widget_control('Custom Posts', array(&$this, 'admin_custom_posts'));
  }
  
  // DESTRUTOR ////////////////////////////////////////////////////////////////////////////////////
  
}

$widget_custom_posts = new widget_custom_posts();

?>
