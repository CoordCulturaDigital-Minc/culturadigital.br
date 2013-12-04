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
 * Abstract decompressor.
 */
abstract class ehough_shortstop_impl_decoding_content_command_AbstractContentDecompressingCommand implements ehough_chaingang_api_Command
{
    /**
     * Execute a unit of processing work to be performed.
     *
     * This Command may either complete the required processing and return true,
     * or delegate remaining processing to the next Command in a Chain containing
     * this Command by returning false.
     *
     * @param ehough_chaingang_api_Context $context The Context to be processed by this Command.
     *
     * @return boolean True if the processing of this Context has been completed, or false if the
     *                 processing of this Context should be delegated to a subsequent Command
     *                 in an enclosing Chain.
     */
    public final function execute(ehough_chaingang_api_Context $context)
    {
        $response       = $context->get('response');
        $encoding       = $response->getHeaderValue(ehough_shortstop_api_HttpResponse::HTTP_HEADER_CONTENT_ENCODING);
        $logger         = $this->getLogger();
        $isDebugEnabled = $logger->isHandling(ehough_epilog_Logger::DEBUG);

        if (strcasecmp($encoding, $this->getExpectedContentEncodingHeaderValue()) !== 0) {

            if ($isDebugEnabled) {

                $logger->debug(sprintf('Content is not encoded with %s', $this->getExpectedContentEncodingHeaderValue()));
            }

            return false;
        }

        if (! $this->isAvailiable()) {

            if ($isDebugEnabled) {

                $logger->debug('Not available on this installation.');
            }

            return false;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $compressed = $response->getEntity()->getContent();

        if (! is_string($compressed)) {

            $logger->error('Can only decompress string data');

            return false;
        }

        if ($isDebugEnabled) {

            $logger->debug('Attempting to decompress data...');
        }

        /* this will throw an exception if we couldn't decompress it. */
        try {

            $uncompressed = $this->getUncompressed($compressed);

        } catch (Exception $e) {

            return false;
        }

        /* do some logging. */
        if ($isDebugEnabled) {

            $this->_logSuccess($logger, strlen($compressed), strlen($uncompressed));
        }

        $context->put('response', $uncompressed);

        /* signal that we've handled execution. */
        return true;
    }

    /**
     * Get the uncompressed version of the given data.
     *
     * @param string $compressed The compressed data.
     *
     * @return string The uncompressed data.
     */
    protected abstract function getUncompressed($compressed);

    /**
     * Get the "friendly" name for logging purposes.
     *
     * @return string The "friendly" name of this compression.
     */
    protected abstract function getDecompressionName();

    /**
     * Determines if this compression is available on the host system.
     *
     * @return boolean True if compression is available on the host system, false otherwise.
     */
    protected abstract function isAvailiable();

    /**
     * Get the Content-Encoding header value that this command can handle.
     *
     * @return string The Content-Encoding header value that this command can handle.
     */
    protected abstract function getExpectedContentEncodingHeaderValue();

    /**
     * @return ehough_epilog_Logger
     */
    protected abstract function getLogger();

    private function _logSuccess(ehough_epilog_psr_LoggerInterface $logger, $before, $after)
    {
        $ratio = 100;

        if ($before != 0) {

            $ratio = number_format(($after / $before) * 100, 2);
        }

        $logger->debug(sprintf('Successfully decoded entity. Result is %s' . '%% of the original size (%s / %s).',
            $ratio, $after, $before));
    }
}