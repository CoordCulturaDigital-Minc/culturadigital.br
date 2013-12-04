<?php /* Mystique/digitalnature */

function mystique_getTinyUrl($url) {
    $response = wp_remote_retrieve_body(wp_remote_get('http://tinyurl.com/api-create.php?url='.$url));     // replaces curl (thanks Joseph!)
    return $response;
}

function mystique_objectToArray($object){
   if(!is_object($object) && !is_array($object)) return $object;
   if(is_object($object)) $object = get_object_vars($object);
   return array_map('mystique_objectToArray', $object);
}

// category walker
class mystique_CategoryWalker extends Walker {
  var $db_fields = array ('parent' => 'parent', 'id' => 'term_id'); //TODO: decouple this
  var $selected = array();

  function mystique_CategoryWalker($type='list', $tag='ul', $selected=array()){
    $this->tag = $tag;
    $this->selected = $selected;
    $this->type = $type;
    $this->level = 1;
  }

  function start_lvl(&$output){
    $this->level++;
    $output .= "\n<".$this->tag." class=\"level-".$this->level."\">\n";
  }

  function end_lvl(&$output) {
    $this->level--;
    $output .= "</".$this->tag.">\n";
  }

  function start_el(&$output, $category, $depth, $args) {
    extract($args);

    $count_text = sprintf(_n('%s post', '%s posts', intval($category->count), 'mystique'),intval($category->count));

    if (in_array($category->term_id, $this->selected)) $checked = ' checked="checked"'; else $checked = '';

    $classes = array();
    $classes[] = 'category category-'.$category->slug;
    $classes[] = 'count-'.intval($category->count);

    if (isset($current_category) && $current_category) $_current_category = get_category($current_category);

    $active_class = '';
    if((isset($current_category) && $current_category && ($category->term_id == $current_category))) $active_class = 'active';
    elseif (isset($_current_category) && $_current_category && ($category->term_id == $_current_category->parent)) $active_class = 'active-parent';

    $classes[] = $active_class;

    $output .= '<li class="'.join(' ', $classes).'">';
    if($this->type == 'checkbox'):
      $output .= '<input class="checkbox '.$active_class.'" id="opt-category-'. $category->term_id. '" name="exclude_categories[]" type="checkbox" value="'. $category->term_id. '"'. $checked. ' />';
      $output .= '<label for="opt-category-'. $category->term_id. '"> <a href="' . get_category_link($category->term_id) . '">'.attribute_escape($category->name).'</a>';
      if($count) $output .= ' <span>('.$count_text.')</span> ';
      $output .= '</label>';
    else:

    if ($category->description) $title = $category->description; else $title = $count_text;
     $output .= '<a class="fadeThis '.$active_class.'" href="'.get_category_link($category->term_id).'" title="'.$title.'"><span class="title">'.attribute_escape($category->name).'</span><span class="pointer"></span></a>';
     if($count)  $output .= ' <span class="post-count">('.intval($category->count).')</span> ';
    endif;
  }

  function end_el(&$output, $page) { $output .= "</li>\n"; }
}


// page walker
class mystique_PageWalker extends Walker {
  var $db_fields = array('parent' => 'post_parent', 'id' => 'ID');
  var $selected = array();

  function mystique_PageWalker($type='list', $tag='ul', $selected=array()){
    $this->tag = $tag;
    $this->selected = $selected;
    $this->type = $type;
    $this->level = 1;
  }

  function start_lvl(&$output) {
    $this->level++;
    $output .= "\n<".$this->tag." class=\"level-".$this->level."\">\n";
  }

  function end_lvl(&$output) {
    $this->level--;
    $output .= "</".$this->tag.">\n";
  }

  function start_el(&$output, $page, $depth, $args, $current_page) {
    extract($args);

    if (in_array($page->ID, $this->selected)) $checked = ' checked="checked"'; else $checked = '';

    $classes = array();
    $classes[] = 'page page-'.$page->post_name;

    $active_class = '';
    if (!empty($current_page)):
       $_current_page = get_page($current_page);
       if (isset($_current_page->ancestors) && in_array($page->ID, (array) $_current_page->ancestors)) $active_class = 'active-ancestor';
       elseif (($page->ID == $current_page)) $active_class = 'active';
       elseif ($_current_page && $page->ID == $_current_page->post_parent) $active_class = 'active-parent';
    endif;

    $classes[] = $active_class;

    //$classes = implode(' ', apply_filters('page_css_class', $css_class, $page));

    $output .= '<li class="'.join(' ', $classes).'">';

    if($this->type == 'checkbox'):
      $output .= '<input class="checkbox '.join(' ', $classes).'" id="opt-page-'. $page->ID. '" name="exclude_pages[]" type="checkbox" value="'.$page->ID.'"'. $checked. ' /> <label for="opt-page-'.$page->ID.'"> <a title="'. __('View page','mystique'). '" href="'.get_page_link($page->ID).'">'. apply_filters('the_title', $page->post_title). '</a> </label>';
    else:
      $output .= '<a class="fadeThis '.$active_class.'" href="'.get_page_link($page->ID).'" title="'.$page->post_title.'"><span class="title">'. apply_filters('the_title', $page->post_title). '</span><span class="pointer"></span></a>';
    endif;

  }

  function end_el(&$output, $page) { $output .= "</li>\n"; }
}

// menu walker
class mystique_MenuWalker extends Walker {
  var $tree_type = array('post_type', 'taxonomy', 'custom');
  var $db_fields = array('parent' => 'menu_item_parent', 'id' => 'db_id');

  function start_lvl(&$output, $depth) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"level-".($depth+2)."\">\n";
  }

  function end_lvl(&$output, $depth) {
   $indent = str_repeat("\t", $depth);
   $output .= "$indent</ul>\n";
  }

  function start_el(&$output, $item, $depth, $args) {
    global $wp_query;
    $indent = ($depth) ? str_repeat("\t", $depth) : '';
    $class_names = $value = '';
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
    if($class_names) $class_names = ' class="' .esc_attr($class_names). '"';

    $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

    $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target)     .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn)        .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url)        .'"' : '';

    $item_output .= '<a'. $attributes .' class="fadeThis">';
    $item_output .= '<span class="title">' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
    $item_output .= '<span class="pointer"></span></a>';

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }

  function end_el(&$output, $item, $depth) {
    $output .= "</li>\n";
  }
}



function _mystique_menu_item_classes_by_context( &$menu_items ) {
	global $wp_query;

	$queried_object = $wp_query->get_queried_object();
	$queried_object_id = (int) $wp_query->queried_object_id;

	$active_object = '';
	$active_parent_item_ids = array();
	$active_parent_object_ids = array();
	$possible_object_parents = array();
	$home_page_id = (int) get_option( 'page_for_posts' );

	if ( $wp_query->is_singular && ! empty( $queried_object->post_type ) && ! is_post_type_hierarchical( $queried_object->post_type ) ) {
		foreach ( (array) get_object_taxonomies( $queried_object->post_type ) as $taxonomy ) {
			if ( is_taxonomy_hierarchical( $taxonomy ) ) {
				$terms = wp_get_object_terms( $queried_object_id, $taxonomy, array( 'fields' => 'ids' ) );
				if ( is_array( $terms ) )
					$possible_object_parents = array_merge( $possible_object_parents, $terms );
			}
		}
	} elseif ( ! empty( $queried_object->post_type ) && is_post_type_hierarchical( $queried_object->post_type ) ) {
		_get_post_ancestors( $queried_object );
	}

	$possible_object_parents = array_filter( $possible_object_parents );

	foreach ( (array) $menu_items as $key => $menu_item ) {
		// if the menu item corresponds to a taxonomy term for the currently-queried non-hierarchical post object
		if ( $wp_query->is_singular && 'taxonomy' == $menu_item->type && in_array( $menu_item->object_id, $possible_object_parents ) ) {
			$active_parent_object_ids[] = (int) $menu_item->object_id;
			$active_parent_item_ids[] = (int) $menu_item->db_id;
			$active_object = $queried_object->post_type;

		// if the menu item corresponds to the currently-queried post or taxonomy object
		} elseif (
			$menu_item->object_id == $queried_object_id &&
			(
				( ! empty( $home_page_id ) && 'post_type' == $menu_item->type && $wp_query->is_home && $home_page_id == $menu_item->object_id ) ||
				( 'post_type' == $menu_item->type && $wp_query->is_singular ) ||
				( 'taxonomy' == $menu_item->type && ( $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax ) )
			)
		) {
			$menu_items[$key]->classes[] = 'active';
			$active_parent_item_ids[] = (int) $menu_item->menu_item_parent;
			$active_parent_object_ids[] = (int) $menu_item->post_parent;
			$active_object = $menu_item->object;

		// if the menu item corresponds to the currently-requested URL
		} elseif ( 'custom' == $menu_item->object ) {
			$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$item_url = strpos( $menu_item->url, '#' ) ? substr( $menu_item->url, 0, strpos( $menu_item->url, '#' ) ) : $menu_item->url;
			if ( $item_url == $current_url ) {
				$menu_items[$key]->classes[] = 'active';
				if ( untrailingslashit($current_url) == home_url() )
					$menu_items[$key]->classes[] = 'home';
				$active_parent_item_ids[] = (int) $menu_item->menu_item_parent;
				$active_parent_object_ids[] = (int) $menu_item->post_parent;
				$active_object = $menu_item->object;
			}
		}

		// back-compat with wp_page_menu: add "current_page_parent" to static home page link for any non-page query
		if ( ! empty( $home_page_id ) && 'post_type' == $menu_item->type && empty( $wp_query->is_page ) && $home_page_id == $menu_item->object_id )
			$menu_items[$key]->classes[] = 'active-parent';
	}

	$active_parent_item_ids = array_filter( array_unique( $active_parent_item_ids ) );
	$active_parent_object_ids = array_filter( array_unique( $active_parent_object_ids ) );

	// set parent's class
	foreach ( (array) $menu_items as $key => $parent_item ) {
		if (
			isset( $parent_item->type ) &&
			'post_type' == $parent_item->type &&
			! empty( $queried_object->post_type ) &&
			is_post_type_hierarchical( $queried_object->post_type ) &&
			in_array( $parent_item->object_id, $queried_object->ancestors )
		)
			$menu_items[$key]->classes[] = 'active-' . $queried_object->post_type . '-ancestor active-ancestor';
		if ( in_array( $parent_item->db_id, $active_parent_item_ids ) )
			$menu_items[$key]->classes[] = 'active-parent';
		if ( in_array( $parent_item->object_id, $active_parent_object_ids ) )
			$menu_items[$key]->classes[] = 'active-' . $active_object . '-parent';
	}
}



// replaces wp_list_categories (didn't like the <li> classes)
function mystique_list_categories($args = array()) {
  global $wp_query;
  $categories = &get_categories($args);
  $walker = new mystique_CategoryWalker();
  if (!empty($categories)) echo $walker->walk($categories, 0, array('count' => false, 'current_category' =>$wp_query->get_queried_object_id()));
}

// replaces wp_list_pages
function mystique_list_pages($args = array()) {
  global $wp_query;
  $pages = &get_pages($args);
  $walker = new mystique_PageWalker();
  if (!empty($pages)) echo $walker->walk($pages,0, array(),$wp_query->get_queried_object_id());
}

function mystique_list_menu($args = array()) {
  $locations = get_nav_menu_locations();
  $menu = wp_get_nav_menu_object($locations['primary']);

  $menu_items = wp_get_nav_menu_items($menu->term_id);
  if(empty($menu_items)): printf('<li><a><span class="error">'.__("Empty menu (%s)","mystique").'</span></a></li>', $menu->slug); return; endif;
  $nav_menu = '';
  $items = '';
  _mystique_menu_item_classes_by_context($menu_items);
  $sorted_menu_items = array();
  foreach ((array) $menu_items as $key => $menu_item) $sorted_menu_items[$menu_item->menu_order] = wp_setup_nav_menu_item($menu_item);

  $walker = new mystique_MenuWalker();
  $items .= $walker->walk($sorted_menu_items, 0, array());

  // Allow plugins to hook into the menu to add their own <li>'s
  $items = apply_filters('wp_nav_menu_items', $items, $args);
  $items = apply_filters("wp_nav_menu_{$menu->slug}_items", $items, $args);
  $nav_menu .= $items;

  $nav_menu = apply_filters('wp_nav_menu', $nav_menu, $args);
  echo $nav_menu;
}

// print the main navigation menu
function mystique_navigation($navtype = false) {
  if(!$navtype): // main navi?
   $navtype = get_mystique_option('navigation');
   $nav_extra = apply_filters("mystique_navigation_extra", '');  // check for new icons and output
  endif;
  if($navtype): ?>

   <div class="shadow-left">
   <div class="shadow-right clear-block">
   <?php
    if($nav_extra) echo '<p class="nav-extra">'.$nav_extra.'</p>';  ?>

   <ul class="navigation <?php echo $nav_type; ?> clear-block">
     <?php
      if((get_option('show_on_front')<>'page') && get_mystique_option('exclude_home')<>'1'):
       if(is_home() && !is_paged()): ?>
        <li class="active home"><a class="home active fadeThis" href="<?php echo get_settings('home'); ?>" title="<?php _e('You are Home','mystique'); ?>"><span class="title"><?php _e('Home','mystique'); ?></span><span class="pointer"></span></a></li>
       <?php else: ?>
        <li class="home"><a class="home fadeThis" href="<?php echo get_option('home'); ?>" title="<?php _e('Click for Home','mystique'); ?>"><span class="title"><?php _e('Home','mystique'); ?></span><span class="pointer"></span></a></li>
      <?php
       endif;
      endif; ?>
     <?php
       if($navtype=='categories'):
         mystique_list_categories(array('hide_empty' => false, 'exclude' => get_mystique_option('exclude_categories')));

       elseif($navtype=='links'):
         $links = get_bookmarks(array(
         'orderby'        => 'name',
         'order'          => 'ASC',
         'limit'          => -1,
         'category'       => null,
         'category_name'  => get_mystique_option('navigation_links'),
         'hide_invisible' => true,
         'show_updated'   => 0,
         'include'        => null,
         'search'         => '.'));

         foreach ($links as $link):
           if($link->link_target) $target = ' target="'.wp_specialchars($link->link_target).'"'; else $target = '';
           if($link->link_rel) $rel = ' rel="'.wp_specialchars($link->link_rel).'"'; else $rel = '';
           if($link->link_description) $title = ' title="'.wp_specialchars($link->link_description).'"'; else $title = '';
           echo '<li><a class="fadeThis" href="'.$link->link_url.'"'.$target.$rel.$title.'><span class="title">'.$link->link_name.'</span><span class="pointer"></span></a><li>';
         endforeach;

       elseif($navtype=='pages'):
         mystique_list_pages(array('exclude' => get_mystique_option('exclude_pages'), 'sort_column' => 'menu_order'));
       else:
         mystique_list_menu();
       endif;

       do_action('mystique_navigation'); ?>
   </ul>
   </div>
   </div>
  <?php endif;
}

// based on hybrid theme's title
function mystique_title($separator = ' &laquo; '){
  global $wp_query;

  if (is_front_page() && is_home()):
   $doctitle = get_bloginfo('name').$separator.get_bloginfo('description');
  elseif (is_home() || is_singular()):
   $id = $wp_query->get_queried_object_id();
   $doctitle = get_post_meta($id, 'title', true);
   $doctitle = (!$doctitle && is_front_page()) ? get_bloginfo('name').$separator.get_bloginfo('description') : get_post_field('post_title', $id);

  elseif (is_archive()):

    if (is_category() || is_tag() || is_tax()):
     $term = $wp_query->get_queried_object();
     $doctitle = $term->name;
    elseif (is_author()):
     $doctitle = get_the_author_meta('display_name', get_query_var('author'));
    elseif (is_date()):
     if (is_day())
      $doctitle = sprintf(__('Archive for %s', "mystique"), get_the_time(__('F jS, Y', "mystique")));
     elseif (get_query_var('w'))
      $doctitle = sprintf(__('Archive for week %1$s of %2$s', "mystique"), get_the_time(__('W', "mystique")), get_the_time(__('Y', "mystique")));
     elseif (is_month())
      $doctitle = sprintf(__('Archive for %s', "mystique"), single_month_title(' ', false));
     elseif (is_year())
      $doctitle = sprintf(__('Archive for year %s', "mystique"), get_the_time(__('Y', "mystique")));
    endif;

  elseif (is_search()):
   $doctitle = sprintf(__('Search results for %s', "mystique"),'&quot;'.esc_attr(get_search_query()).'&quot;');

  elseif (is_404()):
   $doctitle = __('404 Not Found', "mystique");

  endif;

  /* If paged. */
  if ((($page = $wp_query->get('paged')) || ($page = $wp_query->get('page'))) && $page > 1)
   $doctitle .= $separator.sprintf(__('Page %s', "mystique"), $page);

  /* if comment page... */
  if (get_query_var('cpage'))
   $doctitle .= $separator.sprintf(__('Comment Page %s', "mystique"), get_query_var('cpage'));

  /* Apply the wp_title filters so we're compatible with plugins. */
  $doctitle = apply_filters('wp_title', $doctitle, $separator, '');

  echo $doctitle;
}


function mystique_category_breadcrumb($id, $visited = array()){
  $chain = '';
  $parent = &get_category($id);
  $level = 1;
  if (is_wp_error($parent)) return $parent;

  $name = $parent->cat_name;
  if ($parent->parent && ($parent->parent != $parent->term_id) && !in_array($parent->parent, $visited)):
   $visited[] = $parent->parent;
   $chain .= mystique_category_breadcrumb($parent->parent, $visited);
   $level++;
  endif;

  $chain .= '<a class="category level-'.$level.'" href="'.get_category_link($parent->term_id).'" title="'.esc_attr(sprintf(__("View all posts in %s","mystique"), $parent->cat_name)).'">'.$name.'</a>';
  echo ' '.$chain.' ';
}


function mystique_top_category($categorylist,$link = true){
  if($categorylist[0]->category_parent) $top_category = get_category($categorylist[0]->category_parent); else $top_category = get_category($categorylist[0]->cat_ID);

  ($top_category->description) ? $top_category_description = $top_category->description : $top_category_description = sprintf(_n('%s post', '%s posts', intval($top_category->count), 'mystique'),intval($top_category->count));

  if($top_category) echo '<a title="'.$top_category_description.'" href="'.get_category_link($top_category->cat_ID).'" class="category">'.$top_category->cat_name.'</a>';
}


function mystique_logo(){
  $options = get_option('mystique');
  $size = $options['logo_size'];
  if($size) $size = 'width="'.substr($size, 0, strpos($size, "x")).'" height="'.substr($size, strpos($size, 'x')+1).'"';

  $sitename = get_bloginfo('name');
  $siteurl = get_bloginfo('url');

  $tag = (is_home() || is_front_page()) ? 'h1' : 'div';

  $output = '<'.$tag.' id="logo">';

  if($options['logo']) // logo image?
    $output .= '<a href="'.$siteurl.'"><img src="'.$options['logo'].'" title="'.$sitename.'" '.$size.' alt="'.$sitename.'" /></a>';
  else
    $output .= '<a href="'.$siteurl.'">'.$sitename.'</a>';
  $output .= '</'.$tag.'>';
  echo apply_filters('mystique_logo', $output);
}


function mystique_shareThis(){
  global $post;
  $content = get_the_excerpt();
  ?>
   <!-- socialize -->
   <div class="shareThis clear-block">
    <a href="#" class="control share"><?php _e("Share this post!","mystique"); ?></a>
    <ul class="bubble">
     <li><a href="http://twitter.com/home?status=<?php the_title(); ?>+-+<?php echo mystique_getTinyUrl(get_permalink()); ?>" class="twitter" title="Tweet This!"><span>Twitter</span></a></li>
     <li><a href="http://digg.com/submit?phase=2&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" class="digg" title="Digg this!"><span>Digg</span></a></li>
     <li><a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&amp;t=<?php the_title(); ?>" class="facebook" title="Share this on Facebook"><span>Facebook</span></a></li>
     <li><a href="http://del.icio.us/post?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" class="delicious" title="Share this on del.icio.us"><span>Delicious</span></a></li>
     <li><a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" class="stumbleupon" title="Stumbled upon something good? Share it on StumbleUpon"><span>StumbleUpon</span></a></li>
     <li><a href="http://www.google.com/bookmarks/mark?op=add&amp;bkmk=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" class="google" title="Add this to Google Bookmarks"><span>Google Bookmarks</span></a></li>
     <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>&amp;summary=<?php echo strip_tags($content); ?>&amp;source=<?php bloginfo('name'); ?>" class="linkedin" title="Share this on Linkedin"><span>LinkedIn</span></a></li>
     <li><a href="http://buzz.yahoo.com/buzz?targetUrl=<?php the_permalink(); ?>&amp;headline=<?php the_title(); ?>&amp;summary=<?php echo strip_tags($content); ?>" class="yahoo" title="Buzz up!"><span>Yahoo Bookmarks</span></a></li>
     <li><a href="http://technorati.com/faves?add=<?php the_permalink(); ?>" class="technorati" title="Share this on Technorati"><span>Technorati Favorites</span></a></li>
    </ul>
   </div>
   <!-- /socialize -->
 <?php
}


function mystique_search_form(){ ?>
<!-- search form -->
<div class="search-form">
  <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/" class="clear-block">
    <fieldset>
      <div id="searchfield">
       <input type="text" name="s" id="searchbox" class="text clearField" value="<?php _e("Search","mystique"); ?>" />
      </div>
      <input type="submit" value="" class="submit" />
     </fieldset>
 </form>
</div>
<!-- /search form -->
<?php
}

function mystique_highlight_search_query(){
  $referer = urldecode($_SERVER['HTTP_REFERER']);

  if (preg_match('@^http://(.*)?\.?(google|yahoo|lycos).*@i', $referer)) $query = preg_replace('/^.*(&q|query|p)=([^&]+)&?.*$/i','$2', $referer);
  else $query  = attribute_escape(get_search_query());

  if(strlen($query) > 0): ?>
    var highlight_search_query = "<?php echo $query; ?>";
    jQuery(".hentry").each(function(){
     jQuery(this).highlight(highlight_search_query, 1, "highlight");
    });
  <?php
  endif;
}

function mystique_trim_string($input, $string){
  $input = trim($input);
  $string = escape_string_for_regex($string);
  $startPattern = "/^($string)+/i";
  $endPattern = "/($string)+$/i";
  return trim(preg_replace($endPattern, '', preg_replace($startPattern, '', $input)));
}

// filter post content (for other pages than single)
function mystique_trim_the_content($the_contents, $read_more_tag, $perma_link_to = '', $all_words = 100,  $allowed_tags = array('a', 'abbr', 'blockquote', 'b', 'cite', 'pre', 'code', 'em', 'label', 'i', 'p', 'span', 'strong', 'ul', 'ol', 'li')) {

  if($the_contents != ''):

    // process allowed tags
    $allowed_tags = '<' .implode('><',$allowed_tags).'>';
    $the_contents = str_replace(']]>', ']]&gt;', $the_contents);

    // exclude HTML and shortcodes from counting words
    $the_contents = strip_tags($the_contents, $allowed_tags);
    $the_contents = strip_shortcodes($the_contents);

    if(!is_numeric($all_words)) $all_words = 9999; // assuming full post

    // count all
    if($all_words > count(preg_split('/[\s]+/', strip_tags($the_contents), -1))) return $the_contents;

    $all_chunks = preg_split('/([\s]+)/', $the_contents, -1, PREG_SPLIT_DELIM_CAPTURE);

    $the_contents = '';
    $count_words = 0;
    $enclosed_by_tag = false;
    foreach($all_chunks as $chunk):

      // is tag opened?
      if(0 < preg_match('/<[^>]*$/s', $chunk)) $enclosed_by_tag = true; elseif(0 < preg_match('/>[^<]*$/s', $chunk)) $enclosed_by_tag = false;

      // get entire word
      if(!$enclosed_by_tag && '' != trim($chunk) && substr($chunk, -1, 1) != '>') $count_words ++;
      $the_contents .= $chunk;
      if($count_words >= $all_words && !$enclosed_by_tag) break;

    endforeach;

    // note the class named 'more-link'. style it on your own
    $the_contents = $the_contents.' <a class="more-link" href="'.$perma_link_to.'">'.$read_more_tag.'</a>';

    // native WordPress check for unclosed tags
    $the_contents = force_balance_tags($the_contents);
  endif;
  return $the_contents;
}


// strip tags and attributes from a string that can be used to XSS
function mystique_strip_tags_attributes($string, $allowed_tags = '<a><br><style><abbr><blockquote><b><cite><pre><code><em><label><i><p><div><span><strong><ul><ol><li><dt><dd><dl><table><td><tr><th><tbody><tfoot><thead><colgroup><h1><h2><h3><h4><h5><h6><u>', $allowed_attributes = 'class,title,alt,href,dir,id,cite,lang,width,height,border,colspan,rowspan,align,rel,type'){

  $string = preg_replace('#/*\*()[^>]*\*/#i', '', $string); // remove /**/
  $string = preg_replace('#([a-z]*)[\x00-\x20]*e[\x00-\x20]*x[\x00-\x20]*p[\x00-\x20]*r[\x00-\x20]*e[\x00-\x20]*s[\x00-\x20]*s[\x00-\x20]*i[\x00-\x20]*o[\x00-\x20]*n#iU', '', $string); // remove 'expression'
  $string = preg_replace('#([a-z]*)[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU', '', $string); // remove "javascript"
  $string = preg_replace('#([a-z]*)([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU', '', $string); // remove "vbscript"
  $string = preg_replace('#([a-z]*)[\x00-\x20]*([\\\]*)[\\x00-\x20]*@([\\\]*)[\x00-\x20]*i([\\\]*)[\x00-\x20]*m([\\\]*)[\x00-\x20]*p([\\\]*)[\x00-\x20]*o([\\\]*)[\x00-\x20]*r([\\\]*)[\x00-\x20]*t#iU', '', $string); // take out @import

  $string = strip_tags($string, $allowed_tags);

  if (!is_null($allowattributes)):
      if(!is_array($allowattributes)) $allowattributes = explode(",", $allowattributes);
      if(is_array($allowattributes)) $allowattributes = implode(")(?<!", $allowattributes);
      if(strlen($allowattributes) > 0) $allowattributes = "(?<!".$allowattributes.")";
      $string = preg_replace_callback("/<[^>]*>/i",create_function(
          '$matches',
          'return preg_replace("/ [^ =]*'.$allowattributes.'=(\"[^\"]*\"|\'[^\']*\')/i", "", $matches[0]);'
      ), $string);
  endif;
  return $string;
}

function mystique_strip_string($intLength = 0, $strText = "") {
 $strText = strip_tags($strText);
 if(strlen($strText) > $intLength):
   $strText = mb_substr($strText,0,$intLength);
   $strText = mb_substr($strText,0,strrpos($strText,' '));
    return $strText.'...';
 else:
   return $strText;
 endif;
}


function mystique_comment_count($comment_types = 'comments', $post_id = false) {
  global $id;
  $post_id = (int)$post_id;
  if (!$post_id) $post_id = $id;
  $comments = get_approved_comments($post_id);
  $num_pings = 0;
  $num_comments  = 0;
  foreach($comments as $comment) if (get_comment_type() != "comment") $num_pings++; else $num_comments++;
  return ($comment_types == 'comments') ? $num_comments : $num_pings;
}


function mystique_post_thumb($size = 'post-thumbnail', $post_id = false){
  global $post, $id;
  $post_id = (int)$post_id;
  if (!$post_id) $post_id = $id;
  $image = '';

  if (has_post_thumbnail($post_id)) $image = get_the_post_thumbnail($post_id, $size);

  if(get_mystique_option('post_thumb_auto') && !$image): // get the 1st image
    $attachments = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));
    if($attachments):
      $attachment = array_shift($attachments);
      $image = wp_get_attachment_image($attachment->ID, $size);
    endif;
  endif;

  if($image && !get_post_meta($post->ID, 'asides', true)):
   echo '<a class="post-thumb size-'.$size.' alignleft" href="'.get_permalink($post_id).'">'.apply_filters("mystique_post_thumbnail", $image).'</a>';
   return true;
  endif;
  return false;
}

// only inside the loop!
function mystique_post(){
 global $post, $id, $comment;

 $category = get_the_category();
 $category_name = $category[0]->cat_name;

 if(!empty($category_name)) $category_link = '<a href="'.get_category_link($category[0]->cat_ID).'">'.$category_name.'</a>';
 else $category_link = "[...]";

 $post_tags = get_the_tags();
 $post_settings = apply_filters('mystique_post_settings', get_option("mystique"));
 $comment_count = mystique_comment_count('comments');

 do_action('mystique_before_post'); ?>

  <!-- post -->
  <div id="post-<?php the_ID(); ?>" class="<?php mystique_post_class('clear-block'); ?>">

   <?php
    $post_thumb = mystique_post_thumb();
    if($post_settings['post_title']):
      $title_url = get_post_meta($post->ID, 'title_url', true); ?>
     <h2 class="title"><a href="<?php if($title_url) echo $title_url; else the_permalink(); ?>" rel="bookmark" title="<?php _e('Permanent Link:','mystique'); echo ' '; the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
   <?php endif; ?>

   <?php if($post_settings['post_info'] && !get_post_meta($post->ID, 'asides', true)): ?>
     <div class="post-date">
       <p class="day"><?php the_time(__('M jS','mystique')); ?></p>
     </div>

     <div class="post-info clear-block <?php if ($post_thumb) echo 'with-thumbs' ?>">
      <p class="author alignleft"><?php printf(__('Posted by %1$s in %2$s','mystique'),'<a href="'. get_author_posts_url(get_the_author_meta('ID')) .'" title="'. sprintf(__("Posts by %s","mystique"), attribute_escape(get_the_author())).' ">'. get_the_author() .'</a>', $category_link);
        ?>
      <?php if(function_exists('the_views')): ?><span class="postviews">| <?php the_views(); ?></span><?php endif; ?>
      <?php edit_post_link(__('Edit','mystique'),' | '); ?>
      </p>
      <?php
      if((comments_open() || $comment_count > 0)): ?>
      <p class="comments alignright"><a href="<?php the_permalink() ?>#comments" class="<?php if ($comment_count < 1) echo "no"; ?> comments"><?php comments_number(__('No comments', 'mystique'), __('1 comment', 'mystique'), __('% comments', 'mystique')); ?></a></p>
      <?php endif; ?>
     </div>
    <?php endif; ?>

   <?php if($post_settings['post_content']): ?>
     <div class="post-content clear-block">
      <?php

       if($post_settings['post_content_length'] == 'f'): the_content(__('More &gt;','mystique'));
       elseif($post_settings['post_content_length'] == 'e'): the_excerpt();
       else:
         $word_count = $post_settings['post_content_length'];

         // save original post content to variable
         $content = get_the_content();

         // prevent tags strip | it's a bug in WordPress!
         $content = apply_filters('the_content', $content);
         $content = str_replace(']]>', ']]&gt;', $content);

         // throw out trimmed: content to process, read more tag, post permalink, words length
         echo mystique_trim_the_content($content, __('More &gt;','mystique'), get_permalink($post->ID), $word_count);
       endif; ?>
     </div>
   <?php endif; ?>
   <?php if(function_exists('the_ratings')) the_ratings(); ?>

   <?php if ($post_tags && $post_settings['post_tags']): ?>
    <div class="post-tags">
    <?php
      $tags = array();
      $i = 0;
      foreach($post_tags as $tag):
       $tags[$i] .=  '<a href="'.get_tag_link($tag->term_id).'" rel="tag" title="'.sprintf(__('%1$s (%2$s topics)'),$tag->name,$tag->count).'">'.$tag->name.'</a>';
       $i++;
      endforeach;
      echo implode(', ',$tags); ?>
    </div>
    <?php endif; ?>


  </div>
  <!-- /post -->
 <?php do_action('mystique_after_post');

}


// only inside the loop!
function mystique_page(){
  global $post, $id, $comment;

  $tags = get_the_tags();
  $comment_count = mystique_comment_count('comments');

  do_action('mystique_before_page'); ?>

  <!-- post -->
  <div class="<?php mystique_post_class("clear-block"); ?>">

   <?php
     $hide_title = get_post_meta($post->ID, 'hide_title', true);
     if(!$hide_title): ?>
     <h1 class="title">
       <?php
        the_title();
       ?>
     </h1> <?php
     endif;

     the_content();
     wp_link_pages(array('before' => '<p><strong>'.__("Pages: ","mystique").'</strong> ', 'after' => '</p>', 'next_or_number' => 'number'));
     if(function_exists('wp_print')): ?><div class="alignright"><?php print_link(); ?></div><?php endif;
     edit_post_link(__('Edit this page', 'mystique'));
   ?>

  </div>
  <!-- /post -->
  <?php
  do_action('mystique_after_page');
}


function mystique_excerpt_more($excerpt) {
  $link = ' <a href="'.get_permalink().'" class="more-link">'.__('More &gt;', 'mystique').'</a>';
}

function mystique_meta_redirect(){
  if(is_single() || is_page()):
   global $post;
   $field = get_post_meta($post->ID, 'redirect', true);
   if($field) wp_redirect(clean_url($field), 301);
  endif;
}

function mystique_n_round($num, $tonearest) {
  return floor($num/$tonearest)*$tonearest;
}

// wp-pagenavi - http://wordpress.org/extend/plugins/wp-pagenavi
function mystique_pagenavi($class='',$pages_to_show = 5) {
  global $wp_query;
  if (!is_single()):
    $posts_per_page = intval(get_query_var('posts_per_page'));
    $paged = intval(get_query_var('paged'));
    $max_page = $wp_query->max_num_pages;
    if(empty($paged) || $paged == 0) $paged = 1;
    $larger_page_to_show = 3;
    $larger_page_multiple = 10;
    $pages_to_show_minus_1 = $pages_to_show - 1;
    $half_page_start = floor($pages_to_show_minus_1/2);
    $half_page_end = ceil($pages_to_show_minus_1/2);
    $start_page = $paged - $half_page_start;
    if($start_page <= 0) $start_page = 1;
    $end_page = $paged + $half_page_end;
    if(($end_page - $start_page) != $pages_to_show_minus_1) $end_page = $start_page + $pages_to_show_minus_1;
    if($end_page > $max_page):
      $start_page = $max_page - $pages_to_show_minus_1;
      $end_page = $max_page;
    endif;
    if($start_page <= 0) $start_page = 1;
    $larger_per_page = $larger_page_to_show*$larger_page_multiple;
    $larger_start_page_start = (mystique_n_round($start_page, 10) + $larger_page_multiple) - $larger_per_page;
    $larger_start_page_end = mystique_n_round($start_page, 10) + $larger_page_multiple;
    $larger_end_page_start = mystique_n_round($end_page, 10) + $larger_page_multiple;
    $larger_end_page_end = mystique_n_round($end_page, 10) + ($larger_per_page);
    if($larger_start_page_end - $larger_page_multiple == $start_page):
      $larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
      $larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
    endif;
    if($larger_start_page_start <= 0) $larger_start_page_start = $larger_page_multiple;
    if($larger_start_page_end > $max_page) $larger_start_page_end = $max_page;
    if($larger_end_page_end > $max_page) $larger_end_page_end = $max_page;
    if($max_page > 1): ?>

      <!-- page navigation -->
      <div class="page-navigation <?php echo $class; ?> clear-block">

      <?php
      if ($start_page >= 2 && $pages_to_show < $max_page):
        echo '<a href="'.clean_url(get_pagenum_link()).'" class="first" title="'.__('Go to the first page','mystique').'">'.__('&laquo; First','mystique').'</a>';
        echo '<span class="extend">...</span>';
      endif;

      if($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page)
       for($i = $larger_start_page_start; $i < $larger_start_page_end; $i+=$larger_page_multiple)
        echo '<a href="'.clean_url(get_pagenum_link($i)).'" title="'.sprintf(__("Go to page %s","mystique"),$i).'">'.$i.'</a>';

      previous_posts_link('&laquo;');

      for($i = $start_page; $i <= $end_page; $i++)
       if($i == $paged) echo '<span class="current">'.$i.'</span>';
        else echo '<a href="'.clean_url(get_pagenum_link($i)).'" title="'.sprintf(__("Go to page %s","mystique"),$i).'">'.$i.'</a>';

      next_posts_link('&raquo;', $max_page);

      if($larger_page_to_show > 0 && $larger_end_page_start < $max_page)
       for($i = $larger_end_page_start; $i <= $larger_end_page_end; $i+=$larger_page_multiple)
        echo '<a href="'.clean_url(get_pagenum_link($i)).'" title="'.sprintf(__("Go to page %s","mystique"),$i).'">'.$i.'</a>';

      if ($end_page < $max_page):
        echo '<span class="extend">...</span>';
        echo '<a href="'.clean_url(get_pagenum_link($max_page)).'" class="last" title="'.__('Go to the last page','mystique').'">'.__('Last &raquo;','mystique').'</a>';
      endif; ?>

      </div>
      <!-- /page navigation -->
     <?php
    endif;
endif;
}

// don't show page contents in search results
function mystique_exclude_pages_from_search($query){
  if ($query->is_search && !is_admin()) $query->set('post_type', 'post');
  return $query;
}

// search within taxonomies - based on http://wordpress.org/extend/plugins/search-tags
function mystique_search_where($where){
  if (is_search()):
   global $table_prefix, $wpdb, $wp_query;
   $tabletags = $table_prefix . "terms";
   $tablepost2tag = $table_prefix . "term_relationships";
   $tabletaxonomy = $table_prefix . "term_taxonomy";
   $searchInput = $wp_query->query_vars['s'];
   $searchInput = str_replace('"', '', $searchInput);
   $searchInput = str_replace("'", '', $searchInput);
   $where .= " OR (tr.name like '%" . $searchInput . "%' AND post_status = 'publish' AND post_type = 'post')";
  endif;
  return $where;
}

function mystique_search_join($join){
  if (is_search()):
   global $table_prefix, $wpdb;
   $tabletags = $table_prefix . "terms";
   $tablepost2tag = $table_prefix . "term_relationships";
   $tabletaxonomy = $table_prefix . "term_taxonomy";
   $join .= " LEFT JOIN (select distinct tr.object_id, t.name from $tablepost2tag tr inner join $tabletaxonomy tt on tt.term_taxonomy_id = tr.term_taxonomy_id inner join $tabletags t on t.term_id = tt.term_id) tr on $wpdb->posts.ID = tr.object_id ";
  endif;
  return $join;
}

function mystique_search_groupby($groupby){
  global $wpdb;
  if(!is_search()) return $groupby;

  // we need to group on post ID
  $mygroupby = "{$wpdb->posts}.ID";
  if(preg_match("/$mygroupby/", $groupby)) return $groupby; // grouping we need is already there
  if(!strlen(trim($groupby))) return $mygroupby; // groupby was empty, use ours

  // wasn't empty, append ours
  return $groupby . ", " . $mygroupby;
}




function mystique_timeSince($older_date, $newer_date = false){
  $chunks = array(
   'year'   => 60 * 60 * 24 * 365,  // 31,536,000 seconds
   'month'  => 60 * 60 * 24 * 30,   // 2,592,000 seconds
   'week'   => 60 * 60 * 24 * 7,    // 604,800 seconds
   'day'    => 60 * 60 * 24,        // 86,400 seconds
   'hour'   => 60 * 60,             // 3600 seconds
   'minute' => 60,                  // 60 seconds
   'second' => 1                    // 1 second
  );

 $newer_date = ($newer_date == false) ? (time()+(60*60*get_settings("gmt_offset"))) : $newer_date;
 $since = $newer_date - $older_date;

 foreach ($chunks as $key => $seconds)
  if (($count = floor($since / $seconds)) != 0) break;

 $messages = array(
   'year'	=> _n('about %s year ago', 'about %s years ago', $count, 'mystique'),
   'month'	=> _n('about %s month ago', 'about %s months ago', $count, 'mystique'),
   'week'	=> _n('about %s week ago', 'about %s weeks ago', $count, 'mystique'),
   'day'	=> _n('about %s day ago', 'about %s days ago', $count, 'mystique'),
   'hour'	=> _n('about %s hour ago', 'about %s hours ago', $count, 'mystique'),
   'minute'	=> _n('about %s minute ago', 'about %s minutes ago', $count, 'mystique'),
   'second'	=> _n('about %s second ago', 'about %s seconds ago', $count, 'mystique'),
  );
  return sprintf($messages[$key],$count);
}

// returns the layout type that the current page has
function mystique_layout_type(){
  $layout = get_mystique_option('layout');

  // override if layout page templates are used
  if(is_page_template('page-col-1.php')) $layout = 'col-1';
  if(is_page_template('page-col-2-left.php')) $layout = 'col-2-left';
  if(is_page_template('page-col-2-right.php')) $layout = 'col-2-right';
  if(is_page_template('page-col-3.php')) $layout = 'col-3';
  if(is_page_template('page-col-3-left.php')) $layout = 'col-3-left';
  if(is_page_template('page-col-3-right.php')) $layout = 'col-3-right';

  // override again if 'layout' custom field is present
  if (is_single() || is_page()):
   global $post;
   $lcf = get_post_meta($post->ID, 'layout', true);
   if($lcf) $layout = $lcf;
  endif;

  return $layout;
}

// Generates semantic classes for BODY element - based on the sandbox theme
function mystique_body_class($class = '') {
  global $wp_query, $current_user, $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

  // Generic semantic classes for what type of content is displayed
  is_front_page()  ? $classes[] = 'home'       : null; // For the front page, if set
  is_home()        ? $classes[] = 'blog'       : null; // For the blog posts page, if set
  is_archive()     ? $classes[] = 'archive'    : null;
  is_date()        ? $classes[] = 'date'       : null;
  is_search()      ? $classes[] = 'search'     : null;
  is_attachment()  ? $classes[] = 'attachment' : null;
  is_404()         ? $classes[] = 'not-found'  : null; // CSS does not allow a digit as first character

  // Special classes for BODY element when a single post
  if (is_single()):
    $postname = $wp_query->post->post_name;

    the_post();

    // Adds 'single' class and class with the post ID
    $classes[] = 'single-post title-' . $postname;

    // Adds category classes for each category on single posts
    if ($cats = get_the_category()) foreach ($cats as $cat) $classes[] = 'category-'.$cat->slug;

    // Adds tag classes for each tags on single posts
    if ($tags = get_the_tags()) foreach ($tags as $tag) $classes[] = 'tag-'.$tag->slug;

    // Adds author class for the post author
    $classes[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta('login')));
    rewind_posts();

  elseif (is_author()):	// Author name classes for BODY on author archives
    $author = $wp_query->get_queried_object();
    $classes[] = 'author';
    $classes[] = 'author-' . $author->user_nicename;

  elseif (is_category()):	// Category name classes for BODY on category archvies
    $cat = $wp_query->get_queried_object();
    $classes[] = 'category';
    $classes[] = 'category-' . $cat->slug;

  elseif (is_tag()):	// Tag name classes for BODY on tag archives
    $tags = $wp_query->get_queried_object();
    $classes[] = 'tag';
    $classes[] = 'tag-' . $tags->slug;

  elseif (is_page()): 	// Page author for BODY on 'pages'
    $pagename = $wp_query->post->post_name;
    $pageID =  $wp_query->post->ID;
    $page_children = wp_list_pages("child_of=$pageID&echo=0");
    the_post();
    $classes[] = 'single-page page-' . $pagename;
    $classes[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));
    // Checks to see if the page has children and/or is a child page; props to Adam
    if ($page_children) $classes[] = 'level-parent';
    if ($wp_query->post->post_parent) $classes[] = 'level-child';
    rewind_posts();

  elseif (is_search()): 	// Search classes for results or no results
    the_post();
    if (have_posts()) $classes[] = 'search-results'; else $classes[] = 'search-no-results';
    rewind_posts();
  endif;

  // layout type
  $classes[] = mystique_layout_type();

  $classes[] = get_mystique_option('page_width');

  // For when a visitor is logged in while browsing
  if ($current_user->ID) $classes[] = 'loggedin';

    // detect browser
  if($is_lynx) $browser = 'lynx';
  elseif($is_gecko) $browser = 'gecko';
  elseif($is_opera) $browser = 'opera';
  elseif($is_NS4) $browser = 'ns4';
  elseif($is_safari) $browser = 'safari';
  elseif($is_chrome) $browser = 'chrome';
  elseif($is_IE) $browser = 'ie';
  else $browser = 'unknown';
  if($is_iphone) $browser .= '-iphone';

  $classes[] = 'browser-'.$browser;

  // user classes
  if (!empty($class)):
   if (!is_array($class)) $class = preg_split('#\s+#', $class);
   $classes = array_merge($classes, $class);
  endif;

  $class = join(' ', apply_filters('body_class', $classes));

  echo $class;
}

function mystique_post_class($class = '') {
  global $post;
  static $post_alt;


  // add hentry for microformats compliance and the post type
  $classes = array('hentry', $post->post_type);

  // post alt
  $classes[] = 'post-' . ++$post_alt;
  $classes[] = ($post_alt % 2) ? 'odd' : 'even alt';

  // author
  $classes[] = 'author-'.sanitize_html_class(get_the_author_meta('user_nicename'), get_the_author_meta('ID'));

  if(!is_single() && get_post_meta($post->ID, 'asides', true)) $classes[] = 'asides'; // asides
  elseif (is_home() && is_sticky()) $classes[] = 'sticky';  // sticky (only on home/blog page)

  // password-protected?
  if (post_password_required()) $classes[] = 'protected';

  // post category & tags */
  if ('post' == $post->post_type)
   foreach (array('category', 'post_tag') as $tax)
    foreach ((array)get_the_terms($post->ID, $tax) as $term)
     if (!empty($term->slug)) $classes[] = $tax . '-' . sanitize_html_class($term->slug, $term->term_id);

  // user classes
  if (!empty($class)):
   if (!is_array($class)) $class = preg_split('#\s+#', $class);
   $classes = array_merge($classes, $class);
  endif;

  // join all and output them
  $class = join(' ', $classes);
  echo apply_filters("mystique_post_class", $class);
}


function escape_string_for_regex($str){
 //All regex special chars (according to arkani at iol dot pt below):
 // \ ^ . $ | ( ) [ ]
 // * + ? { } ,
 $patterns = array('/\//', '/\^/', '/\./', '/\$/', '/\|/',
                   '/\(/', '/\)/', '/\[/', '/\]/', '/\*/', '/\+/',
                   '/\?/', '/\{/', '/\}/', '/\,/');
 $replace = array('\/', '\^', '\.', '\$', '\|', '\(', '\)',
                  '\[', '\]', '\*', '\+', '\?', '\{', '\}', '\,');
 return preg_replace($patterns,$replace, $str);
}


function get_first_image($post) {
 $first_img = '';
 $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
 $first_img = $matches [1][0];
 return $first_img;
}

// check if sidebar has widgets
function is_sidebar_active($index = 1) {
  global $wp_registered_sidebars;

  if (is_int($index)): $index = "sidebar-$index";
  else :
  	$index = sanitize_title($index);
  	foreach ((array) $wp_registered_sidebars as $key => $value):
    	if (sanitize_title($value['name']) == $index):
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

function mystique_curPageURL() {
  $request = esc_url($_SERVER["REQUEST_URI"]);

  // wp-themes fake request url fix :)
  if (strpos($_SERVER["SERVER_NAME"], 'wp-themes.com') !== false) $request = str_replace($request, '/wordpress/', '/');

  $pageURL = (is_ssl() ? 'https' : 'http').'://';
  if ($_SERVER["SERVER_PORT"] != "80") $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$request; else $pageURL .= $_SERVER["SERVER_NAME"].$request;

  if (false === strpos(get_option('home'), '://www.')) $pageURL = str_replace('://www.', '://', $pageURL);
  if (false !== strpos(get_option('home'), '://www.') && false === strpos($pageURL, '://www.')) $pageURL = str_replace('://', '://www.', $pageURL);

  return $pageURL;
}

// experimental
function mystique_compress_html(){
  function remove_spaces($buffer){
    $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
    $replace = array('>','<','\\1');
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
  }
  if(!@ob_start("ob_gzhandler")) ob_start('remove_spaces');
}



function mystique_meta_description() {
  global $wp_query;
  if (is_home()): $description = get_bloginfo('description');
  elseif (is_singular()):
    if(function_exists('get_metadata')): $description = get_metadata('post', $wp_query->post->ID, 'description', true); endif;
    if (empty($description) && is_front_page()):
      $description = get_bloginfo('description');
    elseif (empty($description)):
      $description = get_post_field('post_excerpt', $wp_query->post->ID);
    endif;
  elseif (is_archive()):
   if (is_author()):
     $description = get_the_author_meta('description', get_query_var('author'));
   elseif (is_category() || is_tag() || is_tax()):
     $description = term_description('', get_query_var('taxonomy'));
   endif;
  endif;

  if (!empty($description) && get_mystique_option('seo')) echo '<meta name="description" content="'.str_replace(array("\r", "\n", "\t"),'',esc_attr(strip_tags($description))).'" />'."\n";
}


function mystique_comment_class($class = '') {
  global $post, $comment;
  $classes = get_comment_class();
  if (get_option('show_avatars')) $classes[] = 'withAvatars';

  if ($comment->user_id > 0):
    $user = new WP_User($comment->user_id);
    if (is_array($user->roles))
     foreach ($user->roles as $role) $classes[] = "role-{$role}";
    $classes[] = 'user-'.sanitize_html_class($user->user_nicename, $user->user_id);
  else:
    $classes[] = 'reader name-'.get_comment_author();
  endif;

  // user classes
  if (!empty($class)):
   if (!is_array($class)) $class = preg_split('#\s+#', $class);
   $classes = array_merge($classes, $class);
  endif;

  echo join(' ', apply_filters("comment_class", $classes));
}


function mystique_get_page_by_slug($page_slug) {
  $page = get_page_by_path($page_slug);
  if ($page) return get_permalink($page->ID); else return false;
}


function mystique_get_avatar($email, $size = 48){
 if(date('m-d') == '04-01') return '<img class="avatar" src="http://unicornify.appspot.com/avatar/'.md5($email).'?s='.$size.'" alt="one trick pony" width="'.$size.'" height="'.$size.'" />'; // 1st april? :)
 else return get_avatar($email, $size);
}

// list pings
function mystique_list_pings($comment, $args, $depth) {
 $GLOBALS['comment'] = $comment;
 ?>
 <li class="ping" id="comment-<?php comment_ID(); ?>"><a class="websnapr" href="<?php comment_author_url();?>" rel="nofollow"><?php comment_author(); ?></a>
<?php
} // </li> is added by WP


// list comments
function mystique_list_comments($comment, $args, $depth) {
 $GLOBALS['comment'] = $comment;
 global $commentcount;

 if(!$commentcount) $commentcount = 0; ?>

  <!-- comment entry -->
  <li class="<?php mystique_comment_class(); ?>" id="comment-<?php comment_ID() ?>">
    <div class="comment-head <?php mystique_comment_class(); ?>">

      <?php if(get_option('show_avatars')): ?><div class="avatar-box"><?php echo mystique_get_avatar($comment->comment_author_email, 48); ?></div><?php endif; ?>
      <div class="author">
       <?php
        if (get_comment_author_url()) $authorlink='<a class="comment-author" id="comment-author-'.get_comment_ID().'" href="'.get_comment_author_url().'" rel="nofollow">'.get_comment_author().'</a>';
        else $authorlink='<b class="comment-author" id="comment-author-'.get_comment_ID().'">'.get_comment_author().'</b>';

        $authorlink = apply_filters("mystique_comment_author_link", $authorlink); ?>

        <span class="by"><?php printf(__('%1$s written by %2$s', 'mystique'), '<a class="comment-id" href="#comment-'.get_comment_ID().'">#'.++$commentcount.'</a>', $authorlink); ?> </span>
        <br />
        <?php echo mystique_timeSince(abs(strtotime($comment->comment_date . " GMT"))); ?>
      </div>

      <div class="controls bubble">
        <?php if (get_mystique_option('jquery') && (comments_open())): ?>
           <?php if(get_option('thread_comments')): ?>
           <a class="reply" id="reply-to-<?php echo get_comment_ID(); ?>" href="<?php echo esc_url(add_query_arg('replytocom', $comment->comment_ID)); ?>#respond"><?php _e("Reply","mystique"); ?></a>
           <?php endif; ?>
           <a class="quote" title="<?php _e('Quote','mystique'); ?>" href="#respond"><?php _e('Quote','mystique'); ?></a>
        <?php endif; ?>
        <?php edit_comment_link('Edit','',''); ?>
      </div>
    </div>
    <div class="comment-body clear-block" id="comment-body-<?php comment_ID() ?>">
      <?php if ($comment->comment_approved == '0'): ?><p class="error"><?php _e('Your comment is awaiting moderation.','mystique'); ?></p><?php endif; ?>
      <div class="comment-text"><?php comment_text(); ?></div>
      <a id="comment-reply-<?php comment_ID() ?>"></a>
    </div>
<?php
}  // </li> is added by WP
?>