<?php

class Ethymos_Query{
	
	/**
	*
	*/
	public function __construct(){
		
		
	}
	
	/**
	*
	*/
	public function destaques($limite = null){
		$limite = ($limite) ? $limite : get_option('posts_per_page');
		$query = new WP_Query(array(
			'post_type' 	 => 'post',
			'posts_per_page' => $limite,
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field' => 'slug',
					'terms' => array('destaques')
				)
			)
		));
		
		return $query;
	}
	
	/**
	*
	*/
	public function blog($limite = null){
		$limite = ($limite) ? $limite : get_option('posts_per_page');
		$query = new WP_Query(array(
			'post_type' 	 => 'post',
			'posts_per_page' => $limite		
		));
		
		return $query;
	}
	
	/**
	*
	*/
	public function links_uteis(){
		
	}
	
	
}



?>