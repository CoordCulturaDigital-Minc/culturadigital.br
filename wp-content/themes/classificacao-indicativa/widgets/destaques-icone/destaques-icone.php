<?php

class Widget_Destaques extends WP_Widget {
	public $path;
	
	public function __construct() {
		parent::__construct('widget-destaques', 'Widget Destaque', array('description' => __('Widget com destaques para home')));
		$this->path = get_stylesheet_directory() . '/widgets/destaques-icone';
	}

	public function widget( $args, $instance ) {
		extract($args);
		$icone = 'educacao';
		switch($instance['modelo']){
			case 'azul' :
				$icone = 'educacao'; break;
			case 'verde' :
				$icone = 'debate'; break;
			case 'vermelho' :
				$icone = 'pasta'; break;
		}
		include $this->path . '/view.php';
	}

 	public function form( $instance ) {
 		//var_dump($instance);
		include $this->path . '/form.php';
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['titulo'] = $new_instance['titulo'];
		$instance['link'] = $new_instance['link'];
		$instance['descricao'] = $new_instance['descricao'];
		$instance['modelo'] = $new_instance['modelo'];
		
		return $instance;
	}

}

register_widget('Widget_Destaques');

?>