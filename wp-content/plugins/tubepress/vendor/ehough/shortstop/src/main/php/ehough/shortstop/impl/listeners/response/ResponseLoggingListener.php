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

class ehough_shortstop_impl_listeners_response_ResponseLoggingListener
{
    /**
     * @var ehough_epilog_Logger
     */
    private $_logger;

    public function __construct()
    {
        $this->_logger = ehough_epilog_LoggerFactory::getLogger('HTTP Response Logging Listener');
    }

    public function onResponse(ehough_tickertape_GenericEvent $event)
    {
        if (!$this->_logger->isHandling(ehough_epilog_Logger::DEBUG)) {

            return;
        }

        $response = $event->getSubject();
        $request  = $event->getArgument('request');

        $this->_logger->debug(sprintf('The raw result for %s is in the HTML source for this page <span style="display:none">%s</span>',
            $request, htmlspecialchars(var_export($response, true))));
    }
}