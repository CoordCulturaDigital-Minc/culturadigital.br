<?php
/*
	Plugin Name: Republish
	Plugin URI: http://xemele.cultura.gov.br
	Description: Republica os posts de outros blogs.
	Author: Equipe WebMinC
	Version: 0.3 bp
	Author URI: http://xemele.cultura.gov.br

	Copyright (C) 2009 Equipe WebMinC

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
	GNU General Public License for more details.
*/

class republish
{
	// ATRIBUTOS ////////////////////////////////////////////////////////////////////////////////////

	// METODOS //////////////////////////////////////////////////////////////////////////////////////
	/************************************************************************************************
		Cria os valores padrão para a configuração do plugin.
		Checa pelos feeds dos usuários.

		@name    install
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-03-16
		@updated 2009-03-16
	************************************************************************************************/
	function install()
	{
		// Inicializa as variáveis necessárias
		$options = array('hashtag' => 'republish', 'interval' => 60, 'quantity' => 5, 'last_republish' => 0, 'credits' => '<p>Original: {post_url}</p>');

		// Salva no banco
		update_option('republish', $options);
	}

	/************************************************************************************************
		Criar os Menus na ára administrativa.

		@name    menus
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-03-16
		@updated 2009-03-16
	************************************************************************************************/
	function menus()
	{
		// Menus secundários
		// add_submenu_page($parent, $page_title, $menu_title, $access_level, $file, $function = '')
		add_submenu_page('options-general.php', __('Republish', 'republish'), __('Republish', 'republish'), 8, 'republish', array(&$this, 'config'));
	}

	/************************************************************************************************
		Configurações do plugin.

		@name    config
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-03-16
		@updated 2009-05-25
	************************************************************************************************/
	function config()
	{
		// Inicializa as variáveis necessárias
		$options = array();

		// Salvando as opções
		if($_POST['republish_save'])
		{
			$options['hashtag']  = $_POST['republish_hashtag'];
			$options['interval'] = (int) $_POST['republish_interval'];
			$options['quantity'] = (int) $_POST['republish_quantity'];
			$options['category'] = (int) $_POST['cat'];
			$options['credits']  = $_POST['republish_credits'];

			// Valor padrão, caso nada tenha sido informado
			if(empty($options['hashtag'])) $options['hashtag'] = 'republish';
			if(empty($options['interval'])) $options['interval'] = 1;
			if(empty($options['quantity'])) $options['quantity'] = 5;

			// Salva no banco
			update_option('republish', $options);
		}

		// Carregar as opções desse widget
		$options = get_option('republish');

		// Formulário
		?>
			<div class="wrap">
				<h2><?php _e('Republish Settings', 'republish'); ?></h2>
				<form action="" method="post">
					<input type="hidden" name="republish_save" value="1" />
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><label for="republish_hashtag"><?php _e('Hashtag', 'republish'); ?>:</label></th>
								<td><input type="text" name="republish_hashtag" maxlength="50" value="<?php print $options['hashtag']; ?>" /> <?php _e('Only posts with this tag will be republished.', 'republish'); ?></td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="republish_interval"><?php _e('Interval', 'republish'); ?>:</label></th>
								<td><input type="text" name="republish_interval" maxlength="10" value="<?php print $options['interval']; ?>" class="small-text" /> <?php _e('minutes.', 'republish'); ?></td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="republish_quantity"><?php _e('Number of posts', 'republish'); ?>:</label></th>
								<td><input type="text" name="republish_quantity" maxlength="2" value="<?php print $options['quantity']; ?>" class="small-text" /> <?php _e('max number of posts to import.', 'republish'); ?></td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="cat"><?php _e('Category to republish', 'republish'); ?>:</label></th>
								<td>
									<?php wp_dropdown_categories("selected={$options['category']}&hide_empty=0"); ?>
									<?php _e('Target category to import the posts.', 'republish'); ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="republish_credits"><?php _e('Credit format', 'republish'); ?>:</label></th>
								<td><textarea name="republish_credits" cols="30" rows="5"><?php print $options['credits']; ?></textarea><br />
								<?php _e('Format of the credits to be included on the posts. {site_title}, {site_url}, {author}, {post_title}, {post_url}', 'republish'); ?></td>
							</tr>
						</tbody>
					</table>

					<p class="submit">
						<button type="submit" class="button-primary"><?php _e('Save'); ?></button>
					</p>
				</form>
			</div>
		<?php
	}

	/************************************************************************************************
		Varre todos os usuários para saber se possuem novos streams.

		@name    check_streams
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-05-25
		@updated 2009-05-25
		@return  bool
	************************************************************************************************/
	function check_streams()
	{
		global $wpdb;

		$now = time();

		// Carrega os arquivos necessários para o importação do rss
		include_once(ABSPATH . WPINC . '/rss.php');

		// Carregar as opções desse widget
		$options = get_option('republish');

		// Verifica se está na hora de iniciar a rotina dos usuários (intervalo x 60 segundos)
		//print ($options['last_republish'] + ($options['interval'] * 60)) . ' > ' . $now . '<br>';
		if(($options['last_republish'] + ($options['interval'] * 60)) > $now)
			return false;

		// Busca todos os usuários desse blog (wp ou wpmu)
		$users = get_users_of_blog();

		// Para cada usuário
		foreach($users as $user) : //print_r($user);
			// Recupera os dados desse usuário
			$user_republish = get_usermeta($user->user_id, 'republish');

			// Se esse usuário tiver sido atualizado recentemente, pular para o próximo usuário
			//print ($user_republish['last_republish'] + ($options['interval'] * 60)) . ' > ' . $now;
			if(($user_republish['last_republish'] + ($options['interval'] * 60)) > $now)
				continue;

			// Atualiza o momento da última republicação desse usuário
			$user_republish['last_republish'] = $now;

			// Salva no banco
			update_usermeta($user->user_id, 'republish', $user_republish);

			// Republicar
			$this->republish_from_blog($user, $options);
			$this->republish_from_twitter($user, $options);
			$this->republish_from_flickr($user, $options);
			//$this->republish_from_delicious($user, $options);

			// Permitir apenas uma republicação por vez (para evitar timeout)
			return true;

		endforeach;

		// Atualiza o momento da última republicação de todos os usuários
		$options['last_republish'] = $now;

		// Salva no banco
		update_option('republish', $options);

		return true;
	}

	/************************************************************************************************
		Verifica se existem novos posts a serem republicados no blog desse usuário.

		@name    republish_from_blog
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-05-25
		@updated 2009-05-25
		@param   int $user
		@return  bool
	************************************************************************************************/
	function republish_from_blog($user, $options)
	{
		global $wpdb;

		// Recupera o feed do blog desse usuário
		if(function_exists('xprofile_get_field_data'))
			$user_feed = xprofile_get_field_data('Feed do blog', $user->user_id);

		// Se o usuário não tiver um feed, retornar
		if(empty($user_feed))
			return false;

		// Recupera os posts
		$rss = fetch_rss($user_feed); //print_r($rss);

		// Se nenhum post estiver sido carregado, retornar
		if(!is_array($rss->items))
			return false;

		// Limita o número de posts
		$items = array_slice($rss->items, 0, $options['quantity']);

		// Para cada post
		foreach($items as $item) : //print_r($item);
			// Se esse post não possuir a hashtag, pula para o próximo post
			if(!stripos($item['category'], $options['hashtag']))
				continue;

			// Inicializando as variáveis necessárias
			$post = array();

			// Montar o post
			$post['post_date']     = date('Y-m-d h:i:s');
			$post['post_title']    = $item['title'];
			$post['post_content']  = ($item['content']['encoded']) ? $item['content']['encoded'] : $item['description'];
			$post['post_author']   = $user->user_id;
			$post['post_status']   = 'publish';
			$post['post_category'] = $options['category'];

			// Monta os créditos
			$credits = $options['credits'];

			$credits = str_replace('{site_title}', $rss->title, $credits);
			$credits = str_replace('{site_url}', $rss->link, $credits);
			$credits = str_replace('{post_title}', $item['title'], $credits);
			$credits = str_replace('{post_url}', $item['link'], $credits);
			$credits = str_replace('{author}', $item['author'], $credits);

			// Adiciona os créditos ao conteúdo
			$post['post_content'] = $credits . $post['post_content'];

			// Buscar o id do post, caso ele já exista
			$post['ID'] = $wpdb->get_var("SELECT p.ID FROM {$wpdb->posts} AS p INNER JOIN {$wpdb->users} AS u ON (p.post_author = u.ID) WHERE post_title = '{$post['post_title']}' AND u.ID = {$post['post_author']}");

			// Não atualizar os posts até resolver o problema com as datas
			if(!empty($post['ID']))
			 continue;

			// Republicar - dessa forma o BuddyPress adiciona a atividade automaticamente
			wp_insert_post($post);
		endforeach;
	}

	/************************************************************************************************
		Adiciona a atividade.

		@name    register_activity
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-06-17
		@updated 2009-06-17
		@param   object $feed
		@param   string $type
		@return  bool
	************************************************************************************************/
	function register_activity($feed, $type = "generic")
	{
		global $wpdb;

		// Criando um item único
		$item_id = time();

		// Cadastrar em Atividade Geral
		$wpdb->query("INSERT INTO {$wpdb->base_prefix}bp_activity_sitewide (user_id, item_id, content, primary_link, component_name, component_action, date_cached, date_recorded) VALUES ('{$feed['author']}', 0, '{$feed['content']}', '{$feed['link']}', '{$type}', 'new_{$type}', '{$feed['date']}', '{$feed['date']}')");

		// Cadastrar em Atividade do Perfil
		$wpdb->query("INSERT INTO {$wpdn->base_prefix}bp_activity_user_activity (user_id, item_id, component_name, component_action, date_recorded) VALUES ('{$feed['author']}', {$item_id}, '{$type}', 'new_{$type}', '{$feed['date']}'')");
		$wpdb->query("INSERT INTO {$wpdn->base_prefix}bp_activity_user_activity_cached (user_id, item_id, content, primary_link, component_name, component_action, date_cached, date_recorded) VALUES ('{$feed['author']}', {$item_id}, '{$feed['content']}', '{$feed['link']}', '{$type}', 'new_{$type}', '{$feed['date']}', '{$feed['date']}')");
	}

	/************************************************************************************************
		Verifica se existem novos posts a serem republicados no twitter desse usuário.

		@name    republish_from_twitter
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-05-25
		@updated 2009-06-17
		@param   object $user
		@param   array $options
		@return  bool
	************************************************************************************************/
	function republish_from_twitter($user, $options)
	{
		global $wpdb;

		// Recupera o profile desse usuário
		if(function_exists('xprofile_get_field_data'))
			$user_profile = xprofile_get_field_data('Perfil no Twitter', $user->user_id);

		// Se o usuário não tiver um feed, retornar
		if(empty($user_profile))
			return false;

		// Monta o endereço do feed
		$user_feed = "http://twitter.com/statuses/user_timeline/{$user_profile}.rss";

		// Recupera os posts
		$rss = fetch_rss($user_feed); //print_r($rss);

		// Se nenhum post estiver sido carregado, retornar
		if(!is_array($rss->items))
			return false;

		// Limita o número de posts
		$items = array_slice($rss->items, 0, $options['quantity']);

		// Para cada post
		foreach($items as $item) : //print_r($item);

			// Se esse post não possuir a hashtag, pula para o próximo post
			if(!stripos($item['title'], "#{$options['hashtag']}") && !stripos($item['title'], "#marcocivil"))
				continue;

			// Inicializando as variáveis necessárias
			$feed = array();

			// Montar o post
			$feed['date']     = date('Y-m-d H:i:s');
			$feed['title']    = $item['title'];
			$feed['link']     = $item['link'];
			$feed['author']   = $user->user_id;

			$feed['content']  = bp_core_get_userlink($user->user_id);
			$feed['content'] .= " tuitou: <a href='{$item['link']}'>{$item['title']}</a>";

			$feed['content']  = addslashes($feed['content']);

			// Buscar o id do post, caso ele já exista
			if($wpdb->get_var("SELECT a.id FROM {$wpdb->base_prefix}bp_activity_sitewide AS a WHERE primary_link = '{$feed['link']}' AND a.user_id = {$feed['author']}"))
				continue;

			// Cadastrar Atividade
			//$wpdb->query("INSERT INTO {$wpdb->base_prefix}bp_activity_sitewide (user_id, item_id, secondary_item_id, content, primary_link, component_name, component_action, date_cached, date_recorded) VALUES ('{$feed['author']}', 0, 0, '{$feed['content']}', '{$feed['link']}', 'twitter', 'updated_twitter', '{$feed['date']}', '{$feed['date']}')");
			$this->register_activity($feed, 'twitter');
		endforeach;
	}

	/************************************************************************************************
		Verifica se existem novos posts a serem republicados no flickr desse usuário.

		@name    republish_from_flickr
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-05-27
		@updated 2009-06-17
		@param   object $user
		@param   array $options
		@return  bool
	************************************************************************************************/
	function republish_from_flickr($user, $options)
	{
		global $wpdb;

		// Recupera o profile desse usuário
		if(function_exists('xprofile_get_field_data'))
			$user_profile = xprofile_get_field_data('Perfil no Flickr', $user->user_id);

		// Se o usuário não tiver um feed, retornar
		if(empty($user_profile))
			return false;

		// Buscar o ID desse usuário
		//$profile = wp_remote_request('http://api.flickr.com/services/rest/?method=flickr.urls.lookupUser&api_key=bed56c11a80c6b68fa62f25ad393a94a&url=http://www.flickr.com/photos/bperotto/');

		// Monta o endereço do feed
		$user_feed = "http://api.flickr.com/services/feeds/photos_public.gne?id={$user_profile}&tags={$options['hashtag']}&lang=pt-br&format=rss_200";

		// Recupera os posts
		$rss = fetch_rss($user_feed); //print_r($rss);

		// Se nenhum post estiver sido carregado, retornar
		if(!is_array($rss->items))
			return false;

		// Limita o número de posts
		$items = array_slice($rss->items, 0, $options['quantity']);

		// Para cada post
		foreach($items as $item) : //print_r($item);
			// Inicializando as variáveis necessárias
			$feed = array();

			// Montar o post
			$feed['date']     = date('Y-m-d H:i:s');
			$feed['title']    = $item['title'];
			$feed['link']     = $item['link'];
			$feed['author']   = $user->user_id;

			$feed['content']  = bp_core_get_userlink($user->user_id);
			$feed['content'] .= " inseriu uma nova imagem: <a href='{$item['link']}'>{$item['title']}</a>";

			$feed['content']  = addslashes($feed['content']);

			// Buscar o id do post, caso ele já exista
			if($wpdb->get_var("SELECT a.id FROM {$wpdb->base_prefix}bp_activity_sitewide AS a WHERE primary_link = '{$feed['link']}' AND a.user_id = {$feed['author']}"))
				continue;

			// Cadastrar Atividade
			//$wpdb->query("INSERT INTO {$wpdb->base_prefix}bp_activity_sitewide (user_id, item_id, secondary_item_id, content, primary_link, component_name, component_action, date_cached, date_recorded) VALUES ('{$feed['author']}', 0, 0, '{$feed['content']}', '{$feed['link']}', 'flickr', 'updated_flickr', '{$feed['date']}', '{$feed['date']}')");
			$this->register_activity($feed, 'flickr');
		endforeach;
	}

	/************************************************************************************************		Verifica se existem novos posts a serem republicados no delicious desse usuário.

		@name    republish_from_delicious
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-06-08
		@updated 2009-06-17
		@param   object $user
		@param   array $options
		@return  bool
	************************************************************************************************/
	function republish_from_delicious($user, $options)
	{
		global $wpdb;

		// Recupera o profile desse usuário
		if(function_exists('xprofile_get_field_data'))
			$user_profile = xprofile_get_field_data('Perfil no Delicious', $user->user_id);

		// Se o usuário não tiver um feed, retornar
		if(empty($user_profile))
			return false;

		// Monta o endereço do feed
		$user_feed = "http://feeds.delicious.com/v2/rss/{$user_profile}/{$options['hashtag']}?count={$options['quantity']}";

		// Recupera os posts
		$rss = fetch_rss($user_feed); //print_r($rss);

		// Se nenhum post estiver sido carregado, retornar
		if(!is_array($rss->items))
			return false;

		// Limita o número de posts
		$items = array_slice($rss->items, 0, $options['quantity']);

		// Para cada post
		foreach($items as $item) : //print_r($item);
			// Inicializando as variáveis necessárias
			$feed = array();

			// Montar o post
			$feed['date']     = date('Y-m-d H:i:s');
			$feed['title']    = $item['title'];
			$feed['link']     = $item['link'];
			$feed['author']   = $user->user_id;

			$feed['content']  = bp_core_get_userlink($user->user_id);
			$feed['content'] .= " inseriu um novo link: <a href='{$item['link']}'>{$item['title']}</a>";

			$feed['content']  = addslashes($feed['content']);

			// Buscar o id do post, caso ele já exista
			if($wpdb->get_var("SELECT a.id FROM {$wpdb->base_prefix}bp_activity_sitewide AS a WHERE primary_link = '{$feed['link']}' AND a.user_id = {$feed['author']}"))
				continue;

			// Cadastrar Atividade
			//$wpdb->query("INSERT INTO {$wpdb->base_prefix}bp_activity_sitewide (user_id, item_id, secondary_item_id, content, primary_link, component_name, component_action, date_cached, date_recorded) VALUES ('{$feed['author']}', 0, 0, '{$feed['content']}', '{$feed['link']}', 'flickr', 'updated_flickr', '{$feed['date']}', '{$feed['date']}')");
			$this->register_activity($feed, 'delicious');
		endforeach;
	}

	// CONSTRUTOR ///////////////////////////////////////////////////////////////////////////////////
	/************************************************************************************************
		@name    republish
		@author  Marcelo Mesquita <stallefish@gmail.com>
		@since   2009-03-16
		@updated 2009-05-25
		@return  void
	************************************************************************************************/
	function republish()
	{
		// carregar o arquivo multilinguas
		//load_plugin_textdomain('standard', '/wp-content/plugins/standard/lang');

		// adicionando o menu
		add_action('admin_menu', array(&$this, 'menus'));

		// verifica se existem novos posts
		add_action('wp_footer', array(&$this, 'check_streams'));

		// instalando o plugin
		register_activation_hook(__FILE__, array(&$this, 'install'));
	}

	// DESTRUTOR ////////////////////////////////////////////////////////////////////////////////////

}

$republish = new republish();

?>
