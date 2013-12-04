<?php
/*----------------------------------------------------------------*
	WordPress 2.8 Plugin: WP-PageNavi 2.60									
	Copyright (c) 2009 Lester "GaMerZ" Chan									
																							
	File Written By:																	
	- Lester "GaMerZ" Chan															
	- http://lesterchan.net
																							
	File Information:																	
	- Page Navigation Options Page
	- wp-content/plugins/wp-pagenavi/pagenavi-options.php
 *----------------------------------------------------------------*/


### Variables Variables Variables
$base_name = plugin_basename('wp-pagenavi/pagenavi-options.php');
$base_page = 'admin.php?page='.$base_name;
$mode = trim(@$_GET['mode']);
$pagenavi_settings = array('pagenavi_options');


### Form Processing
// Update Options
if (!empty($_POST['Submit'])) {

	$pagenavi_options = array();
	$pagenavi_options['pages_text'] = addslashes(@$_POST['pagenavi_pages_text']);
	$pagenavi_options['current_text'] = addslashes(@$_POST['pagenavi_current_text']);
	$pagenavi_options['page_text'] = addslashes(@$_POST['pagenavi_page_text']);
	$pagenavi_options['first_text'] = addslashes(@$_POST['pagenavi_first_text']);
	$pagenavi_options['last_text'] = addslashes(@$_POST['pagenavi_last_text']);
	$pagenavi_options['next_text'] = addslashes(@$_POST['pagenavi_next_text']);
	$pagenavi_options['prev_text'] = addslashes(@$_POST['pagenavi_prev_text']);
	$pagenavi_options['dotright_text'] = addslashes(@$_POST['pagenavi_dotright_text']);
	$pagenavi_options['dotleft_text'] = addslashes(@$_POST['pagenavi_dotleft_text']);
	$pagenavi_options['style'] = intval(@$_POST['pagenavi_style']);
	$pagenavi_options['num_pages'] = intval(@$_POST['pagenavi_num_pages']);
	$pagenavi_options['always_show'] = (bool) @$_POST['pagenavi_always_show'];
	$pagenavi_options['num_larger_page_numbers'] = intval(@$_POST['pagenavi_num_larger_page_numbers']);
	$pagenavi_options['larger_page_numbers_multiple'] = intval(@$_POST['pagenavi_larger_page_numbers_multiple']);
	$pagenavi_options['use_pagenavi_css'] = (bool) @$_POST['use_pagenavi_css'];

	$update_pagenavi_queries = array();
	$update_pagenavi_text = array();
	$update_pagenavi_queries[] = update_option('pagenavi_options', $pagenavi_options);
	$update_pagenavi_text[] = __('Page Navigation Options', 'wp-pagenavi');
	$i=0;
	$text = '';
	foreach ($update_pagenavi_queries as $update_pagenavi_query) {
		if ($update_pagenavi_query) {
			$text .= '<font color="green">'.$update_pagenavi_text[$i].' '.__('Updated', 'wp-pagenavi').'</font><br />';
		}
		$i++;
	}
	if (empty($text)) {
		$text = '<font color="red">'.__('No Page Navigation Option Updated', 'wp-pagenavi').'</font>';
	}
}

// Uninstall WP-PageNavi
if (!empty($_POST['do'])) {
	switch($_POST['do']) {		
		case __('UNINSTALL WP-PageNavi', 'wp-pagenavi') :
			echo '<div id="message" class="updated fade">';
			echo '<p>';
			foreach ($pagenavi_settings as $setting) {
				$delete_setting = delete_option($setting);
				if ($delete_setting) {
					echo '<font color="green">';
					printf(__('Setting Key \'%s\' has been deleted.', 'wp-pagenavi'), "<strong><em>{$setting}</em></strong>");
					echo '</font><br />';
				} else {
					echo '<font color="red">';
					printf(__('Error deleting Setting Key \'%s\'.', 'wp-pagenavi'), "<strong><em>{$setting}</em></strong>");
					echo '</font><br />';
				}
			}
			echo '</p>';
			echo '</div>';
			$mode = 'end-UNINSTALL';
			break;
	}
}


### Determines Which Mode It Is
switch($mode) {
	//  Deactivating WP-PageNavi
	case 'end-UNINSTALL':
		$deactivate_url = 'plugins.php?action=deactivate&amp;plugin=wp-pagenavi/wp-pagenavi.php';
		$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_wp-pagenavi/wp-pagenavi.php');
		echo '<div class="wrap">';
		echo '<h2>'.__('Uninstall WP-PageNavi', 'wp-pagenavi').'</h2>';
		echo '<p><strong>'.sprintf(__('<a href="%s">Click Here</a> To Finish The Uninstallation And WP-PageNavi Will Be Deactivated Automatically.', 'wp-pagenavi'), $deactivate_url).'</strong></p>';
		echo '</div>';
		break;
	// Main Page
	default:
		$pagenavi_options = get_option('pagenavi_options');
?>
<?php if (!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e('Page Navigation Options', 'wp-pagenavi'); ?></h2>
	<h3><?php _e('Page Navigation Text', 'wp-pagenavi'); ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row" valign="top"><?php _e('Text For Number Of Pages', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_pages_text" value="<?php echo stripslashes($pagenavi_options['pages_text']); ?>" size="50" /><br />
				%CURRENT_PAGE% - <?php _e('The current page number.', 'wp-pagenavi'); ?><br />
				%TOTAL_PAGES% - <?php _e('The total number of pages.', 'wp-pagenavi'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Text For Current Page', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_current_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['current_text'])); ?>" size="30" /><br />
				%PAGE_NUMBER% - <?php _e('The page number.', 'wp-pagenavi'); ?><br />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Text For Page', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_page_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['page_text'])); ?>" size="30" /><br />
				%PAGE_NUMBER% - <?php _e('The page number.', 'wp-pagenavi'); ?><br />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Text For First Page', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_first_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['first_text'])); ?>" size="30" /><br />
				%TOTAL_PAGES% - <?php _e('The total number of pages.', 'wp-pagenavi'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Text For Last Page', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_last_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['last_text'])); ?>" size="30" /><br />
				%TOTAL_PAGES% - <?php _e('The total number of pages.', 'wp-pagenavi'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Text For Next Page', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_next_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['next_text'])); ?>" size="30" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Text For Previous Page', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_prev_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['prev_text'])); ?>" size="30" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Text For Next ...', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_dotright_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['dotright_text'])); ?>" size="30" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Text For Previous ...', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_dotleft_text" value="<?php echo stripslashes(htmlspecialchars($pagenavi_options['dotleft_text'])); ?>" size="30" />
			</td>
		</tr>
	</table>
	<h3><?php _e('Page Navigation Options', 'wp-pagenavi'); ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row" valign="top"><?php _e('Use pagenavi.css?', 'wp-pagenavi'); ?></th>
			<td>
				<input type="checkbox" name="use_pagenavi_css" value="1" <?php checked($pagenavi_options['use_pagenavi_css']); ?>>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Page Navigation Style', 'wp-pagenavi'); ?></th>
			<td>
				<select name="pagenavi_style" size="1">
					<option value="1"<?php selected('1', $pagenavi_options['style']); ?>><?php _e('Normal', 'wp-pagenavi'); ?></option>
					<option value="2"<?php selected('2', $pagenavi_options['style']); ?>><?php _e('Drop Down List', 'wp-pagenavi'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Number Of Pages To Show?', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_num_pages" value="<?php echo stripslashes($pagenavi_options['num_pages']); ?>" size="4" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Always Show Page Navigation?', 'wp-pagenavi'); ?></th>
			<td>
				<input type="checkbox" name="pagenavi_always_show" value="1" <?php checked($pagenavi_options['always_show']); ?>>
				<?php _e("Show navigation even if there's only one page", 'wp-pagenavi'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Number Of Larger Page Numbers To Show?', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_num_larger_page_numbers" value="<?php echo stripslashes($pagenavi_options['num_larger_page_numbers']); ?>" size="4" />
				<br />
				<?php _e('Larger page numbers are in additional to the default page numbers. It is useful for authors who is paginating through many posts.', 'wp-pagenavi'); ?>	
				<br />
				<?php _e('For example, WP-PageNavi will display: Pages 1, 2, 3, 4, 5, 10, 20, 30, 40, 50', 'wp-pagenavi'); ?>	
				<br />
				<?php _e('Enter 0 to disable.', 'wp-pagenavi'); ?>	
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Show  Larger Page Numbers In Multiples Of:', 'wp-pagenavi'); ?></th>
			<td>
				<input type="text" name="pagenavi_larger_page_numbers_multiple" value="<?php echo stripslashes($pagenavi_options['larger_page_numbers_multiple']); ?>" size="4" />
				<br />
				<?php _e('If mutiple is in 5, it will show: 5, 10, 15, 20, 25', 'wp-pagenavi'); ?>	
				<br />				
				<?php _e('If mutiple is in 10, it will show: 10, 20, 30, 40, 50', 'wp-pagenavi'); ?>	
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'wp-pagenavi'); ?>" />
	</p>
</div>
</form>
<p>&nbsp;</p>

<!-- Uninstall WP-PageNavi -->
<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
<div class="wrap">
	<h3><?php _e('Uninstall WP-PageNavi', 'wp-pagenavi'); ?></h3>
	<p>
		<?php _e('Deactivating WP-PageNavi plugin does not remove any data that may have been created, such as the page navigation options. To completely remove this plugin, you can uninstall it here.', 'wp-pagenavi'); ?>
	</p>
	<p style="color: red">
		<strong><?php _e('WARNING:', 'wp-pagenavi'); ?></strong><br />
		<?php _e('Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.', 'wp-pagenavi'); ?>
	</p>
	<p style="color: red">
		<strong><?php _e('The following WordPress Options will be DELETED:', 'wp-pagenavi'); ?></strong><br />
	</p>
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e('WordPress Options', 'wp-pagenavi'); ?></th>
			</tr>
		</thead>
		<tr>
			<td valign="top">
				<ol>
				<?php
					foreach ($pagenavi_settings as $settings) {
						echo '<li>'.$settings.'</li>'."\n";
					}
				?>
				</ol>
			</td>
		</tr>
	</table>
	<p>&nbsp;</p>
	<p style="text-align: center;">
		<input type="submit" name="do" value="<?php _e('UNINSTALL WP-PageNavi', 'wp-pagenavi'); ?>" class="button" onclick="return confirm('<?php _e('You Are About To Uninstall WP-PageNavi From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.', 'wp-pagenavi'); ?>')" />
	</p>
</div>
</form>
<?php
} // End switch($mode)
?>
