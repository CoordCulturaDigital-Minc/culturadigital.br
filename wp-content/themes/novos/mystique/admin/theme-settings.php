<?php /* Mystique/digitalnature */

function mystique_setup_admin_js(){
  wp_enqueue_script('mystique-settings', THEME_URL.'/admin/theme-settings.js', array('jquery'));
  wp_enqueue_script('theme-install');
  wp_enqueue_script('theme-preview');
  ?>
 <?php
}

function get_upload_dir($dir) {
  $uploadpath = wp_upload_dir();
  if ($uploadpath['baseurl']=='') $uploadpath['baseurl'] = get_bloginfo('siteurl').'/wp-content/uploads';
  return $uploadpath[$dir];
}

// load theme preview iframe with ajax (faster access to theme settings)
function mystique_get_site_preview() {
  check_ajax_referer("site_preview"); ?>
  <iframe id="themepreview" name="themepreview" src="<?php echo get_option('home'); ?>/?preview=1"></iframe>
  <?php die();
}

function mystique_admin_init_js(){
  $nonce = wp_create_nonce('site_preview');
  $font_styles = font_styles();
  $current_settings = get_option('mystique');
  ?>
  <script type="text/javascript">
  /* <![CDATA[ */
  // init
  jQuery(document).ready(function () {

    // set up tabs
    jQuery("#mystique-settings-tabs").minitabs({contentClass:'.sections',speed:333});

    // show link select box if navigation is set to show links
    jQuery('#opt_navigation').change(function() {
     jQuery('.opt_links, #page-list, #category-list').hide();
     if(jQuery(this).find('option:selected').attr('value')!='0') jQuery('#nav-list').show(); else jQuery('#nav-list').hide();
     if(jQuery(this).find('option:selected').attr('value')=='links') jQuery('.opt_links').show();
     if(jQuery(this).find('option:selected').attr('value')=='pages') jQuery('#page-list').show();
     if(jQuery(this).find('option:selected').attr('value')=='categories') jQuery('#category-list').show();
    });
    jQuery("#opt_navigation").change();

    jQuery('#opt_jquery').change(function() {
     jQuery('#opt_lightbox,#opt_ajax_comments').attr("disabled", true);
     if (jQuery(this).is(":checked")){
       jQuery('#opt_lightbox, #opt_ajax_comments').attr("disabled", false);
     }
    });
    jQuery("#opt_jquery").change();

    jQuery('#opt_post_content').change(function() {
     jQuery('#opt_post_content_length').attr("disabled", true);
     if (jQuery(this).is(":checked")){
       jQuery('#opt_post_content_length').attr("disabled", false);
     }
    });
    jQuery("#opt_post_content").change();

    // not on IE
    if(jQuery.support.leadingWhitespace) jQuery('#layout-settings, #color-scheme').radio2select();

    jQuery.ajax({
		type: "post",url: "admin-ajax.php",data: { action: 'site_preview', _ajax_nonce: '<?php echo $nonce; ?>' },
		beforeSend: function() {jQuery("#themepreview-wrap .loading").show("slow");},
		complete: function() { jQuery("#themepreview-wrap .loading").hide("fast");},
		success: function(html){
			jQuery("#themepreview-wrap").html(html);
			jQuery("#themepreview-wrap").show();

            jQuery('#themepreview-wrap:not(.processed)').TextAreaResizer();

            // wait for load because of IE problems...
            jQuery('#themepreview').load(function(){

               // remove links to other pages from iframe doc.
              jQuery('#themepreview').contents().find("a").each(function() {
                 $href = jQuery(this).attr('href');
                 if ($href && $href.indexOf("#") != 0) jQuery(this).attr("href","#");
              });

              // initialize some variables
              var  scale_grid_12 = ['0', '|', '|', '60', '|', '|', '|', '140', '|', '|', '|', '220', '|', '|', '|', '300', '|', '|', '|', '380', '|', '|', '|', '460', '|', '|', '|', '540', '|', '|', '|', '620', '|', '|', '|', '700', '|', '|', '|', '780', '|', '|', '|', '860', '|', '|', '|', '940'],
                   scale_fluid = ['0', '|', '10', '|', '20', '|', '30', '|', '40', '|','50', '|', '60', '|', '70', '|', '80', '|', '90', '|', '100'],
                   layout = '<?php echo($current_settings['page_width'] == 'fluid' ? 'fluid' : 'fixed'); ?>',
                   layout_type = '<?php echo $current_settings['layout']; ?>',
                   unit = '<?php echo($current_settings['page_width'] == 'fluid' ? '%' : 'px'); ?>',
                   gs = <?php echo($current_settings['page_width'] == 'fluid' ? '100' : '940'); ?>,
                   jstep = <?php echo($current_settings['page_width'] == 'fluid' ? '1' : '10'); ?>,
                   jscale = <?php echo($current_settings['page_width'] == 'scale_fluid' ? '1' : 'scale_grid_12'); ?>,

                   set_slider = function(){
                    jQuery("#dimension_controls td #slider input").appendTo("#dimension_controls td");
                    jQuery("#dimension_controls td #slider").html("");

                    if(layout_type != 'col-1'){
                      jQuery("#dimension_controls #opt_dimensions_"+layout+"_"+layout_type).appendTo(("#dimension_controls td #slider"));


                      jQuery("#dimension_controls td #slider input").slider({
                       from: 0,
                       to: gs,
                       step: jstep,
                       dimension: unit,
                       scale: jscale,
                       limits: false,
                       onstatechange: function(value){
                           //alert(value);

                         var s = value.split(';');

                         s[0] = parseInt(s[0]);
                         s[1] = parseInt(s[1]);

                         switch(layout_type){
                          case 'col-2-left':
                            jQuery('#primary-content',themepreview.document).css({'width': gs-s[0]+unit, 'left': s[0]+unit});
                            jQuery('#sidebar',themepreview.document).css({'width': s[0]+unit, 'left': -(gs-s[0])+unit, 'right': 'auto'});
                            break;
                          case 'col-2-right':
                            jQuery('#primary-content',themepreview.document).css({'width': gs-(gs-s[0])+unit, 'left': '0'});
                            jQuery('#sidebar',themepreview.document).css({'width': gs-s[0]+unit, 'right': '0', 'left': 'auto'});
                            break;
                          case 'col-3':
                            jQuery('#primary-content',themepreview.document).css({'width': (gs-s[0]-(gs-s[1]))+unit, 'left': s[0]+unit, 'right': 'auto'});
                            jQuery('#sidebar',themepreview.document).css({'width': gs-s[1]+unit, 'right': '0', 'left': 'auto'});
                            jQuery('#sidebar2',themepreview.document).css({'width': s[0]+unit, 'left': (-(gs-s[0]-(gs-s[1])))+unit, 'right': 'auto'});
                            break;
                          case 'col-3-left':
                            jQuery('#primary-content',themepreview.document).css({'width': (gs-s[1])+unit, 'left': (s[1])+unit, 'right': 'auto'});
                            jQuery('#sidebar',themepreview.document).css({'width': s[0]+unit, 'left': -(gs-s[0])+unit, 'right': 'auto'});
                            jQuery('#sidebar2',themepreview.document).css({'width': (s[1]-s[0])+unit, 'left': -(gs-s[1])+s[0]+unit, 'right': 'auto'});
                            break;
                          case 'col-3-right':
                            jQuery('#primary-content',themepreview.document).css({'width': s[0]+unit, 'left': '0', 'right': 'auto'});
                            jQuery('#sidebar',themepreview.document).css({'width': (gs-s[1])+unit, 'left': '0', 'right': 'auto'});
                            jQuery('#sidebar2',themepreview.document).css({'width': (s[1]-s[0])+unit, 'left': '0', 'right': 'auto'});
                            break;
                          }

                          return value;
                        }
                      });
                      jQuery("#dimension_controls").show();

                    }
                    else{
                      jQuery("#dimension_controls").hide();
                      jQuery('#primary-content',themepreview.document).css({'width': gs+unit, 'left': '0', 'right': 'auto'});
                    }

                   }

              jQuery('input[name=page_width]').change(function() {
                if(jQuery(this).val() == 'fluid'){
                  unit = '%';
                  gs = 100;
                  jstep = 1;
                  jscale = scale_fluid;
                  layout = jQuery(this).val();
                } else {
                  layout = jQuery(this).val();
                  unit = 'px';
                  gs = 940;
                  jstep = 10;
                  jscale = scale_grid_12;
                }
                set_slider(); // reset range slider
                jQuery('body',themepreview.document).removeClass((jQuery(this).val() == 'fluid') ? "fixed" : "fluid"); // remove old class
                jQuery('body',themepreview.document).addClass((jQuery(this).val() == 'fluid') ? "fluid" : "fixed"); // add new one
                return true;
              });

              jQuery('#layout-settings input[type=radio]').change(function(){
                  jQuery('body',themepreview.document).removeClass(layout_type); // remove old class
                  layout_type = jQuery(this).val();
                  jQuery('body',themepreview.document).addClass(layout_type); // add new one
                  set_slider(); // reset range slider
                  return true;
              });

              jQuery('#color-scheme input[type=radio]').change(function() {
               $sel = jQuery(this).attr('value');
               jQuery('body',themepreview.document).append('<style type="text/css">@import "<?php echo THEME_URL; ?>/color-'+$sel+'.css"; </style>');
              });
              jQuery("#color-scheme input[type=radio]:checked").change();


              set_slider(); // show range slider

              // font style check
              jQuery('#opt_font_style').change(function() {
               $sel = jQuery(this).find('option:selected').attr('value');
               switch($sel) {
                <?php foreach ($font_styles as $i => $value): ?>
                case '<?php echo $i; ?>':jQuery('*',themepreview.document).css('font-family','<?php echo $font_styles[$i]['code']; ?>');break;
                <?php endforeach; ?>
               }
              });

              // colors
              jQuery('.color-selector').ColorPicker({
                onShow: function (colpkr) {
                  jQuery(colpkr).css({ opacity: 0, marginTop: -10 }).show().animate({ opacity: 1, marginTop: 0 }, 160);
                  jQuery(this).ColorPickerSetColor(jQuery(this).find('input').val());
                  return false;
                },
                onHide: function (colpkr) {
                  jQuery(colpkr).fadeOut(160);
                  return false;
                },
                onChange: function (hsb, hex, rgb, el) {
                  jQuery(el).find(".preview").css('background-color', '#'+hex);
                  $input = jQuery(el).find('input');
                  var target;
                  $input.val(hex);
                  if(hex != '000000'){
                    document.getElementById('themepreview').contentWindow.jQuery.cssRule('body', "background-color", "#"+hex);
                    document.getElementById('themepreview').contentWindow.jQuery.cssRule('<?php if(get_mystique_option('background')==''): ?>body,<?php endif; ?>#page', "background-image", "none");
                  }
                  else{ // pretty ugly, need to improve this
                    document.getElementById('themepreview').contentWindow.jQuery.cssRule('body', "background-color", "#000");;
                    <?php if(get_mystique_option('background')==''): ?>
                    document.getElementById('themepreview').contentWindow.jQuery.cssRule('body', "background-image", "url(<?php echo THEME_URL; ?>/images/bg.png)");;
                    document.getElementById('themepreview').contentWindow.jQuery.cssRule('#page', "background-image", "url(<?php echo THEME_URL; ?>/images/header.jpg)");
                    <?php endif; ?>
                  }
                }
              });


        });

       	}
	});

    // make all textareas resizable
    jQuery('.ad-code:not(.processed)').TextAreaResizer();

  });

  <?php do_action("mystique_admin_init_js"); ?>

  /* ]]> */
  </script>
 <?php
}

function mystique_setup_admin_css($dir) {
  wp_register_style('mystique-settings', THEME_URL. '/admin/theme-settings.css');
  wp_enqueue_style('mystique-settings');
  wp_enqueue_style('theme-install');
}

function is_valid_image($image){
  // check mime type
  if(!eregi('image/', $_FILES[$image]['type'])):
   wp_redirect(admin_url('themes.php?page=theme-settings&error=1'));
   exit(0);
  endif;

  // check if valid image
  $imageinfo = getimagesize($_FILES[$image]['tmp_name']);
  if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && isset($imageinfo)):
   wp_redirect(admin_url('themes.php?page=theme-settings&error=2'));
   exit(0);
  endif;

  list($width, $height) = $imageinfo;

  $directory = get_upload_dir('basedir').'/';
  if(!@move_uploaded_file($_FILES[$image]['tmp_name'],$directory.$_FILES[$image]["name"])):
   wp_redirect(admin_url('themes.php?page=theme-settings&error=3'));
   exit(0);
  else:
   return $width.'x'.$height;
  endif;
}

function mystique_update_options() {
  check_admin_referer('theme-settings');

  // enable theme settings for lower level users, but with limitations
  if(!current_user_can('switch_themes')) wp_die(__('You are not authorised to perform this operation.', 'mystique'));
  $options = get_option('mystique');

  foreach (mystique_default_settings() as $key => $value):
   $options[$key] = stripslashes((string)$_POST[$key]);
   if(($key == 'exclude_pages') && ($_POST[$key] != '')) $options[$key] = implode(',', $_POST[$key]);// else $options['exclude_pages'] = '';
   if(($key == 'exclude_categories') && ($_POST[$key] != '')) $options[$key] = implode(',', $_POST[$key]);// else $options['exclude_categories'] = '';

   // filter potentially malicious html/css (eg <script>, onclick, css expressions etc)
   if(!current_user_can('unfiltered_html')) $options[$key] = mystique_strip_tags_attributes($options[$key]);
  endforeach;

  // build dimensions array
  $dimensions = get_mystique_option('dimensions');
  foreach($dimensions as $layout_size => $layout_types)
   foreach($layout_types as $layout => $values)
    $options['dimensions'][$layout_size][$layout] = $_POST['dimensions_'.$layout_size.'_'.$layout];

  if(isset($_POST['remove-logo'])):
   $options['logo'] = '';
   $options['logo_size'] = '';
  elseif($_FILES["file-logo"]["type"]):
   $valid = is_valid_image('file-logo');
   if($valid):
    $options['logo'] = get_upload_dir('baseurl'). "/". $_FILES["file-logo"]["name"];
    $options['logo_size'] = $valid;
   endif;
  endif;

  if(isset($_POST['remove-background'])):
   $options['background'] = '';
  elseif($_FILES["file-background"]["type"]):
   $valid = is_valid_image('file-background');
   if($valid):
    $options['background'] = get_upload_dir('baseurl'). "/". $_FILES["file-background"]["name"];
   endif;
  endif;

  update_option('mystique', $options);

  // reset?
  if (isset($_POST['reset'])) mystique_setup_options();

  wp_redirect(admin_url('themes.php?page=theme-settings&updated=true'));
}

// from themes.php
function mystique_check_update() {
  static $themes_update;
  $theme = get_theme("Mystique");
  if (!isset($themes_update)) $themes_update = get_transient('update_themes');

  if (is_object($theme) && isset($theme->stylesheet)) $stylesheet = $theme->stylesheet;
  elseif (is_array($theme) && isset($theme['Stylesheet'])) $stylesheet = $theme['Stylesheet'];
  else return;

  if (isset($themes_update->response[$stylesheet])):
    $update = $themes_update->response[$stylesheet];
    $theme_name = is_object($theme) ? $theme->name : (is_array($theme) ? $theme['Name'] : '');
    $details_url = add_query_arg(array('TB_iframe' => 'true', 'width' => 1024, 'height' => 800), $update['url']);
    $update_url = wp_nonce_url('update.php?action=upgrade-theme&amp;theme='.urlencode($stylesheet), 'upgrade-theme_'.$stylesheet);
    echo '<div class="updated fade below-h2"><p>';
    if (!current_user_can('update_themes') || empty($update->package))
      printf(__('There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%1$s">View version %3$s Details</a>.'), $theme_name, $details_url, $update['new_version']);
    else printf(__('There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%1$s">View version %3$s Details</a> or <a href="%4$s" >upgrade automatically</a>.'), $theme_name, $details_url, $update['new_version'], $update_url);
    echo '</div>';
  endif;
}

function mystique_selected_class($value, $condition, $output = 'radio-selected'){
  if($condition == $value) return $output;
}


function mystique_theme_settings() {
  global $is_IE;
  if(!current_user_can('switch_themes')) wp_die(__('You are not authorised to perform this operation.', 'mystique')); ?>

  <div id="mystique-settings" class="wrap clear-block">
   <form action="<?php echo admin_url('admin-post.php?action=mystique_update'); ?>" method="post" enctype="multipart/form-data">

   <div class="clear-block">
     <?php screen_icon(); ?><h2 class="alignleft"><?php _e('Mystique settings','mystique'); ?></h2>
     <p class="controls alignleft"><input type="submit" class="button-primary" name="submit" value="<?php _e("Save Changes","mystique"); ?>" /><input type="submit" class="button-primary" name="reset" value="<?php _e("Reset to Defaults","mystique"); ?>" onclick="if(confirm('<?php _e("Reset all theme settings to the default values? Are you sure?", "mystique"); ?>')) return true; else return false;" /></p>
   </div>

   <?php wp_nonce_field('theme-settings'); ?>

   <?php mystique_check_update(); ?>

   <?php if (isset($_GET['updated'])): ?>
   <div class="updated fade below-h2">
    <p><?php printf(__('Settings saved. %s', 'mystique'),'<a href="' . user_trailingslashit(get_bloginfo('url')) . '">' . __('View site','mystique') . '</a>'); ?></p>
   </div>
   <?php elseif (isset($_GET['error'])):
     $errors  = array(
       1 => __("Please upload a valid image file!","mystique"),
       2 => __("The file you uploaded doesn't seem to be a valid JPEG, PNG or GIF image","mystique"),
       3 => __("The image could not be saved on your server","mystique")
     );

   ?>
   <div class="error fade below-h2">
    <p><?php printf(__('Error: %s', 'mystique'),$errors[$_GET['error']]); ?></p>
   </div>
   <?php endif; ?>

   <!-- tabbed content -->
   <div id="mystique-settings-tabs">

    <div class="clear-block">
     <ul class="subsubsub">
      <li class="design"><a href='#tab-design'><?php _e("Design","mystique"); ?></a></li> |
      <li class="content"><a href='#tab-content'><?php _e("Content","mystique"); ?></a></li> |
      <li class="nav"><a href='#tab-navigation'><?php _e("Navigation","mystique"); ?></a></li> |
      <li class="seo"><a href='#tab-seo'><?php _e("SEO","mystique"); ?></a></li> |
      <li class="ads"><a href='#tab-ads'><?php _e("Ads","mystique"); ?></a></li> |
      <li class="adv"><a href='#tab-advanced'><?php _e("Advanced","mystique"); ?></a></li> |
      <li class="usercss"><a href='#tab-css'><?php _e("User CSS","mystique"); ?></a></li> |
     </ul>
    </div>

    <!-- sections -->
    <div class="sections wrap metabox-holder">

     <div class="section" id="tab-design">

      <?php if($is_IE): ?>
      <p><?php _e("The design settings panel is not yet supported by Internet Explorer. Sorry :(","mystique"); ?></p>
      <?php else: ?>

      <div id="themepreview-wrap"><div class="clear-block"><div class="loading"><?php _e("Loading site preview...","mystique"); ?></div></div></div>

      <table class="form-table">
       <?php $layout = get_mystique_option('layout'); ?>
       <tr>
        <th scope="row"><p><?php _e("Layout style","mystique") ?><span><?php _e("Use page templates if you want to apply these only to specific pages","mystique"); ?></span></p></th>
        <td id="layout-settings" class="clear-block">
         <div class="layout-box">
          <label for="layout-settings-col-1" class="layout col-1 <?php echo mystique_selected_class('col-1', $layout); ?>"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-1" value="col-1" <?php checked('col-1', $layout); ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-2-left" class="layout col-2-left <?php echo mystique_selected_class('col-2-left', $layout); ?>"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-2-left" value="col-2-left" <?php checked('col-2-left', $layout); ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-2-right" class="layout col-2-right <?php echo mystique_selected_class('col-2-right', $layout); ?>"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-2-right" value="col-2-right" <?php checked('col-2-right', $layout); ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-3" class="layout col-3 <?php echo mystique_selected_class('col-3', $layout); ?>"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-3" value="col-3" <?php checked('col-3', $layout); ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-3-left" class="layout col-3-left <?php echo mystique_selected_class('col-3-left', $layout); ?>"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-3-left" value="col-3-left" <?php checked('col-3-left', $layout); ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-3-right" class="layout col-3-right <?php echo mystique_selected_class('col-3-right', $layout); ?>"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-3-right" value="col-3-right" <?php checked('col-3-right', $layout); ?>/>
         </div>

        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Color scheme","mystique"); ?></p></th>
        <td id="color-scheme">

         <div class="color-box">
          <label for="color-box-green" class="color_scheme green"></label>
          <input class="radio" type="radio" name="color_scheme" id="color-box-green" value="green" <?php checked('green', get_mystique_option('color_scheme')) ?>/>
         </div>

         <div class="color-box">
          <label for="color-box-blue" class="color_scheme blue"></label>
          <input class="radio" type="radio" name="color_scheme" id="color-box-blue" value="blue" <?php checked('blue', get_mystique_option('color_scheme')) ?>/>
         </div>

         <div class="color-box">
          <label for="color-box-red" class="color_scheme red"></label>
          <input class="radio" type="radio" name="color_scheme" id="color-box-red" value="red" <?php checked('red', get_mystique_option('color_scheme')) ?>/>
         </div>

         <div class="color-box">
          <label for="color-box-grey" class="color_scheme grey"></label>
          <input class="radio" type="radio" name="color_scheme" id="color-box-grey" value="grey" <?php checked('grey', get_mystique_option('color_scheme')) ?>/>
         </div>

        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Page (content) width","mystique"); ?><span><?php _e("Note that fluid pages can be hard to read on large resolutions.","mystique"); ?></span></p></th>
        <td>

         <label for="opt_page_width_fixed"><input name="page_width" type="radio" id="opt_page_width_fixed" class="radio" value="fixed" <?php checked('fixed', get_mystique_option('page_width')) ?> /><?php printf(__("Fixed (%s)","mystique"),'<a href="http://960.gs/" target="_blank">960gs</a>'); ?></label>
         <label for="opt_page_width_fluid"><input name="page_width" type="radio" id="opt_page_width_fluid" class="radio" value="fluid" <?php checked('fluid', get_mystique_option('page_width')) ?> /><?php _e("Fluid (100%)/Custom","mystique"); ?></label>

        </td>
       </tr>

       <tr id="dimension_controls"<?php if(get_mystique_option('layout') == 'col-1'): ?> class="hidden"<?php endif; ?>>
        <th scope="row"><p><?php _e("Column sizes","mystique"); ?><span><?php _e("(With 10 pixel padding between them)","mystique"); ?></span></p></th>
        <td>
         <div id="slider"></div>
         <?php
           $dimensions = get_mystique_option('dimensions');
           foreach($dimensions as $layout_size => $layout_types)
            foreach($layout_types as $layout => $values)
              echo '<input type="hidden" id="opt_dimensions_'.$layout_size.'_'.$layout.'" name="dimensions_'.$layout_size.'_'.$layout.'" value="'.$values.'" />'.PHP_EOL;
         ?>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Font style","mystique"); ?></p></th>
        <td>
          <?php $font_styles = font_styles(); ?>

          <select name="font_style" id="opt_font_style">
           <?php foreach ($font_styles as $entry => $name): ?>
           <option value="<?php echo $entry; ?>" <?php selected($entry, get_mystique_option('font_style')); ?> style='font-size:100%; font-family: <?php echo $font_styles[$entry]['code']; ?>;'><?php echo $font_styles[$entry]['desc']; ?></option>
           <?php endforeach; ?>
         </select>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Background color","mystique"); ?><span><?php _e("Leave Black (#000000) to keep the default background","mystique"); ?></span></p></th>
        <td>

           <div class="color-selector clear-block" id="opt_background_color">
             <div class="preview" style="background-color: #<?php echo wp_specialchars(get_mystique_option('background_color')); ?>">
               <input name="background_color" type="hidden" value="<?php echo wp_specialchars(get_mystique_option('background_color')); ?>" />
             </div>
           </div>

        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Custom logo image","mystique"); ?><span><?php _e("Show a logo image instead of text; Upload the graphic file from your computer","mystique"); ?></span></p></th>
        <td>
          <?php if(is_writable(get_upload_dir('basedir'))): ?>
           <input type="file" name="file-logo" id="file-logo" />
           <?php if(get_mystique_option('logo')): ?>
           <button type="submit" class="button" name="remove-logo" value="0"><?php _e("Remove current image","mystique"); ?></button>
           <div class="clear-block">
            <div class="image-preview"><img src="<?php echo get_mystique_option('logo'); ?>" style="padding:10px;" /></div>
           </div>
           <?php endif; ?>
         <?php else: ?>
         <p class="error" style="padding: 4px;"><?php printf(__("Directory %s doesn't have write permissions - can't upload!","mystique"),'<strong>'.get_upload_dir('basedir').'</strong>'); ?></p><p><?php _e("Check your upload path in Settings/Misc or CHMOD this directory to 755/777.<br />Contact your host if you don't know how","mystique"); ?></p>
         <?php endif; ?>
         <input type="hidden" name="logo" value="<?php echo get_mystique_option('logo'); ?>">
         <input type="hidden" name="logo_size" value="<?php echo get_mystique_option('logo_size'); ?>">
        </td>
       </tr>


       <tr>
        <th scope="row"><p><?php _e("Custom background image","mystique"); ?><span><?php _e("Upload a new background/header image to replace the default one","mystique"); ?></span></p></th>
        <td>
         <?php if(is_writable(get_upload_dir('basedir'))): ?>
           <input type="file" name="file-background" id="file-background" />
           <?php if(get_mystique_option('background')): ?>
           <button type="submit" class="button" name="remove-background" value="0"><?php _e("Remove current image","mystique"); ?></button>
           <div class="clear-block">
            <div class="image-preview"><img src="<?php echo get_mystique_option('background'); ?>" style="padding:10px;" /></div>
           </div>
           <?php endif; ?>
         <?php else: ?>
         <p class="error" style="padding: 4px;"><?php printf(__("Directory %s doesn't have write permissions - can't upload!","mystique"),'<strong>'.get_upload_dir('basedir').'</strong>'); ?></p><p><?php _e("Check your upload path in Settings/Misc or CHMOD this directory to 755/777.<br />Contact your host if you don't know how","mystique"); ?></p>
         <?php endif; ?>
         <input type="hidden" name="background" value="<?php echo get_mystique_option('background'); ?>">
        </td>
       </tr>

      </table>
     <?php endif; ?>
     </div>

     <div class="section" id="tab-content">
      <table class="form-table">

       <tr>
        <th scope="row"><p><?php _e("Posts (single)","mystique"); ?><span><?php _e("How much information do you want to show on the single post page?", "mystique"); ?></span></p></th>
        <td>
         <label for="opt_single_tags">
           <input name="post_single_tags" id="opt_single_tags" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_single_tags')) ?> /> <?php _e("Tags", "mystique"); ?>
         </label>
         <label for="opt_single_meta">
           <input name="post_single_meta" id="opt_single_meta" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_single_meta')) ?> /> <?php _e("Meta", "mystique"); ?>
         </label>
         <label for="opt_single_share">
          <input name="post_single_share" id="opt_single_share" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_single_share')) ?> /> <?php _e("Share", "mystique"); ?>
         </label>
         <label for="opt_single_author">
           <input name="post_single_author" id="opt_single_author" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_single_author')) ?> /> <?php _e("About the author", "mystique"); ?>
         </label>
         <label for="opt_single_print">
          <input name="post_single_print" id="opt_single_print" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_single_print')) ?> /> <?php _e("Print link", "mystique"); ?>
         </label>
         <label for="opt_single_related">
           <input name="post_single_related" id="opt_single_related" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_single_related')) ?> /> <?php _e("Related posts", "mystique"); ?>
         </label>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Post previews","mystique"); ?><span><?php _e("How much info do you want to show on blog/category/archive/search pages?", "mystique"); ?></span></p></th>
        <td>
         <label for="opt_post_title">
           <input name="post_title" id="opt_post_title" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_title')) ?> /> <?php _e("Title", "mystique"); ?>
         </label>
         <label for="opt_post_info">
           <input name="post_info" id="opt_post_info" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_info')) ?> /> <?php _e("Info bar", "mystique"); ?>
         </label>
         <label for="opt_post_tags">
          <input name="post_tags" id="opt_post_tags" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_tags')) ?> /> <?php _e("Tags"); ?>
         </label>
         <label for="opt_post_content" style="margin-right: 0;">
          <input name="post_content" id="opt_post_content" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('post_content')) ?> /> <?php _e("Content", "mystique"); ?>
         </label>
         <select name="post_content_length" id="opt_post_content_length"<?php if(!get_mystique_option('post_content')): ?> disabled="disabled"<?php endif; ?>>
           <option value="50" <?php selected('50', get_mystique_option('post_content_length')); ?>><?php _e('max. 50 words, filtered', 'mystique'); ?></option>
           <option value="100" <?php selected('100', get_mystique_option('post_content_length')); ?>><?php _e('max. 100 words, filtered', 'mystique'); ?></option>
           <option value="200" <?php selected('200', get_mystique_option('post_content_length')); ?>><?php _e('max. 200 words, filtered', 'mystique'); ?></option>
           <option value="f" <?php selected('f', get_mystique_option('post_content_length')); ?>><?php _e('full', 'mystique'); ?></option>
           <option value="ff" <?php selected('ff', get_mystique_option('post_content_length')); ?>><?php _e('full, filtered', 'mystique'); ?></option>
           <option value="e" <?php selected('e', get_mystique_option('post_content_length')); ?>><?php _e('excerpt', 'mystique'); ?></option>
         </select>
        </td>
       </tr>

       <tr>
         <th scope="row"><p><?php _e("Auto generate thumbnails","mystique"); ?><span><?php _e("Get the 1st image attached to a post, if no thumbnail is set manually","mystique"); ?></span></p></th>
         <td><input name="post_thumb_auto" type="checkbox" class="checkbox" value="1" <?php checked( '1', get_mystique_option('post_thumb_auto')) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Post thumbnail size","mystique"); ?><span><?php printf(__("Note that this only works for images you upload from now on, older images will be browser-resized. You should use the %s plugin to create missing image sizes","mystique"),'<a href="http://wordpress.org/extend/plugins/regenerate-thumbnails/" target="_blank">Regenerate Thumbnails</a>'); ?></span></p></th>
        <td>
         <select name="post_thumb">
          <?php $wpsize = get_option('thumbnail_size_w').' x '.get_option('thumbnail_size_h'); ?>
          <option value="48x48" <?php selected('48x48', get_mystique_option('post_thumb')); ?>><?php _e('Very small: 48 x 48', 'mystique'); ?></option>
          <option value="64x64" <?php selected('64x64', get_mystique_option('post_thumb')); ?>><?php _e('Small: 64 x 64', 'mystique'); ?></option>
          <option value="80x80" <?php selected('80x80', get_mystique_option('post_thumb')); ?>><?php _e('Medium: 80 x 80', 'mystique'); ?></option>
          <option value="100x100" <?php selected('100x100', get_mystique_option('post_thumb')); ?>><?php _e('Larger: 100 x 100', 'mystique'); ?></option>
          <option value="<?php echo str_replace (" ","",$wpsize); ?>" <?php selected(str_replace (" ","",$wpsize), get_mystique_option('post_thumb')); ?>><?php printf(__("WP's Media setting: %s", "mystique"),$wpsize); ?></option>
         </select>
        </td>
       </tr>

       <?php do_action("mystique_admin_content"); ?>

       <tr>
        <th scope="row"><p><?php _e("Footer content","mystique"); ?><span><?php  if(current_user_can('unfiltered_html')) _e("You can post HTML code",'mystique'); else _e("Only some HTML tags and attributes are allowed",'mystique'); ?></span></p></th>
        <td>
         <textarea id="opt_footer_content" rows="8" cols="60" name="footer_content" class="code"><?php echo wp_specialchars(get_mystique_option('footer_content')); ?></textarea>
         <br />
         <?php printf(__("Use the following short codes for convenient adjustments: <br />%s",'mystique'),'<code>[rss]</code> <code>[copyright]</code> <code>[credit]</code> <code>[ad code=#]</code> <code>[wp-link]</code> <code>[theme-link]</code> <code>[login-link]</code> <code>[blog-title]</code> <code>[xhtml]</code> <code>[css]</code> <code>[top]</code> <code>[page-rank]</code>.'); ?>
        </td>
       </tr>

      </table>
     </div>

     <div class="section" id="tab-navigation">
      <table class="form-table">
       <tr>
        <th scope="row"><p><?php _e("Top navigation shows","mystique"); ?></p></th>
        <td class="clear-block">
         <select name="navigation" id="opt_navigation" class="alignleft">
          <option value="0" <?php selected('0', get_mystique_option('navigation')); ?>><?php _e('Nothing (disabled)', 'mystique'); ?></option>
          <option value="pages" <?php selected('pages', get_mystique_option('navigation')); ?>><?php _e('Pages', 'mystique'); ?></option>
          <option value="categories" <?php selected('categories', get_mystique_option('navigation')); ?>><?php _e('Categories', 'mystique'); ?></option>
          <option value="links" <?php selected('links', get_mystique_option('navigation')); ?>><?php _e('Links', 'mystique'); ?></option>
          <?php if(WP_VERSION > 2.9): ?><option value="menus" <?php selected('menus', get_mystique_option('navigation')); ?>><?php _e('Custom Menus', 'mystique'); ?></option><?php endif; ?>
         </select>
         <div class="hidden alignleft inline opt_links">
          <?php
           $taxonomy = 'link_category';
           $args ='';
           $terms = get_terms( $taxonomy, $args );
           if ($terms): ?>
             <?php _e("from category","mystique"); ?>
             <select name="navigation_links">
             <?php
             foreach($terms as $term):
              if ($term->count > 0): ?>
               <option value="<?php echo $term->name; ?>" <?php selected($term->name, get_mystique_option('navigation_links')); ?>><?php echo $term->name; ?> (<?php printf(__("%s links","mystique"),$term->count); ?>)</option>
              <?php endif;
             endforeach;
             ?>
             </select>
             <?php
           else: ?>
             <p class="error"><?php _e("No links found","mystique"); ?></p>
           <?php
           endif;
          ?>
         </div>
        </td>
       </tr>

       <tr id="nav-list" <?php if(!get_mystique_option('navigation')): ?>class="hidden"<?php endif; ?>>
        <th scope="row"><p><?php _e("Exclude from navigation","mystique"); ?><span><?php _e("Check the items you wish to hide from the main menu","mystique"); ?></span></p></th>
        <td>

          <?php if(get_option('show_on_front')<>'page'): ?>
          <ul class="nav-exclude">
            <li><input name="exclude_home" id="opt_exclude_home" class="checkbox" type="checkbox" value="1" <?php checked('1', get_mystique_option('exclude_home')) ?> /><label> <a href="<?php echo get_settings('home'); ?>"><?php _e('Home','mystique'); ?></a> </label></li>
          </ul>
          <?php endif; ?>


          <?php
           $categories = &get_categories(array('hide_empty' => false));
           $exclude_categories = explode(',', get_mystique_option('exclude_categories'));
           $walker = new mystique_CategoryWalker('checkbox','ul',$exclude_categories);
           if (!empty($categories)): ?>
             <ul class="hidden nav-exclude" id="category-list">
              <?php echo $walker->walk($categories, 0, array('checkboxes'=> true, 'count' => true)); ?>
             </ul>
           <?php endif; ?>

          <?php
           $pages = &get_pages('sort_column=post_parent,menu_order');
           $exclude_pages = explode(',', get_mystique_option('exclude_pages'));
           $walker = new mystique_PageWalker('checkbox','ul',$exclude_pages);
           if (!empty($pages)): ?>
             <ul class="hidden nav-exclude" id="page-list">
              <?php echo $walker->walk($pages, 0,array(),0); ?>
             </ul>
           <?php endif; ?>

        </td>
       </tr>
       <?php do_action("mystique_admin_navigation"); ?>

      </table>
     </div>

     <div class="section" id="tab-seo">
      <table class="form-table">
       <tr>
        <th scope="row"><p><?php _e("Additional site optimization for search engines","mystique"); ?><span><?php _e("Uncheck if you are using a SEO plugin!","mystique"); ?></span></p></th>
        <td><input name="seo" type="checkbox" class="checkbox" id="opt_seo" value="1" <?php checked('1', get_mystique_option('seo')) ?> /></td>
       </tr>

       <tr>
        <th scope="row"></th>
        <td>
         <h3><?php _e("What does this do?","mystique"); ?></h3>
         <ul style="list-style: disc">
          <li><em><?php printf(__('enables canonical URLs for comments (duplicate content fix, only needed on wp < 2.9)','mystique')); ?></em></li>
          <li><em><?php printf(__('generates unique titles for posts with multiple comment pages (prevents duplicate titles)','mystique')); ?></em></li>
          <li><em><?php printf(__('generates a unique meta description tag for each page (no meta keywords; why? -<a %s>useless</a>)','mystique'),'href="http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html" target="_blank"'); ?> </em></li>
         </ul>
        </td>
       </tr>

       <?php do_action("mystique_admin_seo"); ?>

      </table>
     </div>

     <div class="section" id="tab-ads">
      <table class="form-table">

       <tr>
        <th scope="row">
         <p><?php printf(__("Advertisment blocks","mystique"),$i); ?><span><?php printf(__('Use the %s short code to insert these ads into posts, text widgets or footer','mystique'),'<code>[ad]</code>'); ?><br /><br><?php if(!current_user_can('unfiltered_html')) _e("Only some HTML tags and attributes are allowed",'mystique'); ?></span></p><br />
         <p><span><?php _e("Example:","mystique"); ?></span></p>
         <p><code>[ad code=4 align=center]</code></p>
        </th>
        <td class="clear-block">
         <?php for ($i=1; $i<=6; $i++): ?>
         <div class="ad-code clear-block">
          <label for="opt_ad_code_<?php echo $i; ?>"><?php printf(__("Ad code #%s:","mystique"),$i); ?></label><br />
          <textarea rows="8" cols="40" name="ad_code_<?php echo $i; ?>" id="opt_ad_code_<?php echo $i; ?>" class="code"><?php echo wp_specialchars(get_mystique_option('ad_code_'.$i)); ?></textarea>

         </div>
         <?php endfor; ?>
        </td>
       </tr>

       <?php do_action("mystique_admin_ads"); ?>

      </table>
     </div>

     <div class="section" id="tab-advanced">
      <table class="form-table">
       <tr>
        <th scope="row"><p><?php _e("Use jQuery","mystique"); ?><span><?php _e("For testing purposes only. Only uncheck if you know what you're doing!","mystique"); ?></span></p></th>
        <td><input id="opt_jquery" name="jquery" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('jquery') ) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Enable AJAX for comments","mystique"); ?><span><?php _e("Navigate trough comment pages and post comments without refreshing (faster load, but may be incompatible with some plugins)","mystique"); ?></span></p></th>
        <td><input id="opt_ajax_comments" name="ajax_comments" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('ajax_comments') ) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Enable theme built-in lightbox on all image links","mystique"); ?><span><?php _e("Uncheck if you prefer a lightbox plugin","mystique"); ?></span></p></th>
        <td><input id="opt_lightbox" name="lightbox" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('lightbox') ) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Remove Mystique settings from the database after theme switch","mystique"); ?><span><?php _e("Only check if you're planning to remove and change the theme","mystique"); ?></span></p></th>      <td>
         <input name="remove_settings" id="opt_remove_settings" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('remove_settings') ) ?> />
        </td>
       </tr>

       <?php do_action("mystique_admin_advanced"); ?>

      </table>

      <?php if(current_user_can('edit_themes')): // disable this option for users that can't edit themes (usually on wpmu) ?>
      <hr />
      <table class="form-table">

       <tr>
        <th scope="row">
         <p><?php _e("User functions","mystique"); ?><span><?php _e("PHP code to add to the theme functions. Useful if you have plugins that require you to change the theme's functions.php file","mystique"); ?></span></p>
       </th>
        <td>
         <textarea rows="16" cols="60" name="functions" id="opt_functions" class="code"><?php echo wp_specialchars(get_mystique_option('functions')); ?></textarea>
        </td>
       </tr>

      </table>
      <?php endif; ?>
     </div>

     <div class="section" id="tab-css">
      <table class="form-table">

       <tr>
        <th scope="row"><p><?php _e("CSS to add, or modify theme styles","mystique"); ?><span><?php printf(__("Check %s to see existing theme classes and properties","mystique"),'<a href="'.get_bloginfo('stylesheet_url').'">style.css</a>'); ?></span></p>
        </th>
        <td valign="top">
         <textarea rows="30" cols="80" name="user_css" id="opt_user_css" class="code"><?php echo wp_specialchars(get_mystique_option('user_css')); ?></textarea>
        </td>
       </tr>

       <?php do_action("mystique_admin_user_css"); ?>

      </table>
     </div>


    </div>
    <!-- /sections -->

   </div>
   <!-- /tabbed content -->

   </form>

   <div class="support">
    <form class="alignleft" action="https://www.paypal.com/cgi-bin/webscr" method="post"> <input name="cmd" type="hidden" value="_s-xclick" /> <input name="hosted_button_id" type="hidden" value="4605915" /> <input alt="Donate" name="submit" src="<?php echo THEME_URL; ?>/admin/images/pp.gif" type="image" /></form>
    <a href="http://digitalnature.ro/projects/mystique"><strong>Mystique</strong></a> is a free theme designed by <a href="http://digitalnature.ro">digitalnature</a>.<br />You can support the development of more free resources by donating.
   </div>

  </div>
  <?php
}

function mystique_add_menu() {
  $page = add_theme_page(
       __('Mystique settings', 'mystique'),
       __('Mystique settings', 'mystique'),
       'edit_themes',
       'theme-settings',
       'mystique_theme_settings'
  );
  add_action("admin_print_scripts-$page", 'mystique_setup_admin_js');
  add_action("admin_footer-$page", 'mystique_admin_init_js');
  add_thickbox();
}


function mystique_shortcode_list($text, $screen){
  global $shortcode_tags;
  $text .= '<br /><h5>'.__("Active shortcodes you can use in your posts or widgets","mystique").'</h5><pre>';
  $text .= '<ul>';
  foreach($shortcode_tags as $shortcode => $function) $text .= '<li style="float:left; width: 160px;">['.$shortcode.']</li>';
  $text .= '</ul></pre><br clear="both" />';
  return $text;
}
add_action('contextual_help', 'mystique_shortcode_list', 11, 2);

add_action("admin_print_styles", 'mystique_setup_admin_css');

add_action('wp_ajax_site_preview', 'mystique_get_site_preview');

add_action('admin_menu', 'mystique_add_menu');
add_action('admin_post_mystique_update', 'mystique_update_options');
if(get_mystique_option('remove_settings')) add_action('switch_theme', 'mystique_remove_options');


function mystique_save_meta_data($post_id){
  global $post;
  $data = stripslashes($_POST['asides']);
  if ($data && current_user_can('edit_post', $post_id)):

    if (!get_post_meta($post_id, 'asides', true)):
     add_post_meta($post_id, 'asides', '1', true);
    endif;

  else:
   delete_post_meta($post_id, 'asides');
  endif;
}


function mystique_asides(){
  global $post;
 ?>
  <div class="misc-pub-section misc-pub-section-last">
    <label for="asides">
      <input name="asides" id="asides" type="checkbox" value="1" <?php checked('1', get_post_meta($post->ID, 'asides', true)) ?> /> <?php _e("Display as Asides", "mystique"); ?>
    </label>
  </div>
  <?php
}
add_action('post_submitbox_misc_actions', 'mystique_asides');
add_action('save_post', 'mystique_save_meta_data');

?>