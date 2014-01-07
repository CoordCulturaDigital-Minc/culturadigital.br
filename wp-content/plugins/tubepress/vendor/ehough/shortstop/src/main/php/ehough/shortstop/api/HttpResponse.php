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
 * An HTTP response.
 */
class ehough_shortstop_api_HttpResponse extends ehough_shortstop_api_HttpMessage
{
    const HTTP_STATUS_CODE_OK = 200;

    const HTTP_HEADER_TRANSFER_ENCODING = 'Transfer-Encoding';

    private $_statusCode;

    /**
     * Gets the HTTP status code.
     *
     * @return int The HTTP status code.
     */
    public final function getStatusCode()
    {
        return $this->_statusCode;
    }

    /**
     * Sets the HTTP status code.
     *
     * @param int $code The HTTP status code.
     *
     * @throws ehough_shortstop_api_exception_InvalidArgumentException
     *          If the given code is not an integer between 100 and 599.
     *
     * @return void
     */
    public final function setStatusCode($code)
    {
        if (! is_numeric($code)) {

            throw new ehough_shortstop_api_exception_InvalidArgumentException(
                'Status code must be an integer (' . $code . ')'
            );
        }

        $code = intval($code);

        if ($code < 100 || $code > 599) {

            throw new ehough_shortstop_api_exception_InvalidArgumentException(
                'Status code must be in the range of 100 - 599'
            );
        }

        $this->_statusCode = $code;
    }
}