<?php
$include_path = get_stylesheet_directory() . '/inc';
require_once $include_path . '/admin.class.php';
require_once $include_path . '/contents.class.php';
require_once $include_path . '/query.class.php';
require_once get_stylesheet_directory() . '/widgets/destaques-icone/destaques-icone.php';
require_once get_stylesheet_directory() . '/widgets/subpaginas/subpaginas.php';

class Ethymos{
	public $admin, $contents, $query;
	
	/**
	* Registra actions do wordpress
	*
	*/
	public function __construct(){
		remove_theme_support('custom-header');
		remove_theme_support('custom-background');
	
		add_action('wp_enqueue_scripts', array($this, 'css'));		
		add_action('wp_enqueue_scripts', array($this, 'javascript'));
		add_action('after_setup_theme', array($this, 'action_after_setup_theme'));

		add_theme_support('post-thumbnails');
		
		$this->admin = new Ethymos_Admin();
		$this->contents = new Ethymos_Contents();
		$this->query = new Ethymos_Query();
		
		// Menus
		register_nav_menus(array(
			'principal' => 'Principal topo',
			'rodape' => 'Rodapé'
		));
		
		//Tamanhos de imagem
		add_image_size('destaques-home', 355, 160, true);
		
		// Sidebars
		register_sidebar(array(
			'name'          => _x( 'Listas de posts', 'sidebar', 'classificacao-indicativa'),
			'id'            => 'listas-de-posts',
			'description'   => '',
		    'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<span class="widget-titulo">',
			'after_title'   => '</span>'
		));
		
		register_sidebar(array(
			'name'          => _x( 'Leitura de post', 'sidebar', 'classificacao-indicativa'),
			'id'            => 'leitura-de-post',
			'description'   => '',
		    'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<span class="widget-titulo">',
			'after_title'   => '</span>'
		));
		
		register_sidebar(array(
			'name'          => _x( 'Páginas estáticas', 'sidebar', 'classificacao-indicativa'),
			'id'            => 'páginas-estaticas',
			'description'   => '',
		    'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<span class="widget-titulo">',
			'after_title'   => '</span>'
		));
		
		register_sidebar(array(
			'name'          => _x( 'Listas de posts', 'sidebar', 'classificacao-indicativa'),
			'id'            => 'listas-de-posts',
			'description'   => '',
		    'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<span class="widget-titulo">',
			'after_title'   => '</span>'
		));
				
		register_sidebar(array(
			'name'          => _x( 'Destaques home', 'sidebar', 'classificacao-indicativa'),
			'id'            => 'blocos-home',
			'description'   => '',
		    'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '',
			'after_title'   => ''
		));
	}
	
	/**
	* Ações para redefinir ajustes setados no tema pai.
	*
	*/
	public function action_after_setup_theme(){
		//Cancela ajuste nos resumo
		remove_filter( 'excerpt_more', 'consulta_auto_excerpt_more');
		
		remove_theme_support('custom-header');
		remove_theme_support('custom-background');
	}
	
	/**
	* Função responsável por controlar as folhas de estilo do site
	*
	*/
	public function css(){
		$path = get_stylesheet_directory_uri() . '/css';
		wp_register_style('bootstrap-responsive', $path . '/bootstrap-responsive.min.css');
		wp_register_style('bootstrap', $path . '/bootstrap.min.css');		
		wp_register_style('geral', get_stylesheet_directory_uri() . '/style.css', array('bootstrap'));
		
		wp_enqueue_style('bootstrap');
		//wp_enqueue_style('bootstrap-responsive');
		wp_enqueue_style('geral');
	}
	
	/**
	* Controla os arquivos javascript do site
	*
	*/
	public function javascript(){
		$path = get_stylesheet_directory_uri() . '/js';
		wp_register_script('bootstrap', $path . '/bootstrap.min.js');
		wp_register_script('functions', $path . '/functions.js', array('jquery'));
		wp_enqueue_script('jquery');
		wp_enqueue_script('bootstrap');
		wp_enqueue_script('functions');
	}
	
	/**
	*
	*
	*/
	public function pauta_exibe_icone($term_id){
		$path = get_stylesheet_directory_uri();
		$icones = array(
			'l' => $path . '/imagens/ico-big-l.png',
			'10' => $path . '/imagens/ico-big-10.png',
			'12' => $path . '/imagens/ico-big-12.png',
			'14' => $path . '/imagens/ico-big-14.png',
			'16' => $path . '/imagens/ico-big-16.png',
			'18' => $path . '/imagens/ico-big-18.png'
		);
		
		$icone_term = array(
			get_term_by('slug', 'classificacao-livre', 'tema')->term_id => 'l',
			get_term_by('slug', 'nao-recomendado-para-menores-de-10-anos', 'tema')->term_id => '10',
			get_term_by('slug', 'nao-recomendado-para-menores-de-12-anos', 'tema')->term_id => '12',
			get_term_by('slug', 'nao-recomendado-para-menores-de-14-anos', 'tema')->term_id => '14',
			get_term_by('slug', 'nao-recomendado-para-menores-de-16-anos', 'tema')->term_id => '16',
			get_term_by('slug', 'nao-recomendado-para-menores-de-18-anos', 'tema')->term_id => '18'
		);
		
		$imagem = $icones[$icone_term[$term_id]];
		echo "<img src='{$imagem}' />";
	}
	/*
	function return_after_register($url, $path, $scheme, $blog_id){
	    
	    if($scheme == 'login' && strpos($url, 'action=register') !== false){
	    	$url .= "&redirect_to=".urlencode(get_permalink());
	    }
	    
	    return $url;
	}
	add_filter('site_url', 'return_after_register', 10, 4);
	*/
	
}

$ethymos = new Ethymos();
