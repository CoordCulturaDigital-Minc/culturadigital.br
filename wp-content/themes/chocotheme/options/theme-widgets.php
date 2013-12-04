<?php
include_once('theme-widgets-fields.php');

/*
* Register the new widget classes here so that they show up in the widget list
*/
function load_widgets() {
    register_widget( 'ThemeWidgetExample' );
}
add_action('widgets_init', 'load_widgets');

/*
* An example widget
*/
class ThemeWidgetExample extends ThemeWidgetBase {
	/*
	* Register widget function. Must have the same name as the class
	*/
    function ThemeWidgetExample() {
        $widget_opts = array(
	        'classname' => 'theme-widget', // class of the <li> holder
            'description' => __( 'Displays a block with title/text' ) // description shown in the widget list
        );
        // widget id, widget display title, widget options
        $this->WP_Widget('theme-widget-example', 'Theme Widget - Example', $widget_opts);
        $this->custom_fields = array(
        	array(
		        'name'=>'title', // field name
        		'type'=>'text', // field type (text, textarea, integer etc.)
        		'title'=>'Title', // title displayed in the widget form
        		'default'=>'Hello World!' // default value
        	),
        	array(
        		'name'=>'text',
        		'type'=>'textarea',
        		'title'=>'Content', 
        		'default'=>'Lorem Ipsum dolor sit amet'
        	),
        );
    }
    
    /*
	* Called when rendering the widget in the front-end
	*/
    function front_end($args, $instance) {
        ?>
        <div class="cl">&nbsp;</div>
        <div class="widget-contact">
			<h3><?php echo $instance['title'];?></h3>
			<p><?php echo $instance['text'];?></p>
		</div>
		<div class="cl">&nbsp;</div>
        <?php
    }
}
?>