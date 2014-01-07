<?php
class Ethymos_Admin{

	private $custom_options;
	
	/**
	*
	*
	*/
	public function __construct(){		
		add_action('admin_menu', array($this, 'theme_options'));
		
		/*Campos que serão exibidos na página de opções do tema.
		* Para adicionar um novo basta fazer um novo indice no array abaixo. Ex: 'Meu campo' => 'chave_do_option_no_banco'
		*/
		$this->custom_options = array(
			'Destaque - Texto 1' => '_ethymos_destaque_1',
			'Destaque - Texto 2' => '_ethymos_destaque_2',
			'Destaque - Texto 3' => '_ethymos_destaque_3',
			'Link botão Livre' => '_ethymos_link_botao_livre',
			'Link botão 10' => '_ethymos_link_botao_10',
			'Link botão 12' => '_ethymos_link_botao_12',
			'Link botão 14' => '_ethymos_link_botao_14',
			'Link botão 16' => '_ethymos_link_botao_16',
			'Link botão 18' => '_ethymos_link_botao_18'			
		);
	}


	/**
	* Retorna as opções personalizadas do site para serem usadas no Theme Options
	* @return array $cßßßßustom_options
	*/
	public function get_custom_options(){
		return $this->custom_options;
	}

	/**
	* Registra a página de opções do tema
	*
	*/
	public function theme_options(){
		add_theme_page('Opções', 'Opções', 'edit_theme_options', 'ethymos-theme-options', array($this, 'theme_options_exibe'));
	}	
	
	/**
	* Função responsavel por renderizar o conteúdo da página de opções do tema
	*
	*/
	public function theme_options_exibe(){
		include_once get_template_directory() . '/admin/theme-options.php';
	}
	
	
	/**
	* Salva formulário da página de opções do tema
	*
	*/
	public function theme_options_save(){
		if($_POST['theme-options-form']){
			$options = $this->get_custom_options();
			foreach($options as $key){
				update_option($key, $_POST[$key]);
			}			
			//salva rodapé
			//$conteudo_rodape = esc_js( $_POST['_ethymos_conteudo_rodape']);
			$conteudo_rodape = $_POST['_ethymos_conteudo_rodape'];
			update_option('_ethymos_conteudo_rodape', $conteudo_rodape);
			
			return true;
			
		} else {
			return false;
		}	
	}
}
?>
