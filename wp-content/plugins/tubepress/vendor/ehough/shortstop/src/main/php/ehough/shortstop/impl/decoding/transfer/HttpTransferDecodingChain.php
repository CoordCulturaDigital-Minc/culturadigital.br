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
 * Decodes Transfer-Encoded HTTP messages using chain-of-responsibility.
 */
class ehough_shortstop_impl_decoding_transfer_HttpTransferDecodingChain extends ehough_shortstop_impl_decoding_AbstractDecodingChain
    implements ehough_shortstop_spi_HttpTransferDecoder
{
    protected function getHeaderName()
    {
        return ehough_shortstop_api_HttpResponse::HTTP_HEADER_TRANSFER_ENCODING;
    }
}