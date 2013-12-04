<?php
/*
Function Name: Custom Theme
Description: Allow the user to chose the disposal and visibility of some itens of the theme
Version: 0.1
Author: Marcelo Mesquita
Author URI: http://www.marcelomesquita.com/
*/

class custom_theme
{
  // ATRIBUTES ////////////////////////////////////////////////////////////////////////////////////
  
  // METHODS //////////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    Menu
  ************************************************************************************************/
  function custom_theme_menu()
  {
    add_theme_page(__("Custom theme", "painter"), __("Custom theme", "painter"), "edit_themes", basename(__FILE__), array(&$this, 'admin_theme'));
  }

  /************************************************************************************************
    Manage theme
  ************************************************************************************************/
  function admin_theme()
  {
    // Save options
    if(!empty($_POST['save_theme_options'])) :
      // General Options
      $painter_options['show_session_title'] = (int) $_POST['painter_show_session_title'];
      $painter_options['show_post_navigation'] = (int) $_POST['painter_show_post_navigation'];
      
      // Post Infos
      $painter_options['show_date'] = (int) $_POST['painter_show_date'];
      $painter_options['show_author'] = (int) $_POST['painter_show_author'];
      $painter_options['show_category'] = (int) $_POST['painter_show_category'];
      $painter_options['show_tags'] = (int) $_POST['painter_show_tags'];
      $painter_options['show_comments'] = (int) $_POST['painter_show_comments'];
      
      // Update options
      update_option('painter_options', $painter_options);
      
      // Show message
      printf("<div style='background-color:rgb(207, 235, 247);' id='message' class='updated fade'><p><strong>%s</strong></p></div>", __("Options updated", "painter"));
    endif;
    
    // Get options
    $painter_options = get_option('painter_options');
    
    // Extract options
    if(is_array($painter_options)) extract($painter_options);
    
    // Form
    ?>
      <div class="wrap">
        <h2><?php _e('Custom theme', 'painter'); ?></h2>
        
        <form method="post" action="">
          
          <table class="form-table">
            <tbody>
              <tr valign="top">
                <th scope="row"><?php _e('Theme options', 'painter'); ?></th>
                <td>
                  <label title="<?php _e('Show session title', 'painter'); ?>"><input type="checkbox" name="painter_show_session_title" value="1" <?php if($show_session_title == 1) print 'checked="checked"'; ?> /> <?php _e('Show session title', 'painter'); ?></label><br>
                  <label title="<?php _e('Show post navigation', 'painter'); ?>"><input type="checkbox" name="painter_show_post_navigation" value="1" <?php if($show_post_navigation == 1) print 'checked="checked"'; ?> /> <?php _e('Show post navigation', 'painter'); ?></label><br>
                </td>
              </tr>
              <tr valign="top">
                <th scope="row"><?php _e('Post itens', 'painter'); ?></th>
                <td>
                  <label title="<?php _e('Show post date', 'painter'); ?>"><input type="checkbox" name="painter_show_date" value="1" <?php if($show_date == 1) print 'checked="checked"'; ?> /> <?php _e('Show post date', 'painter'); ?></label><br>
                  <label title="<?php _e('Show post author', 'painter'); ?>"><input type="checkbox" name="painter_show_author" value="1" <?php if($show_author == 1) print 'checked="checked"'; ?> /> <?php _e('Show post author', 'painter'); ?></label><br>
                  <label title="<?php _e('Show post category', 'painter'); ?>"><input type="checkbox" name="painter_show_category" value="1" <?php if($show_category == 1) print 'checked="checked"'; ?> /> <?php _e('Show post category', 'painter'); ?></label><br>
                  <label title="<?php _e('Show post tags', 'painter'); ?>"><input type="checkbox" name="painter_show_tags" value="1" <?php if($show_tags == 1) print 'checked="checked"'; ?> /> <?php _e('Show post tags', 'painter'); ?></label><br>
                  <label title="<?php _e('Show post comments', 'painter'); ?>"><input type="checkbox" name="painter_show_comments" value="1" <?php if($show_comments == 1) print 'checked="checked"'; ?> /> <?php _e('Show post comments', 'painter'); ?></label><br>
                </td>
              </tr>
            </tbody>
          </table>
          
          <p class="submit">
            <input type="submit" name="save_theme_options" class="button-primary" value="<?php _e('Save'); ?>" />
          </p>
          
        </form>
        
      </div>
    <?php
  }
  
  // CONSTRUCTOR //////////////////////////////////////////////////////////////////////////////////
  /************************************************************************************************
    Custom colors constructor
  ************************************************************************************************/
  function custom_theme()
  {
    // ativar o menu
    add_action('admin_menu', array(&$this, 'custom_theme_menu'));
  }
  
  // DESTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
  
}

$custom_theme = new custom_theme();

?>
