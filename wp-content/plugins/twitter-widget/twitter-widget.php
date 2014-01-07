<?php
/*
Plugin Name: Twitter Widget
Plugin URI: http://seanys.com/2007/10/12/twitter-wordpress-widget/
Description: Adds a sidebar widget to display Twitter updates (uses the Javascript <a href="https://twitter.com/settings/widgets">Twitter 'widgets'</a>)
Version: 1.0.4
Author: Sean Spalding
Author URI: http://seanys.com/
License: GPLv2

This software comes without any warranty, express or otherwise, and if it
breaks your blog or results in your cat being shaved, it's not my fault.

*/

function widget_Twidget_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_Twidget($args) {

		// "$args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys." - These are set up by the theme
		extract($args);

		// These are our own options
		$options = get_option('widget_Twidget');
		$account = $options['account'];  // Your Twitter account name
		$datawidgetid = $options['datawidgetid'];   // Twitter data-widget-id
		$title = $options['title'];  // Title in sidebar for widget

        // Output
		echo $before_widget ;

		// start
		echo '<a class="twitter-timeline" data-dnt=true href="https://twitter.com/'.$account.'" data-widget-id="'.$datawidgetid.'">'
              .$before_title.$title.$after_title;
		echo '</a>
		      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';


		// echo widget closing tag
		echo $after_widget;
	}

	// Settings form
	function widget_Twidget_control() {

		// Get options
		$options = get_option('widget_Twidget');
		// options exist? if not set defaults
		if ( !is_array($options) )
			$options = array('account'=>'seanys', 'datawidgetid'=>'259518818584502272', 'title'=>'Twitter Feed');

        // form posted?
		if ( $_POST['Twitter-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['account'] = strip_tags(stripslashes($_POST['Twitter-account']));
			$options['datawidgetid'] = strip_tags(stripslashes($_POST['Twitter-datawidgetid']));
			$options['title'] = strip_tags(stripslashes($_POST['Twitter-title']));
			update_option('widget_Twidget', $options);
		}

		// Get options for form fields to show
		$account = htmlspecialchars($options['account'], ENT_QUOTES);
		$datawidgetid = htmlspecialchars($options['datawidgetid'], ENT_QUOTES);
		$title = htmlspecialchars($options['title'], ENT_QUOTES);

		// The form fields
		echo '<p style="text-align:left;">Create a new <a href="https://twitter.com/settings/widgets">Twitter widget</a>.</p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-account">' . __('Account:') . '
				<input style="width: 200px;" id="Twitter-account" name="Twitter-account" type="text" value="'.$account.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-datawidgetid">' . __('Data Widget ID:') . '
				<input style="width: 200px;" id="Twitter-datawidgetid" name="Twitter-datawidgetid" type="text" value="'.$datawidgetid.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-title">' . __('Title:') . '
				<input style="width: 200px;" id="Twitter-title" name="Twitter-title" type="text" value="'.$title.'" />
				</label></p>';
		echo '<p style="text-align:left;">Go to the <a href="https://twitter.com/settings/widgets/'.$datawidgetid.'/edit">Twitter widget settings page</a>.</p>';
		echo '<input type="hidden" id="Twitter-submit" name="Twitter-submit" value="1" />';
	}


	// Register widget for use
	register_sidebar_widget(array('Twitter', 'widgets'), 'widget_Twidget');

	// Register settings for use, 300x200 pixel form
	register_widget_control(array('Twitter', 'widgets'), 'widget_Twidget_control', 300, 200);
}

// Run code and init
add_action('widgets_init', 'widget_Twidget_init');

?>
