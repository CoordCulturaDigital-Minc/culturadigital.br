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
 * Primary API for TubePress user code.
 *
 * @api
 * @since 3.1.0
 */
class TubePress
{
    /**
     * Retrieve a service instance by identifier.
     *
     * @param string $serviceId The service ID.
     *
     * @return mixed The service instance, or null if no such service.
     *
     * @api
     * @since 3.1.0
     */
    public static function getService($serviceId)
    {
        try {

            return tubepress_impl_patterns_sl_ServiceLocator::getService($serviceId);

        } catch (ehough_iconic_exception_InvalidArgumentException $e) {

            return null;
        }
    }

    /**
     * Retrieve all services that have been registered with the given tag.
     *
     * @param string $tag The tag to retrieve.
     *
     * @return array An array of service instances that have been registered with the given tag.
     *               May be empty, never null.
     *
     * @api
     * @since 3.1.0
     */
    public static function getServicesWithTag($tag)
    {
        return tubepress_impl_patterns_sl_ServiceLocator::getServicesWithTag($tag);
    }
}