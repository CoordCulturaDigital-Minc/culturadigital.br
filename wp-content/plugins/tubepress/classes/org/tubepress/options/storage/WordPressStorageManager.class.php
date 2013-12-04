<?php
/**
 * Copyright 2006 - 2010 Eric D. Hough (http://ehough.com)
 * 
 * This file is part of TubePress (http://tubepress.org)
 * 
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

function_exists('tubepress_load_classes')
    || require(dirname(__FILE__) . '/../../../../tubepress_classloader.php');
tubepress_load_classes(array('org_tubepress_options_storage_AbstractStorageManager',
    'org_tubepress_options_reference_SimpleOptionsReference',
    'org_tubepress_message_WordPressMessageService',
    'org_tubepress_options_validation_SimpleInputValidationService'));

/**
 * Implementation of org_tubepress_options_storage_StorageManager that uses the
 * regular WordPress options API
 *
 */
class org_tubepress_options_storage_WordPressStorageManager extends org_tubepress_options_storage_AbstractStorageManager
{
    /*
     * Prefix all our option names in the WordPress DB
     * with this value. Helps avoid naming conflicts.
     */
    const OPTION_PREFIX = "tubepress-";
    
    /**
     * Constructor. Until I can come up with a better way to validate options, this is gonna be how we
     * check to make sure that the db is initialized.
     * 
     * @return unknown_type
     */
    public function __construct()
	{
	    $needToInit = false;
	    
	    if ($this->exists("version")) {
	        $version = $this->get("version");
	        if (!is_numeric($version) || $version < 188) {
	           $needToInit = true;
	        }
	    } else {
	        $this->create("version", 188);
	        $needToInit = true;
	    }
	    
	    if ($needToInit) {
	        $reference = new org_tubepress_options_reference_SimpleOptionsReference();
		    $msgService = new org_tubepress_message_WordPressMessageService();
		    $validationService = new org_tubepress_options_validation_SimpleInputValidationService();
		    $validationService->setMessageService($msgService);
    	    $ref = new org_tubepress_options_reference_SimpleOptionsReference();
	        $this->setValidationService($validationService);
	        $this->setOptionsReference($ref);
	        $this->init();
	    }
	}
    
    /**
     * Creates an option in storage
     *
     * @param unknown_type $optionName  The name of the option to create
     * @param unknown_type $optionValue The default value of the new option
     * 
     * @return void
     */
    protected function create($optionName, $optionValue)
    {
        add_option(org_tubepress_options_storage_WordPressStorageManager::OPTION_PREFIX . $optionName,
            $optionValue);
    }    
    
    /**
     * Deletes an option from storage
     *
     * @param unknown_type $optionName The name of the option to delete
     * 
     * @return void
     */
    protected function delete($optionName)
    {
        delete_option(org_tubepress_options_storage_WordPressStorageManager::OPTION_PREFIX . $optionName);
    }    
    
    /**
     * Determines if an option exists
     *
     * @param string $optionName The name of the option in question
     * 
     * @return boolean True if the option exists, false otherwise
     */
    public function exists($optionName)
    {
        return get_option(org_tubepress_options_storage_WordPressStorageManager::OPTION_PREFIX . $optionName)
           !== false;
    }    
    
    /**
     * Retrieve the current value of an option
     *
     * @param string $optionName The name of the option
     * 
     * @return unknown_type The option's value
     */
    public function get($optionName)
    {
        return get_option(org_tubepress_options_storage_WordPressStorageManager::OPTION_PREFIX . $optionName);
    }
    
    /**
     * Wipes out all TubePress options and replaces them with their defaults
     * 
     * @return void
     */
    public function nuclear()
    {
        $allOptions = get_alloptions();
        foreach ($allOptions as $key => $value) {
            if (preg_match("/^tubepress.*/", $key)) {
                delete_option($key);
            }
        }
        $this->init();
    }    
    
    /**
     * Sets an option to a new value, without validation
     *
     * @param string       $optionName  The name of the option to update
     * @param unknown_type $optionValue The new option value
     * 
     * @return void
     */
    protected function setOption($optionName, $optionValue)
    {
        update_option(org_tubepress_options_storage_WordPressStorageManager::OPTION_PREFIX . $optionName,
            $optionValue);
    }
}
