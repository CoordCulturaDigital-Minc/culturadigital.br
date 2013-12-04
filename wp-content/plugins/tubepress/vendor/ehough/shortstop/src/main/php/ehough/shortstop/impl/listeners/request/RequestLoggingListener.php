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

class ehough_shortstop_impl_listeners_request_RequestLoggingListener
{
    /**
     * @var ehough_epilog_Logger
     */
    private $_logger;

    public function __construct()
    {
        $this->_logger = ehough_epilog_LoggerFactory::getLogger('Pre-Request Logger Listener');
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

        if ($this->_logger->isHandling(ehough_epilog_Logger::DEBUG)) {

            $this->_logger->debug(sprintf('Will attempt %s', $request));
            $this->_logRequest($request);
        }
    }

    private function _logRequest(ehough_shortstop_api_HttpRequest $request)
    {
        $headerArray = $request->getAllHeaders();

        $this->_logger->debug(sprintf('Here are the ' . count($headerArray) . ' headers in the request for %s', $request));

        foreach($headerArray as $name => $value) {

            $this->_logger->debug("<!--suppress HtmlPresentationalElement --><tt>$name: $value</tt>");
        }
    }
}