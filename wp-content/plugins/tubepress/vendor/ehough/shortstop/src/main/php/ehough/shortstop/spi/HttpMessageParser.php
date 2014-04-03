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
 * Parses out HTTP messages.
 */
interface ehough_shortstop_spi_HttpMessageParser
{
    /**
     * Gets a string representation of the headers of the given HTTP message.
     *
     * @param ehough_shortstop_api_HttpMessage $message The HTTP message.
     *
     * @return string The string representation of the HTTP headers. May be null or empty.
     */
    function getHeaderArrayAsString(ehough_shortstop_api_HttpMessage $message);

    /**
     * Given a raw string of headers, return an associative array of the headers.
     *
     * @param string $rawHeaderString The header string.
     *
     * @return array An associative array of headers with name => value. Maybe null or empty.
     */
    function getArrayOfHeadersFromRawHeaderString($rawHeaderString);

    /**
     * Give the raw string of an HTTP message, return just the header part of the message.
     *
     * @param string $message The raw HTTP message as string.
     *
     * @return string Just the HTTP headers part of the message. May be null or empty.
     */
    function getHeadersStringFromRawHttpMessage($message);

    /**
     * Give the raw string of an HTTP message, return just the body part of the message.
     *
     * @param string $message The raw HTTP message as string.
     *
     * @return string Just the HTTP body part of the message. May be null or empty.
     */
    function getBodyStringFromRawHttpMessage($message);
}