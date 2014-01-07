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
 * Builds template instances.
 */
interface ehough_contemplate_api_TemplateBuilder
{
    /**
     * Get a new template instance.
     *
     * @param string $path The absolute path of the template.
     *
     * @return ehough_contemplate_api_Template The template instance.
     *
     * @throws ehough_contemplate_api_exception_InvalidArgumentException If the given file does not exist.
     */
    function getNewTemplateInstance($path);
}