<?php

/**
 * Provides a datamapper for galleries
 */
class C_Gallery_Mapper extends C_CustomTable_DataMapper_Driver
{
    public static $_instances = array();

	/**
	 * Define the object
	 * @param string $context
	 */
	function define($context=FALSE)
	{
		// Add 'gallery' context
		if (!is_array($context)) $context = array($context);
		array_push($context, 'gallery');

		$this->_primary_key_column = 'gid';

		// Continue defining the object
		parent::define('ngg_gallery', $context);
		$this->set_model_factory_method('gallery');
		$this->add_mixin('Mixin_Gallery_Mapper');
		$this->implement('I_Gallery_Mapper');
	}

	function initialize()
	{
		parent::initialize('ngg_gallery');
	}

	/**
	 * Returns a singleton of the gallery mapper
	 * @param string $context
	 * @return C_Gallery_Mapper
	 */
    public static function get_instance($context = False)
    {
        if (!isset(self::$_instances[$context]))
        {
            self::$_instances[$context] = new C_Gallery_Mapper($context);
        }
        return self::$_instances[$context];
    }
}

class Mixin_Gallery_Mapper extends Mixin
{
	/**
	 * Uses the title property as the post title when the Custom Post driver
	 * is used
	 */
	function get_post_title($entity)
	{
		return $entity->title;
	}

	/**
	 * Override the save method to avoid trying to save the 'new_sortorder' property
	 * to the database, which will fail since the column doesn't exist in the
	 * database.
	 * TODO: This is just a workaround and should be removed when we implement
	 * https://www.wrike.com/open.htm?id=8250095
	 * @param stdClass|C_DataMapper_Model $entity
	 * @return boolean
	 */
	function _convert_to_table_data($entity)
	{
		$new_sortorder 	= property_exists($entity, 'new_sortorder') ? $entity->new_sortorder : NULL;
		unset($entity->new_sortorder);
		$retval = $this->call_parent('_convert_to_table_data', $entity);
		if ($new_sortorder) $entity->new_sortorder = $new_sortorder;
		return $retval;
	}


    function _save_entity($entity)
    {
        $retval = $this->call_parent('_save_entity', $entity);

        if ($retval) {
            do_action('ngg_created_new_gallery', $entity->{$entity->id_field});
			C_Photocrati_Cache::flush();
        }

        return $retval;
    }

	function destroy($image)
	{
		$retval = $this->call_parent('destroy',$image);
		C_Photocrati_Cache::flush();
		return $retval;
	}

    function set_preview_image($gallery, $image, $only_if_empty=FALSE)
    {
        $retval = FALSE;

        // We need the gallery object
        if (is_numeric($gallery)) {
            $gallery = $this->object->find($gallery);
        }

        // We need the image id
        if (!is_numeric($image)) {
            if (method_exists($image, 'id')) {
                $image = $image->id();
            }
            else {
                $image = $image->{$image->id_field};
            }
        }

        if ($gallery && $image) {
            if (($only_if_empty && !$gallery->previewpic) OR !$only_if_empty) {
                $gallery->previewpic = $image;
                $retval = $this->object->save($gallery);
            }
        }

        return $retval;
    }

	/**
	 * Sets default values for the gallery
	 */
	function set_defaults($entity)
	{
		// If author is missing, then set to the current user id
        // TODO: Using wordpress function. Should use abstraction
		$this->object->_set_default_value($entity, 'author', get_current_user_id());
	}
}