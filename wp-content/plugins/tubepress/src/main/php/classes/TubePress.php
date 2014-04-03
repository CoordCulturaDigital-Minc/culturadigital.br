<?php
/**
 * Copyright 2006 - 2014 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
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
}