<?php

class A_NextGen_Basic_Compact_Album_Form extends Mixin_NextGen_Basic_Album_Form
{
	function get_display_type_name()
	{
		return NEXTGEN_GALLERY_NEXTGEN_BASIC_COMPACT_ALBUM;
	}

    /**
     * Returns a list of fields to render on the settings page
     */
    function _get_field_names()
    {
        $fields = parent::_get_field_names();
        $fields[] = 'thumbnail_override_settings';
        return $fields;
    }

    /**
     * Enqueues static resources required by this form
     */
    function enqueue_static_resources()
    {
        wp_enqueue_script(
            'nextgen_basic_compact_albums_settings_script',
            $this->object->get_static_url('photocrati-nextgen_basic_album#compact_settings.js'),
            array('jquery.nextgen_radio_toggle')
        );
    }
}
