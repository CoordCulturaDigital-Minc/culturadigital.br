<?php /* Mystique/digitalnature */

// default theme settings
function mystique_default_settings(){
  $defaults = array(
    'theme_version' => THEME_VERSION,
    'layout' => 'col-2-right',
    'dimensions' => array('fixed' => array('col-2-left' => '310', 'col-2-right' => '630', 'col-3' => '230;710', 'col-3-left' => '230;460', 'col-3-right' => '480;710'),
                          'fluid' => array('col-2-left' => '30', 'col-2-right' => '70', 'col-3' => '25;75', 'col-3-left' => '25;50', 'col-3-right' => '50;75')),
    'page_width' => 'fixed',
    'color_scheme' => 'green',
    'font_style' => 0,
    'footer_content' => '[credit] <br /> [rss] [xhtml] [top]',
    'navigation' => 'pages',
    'navigation_links' => 'Blogroll',
    'exclude_home' => '',
    'exclude_pages' => '',
    'exclude_categories' => '',
    'post_title' => 1,
    'post_info' => 1,
    'post_tags' => 1,
    'post_content' => 1,
    'post_content_length' => 'f',
    'post_thumb' => '64x64',
    'post_thumb_auto' => 1,
    'post_single_print' => 1,
    'post_single_meta' => 1,
    'post_single_share' => 1,
    'post_single_author' => 0,
    'post_single_tags' => 1,
    'post_single_related' => 1,
    'read_more' => 0,
    'seo' => 1,
    'jquery' => 1,
    'ajax_comments' => 1,
    'lightbox' => 1,
    'user_css' => '',
    'logo' => '',
    'logo_size' => '',
    'background' => '',
    'background_color' => '000000',
    'ad_code_1' => '',
    'ad_code_2' => '',
    'ad_code_3' => '',
    'ad_code_4' => '',
    'ad_code_5' => '',
    'ad_code_6' => '',
    'functions' => '<?php'.str_repeat(PHP_EOL, 3),
    'remove_settings' => 0);

  $defaults = apply_filters("mystique_default_settings", $defaults); // check for new default setting entries from extensions
  return $defaults;
}

function font_styles(){
 // default font styles
 return array(
  0 => array('code' => '"Segoe UI",Calibri,"Myriad Pro",Myriad,"Trebuchet MS",Helvetica,Arial,sans-serif',
             'desc' => 'Segoe UI (Windows Vista/7)'),

  1 => array('code' => '"Helvetica Neue",Helvetica,Arial,Geneva,"MS Sans Serif",sans-serif',
             'desc' => 'Helvetica/Arial'),

  2 => array('code' => 'Georgia,"Nimbus Roman No9 L",serif',
             'desc' => 'Georgia (sans serif)'),

  3 => array('code' => '"Lucida Grande","Lucida Sans","Lucida Sans Unicode","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif',
             'desc' => 'Lucida Grande/Sans (Mac/Windows)')
  // you can add more font styles here based on the above entries (4, 5, 6 etc...)
 );
}

function mystique_theme_install_notification(){ ?>
  <div class='updated fade'><p><?php printf(__('You can configure Mystique from the <a%s>theme settings</a> page.','mystique'),' href="themes.php?page=theme-settings"'); ?></p></div>
<?php
}

function mystique_verify_options(){
  $default_settings = mystique_default_settings();
  $current_settings = get_option('mystique');
  if(!$current_settings):
   mystique_setup_options();
   add_action('admin_notices', 'mystique_theme_install_notification');
  else:
   // only go further if the theme version from the database differs from the one in the theme files
   if (version_compare($current_settings['theme_version'], THEME_VERSION, '!=')):
     // check for new options
     foreach($default_settings as $option=>$value):
      if(!array_key_exists($option, $current_settings)) $current_settings[$option] = $default_settings[$option];
     endforeach;

    // delete the old twitter cache option (<2.3.1)
    delete_option('mystique-twitter');

    // update theme version
    $current_settings['theme_version'] = THEME_VERSION;
    update_option('mystique' , $current_settings);
    do_action('mystique_verify_options');
   endif;
  endif;
}

function mystique_setup_options() {
  mystique_remove_options();
  $default_settings = mystique_default_settings();
  update_option('mystique' , $default_settings);
  do_action('mystique_setup_options');
}

function mystique_remove_options() {
  delete_option('mystique');
  do_action('mystique_remove_options');
}

function get_mystique_option($option) {
  $get_mystique_options = get_option('mystique');
  return $get_mystique_options[$option];
}

function print_mystique_option($option) {
  $get_mystique_options = get_option('mystique');
  echo $get_mystique_options[$option];
}

function mystique_is_color_dark($hex){

  // hex to rgb first
  $dec = hexdec($hex);
  $r = 0xFF & ($dec >> 0x10);
  $g = 0xFF & ($dec >> 0x8);
  $b = 0xFF & $dec;

  // rgb to hsb (we only need b)
  $max = max(array($r, $g, $b));
  $min = min(array($r, $g, $b));
  $diff = $max - $min;
  $br = $max / 255;
  if ($max > 0) $s = $diff / $max; else $s = 0;

  $s = round($s * 100);    // saturation
  $br = round($br * 100);  // brightness
  return (($br < 66)  || ($s > 66) ? true : false);
}


function mystique_user_functions(){
  function eval_functions_error(){ ?>
   <div class='error fade'><p><?php _e("There are one or more PHP parse errors in your custom functions.","mystique"); ?></p></div>
  <?php
  }
  $user_functions = get_mystique_option('functions');
  if ($user_functions && $userfunctions<>('<?php'.str_repeat(PHP_EOL, 3))):
    // remove any php tags from start/end of the string (there shouldn't be html output outside any function)
    $user_functions = mystique_trim_string($user_functions, '<?php');
    $user_functions = mystique_trim_string($user_functions, '?>');
    if (FALSE === @eval($user_functions.' return true;')) add_action('admin_notices', 'eval_functions_error');
  endif;
}

function mystique_query_vars($public_query_vars) {
  $public_query_vars[] = 'mystique';
  return $public_query_vars;
}

function mystique_dynamic_css_and_js(){
  $var = get_query_var('mystique');
  if ($var == 'css'):
    include_once(TEMPLATEPATH . '/lib/settings.css.php');
    exit;
  elseif($var == 'jquery_init'):
    include_once(TEMPLATEPATH . '/js/init.js.php');
    exit;
  endif;
}

function mystique_load_stylesheets(){ ?>
<style type="text/css">
 @import "<?php bloginfo('stylesheet_url'); ?>";
 @import "<?php echo esc_url_raw(add_query_arg('mystique', 'css', (is_404() ? get_bloginfo('url') : mystique_curPageURL()))); ?>";
</style>
<!--[if lte IE 6]><link media="screen" rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ie6.css" type="text/css" /><![endif]-->
<!--[if IE 7]><link media="screen" rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ie7.css" type="text/css" /><![endif]-->
  <?php
}

function mystique_load_scripts(){
 if(get_mystique_option('jquery')):
  wp_enqueue_script('jquery');
  wp_enqueue_script('mystique', THEME_URL.'/js/jquery.mystique.js', array('jquery'), $ver=THEME_VERSION, $in_footer=true);
  wp_enqueue_script('mystique-init', esc_url_raw(add_query_arg('mystique', 'jquery_init', (is_404() ? get_bloginfo('url') : mystique_curPageURL()))), array('jquery', 'mystique'), $ver=THEME_VERSION, $in_footer=true);
 endif;
}
