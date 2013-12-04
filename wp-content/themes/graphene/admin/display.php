<?php
/**
 * This function generates the theme's Display Options page in Wordpress administration.
 *
 * @package WordPress
 * @subpackage Graphene
 * @since Graphene 1.0.8
*/
function graphene_options_display(){
	
	// Initialise messages array
	$errors = array();
	$messages = array();
	
	// Updates the database
	if (isset($_POST['graphene_submitted']) && $_POST['graphene_submitted'] == true) {		
		
		// Process the header display options
		$light_header = (!empty($_POST['light_header'])) ? $_POST['light_header'] : false ;
		
		// Process the posts display options
		$hide_post_author = (!empty($_POST['hide_post_author'])) ? $_POST['hide_post_author'] : false ;
		$hide_post_date = (!empty($_POST['hide_post_date'])) ? $_POST['hide_post_date'] : false ;
		$hide_post_commentcount = (!empty($_POST['hide_post_commentcount'])) ? $_POST['hide_post_commentcount'] : false ;
		$hide_post_cat = (!empty($_POST['hide_post_cat'])) ? $_POST['hide_post_cat'] : false ;
		$hide_post_tags = (!empty($_POST['hide_post_tags'])) ? $_POST['hide_post_tags'] : false ;
		$show_post_avatar = (!empty($_POST['show_post_avatar'])) ? $_POST['show_post_avatar'] : false ;
		
		// Process the text style options
		$header_title_font_type = (!empty($_POST['header_title_font_type'])) ? $_POST['header_title_font_type'] : false ;
		$header_title_font_size = (!empty($_POST['header_title_font_size'])) ? $_POST['header_title_font_size'] : false ;
		$header_title_font_lineheight = (!empty($_POST['header_title_font_lineheight'])) ? $_POST['header_title_font_lineheight'] : false ;
		$header_title_font_weight = (!empty($_POST['header_title_font_weight'])) ? $_POST['header_title_font_weight'] : false ;
		$header_title_font_style = (!empty($_POST['header_title_font_style'])) ? $_POST['header_title_font_style'] : false ;
		
		$header_desc_font_type = (!empty($_POST['header_desc_font_type'])) ? $_POST['header_desc_font_type'] : false ;
		$header_desc_font_size = (!empty($_POST['header_desc_font_size'])) ? $_POST['header_desc_font_size'] : false ;
		$header_desc_font_lineheight = (!empty($_POST['header_desc_font_lineheight'])) ? $_POST['header_desc_font_lineheight'] : false ;
		$header_desc_font_weight = (!empty($_POST['header_desc_font_weight'])) ? $_POST['header_desc_font_weight'] : false ;
		$header_desc_font_style = (!empty($_POST['header_desc_font_style'])) ? $_POST['header_desc_font_style'] : false ;
		
		$content_font_type = (!empty($_POST['content_font_type'])) ? $_POST['content_font_type'] : false ;
		$content_font_size = (!empty($_POST['content_font_size'])) ? $_POST['content_font_size'] : false ;
		$content_font_lineheight = (!empty($_POST['content_font_lineheight'])) ? $_POST['content_font_lineheight'] : false ;
		$content_font_colour = (!empty($_POST['content_font_colour'])) ? $_POST['content_font_colour'] : false ;
		
		// Process the footer widget display options
		if (empty($_POST['footerwidget_column'])){
			$footerwidget_column = false;
		} else if (!is_numeric($_POST['footerwidget_column'])){
			$errors[] = __('Please enter only numerical value for the footer widget column count.', 'graphene');
			$footerwidget_column = false;
		} else{
			$footerwidget_column = $_POST['footerwidget_column'];
		}
		// This is for the alternate front page widget
		if (empty($_POST['alt_footerwidget_column'])){
			$alt_footerwidget_column = false;
		} else if (!is_numeric($_POST['alt_footerwidget_column'])){
			$errors[] = __('Please enter only numerical value for the front page footer widget column count.', 'graphene');
			$alt_footerwidget_column = false;
		} else{
			$alt_footerwidget_column = $_POST['alt_footerwidget_column'];
		}
		
		// Process the nav menu display options
		if (empty($_POST['navmenu_child_width'])) {
			$navmenu_child_width = false;	
		} else if (!is_numeric($_POST['navmenu_child_width'])){
			$errors[] = __('Please enter only numerical value for the navigation menu dropdown item width.', 'graphene');
			$navmenu_child_width = false;
		} else {
			$navmenu_child_width = $_POST['navmenu_child_width'];
		}
		
		// Updates all options
		if (empty($errors)) {
			
			// Header options
			update_option('graphene_light_header', $light_header);			
			
			// Posts Display options
			update_option('graphene_hide_post_author', $hide_post_author);
			update_option('graphene_hide_post_date', $hide_post_date);
			update_option('graphene_hide_post_commentcount', $hide_post_commentcount);
			update_option('graphene_hide_post_cat', $hide_post_cat);
			update_option('graphene_hide_post_tags', $hide_post_tags);
			update_option('graphene_show_post_avatar', $show_post_avatar);
			
			// Text style options
			update_option('graphene_header_title_font_type', $header_title_font_type);
			update_option('graphene_header_title_font_size', $header_title_font_size);
			update_option('graphene_header_title_font_lineheight', $header_title_font_lineheight);
			update_option('graphene_header_title_font_weight', $header_title_font_weight);
			update_option('graphene_header_title_font_style', $header_title_font_style);
			
			update_option('graphene_header_desc_font_type', $header_desc_font_type);
			update_option('graphene_header_desc_font_size', $header_desc_font_size);
			update_option('graphene_header_desc_font_lineheight', $header_desc_font_lineheight);
			update_option('graphene_header_desc_font_weight', $header_desc_font_weight);
			update_option('graphene_header_desc_font_style', $header_desc_font_style);
			
			update_option('graphene_content_font_type', $content_font_type);
			update_option('graphene_content_font_size', $content_font_size);
			update_option('graphene_content_font_lineheight', $content_font_lineheight);
			update_option('graphene_content_font_colour', $content_font_colour);
			
			// Bottom widget display options
			update_option('graphene_footerwidget_column', $footerwidget_column);
			update_option('graphene_alt_footerwidget_column', $alt_footerwidget_column);
			
			// Nav menu display options
			update_option('graphene_navmenu_child_width', $navmenu_child_width);
			
			// Print successful message
			$messages[] = __('Settings updated.', 'graphene');
		}
	}
	
	// Get the current options from database
	$light_header = get_option('graphene_light_header');
	
	$hide_post_author = get_option('graphene_hide_post_author');
	$hide_post_date = get_option('graphene_hide_post_date');
	$hide_post_commentcount = get_option('graphene_hide_post_commentcount');
	$hide_post_cat = get_option('graphene_hide_post_cat');
	$hide_post_tags = get_option('graphene_hide_post_tags');
	$show_post_avatar = get_option('graphene_show_post_avatar');
	
	$footerwidget_column = get_option('graphene_footerwidget_column');
	$alt_footerwidget_column = get_option('graphene_alt_footerwidget_column');
	
	$navmenu_child_width = get_option('graphene_navmenu_child_width');
	
	$header_title_font_type = get_option('graphene_header_title_font_type');
	$header_title_font_size = get_option('graphene_header_title_font_size');
	$header_title_font_lineheight = get_option('graphene_header_title_font_lineheight');
	$header_title_font_weight = get_option('graphene_header_title_font_weight');
	$header_title_font_style = get_option('graphene_header_title_font_style');
	
	$header_desc_font_type = get_option('graphene_header_desc_font_type');
	$header_desc_font_size = get_option('graphene_header_desc_font_size');
	$header_desc_font_lineheight = get_option('graphene_header_desc_font_lineheight');
	$header_desc_font_weight = get_option('graphene_header_desc_font_weight');
	$header_desc_font_style = get_option('graphene_header_desc_font_style');
	
	$content_font_type = get_option('graphene_content_font_type');
	$content_font_size = get_option('graphene_content_font_size');
	$content_font_lineheight = get_option('graphene_content_font_lineheight');
	$content_font_colour = get_option('graphene_content_font_colour');
	?>
    
    
    
    <?php 
		/**
		 * The main option page display is defined here.
		 * This determines how the option page is displayed in the Wordpress admin,
		 * including all the form inputs and messages
		*/
	?>
    <div class="wrap">
		<h2><?php _e('Graphene Display Options', 'graphene'); ?></h2>
		<?php 
			// Display errors if exist
			if (!empty($errors)) {
				echo '<div class="error">';
				foreach ($errors as $error) : ?>
					<p><strong><?php _e('ERROR:', 'graphene'); ?> </strong><?php echo $error; ?></p>
				<?php endforeach;
				echo '</div>';
			}
		?>
		
		<?php 
			// Display other messages if exist
			if (!empty($messages)) {
				echo '<div id="message" class="updated fade">';
				foreach ($messages as $message) : ?>
					<p><?php echo $message; ?></p>
				<?php endforeach;
				echo '</div>';
			}
		?>
        
        <?php // Begins the main html form. Note that one html form is used for *all* options ?>
        <form action="" method="post">

        
        <?php /* Header Options */ ?>
        <h3><?php _e('Header Display Options', 'graphene'); ?></h3>
        	<table class="form-table">
                <tr>
                    <th scope="row">
                    	<label><?php _e('Use light-coloured header bars', 'graphene'); ?></label>
                    </th>
                    <td><input type="checkbox" name="light_header" <?php if ($light_header == true) echo 'checked="checked"' ?> value="true" /></td>
                </tr>
            </table>
        
        
        <?php /* Posts Display Options */ ?>
        <h3><?php _e('Posts Display Options', 'graphene'); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row">
                    	<label><?php _e('Hide post author', 'graphene'); ?></label>
                    </th>
                    <td><input type="checkbox" name="hide_post_author" <?php if ($hide_post_author == true) echo 'checked="checked"' ?> value="true" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Hide post date', 'graphene'); ?></label>
                    </th>
                    <td><input type="checkbox" name="hide_post_date" <?php if ($hide_post_date == true) echo 'checked="checked"' ?> value="true" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Hide post categories', 'graphene'); ?></label>
                    </th>
                    <td><input type="checkbox" name="hide_post_cat" <?php if ($hide_post_cat == true) echo 'checked="checked"' ?> value="true" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Hide post tags', 'graphene'); ?></label>
                    </th>
                    <td><input type="checkbox" name="hide_post_tags" <?php if ($hide_post_tags == true) echo 'checked="checked"' ?> value="true" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Hide post comment count', 'graphene'); ?></label><br />
                        <small><?php _e('Only affects posts listing (such as the front page) and not single post view.', 'graphene'); ?></small>                        
                    </th>
                    <td><input type="checkbox" name="hide_post_commentcount" <?php if ($hide_post_commentcount == true) echo 'checked="checked"' ?> value="true" /></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e("Show post author's gravatar", 'graphene'); ?></label></th>
                    <td><input type="checkbox" name="show_post_avatar" <?php if ($show_post_avatar == true) echo 'checked="checked"' ?> value="true" /></td>
                </tr>
            </table>
            
        <?php /* Text Style Options */ ?>
        <h3><?php _e('Text Style Options', 'graphene'); ?></h3>    
        <p><?php _e('Note that these are CSS properties, so any valid CSS values for each particular property can be used.', 'graphene'); ?></p>
        <p><?php _e('Some example CSS properties values:', 'graphene'); ?></p>
        <table class="graphene-code-example">
            <tr>
                <th scope="row"><?php _e('Text font:', 'graphene'); ?></th>
                <td><?php _e("<code>arial</code>, <code>tahoma</code>, <code>georgia</code>, <code>'Trebuchet MS'</code>", 'graphene'); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Text size and line height:', 'graphene'); ?></th>
                <td><?php _e("<code>12px</code>, <code>12pt</code>, <code>12em</code>", 'graphene'); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Text weight:', 'graphene'); ?></th>
                <td><?php _e("<code>normal</code>, <code>bold</code>, <code>100</code>, <code>700</code>", 'graphene'); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Text style:', 'graphene'); ?></th>
                <td><?php _e(" <code>normal</code>, <code>italic</code>, <code>oblique</code>", 'graphene'); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Text colour:', 'graphene'); ?></th>
                <td><?php _e("<code>blue</code>, <code>navy</code>, <code>red</code>, <code>#ff0000</code>", 'graphene'); ?></td>
            </tr>        
        </table>
        <p><?php _e('Leave field empty to use the default value.', 'graphene'); ?></p>
        <h4><?php _e('Header Text', 'graphene'); ?></h4>
            <table class="form-table">
                <tr>
                    <th scope="row">
                    	<label><?php _e('Title text font', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_title_font_type" value="<?php if ($header_title_font_type) echo $header_title_font_type; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Title text size', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_title_font_size" value="<?php if ($header_title_font_size) echo $header_title_font_size; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Title text weight', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_title_font_weight" value="<?php if ($header_title_font_weight) echo $header_title_font_weight; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Title text line height', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_title_font_lineheight" value="<?php if ($header_title_font_lineheight) echo $header_title_font_lineheight; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Title text style', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_title_font_style" value="<?php if ($header_title_font_style) echo $header_title_font_style; ?>" /></td>
                </tr>
            </table>
            
            <table class="form-table" style="margin-top:30px;">               
                <tr>
                    <th scope="row">
                    	<label><?php _e('Description text font', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_desc_font_type" value="<?php if ($header_desc_font_type) echo $header_desc_font_type; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Description text size', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_desc_font_size" value="<?php if ($header_desc_font_size) echo $header_desc_font_size; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Description text weight', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_desc_font_weight" value="<?php if ($header_desc_font_weight) echo $header_desc_font_weight; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Description text line height', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_desc_font_lineheight" value="<?php if ($header_desc_font_lineheight) echo $header_desc_font_lineheight; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Description text style', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="header_desc_font_style" value="<?php if ($header_desc_font_style) echo $header_desc_font_style; ?>" /></td>
                </tr>
            </table>
        <h4><?php _e('Content Text', 'graphene'); ?></h4>
        	<table class="form-table">
                <tr>
                    <th scope="row">
                    	<label><?php _e('Text font', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="content_font_type" value="<?php if ($content_font_type) echo $content_font_type; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Text size', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="content_font_size" value="<?php if ($content_font_size) echo $content_font_size; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Text line height', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="content_font_lineheight" value="<?php if ($content_font_lineheight) echo $content_font_lineheight; ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Text colour', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="content_font_colour" value="<?php if ($content_font_colour) echo $content_font_colour; ?>" /></td>
                </tr>
            </table>
		
        
		<?php /* Footer Widget Display Options */ ?>
        <h3><?php _e('Footer Widget Display Options', 'graphene'); ?></h3>
        <p><?php _e('Leave field empty to use the default value.', 'graphene'); ?></p>
        
            <table class="form-table">
                <tr>
                    <th scope="row" style="width:260px;">
                    	<label><?php _e('Number of columns to display', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="footerwidget_column" value="<?php echo $footerwidget_column; ?>" maxlength="2" size="3" /></td>
                </tr>
                <?php if (get_option('graphene_alt_home_footerwidget')) : ?>
                <tr>
                    <th scope="row">
                    	<label><?php _e('Number of columns to display for front page footer widget', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="alt_footerwidget_column" value="<?php echo $alt_footerwidget_column; ?>" maxlength="2" size="3" /></td>
                </tr>
                <?php endif; ?>
            </table>
            
        
        <?php /* Bottom Widget Display Options */ ?>
        <h3><?php _e('Navigation Menu Display Options', 'graphene'); ?></h3>
        <p><?php _e('Leave field empty to use the default value.', 'graphene'); ?></p>
        
            <table class="form-table">
                <tr>
                    <th scope="row">
                    	<label><?php _e('Dropdown menu item width', 'graphene'); ?></label>
                    </th>
                    <td><input type="text" name="navmenu_child_width" value="<?php echo $navmenu_child_width; ?>" maxlength="3" size="3" /> px</td>
                </tr>
            </table>
                    
        
        <?php /* Ends the main form */ ?>
            <input type="hidden" name="graphene_submitted" value="true" />
            <input type="submit" class="button-primary" value="<?php _e('Update Settings', 'graphene'); ?>" style="margin-top:20px;margin-bottom:50px;" />
        </form>
        
    </div><!-- #wrap -->
    
    
<?php } // Closes the graphene_options_display() function definition ?>