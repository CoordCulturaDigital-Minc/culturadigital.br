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
 * Parses HTTP messages.
 */
class ehough_shortstop_impl_exec_DefaultHttpMessageParser implements ehough_shortstop_spi_HttpMessageParser
{
    /**
     * Gets a string representation of the headers of the given HTTP message.
     *
     * @param ehough_shortstop_api_HttpMessage $message The HTTP message.
     *
     * @return string The string representation of the HTTP headers. May be null or empty.
     */
    public final function getHeaderArrayAsString(ehough_shortstop_api_HttpMessage $message)
    {
        $headers = $message->getAllHeaders();

        if (! is_array($headers)) {

            return '';
        }

        $toReturn = '';

        foreach ($headers as $name => $value) {

            $toReturn .= "$name: $value\r\n";
        }

        return $toReturn;
    }

    /**
     * Given a raw string of headers, return an associative array of the headers.
     *
     * @param string $rawHeaderString The header string.
     *
     * @return array An associative array of headers with name => value. Maybe null or empty.
     */
    public final function getArrayOfHeadersFromRawHeaderString($rawHeaderString)
    {
        // split headers, one per array element
        if (is_string($rawHeaderString)) {

            // tolerate line terminator: CRLF = LF (RFC 2616 19.3)
            $rawHeaderString = str_replace("\r\n", "\n", $rawHeaderString);

            // unfold folded header fields. LWS = [CRLF] 1*(SP | HT) <US-ASCII SP, space (32)>, <US-ASCII HT, horizontal-tab (9)> (RFC 2616 2.2)
            $rawHeaderString = preg_replace('/\n[ \t]/', ' ', $rawHeaderString);

            // create the headers array
            $headers = explode("\n", $rawHeaderString);

        } else {

            $headers = array();
        }

        $toReturn = array();

        foreach ($headers as $header) {

            if (empty($header) || strpos($header, ':') === false) {

                continue;
            }

            list($headerName, $headerValue) = explode(':', $header, 2);

            if (empty($headerValue)) {

                continue;
            }

            if (isset($toReturn[$headerName])) {

                if (!is_array($toReturn[$headerName])) {

                    $toReturn[$headerName] = array($toReturn[$headerName]);
                }

                $toReturn[$headerName][] = trim($headerValue);

            } else {

                $toReturn[$headerName] = trim($headerValue);
            }
        }

        return $toReturn;
    }

    /**
     * Give the raw string of an HTTP message, return just the header part of the message.
     *
     * @param string $message The raw HTTP message as string.
     *
     * @return string Just the HTTP headers part of the message. May be null or empty.
     */
    public final function getHeadersStringFromRawHttpMessage($message)
    {
        return self::_explode($message, 0);
    }

    /**
     * Give the raw string of an HTTP message, return just the body part of the message.
     *
     * @param string $message The raw HTTP message as string.
     *
     * @return string Just the HTTP body part of the message. May be null or empty.
     */
    public final function getBodyStringFromRawHttpMessage($message)
    {
        return self::_explode($message, 1);
    }

    private static function _explode($string, $index)
    {
        if (! is_string($string)) {

            return null;
        }

        $pieces = explode("\r\n\r\n", $string, 2);

        if (isset($pieces[$index])) {

            return $pieces[$index];
        }

        return null;
    }
}