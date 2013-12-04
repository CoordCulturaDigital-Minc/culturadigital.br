<?php
/**
 * Copyright 2013 Eric D. Hough (http://ehough.com)
 *
 * This file is part of shortstop (https://github.com/ehough/shortstop)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Underlying HTTP transport.
 */
interface ehough_shortstop_spi_HttpTransport
{
    /**
     * Determines whether or not this transport is available on the system.
     *
     * @return bool True if this transport is available on the system. False otherwise.
     */
    function isAvailable();

    /**
     * Determines if this transport can handle the given request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The request to handle.
     *
     * @return bool True if this transport can handle the given request. False otherwise.
     */
    function canHandle(ehough_shortstop_api_HttpRequest $request);

    /**
     * Execute the given HTTP request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The request to execute.
     *
     * @return ehough_shortstop_api_HttpResponse The HTTP response.
     */
    function handle(ehough_shortstop_api_HttpRequest $request);
}