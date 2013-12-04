<?php
/*
* Base Theme Widget Class. Extend this class when adding new widgets instead of WP_Widget.
* Handles updating, displaying the form in wp-admin and $before_widget/$after_widget
*/
class ThemeWidgetBase extends WP_Widget {
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		foreach ($this->custom_fields as $field) {
			if ($field['type'] == 'integer') {
				$instance[$field['name']] = intval($new_instance[$field['name']]);
			} else {
				$instance[$field['name']] = $new_instance[$field['name']];
			}
		}
		
		return $instance;
	}
 
	function form($instance) {
		$defaults = array();
		foreach ($this->custom_fields as $field) {
			$defaults[$field['name']] = $field['default'];
		}
		$instance = wp_parse_args( (array) $instance, $defaults);
		?>
		<?php foreach ($this->custom_fields as $field) : ?>
			<?php call_user_func_array('widget_field_'.$field['type'], array($this, $instance, $field['name'], $field['title']))?>
		<?php endforeach; ?>
		<?php
	}
	
	function widget($args, $instance) {
        extract($args);
        echo $before_widget;
        $this->front_end($args, $instance);
        echo $after_widget;
    }
    
    function front_end($args, $instance) {
    	// empty
    }
}

/*
* Field rendering functions. Called in the admin when showing the widget form.
*/
function widget_field_text($obj, $instance, $fieldname, $fieldtitle) {
	$value = $instance[$fieldname];
	?>
	<p>
		<label for="<?php echo $obj->get_field_id($fieldname); ?>"><?php echo $fieldtitle; ?>:</label>
		<input class="widefat" id="<?php echo $obj->get_field_id($fieldname); ?>" name="<?php echo $obj->get_field_name($fieldname); ?>" type="text" value="<?php echo attribute_escape($value); ?>" />
	</p>
	<?php
}
function widget_field_integer($obj, $instance, $fieldname, $fieldtitle) {
	$value = intval($instance[$fieldname]);
	?>
	<p>
		<label for="<?php echo $obj->get_field_id($fieldname); ?>"><?php echo $fieldtitle; ?>:</label>
		<input class="widefat" id="<?php echo $obj->get_field_id($fieldname); ?>" name="<?php echo $obj->get_field_name($fieldname); ?>" type="text" value="<?php echo attribute_escape($value); ?>" />
	</p>
	<?php
}
function widget_field_textarea($obj, $instance, $fieldname, $fieldtitle) {
	$value = $instance[$fieldname];
	?>
	<p>
		<label for="<?php echo $obj->get_field_id($fieldname); ?>"><?php echo $fieldtitle; ?>:</label>
		<textarea class="widefat" id="<?php echo $obj->get_field_id($fieldname); ?>" name="<?php echo $obj->get_field_name($fieldname); ?>" type="text"><?php echo attribute_escape($value); ?></textarea>
	</p>
	<?php
}
?>