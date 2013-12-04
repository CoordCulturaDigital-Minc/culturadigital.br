<?php
if ( function_exists('register_sidebar') )
    register_sidebar();	
    
    
// Add WP 3.0 Menu Theme Support
if ( function_exists( 'add_theme_support' ) ) { 
	add_theme_support( 'nav-menus' );
	add_action( 'init', 'register_gpp_menus' );

	function register_gpp_menus() {
		register_nav_menus(
			array(
				'main-menu' => __( 'Main Menu' )
			)
		);
	}
}

// Make Menu Support compatible with earlier WP versions
function theme_nav() {		
	if ( function_exists( 'wp_nav_menu' ) )
		wp_nav_menu( 'sort_column=menu_order&container_class=menu&menu_class=&menu_location=main-menu&fallback_cb=gpptheme_nav_fallback' );
	else
		theme_nav_fallback();
}

// Create our GPP Fallback Menu
function theme_nav_fallback() {
    wp_page_menu( 'show_home=1&menu_class=menu' );
}


//Auto Load JS
function theme_load_js() {
    if (is_admin()) return;
   	wp_enqueue_script('jquery');
   	wp_enqueue_script('superfish', get_bloginfo('template_directory').'/includes/js/superfish.js', array( 'jquery' ) );
}
add_action('init', 'theme_load_js');

function load_dom_ready_js() {
	$doc_ready_script="";
	$doc_ready_script .= '
	<script type="text/javascript">
		jQuery(document).ready(function(){ 
        	jQuery("div.menu ul").superfish(); 
    	}); 
	</script>';
	echo $doc_ready_script;
}

add_action('wp_head', 'load_dom_ready_js');
	
	
	

// Custom Theme Options

$themename = "simpleX";
$shortname = "sx";
$options = array (    
    array(  "name" => "Choose a Style",
            "id" => $shortname."_choose_a_style",
            "std" => "Simple Default",
            "type" => "select",
			"options" => array("Simple Default", "Simple Autumn", "Simple WordPress")
	),
);

function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=functions.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

            header("Location: themes.php?page=functions.php&reset=true");
            die;

        }
    }

    add_theme_page($themename." Options", "simpleX Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' options saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' options reset.</strong></p></div>';
    
?>
<div class="wrap">
<h2><?php echo $themename; ?> Customization</h2>

<form method="post">
<br/>
<table class="optiontable">

<?php foreach ($options as $value) { 
    
if ($value['type'] == "text") { ?>
        
<tr valign="top"> 
    <th scope="row"><?php echo $value['name']; ?>:</th>
    <td>
        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
    </td>
</tr>

<?php } elseif ($value['type'] == "select") { ?>

    <tr valign="top"> 
        <th scope="row"><?php echo $value['name']; ?>:</th>
        <td>
            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php foreach ($value['options'] as $option) { ?>
                <option<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>

<?php 
} 
}
?>

</table>

<p class="submit">
<input name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>

<?php
}

function mytheme_wp_head() { 
	global $options; 
	foreach ($options as $value) {
        if (get_option( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_option( $value['id'] ); }
		
		if($sx_choose_a_style == "Simple Default") {
			$theme = "css/default.css";
		}
		else if($sx_choose_a_style == "Simple Autumn") {
			$theme = "css/autumn.css";
		}
		else if($sx_choose_a_style == "Simple WordPress") {
			$theme = "css/wordpress.css";
		}	
       
?>
<link href="<?php bloginfo('template_directory'); ?>/<?php echo $theme; ?>" rel="stylesheet" type="text/css" />
<?php  }
}

add_action('wp_head', 'mytheme_wp_head');
add_action('admin_menu', 'mytheme_add_admin'); ?>