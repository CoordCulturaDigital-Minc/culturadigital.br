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

class ehough_shortstop_impl_listeners_request_RequestDefaultHeadersListener
{
    /**
     * @var ehough_epilog_Logger
     */
    private $_logger;

    /**
     * @var bool
     */
    private $_isDebugEnabled;

    /**
     * @var ehough_shortstop_spi_HttpContentDecoder
     */
    private $_httpContentDecoder;

    public function __construct(ehough_shortstop_spi_HttpContentDecoder $httpContentDecoder)
    {
        $this->_logger = ehough_epilog_LoggerFactory::getLogger('Pre-Request Default Headers Listener');

        $this->_httpContentDecoder = $httpContentDecoder;
    }

    /**
     * Sets some default headers on the request.
     *
     * @param ehough_tickertape_GenericEvent $event The pre-request event.
     *
     * @return void
     */
    public function onPreRequest(ehough_tickertape_GenericEvent $event)
    {
        $request = $event->getSubject();

        $this->_isDebugEnabled = $this->_logger->isHandling(ehough_epilog_Logger::DEBUG);

        $this->_setDefaultHeadersBasic($request);
        $this->_setDefaultHeadersCompression($request);
        $this->_setDefaultHeadersContent($request);
    }

    private function _setDefaultHeadersContent(ehough_shortstop_api_HttpRequest $request)
    {
        $entity = $request->getEntity();

        if ($entity === null) {

            if ($this->_isDebugEnabled) {

                $this->_logger->debug('No HTTP entity in request');
            }

            return;
        }

        $contentLength = $entity->getContentLength();
        $content       = $entity->getContent();
        $type          = $entity->getContentType();

        if ($content !== null && $contentLength !== null && $type !== null) {

            $request->setHeader(ehough_shortstop_api_HttpMessage::HTTP_HEADER_CONTENT_LENGTH, "$contentLength");
            $request->setHeader(ehough_shortstop_api_HttpMessage::HTTP_HEADER_CONTENT_TYPE, $type);

            return;
        }

        if ($contentLength === null && $this->_isDebugEnabled) {

            $this->_logger->warn('HTTP entity in request, but no content length set. Ignoring this entity!');
        }

        if ($content === null && $this->_isDebugEnabled) {

            $this->_logger->warn('HTTP entity in request, but no content set. Ignoring this entity!');
        }

        if ($type === null && $this->_isDebugEnabled) {

            $this->_logger->warn('HTTP entity in request, but no content type set. Ignoring this entity!');
        }
    }

    /**
     * Sets compression headers.
     *
     * @param ehough_shortstop_api_HttpRequest $request The request to modify.
     *
     * @return void
     */
    private function _setDefaultHeadersCompression(ehough_shortstop_api_HttpRequest $request)
    {
        if ($this->_isDebugEnabled) {

            $this->_logger->debug('Determining if HTTP compression is available...');
        }

        $header = $this->_httpContentDecoder->getAcceptEncodingHeaderValue();

        if ($header !== null) {

            if ($this->_isDebugEnabled) {

                $this->_logger->debug('HTTP decompression is available.');
            }

            $request->setHeader(ehough_shortstop_api_HttpRequest::HTTP_HEADER_ACCEPT_ENCODING, $header);

        } else {

            if ($this->_isDebugEnabled) {

                $this->_logger->debug('HTTP decompression is NOT available.');
            }
        }
    }

    /**
     * Sets things like user agent and HTTP version.
     *
     * @param ehough_shortstop_api_HttpRequest $request The request to modify.
     *
     * @return void
     */
    private function _setDefaultHeadersBasic(ehough_shortstop_api_HttpRequest $request)
    {
        $map = array(

            /* set our User-Agent */
            ehough_shortstop_api_HttpRequest::HTTP_HEADER_USER_AGENT   => 'shortstop; https://github.com/ehough/shortstop',

            /* set to HTTP 1.1 */
            ehough_shortstop_api_HttpRequest::HTTP_HEADER_HTTP_VERSION => 'HTTP/1.0',
        );

        foreach ($map as $headerName => $headerValue) {

            /* only set these headers if someone else hasn't already */
            if (! $request->containsHeader($headerName)) {

                $request->setHeader($headerName, $headerValue);
            }
        }
    }
}