<?php
/**
* WPlize [Klasse]
*
* Updaten, Setzen, Holen und L&ouml;schen von Optionen in WordPress
*
* WPlize gruppiert und verwaltet alle Optionen eines Plugins bzw.
* Themes in einem einzigen Optionsfeld. Die Anzahl der
* Datenbankabfragen und somit die Ladezeit des Blogs k&ouml;nnen sich
* sich enorm verringern. WPlize richtet sich an die Entwickler
* von WordPress-Plugins und -Themes.
*
* @package  WPlize.php
* @author   Sergej M&uuml;ller und Frank B&uuml;ltge
* @since    26.09.2008
* @change   26.09.2008
* @access   public
*/


class WPlize {
	
	
	/**
  * WPlize [Konstruktor]
  *
  * Setzt Eigenschafen fest und startet die Initialisierung
  *
  * @package  WPlize.php
  * @author   Sergej Müller
  * @since    26.09.2008
  * @change   03.12.2008
  * @access		public
  * @param    array  $option  Name der Multi-Option in der DB [optional]
  * @param    array  $data  	Array mit Anfangswerten [optional]
  */
	
	function WPlize($option = '', $data = array()) {
		if (empty($option) === true) {
			$this->multi_option = 'WPlize_'. md5(get_bloginfo('home'));
		} else {
			$this->multi_option = $option;
		}
		
		if ($data) {
			$this->init_option($data);
		}
	}
	
	
	/**
  * init_option
  *
  * Initialisiert die Multi-Option in der DB
  *
  * @package  WPlize.php
  * @author   Sergej Müller
  * @since    26.09.2008
  * @change   27.02.2009
  * @access		public
  * @param    array  $data  Array mit Anfangswerten [optional]
  */
  
	function init_option($data = array()) {
		add_option($this->multi_option, $data, '', false);	
	}
	
	
	/**
  * delete_option
  *
  * Entfernt die Multi-Option aus der DB
  *
  * @package  WPlize.php
  * @author   Sergej Müller
  * @since    26.09.2008
  * @change   26.09.2008
  * @access		public
  */
	
	function delete_option() {
		delete_option($this->multi_option);
	}
	
	
	/**
  * get_option
  *
  * Liefert den Wert einer Option
  *
  * @package  WPlize.php
  * @author   Sergej Müller
  * @since    26.09.2008
  * @change   26.09.2008
  * @access		public
  * @param    string  $key  Name der Option
  * @return   mixed         Wert der Option [false im Fehlerfall]
  */
	
	function get_option($key) {
		if (empty($key) === true) {
			return false;
		}
		
		$data = get_option($this->multi_option);
		
		return @$data[$key];
	}
	
	
	/**
  * update_option
  *
  * Weist den Optionen neue Werte zu
  *
  * @package  WPlize.php
  * @author   Sergej Müller
  * @since    26.09.2008
  * @change   07.12.2008
  * @access		public
  * @param    mixed    $key  	 Name der Option [alternativ Array mit Optionen]
  * @param    string   $value  Wert der Option [optional]
  * @return   boolean          False im Fehlerfall
  */
	
	function update_option($key, $value = '') {
		if (empty($key) === true) {
			return false;
		}
		
		if (is_array($key) === true) {
			$data = $key;
		} else {
			$data = array($key => $value);
		}
		
		if (is_array(get_option($this->multi_option)) === true) {
			$update = array_merge(
							  					  get_option($this->multi_option),
							  					  $data
							  					 );
		} else {
			$update = $data;
		}
		
		update_option(
									$this->multi_option,
								  $update
								 );
	}
}
?>