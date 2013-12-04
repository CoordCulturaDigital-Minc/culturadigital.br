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
 * Template instance.
 */
interface ehough_contemplate_api_Template
{
    /**
     * Converts this template to a string.
     *
     * @return string The string representation of this template.
     */
    function toString();

    /**
     * Set a template variable.
     *
     * @param string $name  The name of the template variable to set.
     * @param mixed  $value The value of the template variable.
     *
     * @return void
     */
    function setVariable($name, $value);

    /**
     * Get the value of a variable set on this template.
     *
     * @param string $name The name of the template variable to fetch.
     *
     * @return mixed The value of the variable, or null if not set.
     */
    function getVariable($name);

    /**
     * Determine if this template has a particular variable set on it.
     *
     * @param string $name The name of the variable to check for.
     *
     * @return boolean True if this variable has been set, false otherwise.
     */
    function hasVariable($name);

    /**
     * Resets this template for use. Clears out any set variables.
     *
     * @return void
     */
    function reset();
}