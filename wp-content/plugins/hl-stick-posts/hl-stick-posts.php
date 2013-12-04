<?php
/*
* Plugin Name: Highlight Sticky Posts
* Plugin URI: http://xemele.cultura.gov.br/
* Description: highlight stick posts provides a new and easy way to stick
* you blog posts.
* Version: 1
* Author: Marcos M. Lopes
* Author URI: http://culturadigital.br/marcosmlopes/

* hl-stick-posts.php - This file init and include all files needed to plugin
* works.
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
*/

define('PLUGINPATH', get_bloginfo('url').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__)));

/* hl_admin_menu()
 * Description: Call a function to create an admin page.
 */
function hl_admin_menu() {
  add_submenu_page('edit.php', 'Highlight Sticky Posts', 'Sticky Posts', 'level_2', 'hl-stick-posts', 'hl_option_page');
}

add_action('admin_menu', 'hl_admin_menu');


/* hl_page_styles()
 * Description: This function add main stylesheet to admin option page
 */
function hl_page_styles() {
  wp_enqueue_style('hl-master', PLUGINPATH.'/global/css/master.css');
}

add_action('admin_print_styles-posts_page_hl-stick-posts', 'hl_page_styles');


/* hl_page_scripts()
 * Description: This function add main scripts to admin option page
 */
function hl_page_scripts() {
  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script('hl-master', PLUGINPATH.'/global/js/functions.js');
}

add_action('admin_print_scripts-posts_page_hl-stick-posts', 'hl_page_scripts');


/* hl_option_page()
 * Description: Recent option page created functions.
 */
function hl_option_page() {
  include 'global/templates/admin.php';
}