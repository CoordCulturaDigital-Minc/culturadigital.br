<?php

// Filter away the default scripts loaded with Thematic
function childtheme_head_scripts() {
    // Abscence makes the heart grow fonder
}
add_filter('thematic_head_scripts','childtheme_head_scripts');

// Add a link to the footer for credit
function childtheme_theme_link($themelink) {
    return '<a class="child-theme-link" href="http://themeshaper.com/acamas-theme-clarity-elegance-power/" title="Acamas Theme" rel="designer">Acamas Theme</a>';
}
add_filter('thematic_theme_link', 'childtheme_theme_link');

//Look for variant.css in wp-content/acamas-variant
function acamas_variantcss() {
    // Pre-2.6 compatibility
    if ( !defined('WP_CONTENT_URL') )
        define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
    if ( !defined('WP_CONTENT_DIR') )
        define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

    // Guess the location
    $variantcss_path = WP_CONTENT_DIR.'/acamas-variant/variant.css';
    $variantcss_url = WP_CONTENT_URL.'/acamas-variant/variant.css';

    if (file_exists($variantcss_path)) { ?>
        <!-- Custom CSS -->
    	<link rel="stylesheet" type="text/css" href="<?php bloginfo('wpurl'); ?>/wp-content/acamas-variant/variant.css" />

    <?php } elseif (file_exists($variantcss_url)) { ?>
        <!-- Custom CSS -->
    	<link rel="stylesheet" type="text/css" href="<?php bloginfo('wpurl'); ?>/wp-content/acamas-variant/variant.css" />
    <?php }
}
add_action('wp_head', 'acamas_variantcss');

// Fix IE6.
function childtheme_ieupgrade() { ?>
    <!--[if IE 6]>
    <link rel="stylesheet" type="text/css" href="<?php echo dirname( get_bloginfo('stylesheet_url') ) ?>/ie6.css" />
    <![endif]-->
<?php }
add_action('wp_head', 'childtheme_ieupgrade');

// Theme Options

$childthemename = "Acamas MC";
$childshortname = "acmsmc";
$childoptions = array();

function acamas_options() {
    global $childthemename, $childshortname, $childoptions;
		$acmsmc_categories_obj = get_categories('hide_empty=0');
		$acmsmc_categories = array();
		foreach ($acmsmc_categories_obj as $acmsmc_cat) {
				$acmsmc_categories[$acmsmc_cat->cat_ID] = $acmsmc_cat->cat_name;
		}
		$categories_std = array_unshift($acmsmc_categories, "Select a category:");

		$childoptions = array (

				array(	"name" => "Featured Category",
								"desc" => "Select a category of posts to be uniquely featured in this theme.",
								"id" => $childshortname."_featurecategory",
								"std" => $categories_std,
								"type" => "select",
								"options" => $acmsmc_categories),

		  );
}
add_action('init', 'acamas_options');

// Make a Theme Options Page

function childtheme_add_admin() {

    global $childthemename, $childshortname, $childoptions;

    if ( $_GET['page'] == basename(__FILE__) ) {

        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($childoptions as $childvalue) {
                    update_option( $childvalue['id'], $_REQUEST[ $childvalue['id'] ] ); }

                foreach ($childoptions as $childvalue) {
                    if( isset( $_REQUEST[ $childvalue['id'] ] ) ) { update_option( $childvalue['id'], $_REQUEST[ $childvalue['id'] ]  ); } else { delete_option( $childvalue['id'] ); } }

                header("Location: themes.php?page=functions.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($childoptions as $childvalue) {
                delete_option( $childvalue['id'] ); }

            header("Location: themes.php?page=functions.php&reset=true");
            die;

        }
    }

    add_theme_page($childthemename." Options", "$childthemename Options", 'edit_themes', basename(__FILE__), 'childtheme_admin');

}

function childtheme_admin() {

    global $childthemename, $childshortname, $childoptions;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$childthemename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$childthemename.' settings reset.</strong></p></div>';

?>
<div class="wrap">
<h2><?php echo $childthemename; ?> Options</h2>

<form method="post">

<table class="form-table">

<?php foreach ($childoptions as $childvalue) {

	switch ( $childvalue['type'] ) {
		case 'text':
		?>
		<tr valign="top">
		    <th scope="row"><?php echo $childvalue['name']; ?>:</th>
		    <td>
		        <input name="<?php echo $childvalue['id']; ?>" id="<?php echo $childvalue['id']; ?>" type="<?php echo $childvalue['type']; ?>" value="<?php if ( get_settings( $childvalue['id'] ) != "") { echo get_settings( $childvalue['id'] ); } else { echo $childvalue['std']; } ?>" />
			    <?php echo $childvalue['desc']; ?>
		    </td>
		</tr>
		<?php
		break;

		case 'select':
		?>
		<tr valign="top">
	        <th scope="row"><?php echo $childvalue['name']; ?>:</th>
	        <td>
	            <select name="<?php echo $childvalue['id']; ?>" id="<?php echo $childvalue['id']; ?>">
	                <?php foreach ($childvalue['options'] as $option) { ?>
	                <option<?php if ( get_settings( $childvalue['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $childvalue['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
	                <?php } ?>
	            </select>
			    <?php echo $childvalue['desc']; ?>
	        </td>
	    </tr>
		<?php
		break;

		case 'textarea':
		$ta_options = $childvalue['options'];
		?>
		<tr valign="top">
	        <th scope="row"><?php echo $childvalue['name']; ?>:</th>
	        <td>
			    <?php echo $childvalue['desc']; ?>
				<textarea name="<?php echo $childvalue['id']; ?>" id="<?php echo $childvalue['id']; ?>" cols="<?php echo $ta_options['cols']; ?>" rows="<?php echo $ta_options['rows']; ?>"><?php
				if( get_settings($childvalue['id']) != "") {
						echo stripslashes(get_settings($childvalue['id']));
					}else{
						echo $childvalue['std'];
				}?></textarea>
	        </td>
	    </tr>
		<?php
		break;

		case "radio":
		?>
		<tr valign="top">
	        <th scope="row"><?php echo $childvalue['name']; ?>:</th>
	        <td>
	            <?php foreach ($childvalue['options'] as $key=>$option) {
				$radio_setting = get_settings($childvalue['id']);
				if($radio_setting != ''){
		    		if ($key == get_settings($childvalue['id']) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $childvalue['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
	            <input type="radio" name="<?php echo $childvalue['id']; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><?php echo $option; ?><br />
	            <?php } ?>
	        </td>
	    </tr>
		<?php
		break;

		case "checkbox":
		?>
			<tr valign="top">
		        <th scope="row"><?php echo $childvalue['name']; ?>:</th>
		        <td>
		           <?php
						if(get_settings($childvalue['id'])){
							$checked = "checked=\"checked\"";
						}else{
							$checked = "";
						}
					?>
		            <input type="checkbox" name="<?php echo $childvalue['id']; ?>" id="<?php echo $childvalue['id']; ?>" value="true" <?php echo $checked; ?> />
		            <?php  ?>
			    <?php echo $childvalue['desc']; ?>
		        </td>
		    </tr>
			<?php
		break;

		default:

		break;
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

<p><?php _e('For more information about this theme, <a href="http://themeshaper.com">visit ThemeShaper</a>. If you have any questions, visit the <a href="http://themeshaper.com/forums/">ThemeShaper Forums</a>.', 'thematic'); ?></p>

<?php
}

add_action('admin_menu' , 'childtheme_add_admin');


// Create a featured content area on the home page

function acamas_featured_content() {
    if (is_home()) { ?>

        <?php
            global $childoptions;
            foreach ($childoptions as $childvalue) {
            if (get_settings( $childvalue['id'] ) === FALSE) { $$childvalue['id'] = $childvalue['std']; }
            else { $$childvalue['id'] = get_settings( $childvalue['id'] ); }
            }
        ?>

        <div id="home-insert">

            <!-- The featured content only appears on the front of this blog -->
            <div id="featured-content" class="featured">

                <div id="latest-feature">
                    <h3 class="feature-header">Marco Civil</h3>
                <?php $my_query = new WP_Query("category_name=$acmsmc_featurecategory&showposts=1"); ?>
                <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                <?php $do_not_duplicate = $post->ID; ?>
                    <div id="feature-content">
                    	<?php the_excerpt(''.__('Read More <span class="meta-nav">&raquo;</span>', 'thematic').'') ?>
                	</div>
                	<h2 id="latest-feature-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?>opa<span class="meta-nav"> &raquo;</span></a></h2>
                <?php endwhile; ?>
                </div><!-- #latest-feature -->
<? if ( TRUE ): ?>
                <div id="recent-features">
                    <h3 class="feature-header"><?php _e('Destaques Recentes', 'thematic') ?></h3>
<?php $recentPosts = new WP_Query(); $recentPosts->query("category_name=$acmsmc_featurecategory&showposts=5&offset=1"); ?>
                    <ul>
                        <?php while ($recentPosts->have_posts()) : $recentPosts->the_post(); ?>
                        <li class="recent-feature-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'thematic'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></li>
                        <?php endwhile; ?>
                    <?php $acmsmc_featurecategory = get_cat_id($acmsmc_featurecategory); ?>
                        <li id="feature-archives-link"><a href="<?php echo(get_category_link($acmsmc_featurecategory));?>" title ="<?php _e('Outros Destaques', 'thematic') ?>" rel="nofollow"><?php _e('Ver outros destaques', 'thematic') ?><span class="meta-nav">&hellip</span></a></li>
                    </ul>
                </div><!-- #recent-features -->
<? endif ?>
            </div><!-- #featured-content -->

        </div><!-- #home-insert -->



    <?php }
}
add_action('thematic_belowheader','acamas_featured_content');

function acamasmc_breadcrumb()
{

	if( class_exists('bcn_breadcrumb_trail'))
	{
		//Make new breadcrumb object
		$bct = new bcn_breadcrumb_trail();
		$bct->opt['current_item_prefix'] = '<span class="current">';
		$bct->opt['current_item_suffix'] = '</span>';
		$bct->opt['archive_category_prefix'] = '';
		$bct->opt['archive_category_suffix'] = '';
		$bct->opt['home_title'] = 'In&iacute;cio';
		$bct->opt['separator'] = ' &raquo; ';

		$bct->fill();
		print '<div id="breadcrumb">';
		$bct->display();
		print '</div>';
	}

/*
	if ( function_exists('bcn_display') )
	{
		print '<div id="breadcrumb">';
		bcn_display();
		print '</div>';
	}
*/
}

add_action('thematic_belowheader','acamasmc_breadcrumb');

// Remove featured post category from home page loop
function myHomePostsFilter($query) {
    // Get my theme options
    global $childoptions;
    foreach ($childoptions as $childvalue) {
    if (get_settings( $childvalue['id'] ) === FALSE) { $$childvalue['id'] = $childvalue['std']; }
    else { $$childvalue['id'] = get_settings( $childvalue['id'] ); }
    }
    // Set my query
    $acmsmc_featurecategory = '-' . get_cat_id($acmsmc_featurecategory);
    if ($query->is_home) {
	 /* Exclui posts de consulta da home */
	 $cat = get_cat_ID("Consulta");
	 $query->set('cat', $acmsmc_featurecategory . ",-$cat");
    }
    return $query;
}
add_filter('pre_get_posts','myHomePostsFilter');

function acamasmc_search_form() {
	$search_form = "\n" . "\t";
	$search_form .= '<form id="searchform" method="get" action="' . get_bloginfo('home') .'">';
	$search_form .= "\n" . "\t" . "\t";
	$search_form .= '<div>';
	$search_form .= "\n" . "\t" . "\t". "\t";
	if (is_search()) {
		$search_form .= '<input id="s" name="s" type="text" value="' . wp_specialchars(stripslashes($_GET['s']), true) .'" size="32" tabindex="1" />';
	} else {
		$value = 'Digite e pressione ENTER';
		$value = apply_filters('search_field_value',$value);
		$search_form .= '<input id="s" name="s" type="text" value="' . $value . '" onfocus="if (this.value == \'' . $value . '\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'' . $value . '\';}" size="32" tabindex="1" />';
	}
	$search_form .= "\n" . "\t" . "\t". "\t";

	$search_submit = '<input id="searchsubmit" name="searchsubmit" type="submit" value="' . __('Search', 'thematic') . '" tabindex="2" />';

	$search_form .= apply_filters('thematic_search_submit', $search_submit);

	$search_form .= "\n" . "\t" . "\t";
	$search_form .= '</div>';

	$search_form .= "\n" . "\t";
	$search_form .= '</form>';

	return $search_form;
}

add_filter('thematic_search_form', 'acamasmc_search_form');


function acamasmc_logos() {
     $url = get_bloginfo("url");
     print "<div id=\"logos\">
<a href=\"$url\"><img src=\"/wp-content/themes/acamas_mc/banner-mc.gif\" alt=\"Marco Civil\" /></a>
</div>";
}
add_action('thematic_belowheader', 'acamasmc_logos', -1);

// Adds a home link to your menu
// http://codex.wordpress.org/Template_Tags/wp_page_menu
function acamasmc_menu_args($args) {
    $args = array(
        'show_home' => 'In&iacute;cio',
        'sort_column' => 'menu_order',
        'menu_class' => 'menu',
        'echo' => true
    );
    return $args;
}
add_filter('wp_page_menu_args','acamasmc_menu_args');



function acamasmc_category_page_title() {
	if ( is_category() )
	{
		$content .= '<h1 class="page-title">';
		$content .= single_cat_title('', FALSE);
		$content .= '</h1>' . "\n";
		$content .= '<div class="archive-meta">';
		if ( !(''== category_description()) )
			$content .= apply_filters('archive_meta', category_description());
		$content .= '</div>';

		return $content;
	}
}

add_action('thematic_page_title', 'acamasmc_category_page_title');

function acamasmc_doctitle() {
 // You don't want to change this one.
 $site_name = get_bloginfo('name');

 // But you like to have a different separator
 $separator = '&raquo;';


 // We will keep the original code
 if ( is_single() ) {
 $content = single_post_title('', FALSE);
 }
 elseif ( is_home() || is_front_page() ) {
 $content = get_bloginfo('description');
 }
 elseif ( is_page() ) {
 $content = single_post_title('', FALSE);
 }
 elseif ( is_search() ) {
 $content = __('Search Results for:', 'thematic');
 $content .= ' ' . wp_specialchars(stripslashes(get_search_query()), true);
 }
 elseif ( is_category() ) {
 $content = single_cat_title("", false);
 }
 elseif ( is_tag() ) {
 $content = __('Tag Archives:', 'thematic');
 $content .= ' ' . thematic_tag_query();
 }
 elseif ( is_404() ) {
 $content = __('Not Found', 'thematic');
 }
 else {
 $content = get_bloginfo('description');
 }

 if (get_query_var('paged')) {
 $content .= ' ' .$separator. ' ';
 $content .= 'Page';
 $content .= ' ';
 $content .= get_query_var('paged');
 }

 // until we reach this point. You want to have the site_name everywhere?
 // Ok .. here it is.
 $my_elements = array(
 'site_name' => $site_name,
 'separator' => $separator,
 'content' => $content
 );

 // and now we're reversing the array as long as we're not on home or front_page
 if (!( is_home() || is_front_page() )) {
 $my_elements = array_reverse($my_elements);
 }

 // And don't forget to return your new creation
 return $my_elements;

}
add_filter('thematic_doctitle', 'acamasmc_doctitle');




function acamasmc_postheader_postmeta()
{
	global $id, $post, $authordata;

	if ( !is_category() )
		return '';

	$pm = '<div class="rcomm">';

	$comms = get_approved_comments($post->ID);

	foreach ($comms as $c)
	{
		$pm .= "<div id=\"comment-$c->comment_ID\" class=\"comment\">\n";

		$pm .= '<div class="meta">';
		if ( $c->comment_author_url )
			$pm .= "<a href=\"$c->comment_author_url\">";
		$pm .= '<span class="author">' . $c->comment_author . '</span>';
		if ( $c->comment_author_url )
			$pm .= "</a>";
		$pm .= " escreveu:";
		$pm .= "</div>\n";

		$pm .= '<div class="content">';
		$pm .= "<a href=\"" .get_comment_link($c) . "\">";
		$pm .= $c->comment_content;
		$pm .= '</a>';
		$pm .= "</div>\n";

		$pm .= '</div>';
	}

	if ( $post->comment_status == 'open' )
	{
		$pm .= "<div class=\"links\">";
		$pm .= "<a href=\"" . get_permalink($post->ID) . "#comment\">Participar desta discuss&atilde;o</a>";
		$pm .= "</div>\n";
	}

	$pm .= '</div>';

/*
	$pm .= '<pre>' . var_export($comms, 1) . '</pre>';
	$pm .= '<pre>' . var_export($post, 1) . '</pre>';
*/

	return $pm;
}

add_filter('thematic_postheader_postmeta', 'acamasmc_postheader_postmeta');


function acamasmc_banner()
{
     print "
<div id=\"banner-mj\">
<img src=\"/wp-content/themes/acamas_mc/logo_sal_gov.jpg\" alt=\"\" />
</div>
";
}

add_action('thematic_header', 'acamasmc_banner', 1);

function remove_thematic_actions() {
     remove_action('thematic_header',
		   'thematic_blogtitle',
		   3);
     remove_action('thematic_header',
		   'thematic_blogdescription',
		   5);
}

add_action('init','remove_thematic_actions');

// Atualiza RSSs a cada 30min
add_filter('wp_feed_cache_transient_lifetime',
	create_function('$a', 'return 1800;') );

