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
 * Decodes HTTP responses.
 */
interface ehough_shortstop_spi_HttpResponseDecoder
{
    /**
     * Determines if this response needs to be decoded.
     *
     * @param ehough_shortstop_api_HttpResponse $response The HTTP response.
     *
     * @return boolean True if this response should be decoded. False otherwise.
     */
    function needsToBeDecoded(ehough_shortstop_api_HttpResponse $response);

    /**
     * Decodes an HTTP response.
     *
     * @param ehough_shortstop_api_HttpResponse $response The HTTP response.
     *
     * @return void
     */
    function decode(ehough_shortstop_api_HttpResponse $response);
}