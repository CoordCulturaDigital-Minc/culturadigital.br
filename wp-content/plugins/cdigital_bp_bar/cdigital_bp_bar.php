<?php
/*
* cdigital_bp_bar.php - This file have the theme functions
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
*
* Plugin Name: Cultura Digital Buddypress Bar
* Plugin Author: Marcos Maia Lopes
* Plugin URI: http://xemele.cultura.gov.br/
*/

define('PLUGINPATH', get_bloginfo('url').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__)));

// Register custom buddypress bar
function cdigital_bp_bar() {
  global $bp, $wpdb, $current_blog,
         $doing_admin_bar;

  $doing_admin_bar = true;

  if ( (int) get_site_option( 'hide-loggedout-adminbar' )
  && !is_user_logged_in() ) {
    return false;
  }
  ?>
  <div class="nav-bar clearfix">
    <div class="middle">
      <?php if(is_user_logged_in()) : ?>
        <div class="left logged-in">
          <div class="nav">
            <ul class="main-nav">
              <li class="cdigital"><a href="<?php echo $bp->root_domain; ?>">CulturaDigital.br &raquo;</a></li>
              <?php do_action( 'bp_adminbar_menus' ); ?>
            </ul>
          </div>
        </div>
      <?php else : ?>
        <div class="left logged-in">
          <div class="nav">
            <ul class="main-nav">
              <li class="cdigital"><a href="<? echo $bp->root_domain; ?>">CulturaDigital.br</a></li>
            </ul>
          </div>
        </div>
      <?php endif ?>

      <div class="right">
        <?php if(!is_user_logged_in()) : ?>
        <div class="left nav">
          <ul>
            <?php if(bp_get_signup_allowed()) : ?>
              <li><a href="<?php echo $bp->root_domain .'/'. BP_REGISTER_SLUG .'/?redirect_to='. get_bloginfo('url'); ?>">
          	  Registrar</a></li>
            <?php endif; ?>
            <li class="login window">
              <a href="<?php site_url() ?>/wp-login.php">Entrar</a>
              <form action="<?php echo get_bloginfo('url'), '/wp-login.php' ?>"
              method="post" class="window">
                <label for="user">Usuário:</label>
                <input type="text" name="log" id="user" />
                <label for="pwd">Senha:</label>
                <input type="password" name="pwd" id="pwd" />
                <a
                href="<?php bloginfo('url') ?>/wp-login.php?action=lostpassword">
                Esqueceu sua senha?</a>
                <button type="submit" name="wp-submit">Entrar</button>
                <input type="hidden" name="redirect_to"
                value="<?php bloginfo('url') ?>">
                <input type="hidden" name="testcookie" value="1">
              </form>
            </li>
          </ul>
        </div>
        <?php else : ?>
          <div class="left nav">
            <ul>
              <li class="new-activity">
                <a href="<?php echo $bp->loggedin_user->domain, BP_ACTIVITY_SLUG; ?>" title="Postar atividade no culturadigital.br">Nova atividade</a>
                <form action="<?php bp_activity_post_form_action() ?>" method="post" style="display:none;" id="whats-new-form" name="whats-new-form">
                  <div class="activity-text">
                    <input name="whats-new" id="activity-text" />
                  </div>
                  <?php if ( function_exists('bp_has_groups') && !bp_is_my_profile() && !bp_is_group() ) : ?>
                    <label>Publicar em:</label>
                    <div class="custom-select">
                      <div class="inner">
                        <span>Meu perfil</span>
                      </div>
                      <select id="whats-new-post-in" name="whats-new-post-in">
                        <option value="0" selected="selected">Meu perfil</option>
                        <?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0' ) ) : while ( bp_groups() ) : bp_the_group(); ?>
                          <option value="<?php bp_group_id() ?>"><?php bp_group_name() ?></option>
                        <?php endwhile; endif; ?>
                      </select>
                    </div>
                    <input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
                  <?php elseif ( bp_is_group_home() ) : ?>
                    <input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
                    <input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id() ?>" />
                  <?php endif; ?>
                  <?php do_action( 'bp_activity_post_form_options' ) ?>
                  
                  <button type="submit">Enviar Atualização</button>
                  <?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
                  <?php do_action( 'bp_after_activity_post_form' ) ?>
                </form>
              </li>
            </ul>
          </div>
        <?php endif; ?>

        <form action="<?php bloginfo('url') ?>" method="get" class="left search-form">
          <label for="search-terms"
          class="hidden">Pesquisar:</label>
          <input type="search" name="s" id="search-terms"
          placeholder="o que procura?" />
          <button type="submit">Buscar</button>
        </form>
      </div>
    </div>
	</div>
  <?php
}

function cdigital_bar_enqueue() {
  global $current_blog;

  // Deregister bp bar random menu
  remove_action( 'bp_adminbar_menus', 'bp_adminbar_random_menu', 100 );

  // Deregister default bp bar
  remove_action('wp_footer', 'bp_core_admin_bar', 8);
  remove_action('admin_footer', 'bp_core_admin_bar');

  // Register custom bp bar
  if( $current_blog->blog_id != BP_ROOT_BLOG ) {
    add_action('wp_footer', 'cdigital_bp_bar', 8);
  } else {
    add_action('bp_bar', 'cdigital_bp_bar');
  }

  add_action('admin_footer', 'cdigital_bp_bar');

  // Deregister bp bar stylesheet
  wp_deregister_style('bp-admin-bar');

  // Register style and scripts
  //if( $current_blog->blog_id != BP_ROOT_BLOG || is_admin() ) {
    wp_enqueue_style('cdigital_bp_bar', PLUGINPATH.'/css/cdigital_bar.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('cdigital_bp_bar', PLUGINPATH.'/js/cdigital_bar.js', 'jquery');
  //}
}

add_action('init', 'cdigital_bar_enqueue');
