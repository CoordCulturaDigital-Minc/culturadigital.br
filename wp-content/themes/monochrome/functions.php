<?php
$monochromeDefaultOptions = array(
  'site_width' => '1000',
  'use_logo' => false,
  'logo_name' => 'logo.gif',
  'show_information' => true,
  'information_title' => '',
  'information_contents' => '',
  'rss_feed' => true,
  'search_position' => 'top',
  'use_google_search' => false,
  'custom_search_id' => '',
  'tag_list' => false,
  'use_wp_nav_menu' => false,
  'header_menu_type' => 'pages',
  'author' => false,
  'tag' => true,
  'pagetop' => true,
  'exclude_pages' => '',
  'exclude_category' => '',
  'next_preview_post' => true,
  'bread_crumb' => true,
  'image_style' => true,
  'page_navi_type' => 'pager',
  'time_stamp' => false,
);

$optionsSaved = false;
function monochrome_create_options() {
  // Default values
  $options = $GLOBALS['monochromeDefaultOptions'];

  // Overridden values
  $DBOptions = get_option('mc_options');
  if ( !is_array($DBOptions) ) $DBOptions = array();

  // Merge
  // Values that are not used anymore will be deleted
  foreach ( $options as $key => $value )
    if ( isset($DBOptions[$key]) )
      $options[$key] = $DBOptions[$key];
      update_option('mc_options', $options);
      return $options;
}

function monochrome_get_options() {
  static $return = false;
  if($return !== false)
    return $return;

    $options = get_option('mc_options');
      if(!empty($options) && count($options) == count($GLOBALS['monochromeDefaultOptions']))
      $return = $options;
      else $return = $GLOBALS['monochromeDefaultOptions'];
      return $return;
}

function monochrome_add_theme_options() {
  global $optionsSaved;
    if(isset($_POST['monochrome_save_options'])) {

      $options = monochrome_create_options();

      // site width
      $options['site_width'] = stripslashes($_POST['site_width']);

      // logo
      if ($_POST['use_logo']) {
      $options['use_logo'] = (bool)true;
      } else {
      $options['use_logo'] = (bool)false;
      }
      $options['logo_name'] = stripslashes($_POST['logo_name']);


      // information
      if ($_POST['show_information']) {
      $options['show_information'] = (bool)true;
      } else {
      $options['show_information'] = (bool)false;
      }
      $options['information_title'] = stripslashes($_POST['information_title']);
      $options['information_contents'] = stripslashes($_POST['information_contents']);
      if ($_POST['rss_feed']) {
      $options['rss_feed'] = (bool)true;
      } else {
      $options['rss_feed'] = (bool)false;
      }

      // search
      $options['search_position'] = stripslashes($_POST['search_position']);
      if ($_POST['tag_list']) {
      $options['tag_list'] = (bool)true;
      } else {
      $options['tag_list'] = (bool)false;
      }
      if ($_POST['use_google_search']) {
      $options['use_google_search'] = (bool)true;
      } else {
      $options['use_google_search'] = (bool)false;
      }
      $options['custom_search_id'] = stripslashes($_POST['custom_search_id']);

      // wp_nav_menu
      if ($_POST['use_wp_nav_menu']) {
      $options['use_wp_nav_menu'] = (bool)true;
      } else {
      $options['use_wp_nav_menu'] = (bool)false;
      }

      // header menu
      $options['header_menu_type'] = stripslashes($_POST['header_menu_type']);

      // exclude pages
      $options['exclude_pages'] = stripslashes($_POST['exclude_pages']);

      // exclude category
      $options['exclude_category'] = stripslashes($_POST['exclude_category']);

      // show author
      if ($_POST['author']) {
      $options['author'] = (bool)true;
      } else {
      $options['author'] = (bool)false;
      }

      // border around image in post area
      if ($_POST['image_style']) {
      $options['image_style'] = (bool)true;
      } else {
      $options['image_style'] = (bool)false;
      }

      // show next preview post
      if ($_POST['next_preview_post']) {
      $options['next_preview_post'] = (bool)true;
      } else {
      $options['next_preview_post'] = (bool)false;
      }

      // show bread crymb
      if ($_POST['bread_crumb']) {
      $options['bread_crumb'] = (bool)true;
      } else {
      $options['bread_crumb'] = (bool)false;
      }

      // show time stamp
      if ($_POST['time_stamp']) {
      $options['time_stamp'] = (bool)true;
      } else {
      $options['time_stamp'] = (bool)false;
      }

      // show tag
      if ($_POST['tag']) {
      $options['tag'] = (bool)true;
      } else {
      $options['tag'] = (bool)false;
      }

      // page navi type
      $options['page_navi_type'] = stripslashes($_POST['page_navi_type']);

      // show pagetop link
      if ($_POST['pagetop']) {
      $options['pagetop'] = (bool)true;
      } else {
      $options['pagetop'] = (bool)false;
      }

      update_option('mc_options', $options);
      $optionsSaved = true;
    }

    add_theme_page(__('Theme Options', 'monochrome'), __('Theme Options', 'monochrome'), 'edit_themes', basename(__FILE__), 'monochrome_add_theme_page');
}

function monochrome_add_theme_page () {
  global $optionsSaved;

  $options = monochrome_get_options();
  if ( $optionsSaved )
   echo '<div id="message" class="updated fade"><p><strong>'.__('Theme options have been saved.', 'monochrome').'</strong></p></div>';
?>


<div class="wrap">

<h2><?php _e('Monochrome option', 'monochrome'); ?></h2>

<form method="post" action="#" enctype="multipart/form-data">

<p><input class="button-primary" type="submit" name="monochrome_save_options" value="<?php _e('Save Changes', 'monochrome'); ?>" /></p>
<br />

<div class="monochrome_box">
<p><?php _e('Select width of monochrome.', 'monochrome'); ?></p>
<p>
<input name="site_width" type="radio" value="930" <?php if($options['site_width'] == '930') echo "checked='checked'"; ?> /><?php _e('930px.', 'monochrome'); ?><br />
<input name="site_width" type="radio" value="1000" <?php if($options['site_width'] == '1000') echo "checked='checked'"; ?> /><?php _e('1000px', 'monochrome'); ?><br />
<input name="site_width" type="radio" value="1100" <?php if($options['site_width'] == '1100') echo "checked='checked'"; ?> /><?php _e('1100px', 'monochrome'); ?>
</p>
</div>

<div class="monochrome_box">
<p><?php _e('Check if you would like to use original image for logo instead of using plain text.<br />( Don\'t forget to upload image into, <strong>wp-content/themes/monochrome/img/</strong> )', 'monochrome'); ?></p>
<p><input name="use_logo" type="checkbox" value="checkbox" <?php if($options['use_logo']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'monochrome'); ?></p>
<p><?php _e('Write your logo file name.<br />Example : <strong>logo.gif</strong>', 'monochrome'); ?></p>
<p><input type="text" name="logo_name" value="<?php echo($options['logo_name']); ?>" /></p>
</div>

<div class="monochrome_box">
<p><?php _e('Show Information on sidebar.', 'monochrome'); ?></p>
<p><input name="show_information" type="checkbox" value="checkbox" <?php if($options['show_information']) echo "checked='checked'"; ?> /><?php _e('Yes', 'monochrome'); ?></p>
<p><?php _e('Information title.', 'monochrome'); ?></p>
<p><input type="text" name="information_title" value="<?php echo($options['information_title']); ?>" /></p>
<p><?php _e('Information contents. ( HTML tag is available. )', 'monochrome'); ?></p>
<p><textarea name="information_contents" cols="70%" rows="5"><?php echo($options['information_contents']); ?></textarea></p>
</div>

<div class="monochrome_box">
<p><?php _e('Show rss feed on sidebar.', 'monochrome'); ?></p>
<p><input name="rss_feed" type="checkbox" value="checkbox" <?php if($options['rss_feed']) echo "checked='checked'"; ?> /><?php _e('Yes', 'monochrome'); ?></p>
</div>

<div class="monochrome_box">
<p><?php _e('Position of search area on sidebar.', 'monochrome'); ?></p>
<p>
<input name="search_position" type="radio" value="top" <?php if($options['search_position'] != 'bottom') echo "checked='checked'"; ?> /><?php _e('Top', 'monochrome'); ?><br />
<input name="search_position" type="radio" value="bottom" <?php if($options['search_position'] == 'bottom') echo "checked='checked'"; ?> /><?php _e('Bottom', 'monochrome'); ?>
</p>
<p><?php _e('Use <a href="http://www.google.com/cse/">Google Custom Search</a><br />(If you check this option,don\'t forget to write your Google Custom Search ID below.)', 'monochrome'); ?></p>
<p><input name="use_google_search" type="checkbox" value="checkbox" <?php if($options['use_google_search']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'monochrome'); ?></p>
<p><?php _e('Input your Google Custom Search ID.', 'monochrome'); ?></p>
<p><input type="text" name="custom_search_id" value="<?php echo($options['custom_search_id']); ?>" style="width:400px;" /></p>
</div>

<div class="monochrome_box">
<p><?php _e('Show tag list under search area.', 'monochrome'); ?></p>
<p><input name="tag_list" type="checkbox" value="checkbox" <?php if($options['tag_list']) echo "checked='checked'"; ?> /><?php _e('Yes', 'monochrome'); ?></p>
</div>

<div class="monochrome_box">
<p><?php _e('Check if you would like to use Custom Navigation Menus in WordPress 3.0.', 'monochrome'); ?></p>
<p><input name="use_wp_nav_menu" type="checkbox" value="checkbox" <?php if($options['use_wp_nav_menu']) echo "checked='checked'"; ?> id="use_wp_nav_menu" /> <?php _e('Yes', 'monochrome'); ?></p>
<div id="old_menu_function">
<p><?php _e('Header menu.', 'monochrome'); ?></p>
<p>
<input name="header_menu_type" type="radio" value="pages" <?php if($options['header_menu_type'] != 'categories') echo "checked='checked'"; ?> /><?php _e('Use pages for header menu.', 'monochrome'); ?><br />
<input name="header_menu_type" type="radio" value="categories" <?php if($options['header_menu_type'] == 'categories') echo "checked='checked'"; ?> /><?php _e('Use categories for header menu.', 'monochrome'); ?>
</p>
<p><?php _e('Exclude Pages (Page ID\'s you don\'t want displayed in your header navigation. Use a comma-delimited list, eg. 1,2,3)', 'monochrome'); ?></p>
<p><input type="text" name="exclude_pages" value="<?php echo($options['exclude_pages']); ?>" /></p>
<p><?php _e('Exclude Categories (Category ID\'s you don\'t want displayed in your header navigation. Use a comma-delimited list, eg. 1,2,3)', 'monochrome'); ?></p>
<p><input type="text" name="exclude_category" value="<?php echo($options['exclude_category']); ?>" /></p>
</div>
</div>

<div class="monochrome_box">
<p><?php _e('Show author name.', 'monochrome'); ?></p>
<p><input name="author" type="checkbox" value="checkbox" <?php if($options['author']) echo "checked='checked'"; ?> /><?php _e('Yes', 'monochrome'); ?></p>
<br />
<p><?php _e('Show border aroud image in post area.', 'monochrome'); ?></p>
<p><input name="image_style" type="checkbox" value="checkbox" <?php if($options['image_style']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'monochrome'); ?></p>
<br />
<p><?php _e('Show bread crumb at single post page.', 'monochrome'); ?></p>
<p><input name="bread_crumb" type="checkbox" value="checkbox" <?php if($options['bread_crumb']) echo "checked='checked'"; ?> /><?php _e('Yes', 'monochrome'); ?></p>
<br />
<p><?php _e('Show next preview post at the bottom of single post page.', 'monochrome'); ?></p>
<p><input name="next_preview_post" type="checkbox" value="checkbox" <?php if($options['next_preview_post']) echo "checked='checked'"; ?> /><?php _e('Yes', 'monochrome'); ?></p>
<br />
<p><?php _e('Show tag.', 'monochrome'); ?></p>
<p><input name="tag" type="checkbox" value="checkbox" <?php if($options['tag']) echo "checked='checked'"; ?> /><?php _e('Yes', 'monochrome'); ?></p>
</div>

<div class="monochrome_box">
<p><?php _e('Show time on comment.', 'monochrome'); ?></p>
<p><input name="time_stamp" type="checkbox" value="checkbox" <?php if($options['time_stamp']) echo "checked='checked'"; ?> /><?php _e('Yes', 'monochrome'); ?></p>
</div>

<div class="monochrome_box">
<p><?php _e('Page navi type.', 'monochrome'); ?></p>
<p>
<input name="page_navi_type" type="radio" value="pager" <?php if($options['page_navi_type'] != 'normal') echo "checked='checked'"; ?> /> <?php _e('Use Pager.', 'monochrome'); ?><br />
<input name="page_navi_type" type="radio" value="normal" <?php if($options['page_navi_type'] == 'normal') echo "checked='checked'"; ?> /> <?php _e('Use normal style next-previous link.', 'monochrome'); ?>
</p>
</div>

<div class="monochrome_box">
<p><?php _e('Check if you want to show Return top link.', 'monochrome'); ?></p>
<p><input name="pagetop" type="checkbox" value="checkbox" <?php if($options['pagetop']) echo "checked='checked'"; ?> /><?php _e('Yes', 'monochrome'); ?></p>
</div>

<p><input class="button-primary" type="submit" name="monochrome_save_options" value="<?php _e('Save Changes', 'monochrome'); ?>" /></p>

</form>

</div>

<?php
  }

// register function
add_action('admin_menu', 'monochrome_create_options');
add_action('admin_menu', 'monochrome_add_theme_options');

// CSS for admin page
add_action('admin_print_styles', 'monochrome_admin_CSS');
function monochrome_admin_CSS() {
	wp_enqueue_style('monochromeAdminCSS', get_bloginfo('template_url').'/admin/admin.css');
}

// javascript for admin page
add_action('admin_print_scripts', 'monochrome_admin_script');
function monochrome_admin_script() {
	wp_enqueue_script('monochromeAdminScript', get_bloginfo('template_url').'/admin/script.js');
}

// for localization
load_textdomain('monochrome', dirname(__FILE__).'/languages/' . get_locale() . '.mo');

// to use wp_nav_menu() in WordPress3.0
if (function_exists('add_theme_support')) { add_theme_support( 'nav-menus' ); };

// Sidebar widget
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<div class="side_box" id="%1$s">'."\n",
        'after_widget' => "</div>\n",
        'before_title' => '<h3>',
        'after_title' => "</h3>\n",
    ));


// Original custom comments function is written by mg12 - http://www.neoease.com/

if (function_exists('wp_list_comments')) {
	// comment count
	add_filter('get_comments_number', 'comment_count', 0);
	function comment_count( $commentcount ) {
		global $id;
		$_commnets = get_comments('post_id=' . $id);
		$comments_by_type = &separate_comments($_commnets);
		return count($comments_by_type['comment']);
	}
}


function custom_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $commentcount;
	if(!$commentcount) {
		$commentcount = 0;
	}
?>

<?php $options = get_option('mc_options'); ?>

 <li class="comment <?php if($comment->comment_author_email == get_the_author_email()) {echo 'admin-comment';} else {echo 'guest-comment';} ?>" id="comment-<?php comment_ID() ?>">
  <div class="comment-meta">
   <div class="comment-meta-left">
  <?php if (function_exists('get_avatar') && get_option('show_avatars')) { echo get_avatar($comment, 35); } ?>
  
    <ul class="comment-name-date">
     <li class="comment-name">
<?php if (get_comment_author_url()) : ?>
<a id="commentauthor-<?php comment_ID() ?>" class="url <?php if($comment->comment_author_email == get_the_author_email()) {echo 'admin-url';} else {echo 'guest-url';} ?>" href="<?php comment_author_url() ?>" rel="external nofollow">
<?php else : ?>
<span id="commentauthor-<?php comment_ID() ?>">
<?php endif; ?>

<?php comment_author(); ?>

<?php if(get_comment_author_url()) : ?>
</a>
<?php else : ?>
</span>
<?php endif; ?>
     </li>
     <li class="comment-date"><?php echo get_comment_time(__('F jS, Y', 'monochrome')); if ($options['time_stamp']) : echo get_comment_time(__(' g:ia', 'monochrome')); endif; ?></li>
    </ul>
   </div>

   <ul class="comment-act">
<?php if (function_exists('comment_reply_link')) { 
        if ( get_option('thread_comments') == '1' ) { ?>
    <li class="comment-reply"><?php comment_reply_link(array_merge( $args, array('add_below' => 'comment-content', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<span><span>'.__('REPLY','monochrome').'</span></span>'.$my_comment_count))) ?></li>
<?php   } else { ?>
    <li class="comment-reply"><a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment');"><?php _e('REPLY', 'monochrome'); ?></a></li>
<?php   }
      } else { ?>
    <li class="comment-reply"><a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment');"><?php _e('REPLY', 'monochrome'); ?></a></li>
<?php } ?>
    <li class="comment-quote"><a href="javascript:void(0);" onclick="MGJS_CMT.quote('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment-content-<?php comment_ID() ?>', 'comment');"><?php _e('QUOTE', 'monochrome'); ?></a></li>
    <?php edit_comment_link(__('EDIT', 'monochrome'), '<li class="comment-edit">', '</li>'); ?>
   </ul>

  </div>
  <div class="comment-content" id="comment-content-<?php comment_ID() ?>">
  <?php if ($comment->comment_approved == '0') : ?>
   <span class="comment-note"><?php _e('Your comment is awaiting moderation.', 'monochrome'); ?></span>
  <?php endif; ?>
  <?php comment_text(); ?>
  </div>

<?php } ?>
