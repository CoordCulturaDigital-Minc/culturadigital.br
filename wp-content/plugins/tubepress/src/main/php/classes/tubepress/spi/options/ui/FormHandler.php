<?php
/**
 * Copyright 2006 - 2013 TubePress LLC (http://tubepress.org)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Generates HTML for the options form and handles form submission.
 */
interface tubepress_spi_options_ui_FormHandler
{
    const _ = 'tubepress_spi_options_ui_FormHandler';

    /**
     * Generates the HTML for the options form.
     *
     * @return string The HTML for the options form.
    */
    function getHtml();

    /**
     * Handles form submission.
     *
     * @return array An array of failure messages if there's a problem, otherwise null.
     */
    function onSubmit();

    /**
     * Allows this form handler to be uniquely identified.
     *
     * @return string All lowercase alphanumerics.
     */
    function getName();
}
