<?php /* Mystique/digitalnature */

//error_reporting(E_ALL & ~E_NOTICE);

define('WP_VERSION', $wp_version);
if (WP_VERSION < 2.9): // disable theme front end if wp < 2.9

  function mystique_unsupported_wp_version(){ ?>
  <div class='error fade'>
   <p>
   <?php
    printf(__('Your site is running on %1$s. Mystique requires at least %2$s.','mystique'), 'Wordpress '.WP_VERSION, '<a href="http://codex.wordpress.org/Upgrading_WordPress">Wordpress 2.9</a>');           if (current_user_can('switch_themes') && !is_admin()) echo '<br /><a href="'.get_bloginfo('wpurl').'/wp-admin/">'.__("(Dashboard)","mystique").'</a>';
   ?>
   </p>
  </div>
  <?php if(!is_admin()) die();
  }

  add_action('admin_notices', 'mystique_unsupported_wp_version');
  add_action('wp', 'mystique_unsupported_wp_version');

else:

  $mystique_theme_data = get_theme_data(TEMPLATEPATH.'/style.css');
  define('THEME_NAME', $mystique_theme_data['Name']);
  define('THEME_AUTHOR', $mystique_theme_data['Author']);
  define('THEME_URI', $mystique_theme_data['URI']);
  define('THEME_VERSION', trim($mystique_theme_data['Version']));
  define('THEME_URL', get_bloginfo('template_url'));

  // end of line character
  if(!defined("PHP_EOL")) define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");

  if (class_exists('xili_language')):
   define('THEME_TEXTDOMAIN','mystique');
   define('THEME_LANGS_FOLDER','/lang');
  else:
   load_theme_textdomain('mystique', get_template_directory().'/lang');
  endif;

  // core files, required
  require_once(TEMPLATEPATH.'/lib/core.php');
  require_once(TEMPLATEPATH.'/lib/settings.php');

  // optional, shortcodes
  require_once(TEMPLATEPATH.'/lib/shortcodes.php');

  // optional, extensions
  require_once(TEMPLATEPATH.'/extensions/ip2country/ip2country.php');
  require_once(TEMPLATEPATH.'/extensions/code-editing/code-editing.php');
  require_once(TEMPLATEPATH.'/extensions/featured-posts/featured-posts.php');
  require_once(TEMPLATEPATH.'/extensions/xtra-nav/xtra-nav.php');

  require_once(TEMPLATEPATH.'/lib/widgets.php');
  if(is_admin()) require_once(TEMPLATEPATH.'/admin/theme-settings.php');

  if(current_user_can('edit_posts')):
    require_once(TEMPLATEPATH.'/lib/editor.php');
    add_filter('mce_css', 'mystique_editor_styles');
    add_filter('mce_buttons_2', 'mystique_mcekit_editor_buttons');
    add_filter('tiny_mce_before_init', 'mystique_mcekit_editor_settings');
  endif;

  // search adjustments
  add_filter('pre_get_posts','mystique_exclude_pages_from_search');
//  add_filter('posts_where', 'mystique_search_where');
//  add_filter('posts_join', 'mystique_search_join');
//  add_filter('posts_groupby', 'mystique_search_groupby');
  add_action('mystique_jquery_init', 'mystique_highlight_search_query');

  add_action('init', 'mystique_verify_options');
  add_action('init', 'mystique_user_functions');
  add_action('wp_head', 'mystique_load_stylesheets', 1);
  add_action('wp_head', 'mystique_load_scripts', 1);
  add_action('template_redirect', 'mystique_meta_redirect');
  //add_action('get_header', 'mystique_compress_html');

  add_filter('query_vars', 'mystique_query_vars');
  add_action('template_redirect', 'mystique_dynamic_css_and_js');

  add_filter('excerpt_more', 'mystique_excerpt_more');

  // set up widget areas
  if (function_exists('register_sidebar')):
      register_sidebar(array(
        'name' => __('Default sidebar','mystique'),
        'id' => 'sidebar-1',
        'description' => __("This is the default sidebar, visible on 2 or 3 column layouts. If no widgets are active, the default theme widgets will be displayed instead.","mystique"),
        'before_widget' => '<li class="block"><div class="block-%2$s clear-block" id="instance-%1$s">',
  		'after_widget' => '</div></li>',
  		'before_title' => '<h3 class="title"><span>',
  		'after_title' => '</span></h3><div class="block-div"></div><div class="block-div-arrow"></div>'
      ));

      register_sidebar(array(
        'name' => __('Secondary sidebar','mystique'),
        'id' => 'sidebar-2',
        'description' => __("This sidebar is active only on a 3 column setup. ","mystique"),
  	    'before_widget' => '<li class="block"><div class="block-%2$s clear-block" id="instance-%1$s">',
  		'after_widget' => '</div></li>',
  		'before_title' => '<h3 class="title"><span>',
  		'after_title' => '</span></h3><div class="block-div"></div><div class="block-div-arrow"></div>'
      ));

      register_sidebar(array(
        'name' => __('Footer','mystique'),
        'id' => 'footer-1',
        'description' => __("You can add between 1 and 6 widgets here (3 or 4 are optimal). They will adjust their size based on the widget count. ","mystique"),
  		'before_widget' => '<li class="block block-%2$s" id="instance-%1$s"><div class="block-content clear-block">',
  		'after_widget' => '</div></li>',
  		'before_title' => '<h4 class="title">',
  		'after_title' => '</h4>'
      ));

      register_sidebar(array(
        'name' => __('Footer (slide 2)','mystique'),
        'id' => 'footer-2',
        'description' => __("Only visible if jQuery is enabled. ","mystique"),
  		'before_widget' => '<li class="block block-%2$s" id="instance-%1$s"><div class="block-content clear-block">',
  		'after_widget' => '</div></li>',
  		'before_title' => '<h4 class="title">',
  		'after_title' => '</h4>'
      ));

      register_sidebar(array(
        'name' => __('Footer (slide 3)','mystique'),
        'id' => 'footer-3',
        'description' => __("Only visible if jQuery is enabled. ","mystique"),
  		'before_widget' => '<li class="block block-%2$s" id="instance-%1$s"><div class="block-content clear-block">',
  		'after_widget' => '</div></li>',
  		'before_title' => '<h4 class="title">',
  		'after_title' => '</h4>'
      ));

      register_sidebar(array(
        'name' => __('Footer (slide 4)','mystique'),
        'id' => 'footer-4',
        'description' => __("Only visible if jQuery is enabled. ","mystique"),
  		'before_widget' => '<li class="block block-%2$s" id="instance-%1$s"><div class="block-content clear-block">',
  		'after_widget' => '</div></li>',
  		'before_title' => '<h4 class="title">',
  		'after_title' => '</h4>'
      ));
  endif;

  // set up post thumnails
  add_theme_support('post-thumbnails');
  $size = explode('x',get_mystique_option('post_thumb'));
  set_post_thumbnail_size($size[0],$size[1], true);
  add_image_size('featured-thumbnail', 150, 150);

  // nav menus
  if(function_exists('register_nav_menus')) register_nav_menus(array('primary' => __('Primary Navigation', 'mystique')));
  
  add_theme_support('automatic-feed-links');
endif;
?>