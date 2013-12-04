<?php

class Widget_Ethymos_Subpaginas extends WP_Widget {
	public $path;
	
	/**
	*
	*
	*/
	public function __construct() {
		parent::__construct('widget-subpaginas', 'Widget Sub-Páginas', array('description' => __('Exibe as sub-páginas conectadas à página atual.')));
		$this->path = get_stylesheet_directory() . '/widgets/subpaginas';
	}

	/**
	*
	*
	*/
	public function widget( $args, $instance ) {
		extract($args);
		if(is_page()){
			global $post;
			if(!get_post_ancestors($post->ID)){
				$referencia_mae = $post->ID;
			} else {
				$hierarquia = array_reverse(get_post_ancestors($post->ID));
				$referencia_mae = $hierarquia[0];
			}
			include $this->path . '/view.php';
		}
	}

	/**
	*
	*
	*/
 	public function form( $instance ) {
 		//var_dump($instance);
		include $this->path . '/form.php';
	}

	/**
	*
	*
	*/
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['titulo'] = $new_instance['titulo'];		
		return $instance;
	}

}

register_widget('Widget_Ethymos_Subpaginas');

?>