<?php
/*
Function Name: Widget Destaque
Plugin URI: http://xemele.cultura.gov.br/
Version: 0.1
Author: Marcos Maia Lopes
Author URI: http://xemele.cultura.gov.br/
*/

class widget_hightlights extends WP_Widget
{	
	function widget_hightlights()
	{
		$widget_args = array('classname' => 'widget_destaque', 'description' => __( 'Destaque') );
		parent::WP_Widget('destaque', __('Destaque'), $widget_args);
	}

	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Em Destaque' : $instance['title']);
		$maxPages = empty($instance['maxPages']) ? 5 : $instance['maxPages'];
		
		$query = new HL_Query( "headline_category_name=headlines" );
		if($query->have_posts()) :
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		?>
        <div class="hlTabs">
        	<ul>
            	<?php while($query->have_posts()) : $query->the_post(); static $i = 1; ?>
            	<li><a href="#hl-<?php echo $query->post->ID; ?>"><?php echo $i++; ?></a></li>
                <?php endwhile; ?>
            </ul>
        <?php
		
		while($query->have_posts()) : $query->the_post();
		?>
        	<div id="hl-<?php echo $query->post->ID ?>">
				
				<?php the_post_thumbnail( array(777,256) ); ?>
				<br />
				<br />

                <h2><a href="<?php the_permalink(); ?>"><?php limit_chars(get_the_title(), 66); ?></a></h2>
                <?php limit_chars(get_the_excerpt(),120); ?>
            </div>
        <?php
		endwhile;
		echo '</div>';
		echo $after_widget;
		
		endif;
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		if( $instance != $new_instance )
			$instance = $new_instance;
		
		return $instance;
	}

	function form($instance)
	{
	    $title = esc_attr( $instance['title'] );
		$maxPages = esc_attr( $instance['maxPages'] );
	?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Título:</label>
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" maxlength="26" value="<?php echo $title; ?>" class="widefat" />
			</p>
            <p>
				<label for="<?php echo $this->get_field_id('maxPages'); ?>">Número máximo de posts:</label>
				<select id="<?php echo $this->get_field_id('maxPages'); ?>" name="<?php echo $this->get_field_name('maxPages'); ?>">
				    <?php for($i=0; $i <= 10; $i++) : ?>
				        <option <?php if($maxPages == $i) echo 'selected="selected"'; ?> value="<?php if($i == 0) echo '1'; else echo $i; ?>"><?php if($i == 0) echo '1'; else echo $i; ?></option>
				    <?php endfor; ?>
				</select>
            </p>
        <?php
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("widget_hightlights");'));
