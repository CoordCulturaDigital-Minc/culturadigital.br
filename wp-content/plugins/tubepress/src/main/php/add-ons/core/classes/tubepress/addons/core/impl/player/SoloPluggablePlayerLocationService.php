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
 * Plays videos with jqmodal.
 */
class tubepress_addons_core_impl_player_SoloPluggablePlayerLocationService implements tubepress_spi_player_PluggablePlayerLocationService
{
    /**
     * @param tubepress_spi_theme_ThemeHandler $themeHandler The theme handler.
     *
     * @return ehough_contemplate_api_Template The player's template.
     */
    public final function getTemplate(tubepress_spi_theme_ThemeHandler $themeHandler)
    {
        return null;
    }

    /**
     * @return string The name of this playerLocation. Never empty or null. All alphanumerics and dashes.
     */
    public final function getName()
    {
        return 'solo';
    }

    /**
     * @return string Gets the relative path to this player location's JS init script.
     */
    public final function getRelativePlayerJsUrl()
    {
        return '/src/main/web/players/solo/solo.js';
    }

    /**
     * @return boolean True if this player location produces HTML, false otherwise.
     */
    public final function producesHtml()
    {
        return false;
    }

    /**
     * @return string The human-readable name of this player location.
     */
    public final function getFriendlyName()
    {
        return 'in a new window on its own';                            //>(translatable)<
    }
}