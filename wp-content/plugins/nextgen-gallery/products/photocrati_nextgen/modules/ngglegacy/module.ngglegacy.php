<?php

/***
	{
		Module: photocrati-nextgen-legacy
	}
 ***/

define(
	'NEXTGEN_GALLERY_NGGLEGACY_MOD_DIR',
	path_join(NEXTGEN_GALLERY_MODULE_DIR, basename(dirname(__FILE__)))
);

define(
	'NEXTGEN_GALLERY_NGGLEGACY_MOD_URL',
	path_join(NEXTGEN_GALLERY_MODULE_URL, basename(dirname(__FILE__)))
);

class M_NggLegacy extends C_Base_Module
{
	function define()
	{
		parent::define(
			'photocrati-nextgen-legacy',
			'NextGEN Legacy',
			'Embeds the original version of NextGEN 1.9.3 by Alex Rabe',
			'0.2',
			'http://www.nextgen-gallery.com',
			'Photocrati Media',
			'http://www.photocrati.com'
		);

		include_once('class.ngglegacy_installer.php');
		C_Photocrati_Installer::add_handler($this->module_id, 'C_NggLegacy_Installer');
	}

	function initialize()
	{
		parent::initialize();
		include_once(path_join(dirname(__FILE__), 'nggallery.php'));
	}

	function get_type_list()
	{
		return array(
			'C_NggLegacy_Installer' => 'class.ngglegacy_installer.php'
		);
	}
}

new M_NggLegacy();
