<?php
wp_enqueue_script('jquery');// Include jquery
automatic_feed_links();

register_sidebar(array(
	'name'=>'sidebar',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h2 class="widgettitle">',
    'after_title' => '</h2>',
));

function attach_theme_settings() {
	$theme_root = dirname(__FILE__) . DIRECTORY_SEPARATOR;
	include_once($theme_root . 'lib/theme-options/theme-options.php');
	include_once($theme_root . 'options/theme-options.php');
	include_once($theme_root . 'options/navigation-options.php');
	// include_once('options/theme-widgets.php');
}

attach_theme_settings();

function print_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class('comment'); ?>>
		<div class="comment-body" id="comment-<?php comment_ID() ?>">
			<?php echo get_avatar($comment, 70, get_bloginfo('stylesheet_directory') . '/images/avatar.gif'); ?>
			<p class="author">
				<?php comment_author_link() ?>
			</p>
			<p class="comment-meta">
				<?php comment_date() ?> at <?php comment_time() ?>
			</p>
			<div class="comment-content">
				<?php if ($comment->comment_approved == '0') : ?>
			        <em><?php _e('Your comment is awaiting moderation.') ?></em><br />
			    <?php endif; ?>
			    
				<?php comment_text() ?>
				<div class="alignleft"><?php edit_comment_link(__('(Edit)'),'  ','') ?></div>
				
			</div>
			<div class="reply">
		        <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		    </div>
			<div class="cl">&nbsp;</div>
		</div>
	<?php
}

# crawls the pages tree up to top level page ancestor 
# and returns that page as object
function get_page_ancestor($page_id) {
    $page_obj = get_page($page_id);
    while($page_obj->post_parent!=0) {
        $page_obj = get_page($page_obj->post_parent);
    }
    return get_page($page_obj->ID);
}

$nav_limit = 10;
function choco_print_pages_nav() {
	global $post, $nav_limit;
	$current_page_ancestor = get_page_ancestor($post->ID);
	$excluded = get_option('header_nav_pages_exclude');
	if ($excluded) {
		$excluded_pages = "exclude=" . implode(',', $excluded);
	} else {
		$excluded_pages = '';
	}
	
    $pages = get_pages("parent=0&child_of=0&sort_column=menu_order&$excluded_pages");
    
    $html = '';
    
    $i = 0;
    foreach ($pages as $page) {
        $subpages = get_pages('child_of=' . $page->ID . '&parent=' . $page->ID . '&sort_column=menu_order');
        $has_dropdown = !empty($subpages) && get_option('enable_dropdown', 'yes')=='yes';
    	$classes = array();
    	if (is_page() && $current_page_ancestor->ID==$page->ID) {
    		$classes[] = "current_page_item";
    	}
    	
    	if ($has_dropdown) {
    		$classes[] = "has_dropdown";
    	}
    	$html .= '<li class="' . implode(' ', $classes) . '">';
    	$html .= '<a href="' . get_permalink($page->ID) . '"><span>';
    		
		if ($has_dropdown) {
			$html .= '<span>' . apply_filters('the_title', $page->post_title) . '</span>';
		} else {
			$html .= apply_filters('the_title', $page->post_title);
		}
		
    	$html .= '</span></a>';
    	if ($has_dropdown) {
    		$html .= choco_get_pages_nav_dropdown($page, intval(get_option('dropdown_depth', 2)));
    	}
    	
    	$html .= '</li>';
    	if ($i==$nav_limit) {
    		break;
    	}
    	$i++;
    }
    echo $html;
}
function choco_get_pages_nav_dropdown($page, $max_levels, $current_level=1) {
	$html = '';
	$subpages = get_pages('child_of=' . $page->ID . '&parent=' . $page->ID . '&sort_columnm=enu_order');
	if (count($subpages) > 0) {
		$html .= '<div class="dropdown dd-level-' . $current_level . '"><ul>';
    	foreach ($subpages as $subpage) {
    		$html .= '<li>';
    		$html .= '<a href="' . get_permalink($subpage->ID) . '">' . apply_filters('the_title', $subpage->post_title) . '</a>';
    		if ($current_level < $max_levels) {
    			$html .= choco_get_pages_nav_dropdown($subpage, $max_levels, $current_level + 1);
    		}
    		$html .= '</li>';
    	}
		$html .= '</ul></div>';
	}
	return $html;
}
function choco_print_categories_nav() {
	global $nav_limit;
    $excluded = get_option('header_nav_categories_exclude');
	if ($excluded) {
		$excluded_cats = "exclude=" . implode(',', $excluded);
	} else {
		$excluded_cats = '';
	}
	
    $categories = get_categories("$excluded_cats");
	
    $html = '';
    $i=0;
    foreach ($categories as $category) {
    	$classes = array();
    	if (is_category($category->term_id)) {
    		$classes[] = "current_page_item";
    	}
    	$html .= '<li class="' . implode(' ', $classes) . '"><a href="' . get_category_link($category->term_id) . '"><span>' . $category->name . '</span></a></li>';
    	
    	if ($i==$nav_limit) {
    		break;
    	}
    	$i++;
    	
    }
    echo $html;
}
function choco_print_header() {
    $nav_type = get_option('nav_type');
    if ($nav_type=='pages') {
    	choco_print_pages_nav();
    } else if ($nav_type=='categories') {
    	choco_print_categories_nav();
    } else /*shouldn't happen*/ {
    	choco_print_pages_nav();
    }
}
function choco_get_body_style() {
    $bg_color = get_option('background_color', '#3A2820');
    $bg_image = get_option('background_image');
    $bg_repeat = get_option('background_repeat');
    
    $style = 'background: ' . $bg_color;
    if ($bg_image) {
    	$style .= ' url(' . $bg_image . ') center top ' . $bg_repeat;
    }
    $style .= ';';
    
    return $style;
}

function choco_get_theme_path() {
	$map = array(
		'Default Scheme'=>'default',
		'Dark Scheme'=>'darkgray',
		'Red Scheme'=>'red',
	);
	$theme = get_option('choco_color_scheme');
	if (isset($map[$theme])) {
		return $map[$theme];
	}
	return 'default';
}
?>