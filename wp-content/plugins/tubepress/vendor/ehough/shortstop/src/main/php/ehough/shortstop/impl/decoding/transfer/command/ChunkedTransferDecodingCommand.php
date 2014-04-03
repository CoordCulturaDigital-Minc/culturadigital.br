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
 * Decodes messages that are Transfer-Encoding: chunked
 */
class ehough_shortstop_impl_decoding_transfer_command_ChunkedTransferDecodingCommand implements ehough_chaingang_api_Command
{
    /** @var ehough_epilog_Logger */
    private $_logger;

    public function __construct()
    {
        $this->_logger = ehough_epilog_LoggerFactory::getLogger('Chunked-Transfer Decoding Command');
    }

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
    public function execute(ehough_chaingang_api_Context $context)
    {
        $response = $context->get('response');
        $encoding = $response->getHeaderValue(ehough_shortstop_api_HttpResponse::HTTP_HEADER_TRANSFER_ENCODING);

        if (strcasecmp($encoding, 'chunked') !== 0) {

            if ($this->_logger->isHandling(ehough_epilog_Logger::DEBUG)) {

                $this->_logger->debug('Response is not encoded with Chunked-Transfer');
            }

            return false;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $decoded = self::_decode($response->getEntity()->getContent());

        $context->put('response', $decoded);

        return true;
    }

    private static function _decode($body)
    {
        /* http://tools.ietf.org/html/rfc2616#section-19.4.6 */

        /* first grab the initial chunk length */
        $chunkLengthPregMatchResult = preg_match('/^\s*([0-9a-fA-F]+)(?:(?!\r\n).)*\r\n/sm', $body, $chunkLengthMatches);

        if ($chunkLengthPregMatchResult === false || count($chunkLengthMatches) !== 2) {

            throw new ehough_shortstop_api_exception_InvalidArgumentException('Data does not appear to be chunked (missing initial chunk length)');
        }

        /* set initial values */
        $currentOffsetIntoBody = strlen($chunkLengthMatches[0]);
        $currentChunkLength    = hexdec($chunkLengthMatches[1]);
        $decoded               = '';
        $bodyLength            = strlen($body);

        while ($currentChunkLength > 0) {

            /* read in the first chunk data */
            $decoded .= substr($body, $currentOffsetIntoBody, $currentChunkLength);

            /* increment the offset to what we just read */
            $currentOffsetIntoBody += $currentChunkLength;

            /* whoa nelly, we've hit the end of the road. */
            if ($currentOffsetIntoBody >= $bodyLength) {

                return $decoded;
            }

            /* grab the next chunk length */
            $chunkLengthPregMatchResult = preg_match('/\r\n\s*([0-9a-fA-F]+)(?:(?!\r\n).)*\r\n/sm', $body, $chunkLengthMatches, null, $currentOffsetIntoBody);

            if ($chunkLengthPregMatchResult === false || count($chunkLengthMatches) !== 2) {

                return $decoded;
            }

            /* increment the offset to start of next data */
            $currentOffsetIntoBody += strlen($chunkLengthMatches[0]);

            /* set up how much data we want to read */
            $currentChunkLength = hexdec($chunkLengthMatches[1]);
        }

        return $decoded;
    }
}
