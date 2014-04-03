<?php

/***
{
    Module: photocrati-nextgen_basic_templates,
    Depends: { photocrati-nextgen_gallery_display, photocrati-nextgen_basic_album }
}
 ***/

class M_NextGen_Basic_Templates extends C_Base_Module
{
	function define()
	{
		parent::define(
			'photocrati-nextgen_basic_templates',
			'NextGen Basic Templates',
			'Provides a NextGen-Legacy compatible thumbnail gallery for NextGEN Gallery',
			'0.2',
			'http://www.photocrati.com',
			'Photocrati Media',
			'http://www.photocrati.com'
		);
	}

    function _register_utilities()
    {
        $this->get_registry()->add_utility(
            'I_Legacy_Template_Locator',
            'C_Legacy_Template_Locator'
        );
    }

	function _register_adapters()
	{
		$this->get_registry()->add_adapter(
			'I_Form',
			'A_NextGen_Basic_Template_Form'
		);
	}

    function get_type_list()
    {
        return array(
            'A_Nextgen_Basic_Template_Form' => 'adapter.nextgen_basic_template_form.php',
            'C_Legacy_Template_Locator' => 'class.legacy_template_locator.php',
            'I_Legacy_Template_Locator' => 'interface.legacy_template_locator.php',
            'Mixin_Nextgen_Basic_Templates' => 'mixin.nextgen_basic_templates.php'
        );
    }
}

new M_NextGen_Basic_Templates();
