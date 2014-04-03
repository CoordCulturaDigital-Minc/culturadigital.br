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
 * An HTTP message.
 */
abstract class ehough_shortstop_api_HttpMessage
{
    const HTTP_HEADER_HTTP_VERSION     = 'HTTP-Version';
    const HTTP_HEADER_CONTENT_LENGTH   = 'Content-Length';
    const HTTP_HEADER_CONTENT_ENCODING = 'Content-Encoding';
    const HTTP_HEADER_CONTENT_TYPE     = 'Content-Type';

    private $_headers = array();

    /**
     * @var ehough_shortstop_api_HttpEntity
     */
    private $_entity;

    /**
     * Set the message entity.
     *
     * @param ehough_shortstop_api_HttpEntity $entity The entity.
     *
     * @return void
     */
    public final function setEntity(ehough_shortstop_api_HttpEntity $entity)
    {
        $this->_entity = $entity;
    }

    /**
     * Get the HTTP message entity.
     *
     * @return ehough_shortstop_api_HttpEntity The HTTP entity. May be null.
     */
    public final function getEntity()
    {
        return $this->_entity;
    }

    /**
     * Get an associative array of all headers.
     *
     * @return array An associative array of HTTP headers with this message. May be empty.
     */
    public final function getAllHeaders()
    {
        return $this->_headers;
    }

    /**
     * Find a header value by header name.
     *
     * @param string $name The header name to lookup.
     *
     * @return string The header value. May be null.
     */
    public final function getHeaderValue($name)
    {
        self::checkString($name);

        foreach ($this->_headers as $headerName => $headerValue) {

            if (strcasecmp($name, $headerName) === 0) {

                return $headerValue;
            }
        }

        return null;
    }

    /**
     * Set a single header.
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     *
     * @return void
     */
    public final function setHeader($name, $value)
    {
        self::checkString($name);
        self::checkString($value);

        $this->_headers[$name] = $value;
    }

    /**
     * Find whether or not this message carries any headers
     * with the given name.
     *
     * @param string $name The header name to lookup.
     *
     * @return bool True if a header with this name exists. False otherwise.
     */
    public final function containsHeader($name)
    {
        return $this->getHeaderValue($name) !== null;
    }

    /**
     * Removes any headers with the given name.
     *
     * @param string $name The header name.
     *
     * @return void
     */
    public final function removeHeaders($name)
    {
        self::checkString($name);

        foreach ($this->_headers as $headerName => $headerValue) {

            if (strcasecmp($name, $headerName) === 0) {

                unset($this->_headers[$headerName]);
            }
        }
    }

    /**
     * Determines if the given argument is a string.
     *
     * @param mixed $candidate The argument to check.
     *
     * @throws ehough_shortstop_api_exception_InvalidArgumentException If the argument is not a string.
     *
     * @return void
     */
    protected static function checkString($candidate)
    {
        if ($candidate != '' && ! is_string($candidate)) {

            throw new
                ehough_shortstop_api_exception_InvalidArgumentException(
                    "All HTTP headers must be strings ($candidate)"
                );
        }
    }
}