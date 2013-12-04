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
 * Deflates data according to RFC 1952.
 */
class ehough_shortstop_impl_decoding_content_command_NativeGzipDecompressingCommand extends ehough_shortstop_impl_decoding_content_command_AbstractContentDecompressingCommand
{
    /**
     * @var ehough_epilog_Logger
     */
    private $_logger;

    /**
     * Get the uncompressed version of the given data.
     *
     * @param string $compressed The compressed data.
     *
     * @throws ehough_shortstop_api_exception_RuntimeException If we couldn't use gzdecode.
     *
     * @return string The uncompressed data.
     */
    protected function getUncompressed($compressed)
    {
        /** @noinspection PhpUndefinedFunctionInspection */
        $decompressed = @gzdecode($compressed);

        if ($decompressed === false) {

            throw new ehough_shortstop_api_exception_RuntimeException('Could not decompress data with native gzdecode()');
        }

        return $decompressed;
    }

    /**
     * Get the "friendly" name for logging purposes.
     *
     * @return string The "friendly" name of this compression.
     */
    protected function getDecompressionName()
    {
        return 'Native gzip';
    }

    /**
     * Determines if this compression is available on the host system.
     *
     * @return boolean True if compression is available on the host system, false otherwise.
     */
    protected function isAvailiable()
    {
        return function_exists('gzdecode');
    }

    /**
     * Get the Content-Encoding header value that this command can handle.
     *
     * @return string The Content-Encoding header value that this command can handle.
     */
    protected function getExpectedContentEncodingHeaderValue()
    {
        return 'gzip';
    }

    /**
     * @return ehough_epilog_psr_LoggerInterface
     */
    protected function getLogger()
    {
        if (! isset($this->_logger)) {

            $this->_logger = ehough_epilog_LoggerFactory::getLogger('gzdecode Decompressor');
        }

        return $this->_logger;
    }
}