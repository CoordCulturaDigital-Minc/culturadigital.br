<?php
//add_action( 'widgets_init', 'load_widgets' );

function load_widgets() {
	register_widget( 'Recent_News_Widget' );
}
class Recent_News_Widget extends WP_Widget {
	function Recent_News_Widget() {
		/* Widget settings. */
		$widget_ops = array(
			'classname'=>'recent_news', 
			'description'=>'Recent News' 
		);

		/* Widget control settings. */
		$control_ops = array(
			'width'=>250,
			'height'=>'auto',
			'id_base'=>'example-widget'
		);

		/* Create the widget. */
		$this->WP_Widget('example-widget', 'Recent News', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$category = $instance['category'];
		$number_of_posts = $instance['number_of_posts'];
		$show_image = isset( $instance['show_image'] ) ? $instance['show_image'] : false;
		$posts_query = array('numberposts'=>$number_of_posts);
		if ($category!=-1) {
			$posts_query['category'] = $category;
		}
		$posts = get_posts($posts_query);
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div class="recent-posts">';
		foreach ($posts as $post) {
			$img = null;
			if ($show_image) {
				$post_image = get_post_thumbnail($post->ID);
			}
			?>
			<div class="post">
				<div class="cl">&nbsp;</div>
				<?php if ($show_image && isset($post_image)): ?>
					<img src="<?php echo $post_image ?>" alt="<?php echo apply_filters('the_title', $post->post_title) ?>" />
					<div class="cnt">
				<?php else: ?>
					<div class="cnt" style="width: 100%">
				<?php endif ?>
					<h3><a href="<?php echo get_permalink($post->ID) ?>"><?php echo apply_filters('the_title', $post->post_title) ?></a></h3>
					<p><?php echo shortalize(apply_filters('the_content', $post->post_content), 15) ?></p>
				</div>
				<div class="cl">&nbsp;</div>
			</div>
			<?php
		}
		echo '</div><!-- Recent Posts -->';
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['category'] = strip_tags( $new_instance['category'] );
		$instance['number_of_posts'] = strip_tags( $new_instance['number_of_posts'] );
		$instance['show_image'] = strip_tags( $new_instance['show_image'] );
		

		return $instance;
	}
	
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
			'title'=>'Recent News',
			'category' => '0',
			'number_of_posts' => '2',
			'show_image' => '1',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>">Category:</label>
			<?php wp_dropdown_categories('show_option_none=All Categories&show_count=1&hide_empty=0exclude=1&selected=' . $instance['category'] . 
							'&hierarchical=1&name=' . $this->get_field_name( 'category' ) . '&class=widefat') ?>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('number_of_posts') ?>">Number of posts to show:</label>
			<input id="<?php echo $this->get_field_id('number_of_posts') ?>" name="<?php echo $this->get_field_name( 'number_of_posts' ) ?>" type="text" value="<?php echo $instance['number_of_posts'] ?>" size="3" /><br />
			<small>(at most 15)</small>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_image'], true ); ?> id="<?php echo $this->get_field_id( 'show_image' ); ?>" name="<?php echo $this->get_field_name( 'show_image' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_image' ); ?>">Display Image?<small>(If available)</small></label>
		</p>
		
		<?php 
	}
}
?>