<?php
/* Fusion/digitalnature */

function init_language(){
	if (class_exists('xili_language')) {
		define('THEME_TEXTDOMAIN','fusion');
		define('THEME_LANGS_FOLDER','/lang');
	} else {
	   load_theme_textdomain('fusion', get_template_directory() . '/lang');
	}
}
add_action ('init', 'init_language');

// theme options
$options = array (

  array(
   "id" => "fusion_jquery",
   "default" => "yes",
   "type" => "fusion_jquery"),

  array(
   "id" => "fusion_meta",
   "default" => "",
   "type" => "fusion_meta"),

  array(
   "id" => "fusion_indexposts",
   "default" => "full",
   "type" => "fusion_indexposts"),

  array(
   "id" => "fusion_controls",
   "default" => "yes",
   "type" => "fusion_controls"),

  array(
   "id" => "fusion_3col",
   "default" => "no",
   "type" => "fusion_3col"),

  array(
   "id" => "fusion_header",
   "default" => "default",
   "type" => "fusion_header"),

  array(
   "id" => "fusion_headercolor",
   "default" => "000",
   "type" => "fusion_headercolor"),

  array(
   "id" => "fusion_logo",
   "default" => "no",
   "type" => "fusion_logo"),

  array(
   "id" => "fusion_sidebarpos",
   "default" => "right",
   "type" => "fusion_sidebarpos"),

  array(
   "id" => "fusion_topnav",
   "default" => "pages",
   "type" => "fusion_topnav"),

  array(
   "id" => "fusion_search",
   "default" => "default",
   "type" => "fusion_search"),

  array(
   "id" => "fusion_searchcode",
   "default" => "",
   "type" => "fusion_searchcode"),

  array(
   "id" => "fusion_footer",
   "default" => "",
   "type" => "fusion_footer"),

  array(
   "id" => "fusion_css",
   "default" => "",
   "type" => "fusion_css"),

  array(
   "id" => "fusion_sortcategories",
   "default" => "name",
   "type" => "fusion_sortcategories"),

  array(
   "id" => "fusion_showcategorycount",
   "default" => "on",
   "type" => "fusion_showcategorycount"),

  array(
   "id" => "fusion_twitterid",
   "default" => "",
   "type" => "fusion_twitterid"),

  array(
   "id" => "fusion_twitterentries",
   "default" => "5",
   "type" => "fusion_twitterentries"),

  array(
   "id" => "fusion_flickrid",
   "default" => "",
   "type" => "fusion_flickrid")

);

$uploadpath = wp_upload_dir();
if ($uploadpath['baseurl']=='') $uploadpath['baseurl'] = get_bloginfo('siteurl').'/wp-content/uploads';

function fusion_options() {
  global $options, $uploadpath;

  if ( 'fusion_save' == $_REQUEST['action'] ) {

    foreach ($options as $value) {
     if( !isset( $_REQUEST[ $value['id'] ] ) ) {  } else { update_option( $value['id'], stripslashes($_REQUEST[ $value['id']])); } }
     if(stristr($_SERVER['REQUEST_URI'],'&saved=true')) {
     $location = $_SERVER['REQUEST_URI'];
    } else {
     $location = $_SERVER['REQUEST_URI'] . "&saved=true";
    }

    if ($_FILES["file-logo"]["type"]){
     $directory = $uploadpath['basedir'].'/';
     move_uploaded_file($_FILES["file-logo"]["tmp_name"],
     $directory . $_FILES["file-logo"]["name"]);
     update_option('fusion_logoimage', $uploadpath['baseurl']. "/". $_FILES["file-logo"]["name"]);
    }

    if ($_FILES["file-header"]["type"]){
     $directory = $uploadpath['basedir'].'/';
     move_uploaded_file($_FILES["file-header"]["tmp_name"],
     $directory . $_FILES["file-header"]["name"]);
     update_option('fusion_headerimage', $uploadpath['baseurl']. "/". $_FILES["file-header"]["name"]);
    }

    if ($_FILES["file-header2"]["type"]){
     $directory = $uploadpath['basedir'].'/';
     move_uploaded_file($_FILES["file-header2"]["tmp_name"],
     $directory . $_FILES["file-header2"]["name"]);
     update_option('fusion_headerimage2', $uploadpath['baseurl']. "/". $_FILES["file-header2"]["name"]);
    }
    header("Location: $location");
    die;
  }

  // set default options
  foreach ($options as $default) {
  if(get_option($default['id'])=="") {
  	update_option($default['id'],$default['default']);
  }
  }

  /*
  // delete all options
  foreach ($options as $default) {
  delete_option($default['id'],$default['default']);
  }
  */

  // add_menu_page('Fusion', __('Fusion theme','fusion'), 10, 'fusion-settings', 'fusion_admin');
  add_theme_page(__('Fusion settings','fusion'), __('Fusion settings','fusion'), 10, 'fusion-settings', 'fusion_admin');
}

function fusion_admin() {
    global $options, $uploadpath;


?>
<div class="wrap">
  <h2 class="alignleft"><?php _e("Fusion theme settings","fusion");?></h2><a class="alignright" style="margin: 20px;" href="http://digitalnature.ro/projects/fusion">Fusion homepage</a>
  <br clear="all" />
  <?php if ( $_REQUEST['saved'] ) { ?><div id="message" class="updated fade"><p><strong><?php _e('Settings saved.','fusion'); ?></strong></p></div><?php } ?>

  <style type="text/css"> @import "<?php print get_option('siteurl'). "/wp-content/themes/". get_option('template') ?>/js/colorpicker/colorpicker.css"; </style>
  <script type="text/javascript" src="<?php print get_option('siteurl'). "/wp-content/themes/". get_option('template') ?>/js/colorpicker/colorpicker.js"></script>
  <script type="text/javascript">

   // disable the ability to change options based on what the user previously selected
   function checkoptions(){
    document.getElementById('fusion_header').disabled=false;
    var headervalue = document.getElementById("fusion_header").value;
    if(headervalue == "user") { document.getElementById("userheader").style.display = "block"; } else { document.getElementById("userheader").style.display = "none"; }
    if(headervalue == "user2") { document.getElementById("usercolor").style.display = "block"; } else { document.getElementById("usercolor").style.display = "none"; }

    if (document.getElementById('fusion_logo-yes').checked){  document.getElementById("userlogo").style.display = "block"; }
    else { document.getElementById("userlogo").style.display = "none"; }

   }

   // run at page load
   jQuery(document).ready(function() {
    checkoptions();

   jQuery('#fusion_headercolor').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#fusion_headercolor').val(hex);
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#fusion_headercolor').val(hex);
        jQuery('#fusion_headercolor').css("background-color","#"+hex);
        colortype = hex[0];
        if (isNaN(colortype)) jQuery('#fusion_headercolor').css("color","#000");
        else jQuery('#fusion_headercolor').css("color","#fff");
	}
    })
    .bind('keyup', function(){
    	jQuery(this).ColorPickerSetColor(this.value);
    });
   });

  </script>

<form method="post" id="myForm" enctype="multipart/form-data" onclick="checkoptions();">
<div id="poststuff" class="metabox-holder">

 <div class="stuffbox">
  <h3><label for="link_url"><?php _e("General","fusion"); ?></label></h3>
  <div class="inside">
    <table class="form-table" style="width: auto">
    <?php
     foreach ($options as $value) {
      switch ( $value['type'] ) {
        case "fusion_jquery": ?>

        <tr>
        <th scope="row"><?php _e("Use jQuery","fusion"); ?><br /><?php _e("(Don't change this unless you know what you're doing)","fusion"); ?></th>
        <td>
         <label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-yes" type="radio" value="yes"<?php if ( get_option( $value['id'] ) == "yes") { echo " checked"; } ?> /><?php _e("Yes","fusion"); ?></label>
         &nbsp;&nbsp;
         <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-no" type="radio" value="no"<?php if ( get_option( $value['id'] ) == "no") { echo " checked"; } ?> /><?php _e("No","fusion"); ?></label>
        </td>
        </tr>

      <?php break;
      case "fusion_meta": ?>
        <tr>
        <th scope="row"><?php _e("Homepage meta keywords","fusion"); ?><br><?php _e("(Separate with commas. Tags are used as keywords on other pages. Leave empty if you are using a SEO plugin)","fusion"); ?></th>
        <td>
         <label>
          <input type="text" size="60" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php print get_option($value['id']); ?>" />
         </label>
        </td>
        </tr>

      <?php break;
        case "fusion_indexposts": ?>
        <tr>
        <th scope="row"><?php _e("Index page/Archives show:","fusion"); ?></th>
        <td>
         <label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-full" type="radio" value="full"<?php if ( get_option( $value['id'] ) == "full") { echo " checked"; } ?> /><?php _e("Full posts","fusion"); ?></label>
         &nbsp;&nbsp;
         <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-excerpt" type="radio" value="excerpt"<?php if ( get_option( $value['id'] ) == "excerpt") { echo " checked"; } ?> /><?php _e("Excerpts only","fusion"); ?></label>
        </td>
        </tr>

  	  <?php break;
	  case "fusion_controls": ?>
        <tr>
        <th scope="row"><?php _e("Show layout controls (Aa/<>)","fusion"); ?></th>
        <td>
         <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="yes"<?php if ( get_option( $value['id'] ) == "yes") { echo " checked"; } ?> /><?php _e("Yes","fusion"); ?></label>

         &nbsp;&nbsp;
        <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="no"<?php if ( get_option( $value['id'] ) == "no") { echo " checked"; } ?> /><?php _e("No","fusion"); ?></label>
        </td>
        </tr>

      <?php break;
        case "fusion_3col": ?>
        <tr>
        <th scope="row"><?php _e("Enable 2nd sidebar on all pages","fusion"); ?><br /><?php _e("(use the 3-column page template if you only want it on certain pages)","fusion"); ?></th>
        <td>
         <label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-yes" type="radio" value="yes"<?php if ( get_option( $value['id'] ) == "yes") { echo " checked"; } ?> /><?php _e("Yes","fusion"); ?></label>
         &nbsp;&nbsp;
         <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-no" type="radio" value="no"<?php if ( get_option( $value['id'] ) == "no") { echo " checked"; } ?> /><?php _e("No","fusion"); ?></label>
        </td>
        </tr>


      <?php break;
	}
	}
	?>
   </table>
  </div>
 </div>

 <div class="stuffbox">
  <h3><label for="link_url"><?php _e("Header","fusion"); ?></label></h3>
  <div class="inside">
   <table class="form-table" style="width: auto">
    <?php
     foreach ($options as $value) {
      switch ( $value['type'] ) {
        case "fusion_topnav": ?>
        <tr>
        <th scope="row"><?php _e("Top navigation shows","fusion"); ?></th>
        <td>
         <label><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-pages" type="radio" value="pages"<?php if ( get_option( $value['id'] ) == "pages") { echo " checked"; } ?> /><?php _e("Pages","fusion"); ?></label>
         &nbsp;&nbsp;
         <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-categories" type="radio" value="categories"<?php if ( get_option( $value['id'] ) == "categories") { echo " checked"; } ?> /><?php _e("Categories","fusion"); ?></label>
        </td>
        </tr>

      <?php break;
        case "fusion_header": ?>

        <tr>
        <th scope="row"><?php _e("Header background","fusion"); ?></th>
        <td>
         <label>
            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" class="code">
              <option <?php if (get_option($value['id'])=='default') {?> selected="selected" <?php } ?> value="default"><?php _e('Theme default (gray noise)','fusion'); ?></option>
              <option style="color: #ed1f24" <?php if (get_option($value['id'])=='user') {?> selected="selected" <?php } ?> value="user"><?php _e('User defined image(s) (upload)','fusion'); ?></option>
              <option style="color: #ed1f24" <?php if (get_option($value['id'])=='user2') {?> selected="selected" <?php } ?> value="user2"><?php _e('User defined color','fusion'); ?></option>
            </select>
         </label>
         <div id="userheader" style="display: none;">
         <?php if(is_writable($uploadpath['basedir'])) { ?>
          <br />
          <?php _e('Centered image (upload a 960x200 image for best fit):','fusion'); ?><br />
          <input type="file" name="file-header" id="file-header" />
          <br />
          <br />
          <?php if(get_option('fusion_headerimage')) { echo '<div><img src="'; echo get_option('fusion_headerimage'); echo '"  style="margin-top:10px;" /></div>'; } ?>
          <?php _e('Tiled image, repeats itself across the entire header (centered image will show on top of it, if specified):','fusion'); ?><br />
          <input type="file" name="file-header2" id="file-header2" />
          <?php if(get_option('fusion_headerimage2')) { echo '<div><img src="'; echo get_option('fusion_headerimage2'); echo '"  style="margin-top:10px;" /></div>'; } ?>

        <?php } else {  ?>
          <em style="color:#ed1f24"><?php printf(__('Can\'t upload! Directory %s is not writable!<br />Change write permissions with CHMOD 755 or 777','fusion'), $uploadpath['baseurl']); ?></em>
        <?php }  ?>

         </div>

         <div id="usercolor" style="display: none;">
          <?php _e('Pick a color','fusion'); ?> <input type="text" id="fusion_headercolor" name="fusion_headercolor" style="background: #<?php print get_option('fusion_headercolor'); ?>; color: #<?php $colortype = get_option('fusion_headercolor'); $colortype = $colortype[0]; if(is_numeric($colortype)) print 'fff'; else print '000';  ?>" value="<?php print get_option('fusion_headercolor'); ?>" />
         </div>

        </td>
        </tr>

      <?php break;
      case "fusion_logo": ?>

        <tr>
        <th scope="row"><?php _e("Logo image","fusion"); ?></th>
        <td>
         <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-yes" type="radio" value="yes"<?php if ( get_option( $value['id'] ) == "yes") { echo " checked"; } ?> /><?php _e("Yes","fusion"); ?></label>

         &nbsp;&nbsp;
        <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>-no" type="radio" value="no"<?php if ( get_option( $value['id'] ) == "no") { echo " checked"; } ?> /><?php _e("No","fusion"); ?></label>

        <div id="userlogo">
        <?php if(is_writable($uploadpath['basedir'])) {
         _e("Upload a custom logo image","fusion"); ?><br />
         <input type="file" name="file-logo" id="file-logo" />
         <?php if(get_option('fusion_logoimage')) { echo '<div style="background: #666;margin-top:10px;"><img src="'; echo get_option('fusion_logoimage'); echo '"  style="padding:10px;" /></div>'; } ?>
        <?php } else {  ?>
          <em style="color:#ed1f24"><?php printf(__('Can\'t upload! Directory %s is not writable!<br />Change write permissions with CHMOD 755 or 777','fusion'), $uploadpath['baseurl']); ?></em>
        <?php }  ?>

        </div>

        </td>
        </tr>
      <?php break;
    	}
      }
	?>
   </table>
  </div>
 </div>

 <div class="stuffbox">
  <h3><label for="link_url"><?php _e("Sidebar","fusion"); ?></label></h3>
  <div class="inside">
   <table class="form-table" style="width: auto">
<?php
 foreach ($options as $value) {
  switch ( $value['type'] ) {
	case "fusion_sidebarpos": ?>
        <tr>
        <th scope="row"><?php _e("Sidebar position","fusion"); ?></th>
        <td>
         <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="left"<?php if ( get_option( $value['id'] ) == "left") { echo " checked"; } ?> /><?php _e("Left","fusion"); ?></label>

         &nbsp;&nbsp;
        <label><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="right"<?php if ( get_option( $value['id'] ) == "right") { echo " checked"; } ?> /><?php _e("Right (default)","fusion"); ?></label>
        </td>
        </tr>
     <?php
    	}
      }
	?>
   </table>
  </div>
 </div>

 <div class="stuffbox">
  <h3><label for="link_url"><?php _e("Footer","fusion"); ?></label></h3>
  <div class="inside">
   <table class="form-table" style="width: auto">
    <?php
     foreach ($options as $value) {
      switch ( $value['type'] ) {
    	case "fusion_footer": ?>
        <tr>
        <th scope="row"><?php _e("Add content","fusion"); ?><br /><?php _e("(HTML allowed)","fusion"); ?></th>
        <td>
         <label>
          <textarea class="code" rows="4" cols="60" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php print get_option($value['id']); ?></textarea>
         </label>
        </td>
        </tr>
    <?php
      }
     }
	?>
   </table>
  </div>
 </div>

 <div class="stuffbox">
  <h3><label for="link_url"><?php _e("User CSS code","fusion"); ?></label></h3>
  <div class="inside">
   <table class="form-table" style="width: auto">
    <?php
     foreach ($options as $value) {
      switch ( $value['type'] ) {
    	case "fusion_css": ?>

        <tr>
        <th scope="row"><?php _e("Modify anything related to design using simple CSS","fusion"); ?><br /><br /><span style="color: #ed1f24"><?php _e("Avoid modifying theme files and use this option instead to preserve changes after update","fusion"); ?></span></th>
        <td valign="top">
         <label>
          <textarea class="code" rows="12" cols="60" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php print get_option($value['id']); ?></textarea>


         </label>
        </td>
        <td valign="top">
        Examples:
          <p><em style="color: #5db408">/* Set a fluid page width */</em><br /><code>#page{ width: 95%; }</code></p>
              <p><em style="color: #5db408">/* Make links red, without background on mouse over */</em><br /><code>a, a:hover{ color: #ed1f24; }<br />a:hover{ background: none; }</code></p>
              <p><em style="color: #5db408">/* Decrease header height to 150 pixels and hide the logo */</em><br /><code>#page-wrap2, #header{ height: 150px; }<br />body{ background-position: left 150px; }<br />a#logo{ display: none; }</code></p>
              <p><em style="color: #5db408">/* Increase tag line text size */</em><br /><code>#topnav, #topnav a{ font-size: 130%; }</code></p>
              <p><em style="color: #5db408">/* Hide post information bar */</em><br /><code>.postinfo{ display: none; }</code></p>
              <p><em style="color: #5db408">/* Make tabs narrower */</em><br /><code>#tabs{ letter-spacing: -0.04em; font-size: 13px; }<br />#tabs a span span{ padding: 4px 0 0 0; }</code></p>
              <p><em style="color: #5db408">/* Use Windows Arial style fonts, instead of Mac's Lucida */</em><br /><code>body, input, textarea, select, h3, h4, h5, h6,<br />#sidebar h2.title, #sidebar2 h2.title{ font-family: Arial, Helvetica; }</code></p>

        </td>
        </tr>

    <?php
      }
     }
	?>
   </table>
  </div>
 </div>

</div>
<input name="fusion_save" type="submit" class="button-primary" value="<?php _e('Save changes','fusion'); ?>" />
<input type="hidden" name="action" value="fusion_save" />
</form>
</div>
<?php
}
add_action('admin_menu', 'fusion_options');


// register sidebars
if ( function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Default sidebar',
        'id' => 'sidebar-1',
		'before_widget' => '<li><div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></li>',
		'before_title' => '<h2 class="title">',
		'after_title' => '</h2>'
    ));
    register_sidebar(array(
        'name' => '2nd sidebar (only on 3-col pages)',
        'id' => 'sidebar-2',
		'before_widget' => '<li><div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></li>',
		'before_title' => '<h2 class="title">',
		'after_title' => '</h2>'
    ));
    register_sidebar(array(
        'name' => 'Footer',
        'id' => 'sidebar-3',
		'before_widget' => '<li id="%1$s" class="widget %2$s"><div class="the-content">',
		'after_widget' => '</div></li>',
		'before_title' => '<h4 class="title">',
		'after_title' => '</h4>'
    ));
}


// theme widget: Categories
function fusion_CategoryWidget($args){
 extract($args);
 echo $before_widget;
?>
 <!-- sidebar menu (categories) -->
 <ul class="nav">
   <?php if(get_option('fusion_jquery')=='no') {
      echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i', '<li$1><a$2><span>$3</span></a>', wp_list_categories('orderby='.get_option('fusion_sortcategories').'&show_count=0&echo=0&title_li='));
   }
   else{
    if(get_option('fusion_categorycount')=='on')
      echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a> \(\<a ([^>]*)([^>]*)>XML\<\/a>\) \((.*?)\)@i', '<li $1><a$2><span>$3 <em>($6)</em></span></a><a class="rss tip" $4></a>', wp_list_categories('orderby='.get_option('fusion_sortcategories').'&show_count=1&echo=0&title_li=&feed=XML'));
    else
   echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a> \(\<a ([^>]*) ([^>]*)>(.*?)\<\/a>\)@i', '<li $1><a$2><span>$3</span></a><a class="rss tip" $4></a>', wp_list_categories('show_count=0&echo=0&title_li=&feed=XML'));
   }
 ?>
   <?php if (function_exists('xili_language_list')) { xili_language_list(); } ?>
  </ul>
  <!-- /sidebar menu -->
 <?php
 echo $after_widget;
}

function fusion_CategoryWidgetAdmin() {
  // check if anything's been sent
  if (isset($_POST['update_widgetoptions'])) {
   	update_option("fusion_sortcategories",strip_tags(stripslashes($_POST['fusion_sortcategories'])));
   	update_option("fusion_categorycount",strip_tags(stripslashes($_POST['fusion_categorycount'])));
  }
?>
  <label>
    <?php _e('Sort categories by:','fusion'); ?>
    <select style="width: 100%;" name="fusion_sortcategories" id="fusion_sortcategories" class="code">
       <option <?php if (get_option('fusion_sortcategories')=='name') {?> selected="selected" <?php } ?> value="name"><?php _e('Alphabetically (default)','fusion'); ?></option>
       <option <?php if (get_option('fusion_sortcategories')=='id') {?> selected="selected" <?php } ?> value="id"><?php _e('Unique category ID','fusion'); ?></option>
       <option <?php if (get_option('fusion_sortcategories')=='slug') {?> selected="selected" <?php } ?> value="slug"><?php _e('Category slug','fusion'); ?></option>
       <option <?php if (get_option('fusion_sortcategories')=='count') {?> selected="selected" <?php } ?> value="count"><?php _e('Post count','fusion'); ?></option>
    </select>
  </label>
  <br />
  <br />
  <label>
  <input name="fusion_categorycount" id="fusion_categorycount" type="checkbox" <?php if (get_option('fusion_categorycount')=='on') { echo " checked"; } ?> /><?php _e("Show category count","fusion"); ?>
  </label>
  <input type="hidden" id="update_widgetoptions" name="update_widgetoptions" value="1" />
<?php
}

register_sidebar_widget('Fusion: '.__('Categories','fusion'), 'fusion_CategoryWidget');
register_widget_control('Fusion: '.__('Categories','fusion'), 'fusion_CategoryWidgetAdmin');


// theme widget: Search
function fusion_SearchWidget($args){
 extract($args);
 echo $before_widget;
 if(get_option('fusion_search')=='googlesearch') { ?>
         <!-- google search form -->
          <div id="searchtab">
            <div class="inside">
			  <form action="http://www.google.com/cse" method="get">
					<div class="content">
                       <fieldset>
						<input type="text" class="searchfield" id="searchbox" name="q" size="24" value="<?php _e("Search","fusion"); ?>" onfocus="if(this.value == '<?php _e("Search","fusion"); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e("Search","fusion"); ?>';}" />
						<input type="submit" class="searchbutton" name="sa" value="GO" />
						<input type="hidden" name="cx" value="<?php print get_option('fusion_searchcode'); ?>" />
						<input type="hidden" name="ie" value="UTF-8" />
                       </fieldset>
					</div>
			  </form>
            </div>
          </div>
         <!-- /google search form -->
  <?php } else { ?>
         <!-- wp search form -->
          <div id="searchtab">
            <div class="inside">
              <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
              <fieldset>
                <input type="text" name="s" id="searchbox" class="searchfield" value="<?php _e("Search","fusion"); ?>" onfocus="if(this.value == '<?php _e("Search","fusion"); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e("Search","fusion"); ?>';}" />
                 <input type="submit" value="Go" class="searchbutton" />
              </fieldset>
              </form>
            </div>
          </div>
         <!-- /wp search form -->
  <?php }
 echo $after_widget;
}

function fusion_SearchWidgetAdmin() {
  // check if anything's been sent
  if (isset($_POST['update_widgetoptions'])) {
   	update_option("fusion_search",strip_tags(stripslashes($_POST['fusion_search'])));
   	update_option("fusion_searchcode",strip_tags(stripslashes($_POST['fusion_searchcode'])));
  }
?>
  <script type="text/javascript">
   // disable the ability to change options based on what the user previously selected
   function checkoptions(){
    if (document.getElementById('fusion_search-googlesearch').checked){ document.getElementById("googlesearchcode").style.display = "block"; }
    else { document.getElementById("googlesearchcode").style.display = "none"; }
   }
   // run at page load
   jQuery(document).ready(function() {
    checkoptions();
   });
  </script>
  <h3><?php _e('Search handled by:','fusion'); ?></h3>
  <label><input name="fusion_search" id="fusion_search-default" type="radio" onchange="checkoptions();" value="default"<?php if (get_option('fusion_search') == "default") { echo " checked"; } ?> />Wordpress</label>&nbsp;&nbsp;
  <label><input name="fusion_search" id="fusion_search-googlesearch" type="radio" onchange="checkoptions();" value="googlesearch"<?php if (get_option('fusion_search') == "googlesearch") { echo " checked"; } ?> />Google</label>&nbsp;&nbsp;
  <div id="googlesearchcode">
    <strong>CX</strong> <input type="text" size="40" name="fusion_searchcode" id="fusion_searchcode" value="<?php print get_option('fusion_searchcode'); ?>" />
    <br /><small><?php _e("Find <code>name='cx'</code> in the <strong>Search box code</strong> of <a href='http://www.google.com/coop/cse/'>Google Custom Search Engine</a>, and type the <code>value</code> above.<br/>","fusion"); ?></small>
  </div>
  <input type="hidden" id="update_widgetoptions" name="update_widgetoptions" value="1" />
<?php
}
register_sidebar_widget('Fusion: '.__('Search','fusion'), 'fusion_SearchWidget');
register_widget_control('Fusion: '.__('Search','fusion'), 'fusion_SearchWidgetAdmin',300);


// theme widget: Twitter
function fusion_TwitterWidget($args){
 extract($args);
 echo $before_widget;
 print $before_title.__('Twitter posts','fusion').$after_title; ?>

 <?php
 $username = get_option('fusion_twitterid');
 $limit = get_option('fusion_twitterentries');
 if($username<>'') {
  $feed = file_get_contents("http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=" . $limit);
  $feed = str_replace("&lt;", "<", $feed);
  $feed = str_replace("&gt;", ">", $feed);
  $clean = explode("<content type=\"html\">", $feed);
  $amount = count($clean) - 1;
  print '<ul id="twitterupdates">';
  for ($i = 1; $i <= $amount; $i++) {
   $cleaner = explode("</content>", $clean[$i]);
   print '<li class="entry">';
   echo $cleaner[0];
   print '</li>';
  }
  print '</ul>';
 }

 echo $after_widget;
}

function fusion_TwitterWidgetAdmin() {
  // check if anything's been sent
  if (isset($_POST['update_widgetoptions'])) {
   	update_option("fusion_twitterid", strip_tags(stripslashes($_POST['fusion_twitterid'])));
   	update_option("fusion_twitterentries", strip_tags(stripslashes($_POST['fusion_twitterentries'])));
  }
  ?>
  <fieldset>
   <strong><?php _e('Your Twitter ID:','fusion'); ?></strong><br />
   <input type="text" size="30" name="fusion_twitterid" id="fusion_twitterid" value="<?php print get_option('fusion_twitterid'); ?>" />
  </fieldset>
  <br />
  <fieldset>
   <strong><?php _e('Maximum number of entries to display:','fusion'); ?></strong><br />
   <input type="text" size="6" name="fusion_twitterentries" id="fusion_twitterentries" value="<?php print get_option('fusion_twitterentries'); ?>" />
  </fieldset>
  <input type="hidden" id="update_widgetoptions" name="update_widgetoptions" value="1" />
<?php
}
register_sidebar_widget('Fusion: '.__('Twitter posts','fusion'), 'fusion_TwitterWidget');
register_widget_control('Fusion: '.__('Twitter posts','fusion'), 'fusion_TwitterWidgetAdmin');


// theme widget: Flickr
function fusion_FlickrWidget($args){
 extract($args);
 echo $before_widget;
 print $before_title.__('Flickr gallery','fusion').$after_title; ?>

 <div class="flickr">
 <div class="flickr-thumb">
 <?php
 $username = get_option('fusion_flickrid');
 ?>

 <script type="text/javascript">
  jQuery(function(){
   jQuery.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?ids=<?php print $username; ?>&lang=en-us&format=json&jsoncallback=?", function(data){
     jQuery.each(data.items, function(index, item){
      jQuery("<img />").attr("src", item.media.m).addClass("thumb").appendTo(".flickr").wrap("<a href=\"" + item.link + "\" mce_href=\"" + item.link + "\"></a>").wrap("<div class='flickr-thumb' />");
      webshot(".flickr-thumb img","tooltip");
     });
   });
  });
 </script>
</div>
</div>
 <?php

 echo $after_widget;
}

function fusion_FlickrWidgetAdmin() {
  // check if anything's been sent
  if (isset($_POST['update_widgetoptions'])) {
   	update_option("fusion_flickrid", strip_tags(stripslashes($_POST['fusion_flickrid'])));
  }
  ?>
  <fieldset>
   <strong><?php _e('Your <a href="http://idgettr.com/" target="_blank">Flickr ID</a>:','fusion'); ?></strong><br />
   <input type="text" size="30" name="fusion_flickrid" id="fusion_flickrid" value="<?php print get_option('fusion_flickrid'); ?>" />
  </fieldset>
  <input type="hidden" id="update_widgetoptions" name="update_widgetoptions" value="1" />
<?php
}
register_sidebar_widget('Fusion: '.__('Flickr gallery','fusion'), 'fusion_FlickrWidget');
register_widget_control('Fusion: '.__('Flickr gallery','fusion'), 'fusion_FlickrWidgetAdmin');


// check if sidebar has widgets
function is_sidebar_active($index = 1) {
  global $wp_registered_sidebars;
  if (is_int($index)): $index = "sidebar-$index";
  else :
  	$index = sanitize_title($index);
  	foreach ((array) $wp_registered_sidebars as $key => $value):
    	if ( sanitize_title($value['name']) == $index):
		 $index = $key;
	     break;
		endif;
	endforeach;
  endif;
  $sidebars_widgets = wp_get_sidebars_widgets();
  if (empty($wp_registered_sidebars[$index]) || !array_key_exists($index, $sidebars_widgets) || !is_array($sidebars_widgets[$index]) || empty($sidebars_widgets[$index]))
    return false;
  else
  	return true;
}

// list pings
function list_pings($comment, $args, $depth) {
 $GLOBALS['comment'] = $comment;
 ?>
 <li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php
}

// list comments
function list_comments($comment, $args, $depth) {
 $GLOBALS['comment'] = $comment;
 global $commentcount;
 if(!$commentcount) { $commentcount = 0; }
 ?> <!-- comment entry -->
	<li <?php if (function_exists('comment_class')) { if (function_exists('get_avatar') && get_option('show_avatars')) echo comment_class('with-avatars'); else comment_class(); } else { print 'class="comment';if (function_exists('get_avatar') && get_option('show_avatars')) print ' with-avatars'; print '"';  } ?> id="comment-<?php comment_ID() ?>">
      <div class="wrap tiptrigger">
       <?php if (function_exists('get_avatar') && get_option('show_avatars')): ?>
       <div class="avatar">
         <a class="gravatar"><?php echo get_avatar($comment, 64); ?></a>
       </div>
       <?php endif; ?>
       <div class="details <?php if($comment->comment_author_email == get_the_author_email()) echo 'admincomment'; else echo 'regularcomment'; ?>">
        <p class="head">
         <span class="info">
          <?php
           if (get_comment_author_url()):
            $authorlink='<a id="commentauthor-'.get_comment_ID().'" href="'.get_comment_author_url().'">'.get_comment_author().'</a>';
           else:
            $authorlink='<b id="commentauthor-'.get_comment_ID().'">'.get_comment_author().'</b>';
           endif;
           printf(__('%s by %s on %s', 'fusion'), '<a href="#comment-'.get_comment_ID().'">#'.++$commentcount.'</a>', $authorlink, get_comment_time(get_option('date_format')).' - '.get_comment_time(get_option('time_format')));
          ?>
         </span>
        </p>
        <!-- comment contents -->
        <div class="text">
		 <?php if ($comment->comment_approved == '0'): ?>
		 <p class="error"><small><?php _e('Your comment is awaiting moderation.','fusion'); ?></small></p>
		 <?php endif; ?>
		 <div id="commentbody-<?php comment_ID() ?>">
          <?php comment_text(); ?>
         </div>
       </div>
       <!-- /comment contents -->
       </div>
   	   <div class="act tip">
        <?php if(comments_open()): ?>
         <?php if (function_exists('comment_reply_link')): ?>
	     <span class="button reply"><?php comment_reply_link(array_merge( $args, array('add_below' => 'commentbody', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<span><span>'.__('Reply','fusion').'</span></span>'.$my_comment_count))) ?></span>
         <?php endif; ?>
         <span class="button quote"><a title="<?php _e('Quote','fusion'); ?>" href="javascript:void(0);" onclick="MGJS_CMT.quote('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'commentbody-<?php comment_ID() ?>', 'comment');"><span><span><?php _e('Quote','fusion'); ?></span></span></a></span>
        <?php endif; ?>
        <?php edit_comment_link('<span><span>'.__("Edit","fusion").'</span></span>','<span class="button submit">','</span>'); ?>
	   </div>

      </div>
<?php
  } // </li> is added by wordpress
?>