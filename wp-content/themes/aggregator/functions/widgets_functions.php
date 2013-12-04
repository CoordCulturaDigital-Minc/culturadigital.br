<?php
// Register widgetized areas
if ( function_exists('register_sidebar') ) 
{
	register_sidebars(1,array('name' => 'Home Page Feeds','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h3><span>','after_title' => '</span></h3>'));
	register_sidebars(1,array('name' => 'Inner Page Sidebar','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h3><span>','after_title' => '</span></h3>'));
}

// =============================== Normal Widget ======================================
class aggregatorWidget extends WP_Widget {
	function aggregatorWidget() {
	//Constructor
		$widget_ops = array('classname' => 'widget Aggregator Widget', 'description' => 'Aggregator Widget' );		
		$this->WP_Widget('widget_normal', 'Aggregator Widget', $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$urllink = empty($instance['urllink']) ? '' : apply_filters('widget_urllink', $instance['urllink']);
		$title_icon = empty($instance['title_icon']) ? '' : apply_filters('widget_title_icon', $instance['title_icon']);
		$no_of_items  = empty($instance['no_of_items']) ? '' : apply_filters('widget_no_of_items', $instance['no_of_items']);
		$thumbnail  = empty($instance['thumbnail']) ? '' : apply_filters('widget_thumbnail', $instance['thumbnail']);
		
		 ?>
<li>
<?php 
if($thumbnail=='image')
{
echo newsblocks::wide($urllink, array(
'title' => $title,
'direction' => 'ltr',
'items' => $no_of_items,
'more_move'=>false,
'more'=>'',
	));
}else
{
echo newsblocks::listing($urllink, array(
'title' => $title,
'direction' => 'ltr',
'items' => $no_of_items,
'more_move'=>false,
'more'=>'',
	));
} ?> 
</li>
<?php
	}
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['title_icon'] = strip_tags($new_instance['title_icon']);		
		$instance['urllink'] = ($new_instance['urllink']);
		$instance['thumbnail'] = ($new_instance['thumbnail']);		
		$instance['no_of_items'] = ($new_instance['no_of_items']);		
		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'urllink' => '' ) );		
		$title = strip_tags($instance['title']);
		$title_icon = ($instance['title_icon']);
		$urllink = ($instance['urllink']);
		$thumbnail = ($instance['thumbnail']);
		$no_of_items = ($instance['no_of_items']);	
			
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Aggregator Title:
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
</p>
<!--<p>
  <label for="<?php // echo $this->get_field_id('title_icon'); ?>">Title Icon:
  <input class="widefat" id="<?php // echo $this->get_field_id('title_icon'); ?>" name="<?php // echo $this->get_field_name('title_icon'); ?>" type="text" value="<?php // echo attribute_escape($title_icon); ?>" />
  </label>
</p>-->
<p>
  <label for="<?php echo $this->get_field_id('urllink'); ?>">URL Link Here
  <input class="widefat" id="<?php echo $this->get_field_id('urllink'); ?>" name="<?php echo $this->get_field_name('urllink'); ?>" type="text" value="<?php echo attribute_escape($urllink); ?>" />
  <?php /*?><textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('desc1'); ?>" name="<?php echo $this->get_field_name('desc1'); ?>"><?php echo attribute_escape($desc1); ?></textarea><?php */?>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('thumbnail'); ?>">Display Type: 
  <input id="<?php echo $this->get_field_id('thumbnail'); ?>_list" name="<?php echo $this->get_field_name('thumbnail'); ?>" <?php if(attribute_escape($thumbnail)=='list' || attribute_escape($thumbnail)==''){?> checked<?php }?> type="radio" value="list" /> List &nbsp;
  <input  id="<?php echo $this->get_field_id('thumbnail'); ?>_image" name="<?php echo $this->get_field_name('thumbnail'); ?>" <?php if(attribute_escape($thumbnail)=='image'){?> checked<?php }?> type="radio" value="image" /> Image
  <?php /*?><textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('desc1'); ?>" name="<?php echo $this->get_field_name('desc1'); ?>"><?php echo attribute_escape($desc1); ?></textarea><?php */?>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('no_of_items'); ?>">show how many posts?
  <input class="widefat" id="<?php echo $this->get_field_id('no_of_items'); ?>" name="<?php echo $this->get_field_name('no_of_items'); ?>" type="text" value="<?php echo attribute_escape($no_of_items); ?>" />
  </label>
</p>
<?php
	}}
register_widget('aggregatorWidget');




// =============================== Normal Widget ======================================
class advtWidget extends WP_Widget {
	function advtWidget() {
	//Constructor
		$widget_ops = array('classname' => 'widget Advt 300x200px', 'description' => 'Advt 300x200px Widget' );		
		$this->WP_Widget('widget_normal2', 'Advt 300x200px', $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$urllink = empty($instance['urllink']) ? '' : apply_filters('widget_urllink', $instance['urllink']);
		$title_icon = empty($instance['title_icon']) ? '' : apply_filters('widget_title_icon', $instance['title_icon']);
		$no_of_items  = empty($instance['no_of_items']) ? '' : apply_filters('widget_no_of_items', $instance['no_of_items']);
		$thumbnail  = empty($instance['thumbnail']) ? '' : apply_filters('widget_thumbnail', $instance['thumbnail']);
		
		 ?>
<li>

<div class="advt">
   		<?php if ( $title_icon <> "" ) { ?>
  <a href="<?php echo $urllink; ?>"><img src="<?php echo $title_icon; ?> " alt="" /></a>
  <?php } ?>
        
	</div>
</li>

<?php
	}
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['title_icon'] = strip_tags($new_instance['title_icon']);		
		$instance['urllink'] = ($new_instance['urllink']);
		$instance['thumbnail'] = ($new_instance['thumbnail']);		
		$instance['no_of_items'] = ($new_instance['no_of_items']);		
		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'urllink' => '' ) );		
		$title = strip_tags($instance['title']);
		$title_icon = ($instance['title_icon']);
		$urllink = ($instance['urllink']);
		$thumbnail = ($instance['thumbnail']);
		$no_of_items = ($instance['no_of_items']);	
			
?>

<p>
  <label for="<?php  echo $this->get_field_id('title_icon'); ?>">Advt Image URL :
  <input class="widefat" id="<?php  echo $this->get_field_id('title_icon'); ?>" name="<?php echo $this->get_field_name('title_icon'); ?>" type="text" value="<?php echo attribute_escape($title_icon); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('urllink'); ?>">Advt URL Link Here
  <input class="widefat" id="<?php  echo $this->get_field_id('urllink'); ?>" name="<?php echo $this->get_field_name('urllink'); ?>" type="text" value="<?php echo attribute_escape($urllink); ?>" />
  </label>
</p>
<?php
	}}
register_widget('advtWidget');
?>