<?php
/*
  Plugin Name: FeedUp
  Plugin URI: http://www.webdf.com.br
  Description: Lista os posts de outros sites através do RSS com a opção de republicar algum post no seu site
  Author: WebDF
  Version: beta 0.1
  Author URI: http://www.webdf.com.br
  
  Baseado no plugin repost criado por Leo Germani http://pirex.com.br/wordpress-plugins/
  
  Copyright (C) 2008 Equipe WebMinC
  
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.
  
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
*/

if($_REQUEST['ajax'] == 1)
  require_once('../../../wp-config.php');

class feedup
{
  // ATRIBUTOS ////////////////////////////////////////////////////////////////
  
  // METODOS //////////////////////////////////////////////////////////////////
  /****************************************************************************
    Definindo as Tabelas
  ****************************************************************************/
  function feedup_tables()
  {
    global $wpdb, $userdata;
    
    $wpdb->feedup_feeds = $wpdb->prefix."feedup_feeds";
    $wpdb->feedup_posts = $wpdb->prefix."feedup_posts";
  }
  
  /****************************************************************************
    Instalando
  ****************************************************************************/
  function feedup_install()
  {
    global $wpdb;
    
    if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->feedup_feeds}'") !== $wpdb->feedup_feeds)    {
      $sql = "
      CREATE TABLE {$wpdb->feedup_feeds}
      (        feed_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
        feed_title VARCHAR(255) NULL,
        feed_url VARCHAR(255) NULL,
        feed_rss VARCHAR(255) NOT NULL,
        feed_registered DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        feed_last_checked DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY(feed_id)
      )";
      
      $wpdb->query($sql);
    }
    
    if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->feedup_posts}'") !== $wpdb->feedup_posts)    {
      $sql = "
      CREATE TABLE {$wpdb->feedup_posts}
      (        post_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
        post_title VARCHAR(255) NOT NULL,
        post_guid VARCHAR(255) NOT NULL,
        post_author VARCHAR(255) NULL,
        post_content TEXT NULL,
        post_date DATETIME NULL DEFAULT '0000-00-00 00:00:00',
        post_readed BOOLEAN NOT NULL DEFAULT '0',
        post_last_checked DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        feed_id INTEGER UNSIGNED NOT NULL,
                PRIMARY KEY(post_id)
      )";
      
      $wpdb->query($sql);
    }
    
    update_option('feedup_feed_limit', 5);
    update_option('feedup_post_limit', 30);
    update_option('feedup_post_add', '<p>Fonte: {author} de {vehicle}</p>'); // vehicle, vehicle_url, title, author, permalink
    
    $role = get_role("administrator");
    $role->add_cap("feedup");
    $role->add_cap("feedup_feeds");
    
    $role = get_role("editor");
    $role->add_cap("feedup");
    
    $role = get_role("author");
    $role->add_cap("feedup");
  }
  
  /****************************************************************************
    Desistalando
  ****************************************************************************/
  function feedup_uninstall()
  {
    global $wpdb, $wp_roles;
    
    $wpdb->query("DROP TABLES {$wpdb->feedup_feeds}, {$wpdb->feedup_posts}");
    
    foreach($wp_roles->role_names as $role => $rolename)
    {
      $wp_roles->role_objects[$role]->remove_cap("feedup");
      $wp_roles->role_objects[$role]->remove_cap("feedup_feeds");
    }
  }
  
  /****************************************************************************
    Criando o Menu
  ****************************************************************************/
  function feedup_menu()
  {
    add_menu_page("FeedUp", "FeedUp", "feedup", __FILE__, array(&$this, "feedup_post"));
    add_submenu_page(__FILE__, "Feeds", "Feeds", "feedup_feeds", "feedup_feeds", array(&$this, "feedup_feeds"));
    add_submenu_page(__FILE__, "Opcoes", "Opcoes", "feedup_feeds", "feedup_manager", array(&$this, "feedup_manager"));
  }
  
  /****************************************************************************
    Gerenciar Posts
  ****************************************************************************/
  function feedup_post()
  {
    global $wpdb;
    
    $feed_id = $_REQUEST['feed_id'];
    
    $feeds = $wpdb->get_results("SELECT feed_id, feed_title FROM {$wpdb->feedup_feeds}");
    
    if(empty($feed_id)) $feed_id = $feeds[0]->feed_id;
    
    $current_feed = $wpdb->get_row("SELECT feed_id, feed_title, feed_url, feed_rss FROM {$wpdb->feedup_feeds} WHERE feed_id = {$feed_id}");
    
    if(!empty($current_feed))
      $this->get_posts($current_feed->feed_id, $current_feed->feed_rss);
    
    $posts = $wpdb->get_results("SELECT post_id, post_title, post_author, post_date, post_content FROM {$wpdb->feedup_posts} WHERE feed_id = {$current_feed->feed_id} AND post_readed = 0 ORDER BY post_date DESC");
    
    ?>
    <script type="text/javascript" src="<?php bloginfo('url'); ?>/wp-content/plugins/feedup/js/jquery.js"></script>
    <script type="text/javascript" src="<?php bloginfo('url'); ?>/wp-content/plugins/feedup/js/jquery.feedup.js"></script>
    
    <div class='wrap'>
      <form action="" method="post" id="posts-filter">
        <h2><?php print __("FeedUp"); ?></h2>
        <p id="post-search">
          <select name="feed_id">
            <?php foreach($feeds as $feed) print "<option value='{$feed->feed_id}' ".(($feed_id == $feed->feed_id) ? "selected='selected'" : "").">{$feed->feed_title}</option>"; ?>
          </select>
          <button type="submit" name="action" value="show_posts" class="button"><?php _e('Filter &#187;'); ?></button>
        </p>
      </form>
      
      <h3><a href="<?php print $current_feed->feed_url; ?>" title="<?php print $current_feed->feed_title; ?>"><?php print $current_feed->feed_title; ?></a></h3>
      
      <form action="" method="post">
        <table class="widefat">
          <thead>
            <tr>
              <th scope="col" class="check-column"><input onclick="checkAll(document.getElementById('feedup'));" type="checkbox"></th>
              <th scope="col" width="60%"><?php print __("Feed"); ?></th>
              <th scope="col"><?php print __("Data"); ?></th>
              <th scope="col" class="action-links"><?php print __("Ações"); ?></th>
            </tr>
          </thead>
          <tbody>
      
          <?php if(!empty($posts)) foreach($posts as $post) : ?>
            <tr id="feedup-<?php print $post->post_id; ?>">
              <td class="check-column">
                <input name="posts_ids[]" value="<?php print $post->post_id; ?>" type="checkbox">
              </td>
              <td>
                <p>
                  <strong><a class="row-title" href="<?php print $post->post_guid; ?>" title="<?php print $post->post_title; ?>"><?php print $post->post_title; ?></a></strong><br>
                  <?php print $post->post_guid; ?>
                </p>
                <p><?php print $this->limit_chars($post->post_content, 250); ?></p>
                <p>Autor: <?php print $post->post_author; ?></p>
              </td>
              <td><?php print $post->post_date; ?></td>
              <td class="action-links">
                <span><a href="<?php bloginfo('url'); ?>/wp-content/plugins/feedup/feedup.php?action=mark_as_readed&amp;post_id=<?php print $post->post_id; ?>" title="Marcar como lido" class="mark_as_readed">Marcar como lido</a> | </span>
                <span><a href="<?php bloginfo('url'); ?>/wp-content/plugins/feedup/feedup.php?action=republish&amp;post_id=<?php print $post->post_id; ?>" title="RePublicar este post" class="republish">RePublicar</a></span>
                <p><?php wp_dropdown_categories('hide_empty=0&name=category_id&orderby=id&hierarchical=1'); ?></p>
              </td>
            </tr>
          <?php endforeach; ?>
          
          </tbody>
        </table>
      
    </div>
    <?php
  }
  
  /****************************************************************************
    Divide os posts e seus respectivos dados.
    
    Função original em wp-admin/import/rss.php
  ****************************************************************************/
  function get_posts($feed_id, $file)
  {
    global $wpdb;
    
    // Delete de old posts
    $post_limit = get_option('feedup_post_limit');
    $post_limit_date = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), (date("d") - $post_limit), date("Y")));
    
    $wpdb->query("DELETE FROM {$wpdb->feedup_posts} WHERE post_last_checked > {$post_limit_date}");
    
    // Check if the actual feed is recent
    $feed_limit = get_option('feedup_feed_limit');
    $feed_limit_date = date("Y-m-d H:i:s", mktime(date("H"), (date("i") - $feed_limit), date("s"), date("m"), date("d"), date("Y")));
    
    if($feed_last_checked = $wpdb->get_var("SELECT feed_last_checked FROM {$wpdb->feedup_feeds} WHERE feed_id = {$feed_id} AND feed_last_checked > '{$feed_limit_date}' AND feed_last_checked <> '0000-00-00 00:00:00'"))
      return false;
    
    if(function_exists('curl_init'))
    {
      $curl = curl_init($file);
      
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      
      $rss = curl_exec($curl);
      
      curl_close($curl);
    }
    else
    {
      $rss = @file_get_contents($file);
    }
    
    // Check if the file is loaded
    if(empty($rss))
      return false;
    
    $rss = str_replace(array("\r\n", "\r"), "\n", $rss);
    
    preg_match_all('|<item>(.*?)</item>|is', $rss, $posts);
    $posts = $posts[1];
    
    foreach($posts as $post)
    {
      // post_title
      preg_match('|<title>(.*?)</title>|is', $post, $post_title);
      $post_title = str_replace(array('<![CDATA[', ']]>'), '', $wpdb->escape(trim($post_title[1])));
      
      // post_date
      preg_match('|<pubdate>(.*?)</pubdate>|is', $post, $post_date_gmt);
      
      if($post_date_gmt)
      {
        $post_date_gmt = strtotime($post_date_gmt[1]);
      }
      else
      {
        // if we don't already have something from pubDate
        preg_match('|<dc:date>(.*?)</dc:date>|is', $post, $post_date_gmt);
        $post_date_gmt = preg_replace('|([-+])([0-9]+):([0-9]+)$|', '\1\2\3', $post_date_gmt[1]);
        $post_date_gmt = str_replace('T', ' ', $post_date_gmt);
        $post_date_gmt = strtotime($post_date_gmt);
      }
      
      $post_date_gmt = gmdate('Y-m-d H:i:s', $post_date_gmt);
      $post_date = get_date_from_gmt($post_date_gmt);
      
      // post_guid
      preg_match('|<guid.*?>(.*?)</guid>|is', $post, $post_guid);
      if($post_guid)
        $post_guid = $wpdb->escape(trim($post_guid[1]));
      else
        $post_guid = '';
      
      // post_author
      preg_match('|<dc:creator>(.*?)</dc:creator>|is', $post, $post_author);
      if($post_author)
        $post_author = $wpdb->escape(trim($post_author[1]));
      else
        $post_author = '';
      
      // post_content
      preg_match('|<content:encoded>(.*?)</content:encoded>|is', $post, $post_content);
      $post_content = str_replace(array ('<![CDATA[', ']]>'), '', $wpdb->escape(trim($post_content[1])));
      
      if(!$post_content)
      {
        // This is for feeds that put content in description
        preg_match('|<description>(.*?)</description>|is', $post, $post_content);
        $post_content = $wpdb->escape($this->unhtmlentities(trim($post_content[1])));
      }

      // Clean up content
      $post_content = preg_replace('|<(/?[A-Z]+)|e', "'<' . strtolower('$1')", $post_content);
      $post_content = str_replace('<br>', '<br />', $post_content);
      $post_content = str_replace('<hr>', '<hr />', $post_content);
      
      if(!$wpdb->query("SELECT post_id FROM {$wpdb->feedup_posts} WHERE post_title = '{$post_title}'"))
        $wpdb->query("INSERT INTO {$wpdb->feedup_posts} (post_title, post_guid, post_author, post_content, post_date, post_last_checked, feed_id) VALUES ('{$post_title}', '{$post_guid}', '{$post_author}', '{$post_content}', '{$post_date}', NOW(), {$feed_id})");
    }
    
    // Update de time last checked
    $wpdb->query("UPDATE {$wpdb->feedup_feeds} SET feed_last_checked = '".date('Y-m-d H:i:s')."' WHERE feed_id = {$feed_id}");
  }
  
  /****************************************************************************
    unhtmlentities
    
    Função original em wp-admin/import/rss.php unhtmlentities()
  ****************************************************************************/
  function unhtmlentities($string)
  { // From php.net for < 4.3 compat
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
  }
  
  /****************************************************************************
    limita a quantidade de caracteres
  ****************************************************************************/
  function limit_chars($content, $length)
  {
    $content = strip_tags($content);
    
    if(strlen($content) > $length)
    {
      $content = substr($content, 0, $length);
      $content = substr($content, 0, strrpos($content, " "))."...";
    }
    
    return $content;
  }
  
  /****************************************************************************
    Gerenciando os Feeds
  ****************************************************************************/
  function feedup_feeds()
  {
    $id         = $_REQUEST['id'];
    $feed_id    = $_REQUEST['feed_id'];
    $feeds_ids  = $_REQUEST['feeds_ids'];
    $feed_title = $_REQUEST['feed_title'];
    $feed_url   = $_REQUEST['feed_url'];
    $feed_rss   = $_REQUEST['feed_rss'];
    
    switch($_REQUEST['action'])
    {
      case "add_feed" :
        $this->add_feed($feed_title, $feed_url, $feed_rss);
        break;
      case "edit_feed" :
        $this->edit_feed($feed_id, $feed_title, $feed_url, $feed_rss);
        break;
      case "delete_feed" :
        $this->delete_feed($feeds_ids);
        break;
    }
    
    $this->show_feeds($id);
  }
  
  /****************************************************************************
    Adicionar o Feed
  ****************************************************************************/
  function add_feed($feed_title, $feed_url, $feed_rss)
  {
    global $wpdb;
    
    if($wpdb->query("INSERT INTO {$wpdb->feedup_feeds} (feed_title, feed_url, feed_rss) VALUES ('{$feed_title}', '{$feed_url}', '{$feed_rss}')"))
      print "<div class='updated'><p>Feed Cadastrado!</p></div>";
    else
      print "<div class='updated'><p>Falha ao Cadastrar Feed!</p></div>";
  }
  
  /****************************************************************************
    Editar o Feed
  ****************************************************************************/
  function edit_feed($feed_id, $feed_title, $feed_url, $feed_rss)
  {
    global $wpdb;
    
    if($wpdb->query("UPDATE {$wpdb->feedup_feeds} SET feed_title = '{$feed_title}', feed_url = '{$feed_url}', feed_rss = '{$feed_rss}' WHERE feed_id = {$feed_id}"))
      print "<div class='updated'><p>Feed Atualizado!</p></div>";
    else
      print "<div class='updated'><p>Falha ao Atualizar Feed!</p></div>";
  }
  
  /****************************************************************************
    Deletar o Feed
  ****************************************************************************/
  function delete_feed($feeds_ids)
  {
    global $wpdb;
    
    foreach($feeds_ids as $feed_id) :
      if($wpdb->query("DELETE FROM {$wpdb->feedup_feeds} WHERE feed_id = {$feed_id}"))
        print "<div class='updated'><p>Feed deletado com sucesso!</p></div>";
      else
        print "<div class='updated'></p>Falha ao deletar feed!</p></div>";
    endforeach;
  }
  
  /****************************************************************************
    Mostrar os Feeds
  ****************************************************************************/
  function show_feeds($id)
  {
    print "<div class='wrap'>";
    $this->form_feeds($id);
    print "</div>";
    
    print "<div class='wrap'>";
    $this->list_feeds();
    print "</div>";
  }
  
  /****************************************************************************
    Mostrar o Formulário dos Feeds
  ****************************************************************************/
  function form_feeds($id = 0)
  {
    global $wpdb;
    
    if(!empty($id))
      $feed = $wpdb->get_row("SELECT feed_title, feed_url, feed_rss FROM {$wpdb->feedup_feeds} WHERE feed_id = {$id}");
    
    ?>
    <?php if(empty($id)) : ?>
      <h2><?php print __("Adicionar Feed"); ?></h2>
    <?php else : ?>
      <h2><?php print __("Editar Feed"); ?></h2>
    <?php endif; ?>
    
    <form action="" method="post">
      <input type="hidden" name="feed_id" value="<?php print $id; ?>" />
      <table class="form-table">
        <tbody>
          <tr class="form-field form-required">
            <th valign="top"><label for="feed_title"><?php print __('Nome'); ?>:</label></th>
            <td>
              <input type="text" name="feed_title" id="feed_title" value="<?php print $feed->feed_title; ?>" size="40" taborder="1" /><br />
              Nome do Veículo
            </td>
          </tr>
          <tr>
            <th><label for="feed_url"><?php print __('URL'); ?>:</label></th>
            <td>
              <input type="text" name="feed_url" id="feed_url" value="<?php print $feed->feed_url; ?>" size="40" taborder="2" /><br />
              Site do Veículo
            </td>
          </tr>
          <tr>
            <th><label for="feed_rss"><?php print __('RSS'); ?>:</label></th>
            <td>
              <input type="text" name="feed_rss" id="feed_rss" value="<?php print $feed->feed_rss; ?>" size="40" taborder="3" /><br />
              RSS do Veículo
            </td>
          </tr>
        </tbody>
      </table>
      <?php if(empty($id)) : ?>
        <p class="submit"><button type="submit" name="action" value="add_feed" taborder="4" class="button">Adicionar &raquo;</button></p>
      <?php else : ?>
        <p class="submit"><button type="submit" name="action" value="edit_feed" taborder="4" class="button">Editar &raquo;</button></p>
      <?php endif; ?>
    </form>
    <?php
  }
  
  /****************************************************************************
    Mostrar a Lista dos Feeds
  ****************************************************************************/
  function list_feeds()
  {
    global $wpdb;
    
    $feeds = $wpdb->get_results("SELECT feed_id, feed_title, feed_url, feed_rss FROM {$wpdb->feedup_feeds}");
    
    ?>
    <h2>Gerenciar Feeds</h2>
    
    <form action="" method="post">
      <div class="tablenav">
        <div class="alignleft">
          <button type="submit" name="action" value="delete_feed" class="button-secondary delete"><?php print __('delete'); ?></button>
        </div>
        <br class="clear">
      </div>
      
      <br class="clear">
      
      <table class="widefat">
        <thead>
          <tr>
            <th class="check-column"><input onclick="checkAll(document.getElementById('fc_id[]'));" type="checkbox"></th>
            <th><?php print __('Veículo'); ?></th>
            <th><?php print __('URL'); ?></th>
            <th><?php print __('RSS'); ?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($feeds as $feed) : ?>
          <?php $alternate = !$alternate; ?>
          <tr <?php if($alternate) print 'class="alternate"'; ?>>
            <td class="check-column"><input type="checkbox" name="feeds_ids[]" value="<?php print $feed->feed_id; ?>" /></td>
            <td><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=feedup_feeds&id=<?php print $feed->feed_id; ?>"><?php print $feed->feed_title; ?></a></td>
            <td><a href="<?php print $feed->feed_url; ?>"><?php print $feed->feed_url; ?></a></td>
            <td><a href="<?php print $feed->feed_rss; ?>"><?php print $feed->feed_rss; ?></a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </form>
    
    <?php
  }
  
  /****************************************************************************
    FeedUp Ajax
  ****************************************************************************/
  function feedup_ajax()
  {
    $post_id = $_REQUEST['post_id'];
    $category_id = $_REQUEST['category_id'];
    
    switch($_REQUEST['action'])
    {
      case "republish" :
        $this->republish($post_id, $category_id);
        break;
      case "mark_as_readed" :
        $this->mark_as_readed($post_id);
        break;
    }
  }
  
  /****************************************************************************
    Re-Publicar o Post
  ****************************************************************************/
  function republish($post_id, $category_id)
  {
    global $wpdb;
    
    $ob_post = $wpdb->get_row("SELECT post_id, post_title, post_guid, post_author, post_content, post_date, feed_title, feed_url FROM {$wpdb->feedup_posts} INNER JOIN {$wpdb->feedup_feeds} ON ({$wpdb->feedup_posts}.feed_id = {$wpdb->feedup_feeds}.feed_id) WHERE post_id = {$post_id}");
    
    $post['post_id'] = $ob_post->post_id;
    $post['post_title'] = $ob_post->post_title;
    $post['guid'] = $ob_post->post_guid;
    $post['post_content'] = $ob_post->post_content;
    $post['post_date'] = $ob_post->post_date;
    $post['post_author'] = 1;
    $post['post_status'] = "publish";
    
    $add = get_option('feedup_post_add');
    
    $add = str_replace("{vehicle}", $ob_post->feed_title, $add);
    $add = str_replace("{vehicle_url}", $ob_post->feed_url, $add);
    $add = str_replace("{title}", $ob_post->post_title, $add);
    $add = str_replace("{author}", $ob_post->post_author, $add);
    $add = str_replace("{permalink}", $ob_post->post_guid, $add);
    
    $post['post_content'] = $add . $post['post_content'];
    
    if($post_id = $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE post_title = '{$post['post_title']}'"))
    {
      print __('Esse post já existe!');
    }
    else
    {
      $post_id = wp_insert_post($post);
      if(is_wp_error($post_id))
        return $post_id;
      if(!$post_id)
      {
        print __('Não foi possível republicar esse post');
        return;
      }
      
      wp_set_post_categories($post_id, array($category_id));
      
      $wpdb->query("UPDATE {$wpdb->feedup_posts} SET post_readed = 1 WHERE post_id = {$post['post_id']}");
      
      print $post['post_id'];
    }
  }
  
  /****************************************************************************
    Marcar como Lido
  ****************************************************************************/
  function mark_as_readed($post_id)
  {
    global $wpdb;
    
    if($wpdb->query("UPDATE {$wpdb->feedup_posts} SET post_readed = 1 WHERE post_id = {$post_id}"))
      print $post_id;
    else
      print "Falha ao atualizar post";
  }
  
  /****************************************************************************
    Configurações do FeedUp
  ****************************************************************************/
  function feedup_manager()
  {
    if($_POST['feedup_update_options'])
    {
      update_option('feedup_post_add', $_POST['feedup_post_add']);
      if(is_numeric($_POST['feedup_post_limit'])) update_option('feedup_post_limit', $_POST['feedup_post_limit']);
      if(is_numeric($_POST['feedup_feed_limit'])) update_option('feedup_feed_limit', $_POST['feedup_feed_limit']);
    }
    
    ?>
    <div class="wrap">
      <h2><?php print __('Opções'); ?></h2>
      <form action="" method="post">
      <table class="form-table">
        <tbody>
          <tr class="form-field form-required">
            <th valign="top"><label for="feedup_post_add"><?php print __('Adicionar essa nota ao conteúdo republicado'); ?>:</label></th>
            <td>
              <textarea name="feedup_post_add" id="feedup_post_add" cols="50"><?php print get_option('feedup_post_add'); ?></textarea><br />
              Texto a ser adicionado ao conteúdo do post. Possíveis variáveis: {vehicle} {vehicle_url} {title} {author} {permalink}
            </td>
          </tr>
          <tr>
            <th><label for="feedup_post_limit"><?php print __('Tempo limite do post'); ?>:</label></th>
            <td>
              <input type="text" name="feedup_post_limit" id="feedup_post_limit" value="<?php print get_option('feedup_post_limit'); ?>" taborder="2" /> dias<br />
              Período máximo que um post ficará aguardando a republicação
            </td>
          </tr>
          <tr>
            <th><label for="feedup_feed_limit"><?php print __('Frequência de atualização dos feeds'); ?>:</label></th>
            <td>
              <input type="text" name="feedup_feed_limit" id="feedup_feed_limit" value="<?php print get_option('feedup_feed_limit'); ?>" taborder="3" /> minutos<br />
              De quanto em quanto tempo o sistema irá atualizar os feeds.
            </td>
          </tr>
        </tbody>
      </table>
      <p class="submit"><button type="submit" name="feedup_update_options" value="1" taborder="4" class="button"><?php print __("Atualizar"); ?> &raquo;</button></p>
    </form>
    </div>
    <?php
  }
  
  // CONSTRUTOR ///////////////////////////////////////////////////////////////
  /****************************************************************************
    Mostrar os Feeds
  ****************************************************************************/
  function feedup()
  {
    $this->feedup_tables();
    
    // adicionando o menu
    add_action('admin_menu', array(&$this, 'feedup_menu'));
    
    // instalando o plugin
    register_activation_hook(__FILE__, array(&$this, 'feedup_install'));
    
    // desinstalando o plugin
    register_deactivation_hook(__FILE__, array(&$this, 'feedup_uninstall'));
    
    if($_REQUEST['ajax'] == 1)
      $this->feedup_ajax();
  }
  
  // DESTRUTOR ////////////////////////////////////////////////////////////////
  
}

$feedup = new feedup();

?>
