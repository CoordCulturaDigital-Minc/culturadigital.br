<?php
$pianoblackDefaultOptions = array(
  'use_logo' => false,
  'logo_name' => 'logo.gif',
  'show_information' => true,
  'information_title' => '',
  'information_contents' => '',
  'use_wp_nav_menu' => false,
  'header_menu_type' => 'pages',
  'exclude_pages' => '',
  'exclude_category' => '',
  'header_rss' => true,
  'header_twitter' => true,
  'twitter_url' => 'http://twitter.com/wordpress',
  'header_search' => true,
  'use_google_search' => false,
  'custom_search_id' => '',
  'post_meta_type' => 'category',
  'next_preview_post' => true,
  'author' => false,
  'return_top' => true,
  'page_navi_type' => 'pager',
  'image_style' => true,
  'time_stamp' => false,
);

$optionsSaved = false;
function pianoblack_create_options() {
  // Default values
  $options = $GLOBALS['pianoblackDefaultOptions'];

  // Overridden values
  $DBOptions = get_option('pb_options');
  if ( !is_array($DBOptions) ) $DBOptions = array();

  // Merge
  // Values that are not used anymore will be deleted
  foreach ( $options as $key => $value )
    if ( isset($DBOptions[$key]) )
      $options[$key] = $DBOptions[$key];
      update_option('pb_options', $options);
      return $options;
}

function pianoblack_get_options() {
  static $return = false;
  if($return !== false)
    return $return;

    $options = get_option('pb_options');
      if(!empty($options) && count($options) == count($GLOBALS['pianoblackDefaultOptions']))
      $return = $options;
      else $return = $GLOBALS['pianoblackDefaultOptions'];
      return $return;
}

function pianoblack_add_theme_options() {
  global $optionsSaved;
    if(isset($_POST['pianoblack_save_options'])) {

      $options = pianoblack_create_options();

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

      // show header rss
      if ($_POST['header_rss']) {
      $options['header_rss'] = (bool)true;
      } else {
      $options['header_rss'] = (bool)false;
      }

      // show header twitter
      if ($_POST['header_twitter']) {
      $options['header_twitter'] = (bool)true;
      } else {
      $options['header_twitter'] = (bool)false;
      }

      $options['twitter_url'] = stripslashes($_POST['twitter_url']);

      // show header search
      if ($_POST['header_search']) {
      $options['header_search'] = (bool)true;
      } else {
      $options['header_search'] = (bool)false;
      }
      if ($_POST['use_google_search']) {
      $options['use_google_search'] = (bool)true;
      } else {
      $options['use_google_search'] = (bool)false;
      }
      $options['custom_search_id'] = stripslashes($_POST['custom_search_id']);

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

      // post meta
      $options['post_meta_type'] = stripslashes($_POST['post_meta_type']);

      // show next preview post
      if ($_POST['next_preview_post']) {
      $options['next_preview_post'] = (bool)true;
      } else {
      $options['next_preview_post'] = (bool)false;
      }

      // show time stamp
      if ($_POST['time_stamp']) {
      $options['time_stamp'] = (bool)true;
      } else {
      $options['time_stamp'] = (bool)false;
      }

      // page navi type
      $options['page_navi_type'] = stripslashes($_POST['page_navi_type']);

      // show return top link
      if ($_POST['return_top']) {
      $options['return_top'] = (bool)true;
      } else {
      $options['return_top'] = (bool)false;
      }

      update_option('pb_options', $options);
      $optionsSaved = true;
    }

    add_theme_page(__('Theme Options', 'piano-black'), __('Theme Options', 'piano-black'), 'edit_themes', basename(__FILE__), 'pianoblack_add_theme_page');
}

function pianoblack_add_theme_page () {
  global $optionsSaved;

  $options = pianoblack_get_options();
  if ( $optionsSaved )
   echo '<div id="message" class="updated fade"><p><strong>'.__('Theme options have been saved.', 'piano-black').'</strong></p></div>';
?>

<div class="wrap">

<h2><?php _e('Piano Black Options', 'piano-black'); ?></h2>

<form method="post" action="#" enctype="multipart/form-data">

<p><input class="button-primary" type="submit" name="pianoblack_save_options" value="<?php _e('Save Changes', 'piano-black'); ?>" /></p>
<br />

<div class="pianoblack_box">
<p><?php _e('Check if you would like to use original image for logo instead of using plain text.<br />( Don\'t forget to upload image to, wp-content/themes/pianoblack/img/ )', 'piano-black'); ?></p>
<p><input name="use_logo" type="checkbox" value="checkbox" <?php if($options['use_logo']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'piano-black'); ?></p>
<p><?php _e('Write your logo file name.', 'piano-black'); ?></p>
<p><input type="text" name="logo_name" value="<?php echo($options['logo_name']); ?>" /></p>
</div>

<div class="pianoblack_box">
<p><?php _e('Show Information on sidebar.', 'piano-black'); ?></p>
<p><input name="show_information" type="checkbox" value="checkbox" <?php if($options['show_information']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'piano-black'); ?></p>
<br />
<p><?php _e('Information title.', 'piano-black'); ?></p>
<p><input type="text" name="information_title" value="<?php echo($options['information_title']); ?>" /></p>
<br />
<p><?php _e('Information contents. ( HTML tag is available. )', 'piano-black'); ?></p>
<p><textarea name="information_contents" cols="70%" rows="5"><?php echo($options['information_contents']); ?></textarea></p>
</div>

<div class="pianoblack_box">
<p><?php _e('Check if you would like to use Custom Navigation Menus in WordPress 3.0.', 'piano-black'); ?></p>
<p><input name="use_wp_nav_menu" type="checkbox" value="checkbox" <?php if($options['use_wp_nav_menu']) echo "checked='checked'"; ?> id="use_wp_nav_menu" /> <?php _e('Yes', 'piano-black'); ?></p>
<br />
<div id="old_menu_function">
<p><?php _e('Header menu.', 'piano-black'); ?></p>
<p>
<input name="header_menu_type" type="radio" value="pages" <?php if($options['header_menu_type'] != 'categories') echo "checked='checked'"; ?> /> <?php _e('Use pages for header menu.', 'piano-black'); ?><br />
<input name="header_menu_type" type="radio" value="categories" <?php if($options['header_menu_type'] == 'categories') echo "checked='checked'"; ?> /> <?php _e('Use categories for header menu.', 'piano-black'); ?>
</p>
<br />
<p><?php _e('Exclude Pages<br />(Page ID\'s you don\'t want displayed in your header navigation. Use a comma-delimited list, eg. 1,2,3)', 'piano-black'); ?></p>
<p><input type="text" name="exclude_pages" value="<?php echo($options['exclude_pages']); ?>" /></p>
<br />
<p><?php _e('Exclude Categories<br />(Category ID\'s you don\'t want displayed in your header navigation. Use a comma-delimited list, eg. 1,2,3)', 'piano-black'); ?></p>
<p><input type="text" name="exclude_category" value="<?php echo($options['exclude_category']); ?>" /></p>
</div>
</div>

<div class="pianoblack_box">
<p><?php _e('Show search on header.', 'piano-black'); ?></p>
<p><input name="header_search" type="checkbox" value="checkbox" <?php if($options['header_search']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'piano-black'); ?></p>
<br />
<p><?php _e('Use <a href="http://www.google.com/cse/">Google Custom Search</a><br />(If you check this option,don\'t forget to write your Google Custom Search ID below.)', 'piano-black'); ?></p>
<p><input name="use_google_search" type="checkbox" value="checkbox" <?php if($options['use_google_search']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'piano-black'); ?></p>
<p><?php _e('Input your Google Custom Search ID.', 'piano-black'); ?></p>
<p><input type="text" name="custom_search_id" value="<?php echo($options['custom_search_id']); ?>" style="width:400px;" /></p>
</div>

<div class="pianoblack_box">
<p><?php _e('Show rss on header.', 'piano-black'); ?></p>
<p><input name="header_rss" type="checkbox" value="checkbox" <?php if($options['header_rss']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'piano-black'); ?></p>
</div>

<div class="pianoblack_box">
<p><?php _e('Show Twitter button on header.', 'piano-black'); ?></p>
<p><input name="header_twitter" type="checkbox" value="checkbox" <?php if($options['header_twitter']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'piano-black'); ?></p>
<p><?php _e('Write your Twitter URL.', 'piano-black'); ?></p>
<p><input type="text" name="twitter_url" value="<?php echo($options['twitter_url']); ?>" style="width:400px;" /></p>
</div>

<div class="pianoblack_box">
<p><?php _e('Show author.', 'piano-black'); ?></p>
<p><input name="author" type="checkbox" value="checkbox" <?php if($options['author']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'piano-black'); ?></p>
<br />
<p><?php _e('Show border aroud image in post area.', 'piano-black'); ?></p>
<p><input name="image_style" type="checkbox" value="checkbox" <?php if($options['image_style']) echo "checked='checked'"; ?> /> <?php _e('Yes', 'piano-black'); ?></p>
<br />
<p><?php _e('Post meta.', 'piano-black'); ?></p>
<p>
<input name="post_meta_type" type="radio" value="category" <?php if($options['post_meta_type'] != 'tag') echo "checked='checked'"; ?> /> <?php _e('Show category.', 'piano-black'); ?><br />
<input name="post_meta_type" type="radio" value="tag" <?php if($options['post_meta_type'] == 'tag') echo "checked='checked'"; ?> /> <?php _e('Show tag.', 'piano-black'); ?>
</p>
<br />
<p><?php _e('Show next preview post at the bottom of single post page.', 'piano-black'); ?></p>
<p><input name="next_preview_post" type="checkbox" value="checkbox" <?php if($options['next_preview_post']) echo "checked='checked'"; ?> /><?php _e('Yes', 'piano-black'); ?></p>
</div>

<div class="pianoblack_box">
<p><?php _e('Show time on comment.', 'piano-black'); ?></p>
<p><input name="time_stamp" type="checkbox" value="checkbox" <?php if($options['time_stamp']) echo "checked='checked'"; ?> /><?php _e('Yes', 'piano-black'); ?></p>
</div>

<div class="pianoblack_box">
<p><?php _e('Page navi type.', 'piano-black'); ?></p>
<p>
<input name="page_navi_type" type="radio" value="pager" <?php if($options['page_navi_type'] != 'normal') echo "checked='checked'"; ?> /> <?php _e('Use Pager.', 'piano-black'); ?><br />
<input name="page_navi_type" type="radio" value="normal" <?php if($options['page_navi_type'] == 'normal') echo "checked='checked'"; ?> /> <?php _e('Use normal style next-previous link.', 'piano-black'); ?>
</p>
</div>

<div class="pianoblack_box">
<p><?php _e('Check if you want to show Return top link.', 'piano-black'); ?></p>
<p><input name="return_top" type="checkbox" value="checkbox" <?php if($options['return_top']) echo "checked='checked'"; ?> /> <?php _e('Show Return top link.', 'piano-black'); ?></p>
</div>

<p><input class="button-primary" type="submit" name="pianoblack_save_options" value="<?php _e('Save Changes', 'piano-black'); ?>" /></p>

</form>

</div>

<?php
  }

// register function
add_action('admin_menu', 'pianoblack_create_options');
add_action('admin_menu', 'pianoblack_add_theme_options');

// CSS for admin page
add_action('admin_print_styles', 'pianoblack_admin_CSS');
function pianoblack_admin_CSS() {
	wp_enqueue_style('pianoblackAdminCSS', get_bloginfo('template_url').'/admin/admin.css');
}

// javascript for admin page
add_action('admin_print_scripts', 'pianoblack_admin_script');
function pianoblack_admin_script() {
	wp_enqueue_script('pianoblackAdminScript', get_bloginfo('template_url').'/admin/script.js');
}

// for localization
load_textdomain('piano-black', dirname(__FILE__).'/languages/' . get_locale() . '.mo');

// to use wp_nav_menu() in WordPress3.0
if (function_exists('add_theme_support')) { add_theme_support( 'nav-menus' ); };

// Remove [...] from excerpt
function trim_excerpt($text) {
  return rtrim($text,'[...]');
}
add_filter('get_the_excerpt', 'trim_excerpt');


// Sidebar widget
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'before_widget' => '<div class="side-box" id="%1$s">'."\n",
        'after_widget' => "</div>\n",
        'before_title' => '<h3 class="side-title">',
        'after_title' => "</h3>\n",
        'name' => 'top',
        'id' => 'top'
    ));
    register_sidebar(array(
        'before_widget' => '<div class="side-box-short" id="%1$s">'."\n",
        'after_widget' => "</div>\n",
        'before_title' => '<h3 class="side-title">',
        'after_title' => "</h3>\n",
        'name' => 'left',
        'id' => 'left'
    ));
    register_sidebar(array(
        'before_widget' => '<div class="side-box-short" id="%1$s">'."\n",
        'after_widget' => "</div>\n",
        'before_title' => '<h3 class="side-title">',
        'after_title' => "</h3>\n",
        'name' => 'right',
        'id' => 'right'
    ));
    register_sidebar(array(
        'before_widget' => '<div class="side-box" id="%1$s">'."\n",
        'after_widget' => "</div>\n",
        'before_title' => '<h3 class="side-title">',
        'after_title' => "</h3>\n",
        'name' => 'bottom',
        'id' => 'bottom'
    ));
}


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
<?php $options = get_option('pb_options'); ?>

 <li class="comment <?php if($comment->comment_author_email == get_the_author_email()) {echo 'admin-comment';} else {echo 'guest-comment';} ?>" id="comment-<?php comment_ID() ?>">
  <div class="comment-meta">
   <div class="comment-meta-left clearfix">
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
     <li class="comment-date"><?php echo get_comment_time(__('F jS, Y', 'piano-black')); if ($options['time_stamp']) : echo get_comment_time(__(' g:ia', 'piano-black')); endif; ?></li>
    </ul>
   </div>

   <ul class="comment-act">
<?php if (function_exists('comment_reply_link')) { 
        if ( get_option('thread_comments') == '1' ) { ?>
    <li class="comment-reply"><?php comment_reply_link(array_merge( $args, array('add_below' => 'comment-content', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<span><span>'.__('REPLY','piano-black').'</span></span>'.$my_comment_count))) ?></li>
<?php   } else { ?>
    <li class="comment-reply"><a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment');"><?php _e('REPLY', 'piano-black'); ?></a></li>
<?php   }
      } else { ?>
    <li class="comment-reply"><a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment');"><?php _e('REPLY', 'piano-black'); ?></a></li>
<?php } ?>
    <li class="comment-quote"><a href="javascript:void(0);" onclick="MGJS_CMT.quote('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment-content-<?php comment_ID() ?>', 'comment');"><?php _e('QUOTE', 'piano-black'); ?></a></li>
    <?php edit_comment_link(__('EDIT', 'piano-black'), '<li class="comment-edit">', '</li>'); ?>
   </ul>

  </div>
  <div class="comment-content" id="comment-content-<?php comment_ID() ?>">
  <?php if ($comment->comment_approved == '0') : ?>
   <span class="comment-note"><?php _e('Your comment is awaiting moderation.', 'piano-black'); ?></span>
  <?php endif; ?>
  <?php comment_text(); ?>
  </div>

<?php } ?>