<?php
/*
Function Name: Widget: Videos em Destaque
Plugin URI: http://xemele.cultura.gov.br/
Description: Allow the user to load som posts on sidebar
Version: 0.1
Author: Equipe Web MinC
Author URI: http://xemele.cultura.gov.br/
*/

class widget_videos
{
  // ATRIBUTOS ////////////////////////////////////////////////////////////////////////////////////
  
  // METODOS //////////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function custom_videos_display($args)
  {
    // Extraindo as variáveis dos widgets
    extract($args);
    
    // Carregar as opções desse widget
    $options = get_option("custom-videos");
    
    // Valor padrão, caso nada tenha sido informado
    if(empty($options['showposts'])) $options['showposts'] = 1;
    
    // Posts
    ?>
    
      <?php $custom_videos = new WP_Query("showposts={$options['showposts']}&cat={$options['category']}"); ?>
 
 
 		<div class="videoHome">

			
			
				<?php if($custom_videos->have_posts()) : ?>
				<?php while($custom_videos->have_posts()) : $custom_videos->the_post(); ?>
		
				<div class="textwidget mostrarVideo">
				
				<h2><?php print $options['title']; ?></h2>
				
				<object width="467" height="273"><param name="movie" value="<?php print $options['url_video']; ?>"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="<?php print $options['url_video']; ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="467" height="273"></embed></object>
				</div>

					<?php endwhile; ?>


				<?php else : ?>  

				<div class="post">  

					<h2>N&atilde;o encontrado </h2>

					<p>Desculpe, mas o que voc&ecirc; estava procurando n&atilde;o est&aacute; aqui.</p>

				</div>

				<?php endif; ?>		

		</div>


 


    
    <?php
  }
  
  /************************************************************************************************
    
  ************************************************************************************************/
  function custom_videos_control()
  {
    // Carregar as opções desse widget
    $options = get_option('custom-videos');
    
    // Salvando as opções
    if($_POST['custom-videos'])
    {
      $newoptions['title'] = $_POST['custom-videos-title'];
	  $newoptions['url_video'] = $_POST['custom-videos-url'];
      
      if($options != $newoptions)
      {
        $options = $newoptions;
        update_option('custom-videos', $options);
      }
    }
    
    // Valor padrão, caso nada tenha sido informado
    if(empty($options['showposts'])) $options['showposts'] = 1;
    
    // Formulário
    ?>
      <input type="hidden" name="custom-videos" value="1" />
      
      <p>
        <label for="custom-videos-title"><?php _e('title'); ?>:</label>
        <input type="text" name="custom-videos-title" maxlength="26" value="<?php print $options['title']; ?>" class="widefat" />
      </p>
	  
	   <p>
        <label for="custom-videos-url"><?php _e('Url do Video'); ?>:</label>
        <input type="text" name="custom-videos-url" value="<?php print $options['url_video']; ?>" class="widefat" /><br />
		Digite o caminho completo
      </p>
	  
      
    <?php
  }

  // CONSTRUTOR ///////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function widget_videos()
  {
    // Registrando o widget
    register_sidebar_widget(__('Videos em Destaque'), array(&$this, 'custom_videos_display'), 'custom-videos');
    
    // Registrando as opções do widget
    register_widget_control(__('Videos em Destaque'), array(&$this, 'custom_videos_control'));
  }
  
  // DESTRUTOR ////////////////////////////////////////////////////////////////////////////////////
  
}

$widget_videos = new widget_videos();

?>