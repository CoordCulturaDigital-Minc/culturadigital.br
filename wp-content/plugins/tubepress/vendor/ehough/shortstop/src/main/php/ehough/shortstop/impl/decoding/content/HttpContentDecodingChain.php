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
 * Decodes Content-Encoded HTTP messages using chain-of-responsibility.
 */
class ehough_shortstop_impl_decoding_content_HttpContentDecodingChain extends ehough_shortstop_impl_decoding_AbstractDecodingChain
    implements ehough_shortstop_spi_HttpContentDecoder
{
    protected final function getHeaderName()
    {
        return ehough_shortstop_api_HttpResponse::HTTP_HEADER_CONTENT_ENCODING;
    }

    /**
     * Get the Accept-Encoding header value to send with HTTP requests.
     *
     * @return string the Accept-Encoding header value to send with HTTP requests. May be null.
     */
    public final function getAcceptEncodingHeaderValue()
    {
        /* we can always handle gzip */
        $toReturn = 'gzip';

        /* we can sometimes do deflate... */
        if (function_exists('gzuncompress') || function_exists('gzinflate')) {

            $toReturn .= ';q=1.0, deflate;q=0.5';
        }

        return $toReturn;
    }
}