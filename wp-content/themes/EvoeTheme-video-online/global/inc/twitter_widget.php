<?php
/*
Plugin Name: Widget: Ultimos do Twitter
Plugin URI: http://xemele.cultura.gov.br/
Description: Show the content of the current page
Version: 0.1
Author: Equipe Web MinC
Author URI: http://xemele.cultura.gov.br/
*/

class widget_twitter_last
{
  // ATRIBUTOS ////////////////////////////////////////////////////////////////////////////////////
  
  // METODOS //////////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function display_twitter_last($args)
  {
  	global $wpdb;
    // Extraindo as variáveis dos widgets
    extract($args);
	
	// Carregar as opções desse widget
    $options = get_option("twitter-last");
    
    // Posts
    ?>
  
    <?php print $before_widget; ?>
	<?php print $before_title . $options['title'] . $after_title; ?>
    <span class="twitt">Tweets:</span>
	<ul>
		<?php
		 
		$username = $xl_twitter_username;
		$xml_link = "http://twitter.com/statuses/user_timeline/". $options['user'] .".xml?count=" . $options['number'];
		$f = file($xml_link);
		$i = 0; $j = 0; $k = 0;
		foreach($f as $l => $v) {
			if(preg_match('/<created_at>(.*?)<\/created_at>/', $v, $res_date))
				if($res_date[1] != 'Sun Aug 23 18:21:24 +0000 2009') { 
					$i++;
					$date[$i] = substr($res_date[1], 10, 6);
				}
			if(preg_match('/<text>(.*?)\/text>/', $v, $res_text)) {
				$j++;
				$text[$j] = substr($res_text[1], 0, -1);
				if(preg_match('/http:\/\/(.*?)</', $res_text[1], $res_text2)) 
					$text[$j] = str_replace(' - http://'.$res_text2[1], ' <a href="http://'.$res_text2[1].'">(link)</a>', $text[$j]);
				if(preg_match('/@(.*?) /', $text[$j], $res_text3))
					$text[$j] = str_replace($res_text3[1], '<a href="http://twitter.com/'.$res_text3[1].'">'.$res_text3[1].'</a>', $text[$j]);
			}
			if(preg_match('/<source>(.*?)<\/source>/', $v, $res_source)) {
				$k++;
				$source[$k] = $res_source[1];
				if(preg_match('/nofollow(.*?)a/', $source[$k] , $res_source2)) {
					$source[$k] = $res_source2[1];
					$source[$k] = str_replace('/', '', $source[$k]);
					$source[$k] = substr($source[$k], 10, -4);
				}
			}
		}
		for($i = 1; $i <= $k; $i++) 
			echo "<li><p>". $text[$i] . "</p>" . "<p class='date'>às " . $date[$i] . "</p>" . "</li>";
				
		?>
	</ul>
    <div class="clear"></div>
	<?php print $after_widget; ?>
    
    <?php
  }
  
  
    /************************************************************************************************
    
  ************************************************************************************************/
  function admin_twitter_last()
  {
    // Carregar as opções desse widget
    $options = get_option('twitter-last');
    
    // Salvando as opções
    if($_POST['twitter-last'])
    {
      $newoptions['title'] = $_POST['twitter-last-title'];
	  $newoptions['user'] = $_POST['twitter-last-user'];
      $newoptions['number'] = (int) $_POST['twitter-last-number'];
	  
      if($options != $newoptions)
      {
        $options = $newoptions;
        update_option('twitter-last', $options);
      }
    }
    
    // Valor padrão, caso nada tenha sido informado
    if(empty($options['title'])) $options['title'] = "Ultimas do Twitter";
	if(empty($options['user'])) $options['user'] = "xemele";
	if(empty($options['number'])) $options['number'] = "5";
    
    // Formulário
    ?>
	
      <input type="hidden" name="twitter-last" value="1" />
      
      <p>
        <label for="twitter-last-title">T&iacute;tulo:</label>
        <input type="text" name="twitter-last-title" maxlength="26" value="<?php print $options['title']; ?>" class="widefat" />
      </p>
	  
	  <p>
        <label for="twitter-last-user">Usu&aacute;rio:</label>
        <input type="text" name="twitter-last-user" maxlength="56" value="<?php print $options['user']; ?>" class="widefat" />
      </p>
	  
	  <p>
        <label for="twitter-last--number">Quantidade:</label>
        <input type="text" name="twitter-last-number" maxlength="26" value="<?php print $options['number']; ?>" class="widefat" />
      </p>
 
   <?php
  }
  
  // CONSTRUTOR ///////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    
  ************************************************************************************************/
  function widget_twitter_last()
  {
    // Registrando o widget
    register_sidebar_widget('Twitter', array(&$this, 'display_twitter_last'));
	
	// Registrando as opções do widget
    register_widget_control('Twitter', array(&$this, 'admin_twitter_last'));
  }
  
  // DESTRUTOR ////////////////////////////////////////////////////////////////////////////////////
  
}

$widget_twitter_last = new widget_twitter_last();

?>