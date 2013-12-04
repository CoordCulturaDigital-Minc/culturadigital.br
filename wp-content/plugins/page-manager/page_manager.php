<?php
/*
Plugin Name: Page manager
Plugin URI: http://plugins.trendwerk.nl/documentation/page-manager/
Description: Allow users to order pages easily in the page overview and also add some clarity to the overview. Also includes the Show more pages plugin.
Version: 1.1
Author: Ontwerpstudio Trendwerk
Author URI: http://plugins.trendwerk.nl/
*/

//when adding a new page, it should be last
function add_last_volgorde($postId) {
	global $wpdb;
	if(!wp_is_post_revision($postId)) {
		$the_post = get_post($postId);
		if($the_post->post_status == 'draft') {
			$lastMenu = $wpdb->get_results("SELECT * FROM ".$wpdb->posts." ORDER BY menu_order DESC LIMIT 0,1");
			$next_menuorder = $lastMenu[0]->menu_order + 1;
			$wpdb->query("UPDATE ".$wpdb->posts." SET menu_order='".$next_menuorder."' WHERE ID='".$postId."'");
		}
	}
}

add_action('save_post','add_last_volgorde');

function keep_last_volgorde($postId) {
	global $wpdb;
	if(!wp_is_post_revision($postId)) {
		$the_post = get_post($postId);
		if($the_post->menu_order == 0) {
			$lastMenu = $wpdb->get_results("SELECT * FROM ".$wpdb->posts." ORDER BY menu_order DESC LIMIT 0,1");
			$next_menuorder = $lastMenu[0]->menu_order + 1;
			$wpdb->query("UPDATE ".$wpdb->posts." SET menu_order='".$next_menuorder."' WHERE ID='".$postId."'");
		}
	}
}

add_action('publish_page','keep_last_volgorde');

function add_verplaats_css() {
	$pluginPath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
	echo '
	<style type="text/css">
		.column-verplaats {
			width: 100px;
		}
		.column-verplaats div {
			margin-top: 9px;
			margin-left: 35px;
			cursor: move;
		}
		.alternate, .iedit {
			background-color: #eee;	
		}
		.childClass {
			background-color: #fff;
		}
	</style>
	';
}
add_action('admin_head-edit-pages.php','add_verplaats_css');

function add_verplaats_js() {
	$pluginPath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
	echo '<script type="text/javascript" src="'.$pluginPath.'js/verplaats.js"></script>';	
}
add_action('admin_head-edit-pages.php','add_verplaats_js');

function add_verplaats_kolom($huidig) {
	unset($huidig['date']);
	unset($huidig['comments']);
	$huidig['verplaats'] = __('Change order',"trendwerk");
	$huidig['title'] = __('Title',"trendwerk");
	$huidig['author'] = __('Author',"trendwerk");
	$huidig['comments'] = __('<div class="vers"><img src="images/comment-grey-bubble.png" alt=""/></div>');
	$huidig['date'] = __('Date',"trendwerk");
	return $huidig;
}

add_filter('manage_pages_columns','add_verplaats_kolom');

function add_image_in_kolom($columnName) {
	if(!$_GET['s'] && !$_GET['post_status']) {
		if($columnName == 'verplaats') {
			$isChild = false;
			$showMovetool = true;
			
			$pluginPath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
			//is this page a subpage?
			$pageInfo = get_page(get_the_ID());
			$parent = $pageInfo->post_parent;
			
			//how many childs has the parent of this page?
			if($parent) {
				$isChild = true;
				$childs = get_pages('child_of='.$parent);
				if(count($childs) < 2) {
					$showMovetool = false;
				}
			}
			
			//is this page also a parent?
			$childs = get_pages('child_of='.get_the_ID());
			if(count($childs) > 0) {
				$isParent = true;
			}
			
			//determine the paddingleft
			$newPageId = get_the_ID();
			for($i=0;$i>=0;$i++) {
				$upperPage = get_page($newPageId);
				$newPageId = $upperPage->post_parent;
				if(!$newPageId) {
					$totalSubPages = $i;
					break;
				}
			}
			$paddingLeftChilds = $totalSubPages * 25;
			if($paddingLeftChilds == 0) {
				$paddingLeftChilds = 7;
			}
			
			if($showMovetool) {
				$addClass="";
				if($parent) {
					$addClass='isChild'.$parent;
				}
				if($isParent) {
					$addClass .= ' isParent';
				}
				echo '<div style="background:url('.$pluginPath.'images/move.png); height: 19px; width: 19px;" class="childPad'.$paddingLeftChilds.' movePageIcons '.$addClass.'" onmousedown="startDrag(this,event);" alt="'.__("Change order","trendwerk").'" /></div>';
			} else {
				echo '<div class="childPad'.$paddingLeftChilds.' movePageIcons isChild'.$parent.'"></div>';
			}
		}
	}
}

add_action('manage_pages_custom_column','add_image_in_kolom');


function orderDoorgeven() { //ajax function
	global $wpdb;

	$startNumber = $_POST['startNumber'];
	$theOrder = $_POST['theOrder'];
	$theOrder = explode(',',$theOrder);
	
	for($i=0;$i<count($theOrder);$i++) {
		$menuOrderNr = $i + $startNumber;
		$wpdb->query("UPDATE $wpdb->posts SET menu_order='".$menuOrderNr."' WHERE ID='".$theOrder[$i]."'");
	}
}
add_action('wp_ajax_orderDoorgeven', 'orderDoorgeven' );


function voeg_js_sackheader_toe() {
//add the 'sack' library
  wp_print_scripts( array( 'sack' ));

//javascript for ajax
?>
<script type="text/javascript">
//<![CDATA[
function orderDoorgeven(theOrder) {
	var pageNumber = getURLParam('pagenum');
	if(!pageNumber) {
		pageNumber = 1;
	}
	var startNumber = (pageNumber-1) * 20;
	var orderStr = theOrder.join(',');
	var geefDoor = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );
	
	geefDoor.execute = 1;
	geefDoor.method = 'POST';
	geefDoor.setVar( "action", "orderDoorgeven" );
	geefDoor.setVar( "theOrder", orderStr );
	geefDoor.setVar( "startNumber", startNumber+1 );
	geefDoor.encVar( "cookie", document.cookie, false );
	geefDoor.onError = function() { alert('There was an error ordering the pages.'); };
	geefDoor.runAJAX();
	
	return true;
}
//]]>
</script>
<?php
}

add_action('admin_print_scripts', 'voeg_js_sackheader_toe' );

?>