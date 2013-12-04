<?php
  /** 
   * Plugin Name: CulturaDigital.br Stats
   * Description: Retorna as estatisticas dos blogs, membros, grupos e fórums em formato csv
   * Version: 1
   * Author: Marcos Maia
   * Author URI: http://culturadigital.br/marcosmaia
   * License: GPLv2
   */

  function cdbr_stats() {
    add_submenu_page(
      'tools.php',
      'CulturaDigital.br Statistics',
      'cdbr stats',
      8,
      'cdbr_stats',
      'cdbr_stats_admin_page'
    );
  }

  add_action('admin_menu', 'cdbr_stats');


  function cdbr_stats_admin_page() {
    echo '<div class="wrap">';
    echo '<h2>CulturaDigital.br Statistics</h2>';

    if (isset($_POST['stats'])) {
      if ($_POST['stats'] == 'blog') {
        cdbr_blog_stats();
      } elseif ($_POST['stats'] == 'member') {
        cdbr_member_stats();
      }
    } else {
      echo '<form action="" method="post">';
      echo '<p>Download the plataform statistcs:</p>';
      echo '<p><label><input type="radio" name="stats" value="blog"> Blogs</label></p>';
      echo '<p><label><input type="radio" name="stats" value="member"> Member</label></p>';
      echo '<button type="submit">Go!</button>';
      echo '</form>';
    }
    echo '</div>';
  }

  function cdbr_blog_stats() {
    global $wpdb;

    echo '<textarea cols="60" rows="15">';
    echo "\"Blog Name\",\"Path\",\"Post count\",\"Registered\",\"Last updated\"\n";

    $blogs = $wpdb->get_results(
        "SELECT blog_id, path, registered, last_updated "
      . "FROM wp_blogs WHERE blog_id > 1 "
      . "AND deleted = 0 AND spam = 0 "
      . "ORDER BY registered "
    );

    foreach ($blogs as $blog) {
      $obj = $wpdb->get_results(
          "SELECT a.option_value as 'blog_name', count(b.id) as 'post_count' "
        . "FROM wp_{$blog->blog_id}_options a, wp_{$blog->blog_id}_posts b "
        . "WHERE a.option_name = 'blogname' AND b.post_type = 'post' AND b.post_status = 'publish'"
      );

      $obj = $obj[0];

      if ($obj->post_count > 1) {
        echo "\"{$obj->blog_name}\",\"{$blog->path}\",\"{$obj->post_count}\",\"{$blog->registered}\",\"{$blog->last_updated}\"\n";
      }
    }

    echo '</textarea>';
  }

  function cdbr_member_stats() {
    global $wpdb;

    $users = $wpdb->get_results(
        "SELECT a.user_nicename, a.user_email, a.display_name, a.user_registered, b.meta_value AS 'last_activity' "
      . "FROM wp_users a, wp_usermeta b "
      . "WHERE a.spam = 0 AND a.deleted = 0 AND b.meta_key = 'last_activity' AND b.user_id = a.id "
      . "ORDER BY (SELECT meta_value FROM wp_usermeta WHERE user_id = a.id AND meta_key = 'last_activity') DESC"
    );

    echo '<textarea cols="60" rows="15">';

    echo "\"Nice Name\",\"E-mail\",\"Display Name\",\"Registered\",\"Last activity\"\n";

    foreach ($users as $user) {
      echo "\"{$user->user_nicename}\",\"{$user->user_email}\",\"{$user->display_name}\",\"{$user->user_registered}\",\"{$user->last_activity}\"\n";
    }

    echo '</textarea>'; 
  }
