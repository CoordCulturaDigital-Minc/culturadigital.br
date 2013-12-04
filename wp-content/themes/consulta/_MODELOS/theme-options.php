<?php

function get_theme_default_options() {

    return array(
        'wellcome_title' => 'Benvindo!',
        'wellcome_video' => 'youtube.com/video',
        'itsnoon_creative_currency' => 0
    );

}

function get_theme_option($option_name) {
    $option = wp_parse_args( 
                    get_option('theme_options'), 
                    get_theme_default_options()
                );
    return isset($option[$option_name]) ? $option[$option_name] : false;
}

add_action('admin_init', 'theme_options_init');
add_action('admin_menu', 'theme_options_menu');

function theme_options_init() {
    register_setting('theme_options_options', 'theme_options', 'theme_options_validate_callback_function');
}

function theme_options_menu() {
    
    
    $topLevelMenuLabel = 'SLUG';
    $page_title = 'Opções';
    $menu_title = 'Opções';
    
    /* Top level menu */
    add_submenu_page('theme_options', $page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    add_menu_page($topLevelMenuLabel, $topLevelMenuLabel, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
        
    /* Menu embaixo de um menu existente */
    //add_dashboard_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_posts_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_plugin_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_media_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_links_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_pages_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_comments_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_plugins_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_users_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_management_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_options_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    //add_theme_page($page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    
}

function theme_options_validate_callback_function($input) {

    //$input['slug_updates'] = sanitize_title($input['slug_updates']);
    return $input;

}



function theme_options_page_callback_function() {
    
?>
  <div class="wrap span-20">
    <h2><?php echo __('Theme Options', 'SLUG'); ?></h2>

    <form action="options.php" method="post" class="clear prepend-top">
      <?php settings_fields('theme_options_options'); ?>
      <?php $options = wp_parse_args( get_option('theme_options'), get_theme_default_options() );?>
      
      <div class="span-20 ">
      
        <h3><?php _e("Home Page for visitors"); ?></h3>
        
        <div class="span-6 last">
          
          
          <label for="wellcome_title"><strong><?php _e("Welcome Title", "itsnoon"); ?></strong></label><br/>
          <input type="text" id="wellcome_title" class="text" name="theme_options[wellcome_title]" value="<?php echo htmlspecialchars($options['wellcome_title']); ?>"/>
          <br/><br/>


          <!-- TEXTAREA -->
          <label for="wellcome_video"><strong><?php _e("Video", "itsnoon"); ?></strong></label><br/>
          <textarea id="wellcome_video" name="theme_options[wellcome_video]"><?php echo htmlspecialchars($options['wellcome_video']); ?></textarea>
          <br/><br/>

          
          <!-- CHECKBOX -->
          <input type="checkbox" id="itsnoon_creative_currency" class="text" name="theme_options[itsnoon_creative_currency]" value="1" <?php checked(true, get_theme_option('itsnoon_creative_currency'), true); ?>/>
          <label for="itsnoon_creative_currency"><strong><?php _e("Enable creative currency", "itsnoon"); ?></strong></label><br/>
          <br/><br/>
          
        </div>
      </div>
      
      <p class="textright clear prepend-top">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
      </p>
    </form>
  </div>

<?php } ?>
