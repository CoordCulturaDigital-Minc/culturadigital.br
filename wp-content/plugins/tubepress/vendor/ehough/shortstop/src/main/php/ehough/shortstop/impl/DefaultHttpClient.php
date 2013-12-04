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
 * Class for managing HTTP Transports and making HTTP requests.
 */
class ehough_shortstop_impl_DefaultHttpClient implements ehough_shortstop_api_HttpClientInterface
{
    /** @var ehough_epilog_Logger */
    private $_logger;

    /**
     * @var ehough_tickertape_EventDispatcherInterface
     */
    private $_eventDispatcher;

    /**
     * @var ehough_chaingang_api_Chain
     */
    private $_executionChain;

    public function __construct(ehough_tickertape_EventDispatcherInterface $eventDispatcher,
        ehough_chaingang_api_Chain $chain)
    {
        $this->_logger          = ehough_epilog_LoggerFactory::getLogger('Default HTTP Client');
        $this->_eventDispatcher = $eventDispatcher;
        $this->_executionChain  = $chain;
    }

    /**
     * Execute a given HTTP request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The HTTP request.
     *
     * @throws ehough_shortstop_api_exception_RuntimeException If something goes wrong.
     *
     * @return ehough_shortstop_api_HttpResponse The HTTP response.
     */
    public final function execute(ehough_shortstop_api_HttpRequest $request)
    {
        /**
         * Fire request event.
         */
        $requestEvent = new ehough_tickertape_GenericEvent($request);
        $this->_eventDispatcher->dispatch(ehough_shortstop_api_Events::REQUEST, $requestEvent);

        /**
         * Execute the chain.
         */
        $chainContext = new ehough_chaingang_impl_StandardContext();
        $chainContext->put('request', $request);
        $result = $this->_executionChain->execute($chainContext);

        if (!$result || !$chainContext->containsKey('response') || (!($chainContext->get('response') instanceof ehough_shortstop_api_HttpResponse))) {

            throw new ehough_shortstop_api_exception_RuntimeException(sprintf('No HTTP transports could execute %s', $request));
        }

        /**
         * Fire response event.
         */
        $response      = $chainContext->get('response');
        $responseEvent = new ehough_tickertape_GenericEvent($response, array('request' => $requestEvent->getSubject()));
        $this->_eventDispatcher->dispatch(ehough_shortstop_api_Events::RESPONSE, $responseEvent);

        /**
         * All done. Return the response.
         */
        return $response;
    }
}