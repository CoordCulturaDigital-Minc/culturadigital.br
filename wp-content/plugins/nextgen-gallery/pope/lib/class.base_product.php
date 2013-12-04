<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * A Product is a collection of modules with some meta data.
 *
 * Products are responsible for including and loading any modules required
 * for the functionality of the product.
 *
 * Module initialization is handled by the bootstrap procedure.
 */
abstract class C_Base_Product extends C_Base_Module
{
	function define($id, $name, $description='', $version='', $uri='', $author='', $author_uri='')
	{
		parent::define($id, $name, $description, $version, $uri, $author, $author_uri);

		$this->get_registry()->add_product($this->module_id, $this);
	}

  function get_type_list()
  {
      return array();
  }

	function is_background_product()
	{
		return false;
	}

	function get_dashboard_message($type = null)
	{
		return false;
	}
}
