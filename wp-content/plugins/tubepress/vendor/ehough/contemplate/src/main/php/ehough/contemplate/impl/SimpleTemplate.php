<?php
/**
 * Copyright 2006 - 2013 Eric D. Hough (http://ehough.com)
 *
 * This file is part of contemplate (https://github.com/ehough/contemplate)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Template implementation.
 */
class ehough_contemplate_impl_SimpleTemplate implements ehough_contemplate_api_Template
{
    /**
     * @var array
     */
    private $_source = array();

    /**
     * @var string
     */
    private $_path;

    /**
     * Sets the template path.
     *
     * @param string $path The path to the template file.
     *
     * @return void
     *
     * @throws ehough_contemplate_api_exception_InvalidArgumentException If the path is not a readable file.
     */
    public final function setPath($path)
    {
        if (! is_file($path) || ! is_readable($path)) {

            throw new ehough_contemplate_api_exception_InvalidArgumentException("Cannot read template at $path");
        }

        $this->_path = $path;
    }

    /**
     * Set a template variable.
     *
     * @param string $name  The name of the template variable to set.
     * @param mixed  $value The value of the template variable.
     *
     * @return void
     */
    public function setVariable($name, $value)
    {
        $this->_source[$name] = $value;
    }

    /**
     * Converts this template to a string.
     *
     * @return string The string representation of this template.
     */
    public function toString()
    {
        ob_start();

        extract($this->_source);

        /** @noinspection PhpIncludeInspection */
        include $this->_path;

        $result = ob_get_contents();

        ob_end_clean();

        return $result;
    }

    /**
     * Resets this template for use. Clears out any set variables.
     *
     * @return void
     */
    public function reset()
    {
        $this->_source = array();
    }

    /**
     * Get the value of a variable set on this template.
     *
     * @param string $name The name of the template variable to fetch.
     *
     * @return mixed The value of the variable, or null if not set.
     */
    public function getVariable($name)
    {
        if (!$this->hasVariable($name)) {

            return null;
        }

        return $this->_source[$name];
    }

    /**
     * Determine if this template has a particular variable set on it.
     *
     * @param string $name The name of the variable to check for.
     *
     * @return boolean True if this variable has been set, false otherwise.
     */
    public function hasVariable($name)
    {
        return isset($this->_source[$name]);
    }
}