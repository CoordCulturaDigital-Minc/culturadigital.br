<?php /* Mystique/digitalnature */


// //based on Austin Matzko's code from wp-hackers email list -- not used anymore
// function mystique_filter_where($where = '') {
//  //posts in the last 30 days
//  $where .= " AND post_date > '".date('Y-m-d', strtotime('-'.get_mystique_option('featured_timeframe').' days'))."'";
//  return $where;
// }
//  add_filter('posts_where', 'mystique_filter_where');

// check if the user wants featured posts enabled on whatever page we're on
function mystique_featured_post_target_page(){
 $enable = (is_page_template('page-featured.php')) || (get_mystique_option('featured_on_home') && is_home()) || (get_mystique_option('featured_on_single') && is_singular()) || (get_mystique_option('featured_on_archives') && is_archive()) || (get_mystique_option('featured_on_404') && is_404()) || (get_mystique_option('featured_on_search') && is_search()) || (get_mystique_option('featured_on_archives') && is_archive()) || (get_mystique_option('featured_on_pages') && is_page());
 return ($enable ? true : false);
}

function mystique_featured_post() {
  if(mystique_featured_post_target_page()):
   //global $valid_featured_posts;


  global $wp_query;
  $featured_posts = get_mystique_option('featured_posts');

  $found_posts = array();
  $featured_posts = explode(',', $featured_posts);
  shuffle($featured_posts); // randomize

  foreach($featured_posts as $featured_post):
    $current_post = get_post($featured_post);
    $current_post = (array)$current_post;

    if($current_post)       // take it in consideration only if is a recent post
     if(strtotime($current_post['post_date_gmt']) > strtotime('-'.get_mystique_option('featured_timeframe').' days')) $found_posts[] = $current_post;
  endforeach;

  // remove posts from the main loop (on homepage)?
  // $wp_query->query_vars['post__not_in'] = $found_posts;

   $count = (int)get_mystique_option('featured_count');
  ?>

<div id="featured-content"<?php if($count > 1 && count($found_posts) > 1): ?> class="withSlider"<?php endif; ?>>
 <?php if($found_posts): // only show if the global variable is set in the function above ?>
 <!-- block container -->
 <div class="slide-container">
  <ul class="slides">
       <?php
        $number = 1;
        foreach($found_posts as $featured_post): ?>
         <!-- slide (100%) -->
         <li class="slide slide-<?php echo $number; ?> featured-content">
          <div class="slide-content clearfix">
            <div class="details clearfix">
             <?php
              mystique_post_thumb('featured-thumbnail', $featured_post['ID']);
              echo '<h2><a href="'.get_permalink($featured_post['ID']).'">'.mystique_strip_string(70, strip_tags($featured_post['post_title'])).'</a></h2>';
              echo '<div class="summary">'.mystique_strip_string(420, strip_tags(strip_shortcodes($featured_post['post_content']))).'</div>';
             ?>
            </div>
          <a class="readmore" href="<?php echo get_permalink($featured_post['ID']); ?>"><?php _e("More","mystique"); ?></a>
          </div>
         </li>
         <!-- /slide -->
         <?php
          if($count == $number) break;
          $number++;
        endforeach;
       ?>
  </ul>
 </div>
 <!-- /block container -->
 <?php
  else:
   if (current_user_can('switch_themes')) echo '<h4>'.sprintf(__("No featured posts found older than %s days.", "mystique"), get_mystique_option('featured_timeframe')).'</h4>';
  endif; ?>
</div>

   <?php
  endif;
}

function mystique_featured_post_class($class){
  global $post;
  $featured_posts = explode(',', get_mystique_option('featured_posts'));

  // featured post?
  if (in_array($post->ID, $featured_posts)) $class .= ' featured';
  return $class;
}


function mystique_add_featured_posts_column($defaults) {
  $defaults['featured'] = __('Featured','mystique');
  return $defaults;
}

// build the post listing cta for each row
function mystique_featured_posts_column($column_name, $id) {
  if ($column_name == 'featured'):
    $featured_posts = get_mystique_option('featured_posts');
    $featured_arr = explode(',', $featured_posts);
    echo '<div class="featured_post"><a id="featured-'.$id.'"  class="'.(in_array($id, $featured_arr) ? "on" : "off").'"></a></div>';
  endif;
}

function mystique_featured_process_js() {
  wp_print_scripts(array('jquery'));
  ?>
  <script type="text/javascript">
  //<![CDATA[

  jQuery(document).ready(function ($) {
   jQuery(".featured_post a").click(function () {
    $link = jQuery(this);
    var pos = $link.attr('id').lastIndexOf('-');
    var targetID = $link.attr('id').substr(++pos);

    jQuery.ajax({
        url: '<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php',
        type: "GET",
        data: ({action: 'featured_process', id: targetID, isOn: ($link.hasClass('on') ? 1 : 0)  }),
		beforeSend: function() {
		  $link.attr("class","loading");
		},

		error: function(request){
		   alert('<?php echo wp_specialchars(__("Error while featuring posts","mystique"), 'single'); // thanks Frasten :) ?>');
          $link.attr('class',"error");
		},

        success: function(data) {
          $link.attr('class',data);
		}

	});
   });
  });

  //]]>
  </script>
<?php
}


// the ajax
function mystique_featured_process() {

  $mystique_settings = get_option('mystique');

  // read submitted information
  $id = $_GET['id'];
  $is_on = $_GET['isOn'];

  $featured_arr = $mystique_settings['featured_posts'] ? explode(',', $mystique_settings['featured_posts']) : array();

  // add to array if not on and not currently in the array
  if (!$is_on && ! in_array($id, $featured_arr)) array_push($featured_arr, $id);
  rsort($featured_arr);

  $featured_str = '';

  // if not the same as selected, add to the featured str
  foreach ($featured_arr as $post_id) if (!($is_on && $post_id == $id)) $featured_str .= $post_id.',';
  if ($featured_str) $featured_str = substr($featured_str, 0, -1);

  $mystique_settings['featured_posts'] = $featured_str;
  update_option('mystique', $mystique_settings);

  // reverse classes
  die($is_on ? 'off' : 'on');
}


function mystique_featured_default_settings($defaults){
//  $d = array();
//  if(!get_mystique_option('featured_posts')): // query only if there are no f.p. set
//    $a_few_random_posts = get_posts('numberposts=3&orderby=rand');
//    $a_few_random_posts = mystique_objectToArray($a_few_random_posts);
//    foreach($a_few_random_posts as $random_post) $d[] = $random_post['ID'];
//  endif;
//  $defaults['featured_posts'] = implode(",", $d);

  $defaults['featured_posts'] = '';
  $defaults['featured_timeframe'] = 30; // days
  $defaults['featured_on_home'] = 0;
  $defaults['featured_on_single'] = 0;
  $defaults['featured_on_archives'] = 0;
  $defaults['featured_on_404'] = 0;
  $defaults['featured_on_search'] = 0;
  $defaults['featured_on_pages'] = 0;
  $defaults['featured_count'] = 5;
  $defaults['featured_timeout'] = 10;
  return $defaults;
}

function mystique_featured_admin(){ ?>
 <tr>
  <th scope="row"><p><?php _e("Featured posts","mystique"); ?><span><?php printf(__("You can mark posts as featured on the %s page", "mystique"), '<a href="'.admin_url('edit.php').'">Posts</a>'); ?></span></p></th>
  <td>

    <table>

     <tr>
      <th scope="row"><p><?php _e("Show on","mystique"); ?></p></th>
      <td>

       <label for="opt_featured_on_template">
         <input disabled="disabled" checked="checked" name="featured_on_template" id="opt_featured_on_template" type="checkbox" class="checkbox" value="1" ?> <?php _e("Pages that use the Featured posts template", "mystique"); ?>
       </label>
       <br />

       <label for="opt_featured_on_home">
         <input name="featured_on_home" id="opt_featured_on_home" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('featured_on_home')) ?> /> <?php _e("Home page", "mystique"); ?>
       </label>
       <br />

       <label for="opt_featured_on_single">
         <input name="featured_on_single" id="opt_featured_on_single" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('featured_on_single')) ?> /> <?php _e("Single post pages", "mystique"); ?>
       </label>
       <br />

       <label for="opt_featured_on_archives">
         <input name="featured_on_archives" id="opt_featured_on_archives" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('featured_on_archives')) ?> /> <?php _e("Archive pages (Category, Tags etc)", "mystique"); ?>
       </label>
       <br />

       <label for="opt_featured_on_404">
         <input name="featured_on_404" id="opt_featured_on_404" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('featured_on_404')) ?> /> <?php _e("404 pages", "mystique"); ?>
       </label>
       <br />

       <label for="opt_featured_on_search">
         <input name="featured_on_search" id="opt_featured_on_search" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('featured_on_search')) ?> /> <?php _e("Search page", "mystique"); ?>
       </label>
       <br />

       <label for="opt_featured_on_pages">
         <input name="featured_on_pages" id="opt_featured_on_pages" type="checkbox" class="checkbox" value="1" <?php checked('1', get_mystique_option('featured_on_pages')) ?> /> <?php _e("All other pages", "mystique"); ?>
       </label>

      </td>
     </tr>

     <tr>
      <th scope="row"><p><?php _e("Maximum number of posts to display","mystique"); ?></p></th>
      <td>
       <input type="text" size="5" name="featured_count" value="<?php echo wp_specialchars(get_mystique_option('featured_count')); ?>" />
      </td>
     </tr>

     <tr>
      <th scope="row"><p><?php _e("Slide delay","mystique"); ?></p></th>
      <td>
       <input type="text" size="5" name="featured_timeout" value="<?php echo wp_specialchars(get_mystique_option('featured_timeout')); ?>" />
       <?php _e("seconds","mystique"); ?>
      </td>
     </tr>

     <tr>
      <th scope="row"><p><?php _e("Don't show posts older than","mystique"); ?></p></th>
      <td>
       <input type="text" size="5" name="featured_timeframe" value="<?php echo wp_specialchars(get_mystique_option('featured_timeframe')); ?>" />
       <?php _e("days","mystique"); ?>
      </td>
     </tr>

    </table>

    <input type="hidden" name="featured_posts" value="<?php echo get_mystique_option('featured_posts'); ?>" />

  </td>
 </tr>
 <?php
}


add_action('mystique_admin_content','mystique_featured_admin');

// add column to post listings
add_filter('manage_posts_columns', 'mystique_add_featured_posts_column');
add_filter('manage_posts_custom_column', 'mystique_featured_posts_column', 10, 2);

add_filter('mystique_post_class', 'mystique_featured_post_class');
add_filter('mystique_default_settings','mystique_featured_default_settings');

// add ajax processing stuff
add_action('wp_ajax_featured_process', 'mystique_featured_process');
add_action('admin_print_scripts', 'mystique_featured_process_js' );

add_action('mystique_before_main', 'mystique_featured_post');

?>