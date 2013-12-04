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
 * An HTTP entity.
 */
class ehough_shortstop_api_HttpEntity
{
    private $_content;

    private $_contentLength = 0;

    private $_contentType;


    /**
     * Get the content of this entity.
     *
     * @return mixed The content of this entity. May be null.
     */
    public final function getContent()
    {
        return $this->_content;
    }

    /**
     * Sets the content of this entity.
     *
     * @param mixed $content The entity content.
     *
     * @return void
     */
    public final function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * Get the Content-Length of this entity.
     *
     * @return int The content length of this entity.
     */
    public final function getContentLength()
    {
        return $this->_contentLength;
    }

    /**
     * Sets the content length of the entity.
     *
     * @param int $length The Content-Length.
     *
     * @throws ehough_shortstop_api_exception_InvalidArgumentException
     *           If the supplied length is not a non-negative integer.
     *
     * @return void
     */
    public final function setContentLength($length)
    {
        if (! is_numeric($length)) {

            throw new
                ehough_shortstop_api_exception_InvalidArgumentException("Content-Length must be an integer ($length)");
        }

        $length = intval($length);

        if ($length < 0) {

            throw new
                ehough_shortstop_api_exception_InvalidArgumentException("Content-Length cannot be negative ($length)");
        }

        $this->_contentLength = $length;
    }

    /**
     * Get the Contenty-Type of this entity.
     *
     * @return string The Content-Type of this entity. May be null.
     */
    public final function getContentType()
    {
        return $this->_contentType;
    }

    /**
     * Sets the Contenty-Type of this entity
     *
     * @param string $type The Contenty-Type of this entity.
     *
     * @throws ehough_shortstop_api_exception_InvalidArgumentException If the given type is not a string.
     *
     * @return void
     */
    public final function setContentType($type)
    {
        if (! is_string($type)) {

            throw new ehough_shortstop_api_exception_InvalidArgumentException("Content-Type must be a string ($type)");
        }

        $this->_contentType = $type;
    }
}