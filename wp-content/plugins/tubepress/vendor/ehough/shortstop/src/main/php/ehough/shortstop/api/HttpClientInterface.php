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
 * Handles HTTP client functionality.
 */
interface ehough_shortstop_api_HttpClientInterface
{
    /**
     * Execute a given HTTP request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The HTTP request.
     *
     * @throws ehough_shortstop_api_exception_RuntimeException If something goes wrong.
     *
     * @return ehough_shortstop_api_HttpResponse The HTTP response.
     */
    function execute(ehough_shortstop_api_HttpRequest $request);
}