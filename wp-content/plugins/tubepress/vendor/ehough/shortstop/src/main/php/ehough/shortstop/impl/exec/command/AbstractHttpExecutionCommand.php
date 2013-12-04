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
 * Lifted from http://core.trac.wordpress.org/browser/tags/3.0.4/wp-includes/class-http.php
 *
 * Base HTTP command.
 */
abstract class ehough_shortstop_impl_exec_command_AbstractHttpExecutionCommand
    implements ehough_chaingang_api_Command, ehough_shortstop_spi_HttpTransport
{
    /**
     * @var ehough_shortstop_spi_HttpMessageParser
     */
    private $_httpMessageParser;

    /**
     * @var ehough_tickertape_EventDispatcherInterface
     */
    private $_eventDispatcher;

    public function __construct(ehough_shortstop_spi_HttpMessageParser $httpMessageParser,
        ehough_tickertape_EventDispatcherInterface $eventDispatcher)
    {
        $this->_httpMessageParser = $httpMessageParser;
        $this->_eventDispatcher   = $eventDispatcher;
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
     * @throws ehough_shortstop_api_exception_RuntimeException If something goes wrong.
     *
     * @return boolean True if the processing of this Context has been completed, or false if the
     *                 processing of this Context should be delegated to a subsequent Command
     *                 in an enclosing Chain.
     */
    public final function execute(ehough_chaingang_api_Context $context)
    {
        /* this will never be null if the parent chain does its job */
        $request        = $context->get('request');
        $logger         = $this->getLogger();
        $isDebugEnabled = $logger->isHandling(ehough_epilog_Logger::DEBUG);

        if ($isDebugEnabled) {

            $logger->debug(sprintf('Seeing if able to handle %s', $request));
        }

        if ($this->isAvailable() === false || $this->canHandle($request) === false) {

            if ($isDebugEnabled) {

                $logger->debug(sprintf('Unavailable or declined to handle %s', $request));
            }

            return false;
        }

        if ($this->_objectionsToTransportSelection($request)) {

            if ($isDebugEnabled) {

                $logger->debug(sprintf('Event listeners turned down offer to handle %s', $request));
            }

            return false;
        }

        if ($isDebugEnabled) {

            $logger->debug(sprintf('Offered to handle %s. Now initializing.', $request));
        }

        try {

            $response = $this->handle($request);

            $context->put('response', $response);

            /**
             * Fire a success event.
             */
            $transportSuccessEvent = new ehough_tickertape_GenericEvent($this, array(

                'request'  => $request,
                'response' => $response
            ));
            $this->_eventDispatcher->dispatch(ehough_shortstop_api_Events::TRANSPORT_SUCCESS, $transportSuccessEvent);

            return true;

        } catch (Exception $e) {

            return $this->_handleTransportException($e, $request, $context, $isDebugEnabled, $logger);
        }
    }

    /**
     * Execute the given HTTP request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The request to execute.
     *
     * @return ehough_shortstop_api_HttpResponse The HTTP response.
     */
    public final function handle(ehough_shortstop_api_HttpRequest $request)
    {
        $logger         = $this->getLogger();
        $isDebugEnabled = $logger->isHandling(ehough_epilog_Logger::DEBUG);

        if ($isDebugEnabled) {

            $logger->debug(sprintf('Preparing to handle %s', $request));
        }

        /** allow for setup */
        $this->prepareToHandleNewRequest($request);

        /**
         * Fire transport initialized event.
         */
        $transportInitializedEvent = new ehough_tickertape_GenericEvent($this, array('request' => $request));
        $this->_eventDispatcher->dispatch(ehough_shortstop_api_Events::TRANSPORT_INITIALIZED, $transportInitializedEvent);

        if ($isDebugEnabled) {

            $logger->debug(sprintf('Now handling %s', $request));
        }

        /** handle the request. */
        $rawResponse = $this->handleRequest($request);

        if ($isDebugEnabled) {

            $logger->debug(sprintf('Assembling response from %s', $request));
        }

        $response = $this->_buildResponse($rawResponse, $request, $isDebugEnabled);

        if ($isDebugEnabled) {

            $logger->debug(sprintf('Tearing down after %s', $request));
        }

        $this->_tearDown($request, false);

        if ($isDebugEnabled) {

            $logger->debug(sprintf('Successfully handled %s', $request));
        }

        return $response;
    }

    private function _tearDown(ehough_shortstop_api_HttpRequest $request, $becauseOfError)
    {
        $this->tearDown();

        /**
         * Fire torn down event.
         */
        $transportTornDownEvent = new ehough_tickertape_GenericEvent($this, array(

            'request'       => $request,
            'becaseOfError' => $becauseOfError
        ));
        $this->_eventDispatcher->dispatch(ehough_shortstop_api_Events::TRANSPORT_TORNDOWN, $transportTornDownEvent);
    }

    /**
     * Perform handling of the given request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The HTTP request.
     *
     * @return string The raw response for this request. May be empty or null.
     */
    protected abstract function handleRequest(ehough_shortstop_api_HttpRequest $request);

    /**
     * Get the name of this transport.
     *
     * @return string The name of this transport.
     */
    protected abstract function getTransportName();

    /**
     * Get the response code.
     *
     * @return int the HTTP response code.
     */
    protected abstract function getResponseCode();

    /**
     * @return ehough_epilog_Logger
     */
    protected abstract function getLogger();

    /**
     * Perform optional setup to handle a new HTTP request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The HTTP request to handle.
     *
     * @return void
     */
    protected function prepareToHandleNewRequest(ehough_shortstop_api_HttpRequest $request)
    {
        //override point
    }

    /**
     * Perform optional tear down after handling a request.
     *
     * @return void
     */
    protected function tearDown()
    {
        //override point
    }

    private function _buildResponse($rawResponse, ehough_shortstop_api_HttpRequest $request, $debugging)
    {
        /* first separate the headers from the body */
        $headersString = $this->_httpMessageParser->getHeadersStringFromRawHttpMessage($rawResponse);

        if (empty($headersString)) {

            throw new ehough_shortstop_api_exception_RuntimeException('Could not parse headers from response');
        }

        /* grab the body (may be empty) */
        $bodyString = $this->_httpMessageParser->getBodyStringFromRawHttpMessage($rawResponse);

        /* make an array from the headers (may be empty) */
        $headers = $this->_httpMessageParser->getArrayOfHeadersFromRawHeaderString($headersString);

        /* create a new response. */
        $response = new ehough_shortstop_api_HttpResponse();

        $this->_assignStatusToResponse($response, $request, $debugging);
        $this->_assignHeadersToResponse($headers, $response, $request, $debugging);
        $this->_assignEntityToResponse($bodyString, $response, $debugging);

        return $response;
    }

    private function _assignStatusToResponse(ehough_shortstop_api_HttpResponse $response, ehough_shortstop_api_HttpRequest $request, $debugging)
    {
        $code = $this->getResponseCode();

        if ($debugging) {

            $this->getLogger()->debug(sprintf('%s returned HTTP %s', $request, $code));
        }

        $response->setStatusCode($code);
    }

    private function _assignHeadersToResponse($headerArray, ehough_shortstop_api_HttpResponse $response, ehough_shortstop_api_HttpRequest $request, $debugging)
    {
        if (! is_array($headerArray) || empty($headerArray)) {

            throw new ehough_shortstop_api_exception_RuntimeException(sprintf('No headers in response from %s', $request));
        }

        foreach ($headerArray as $name => $value) {

            if (is_array($value)) {

                $value = implode(', ', $value);
            }

            $response->setHeader($name, $value);
        }

        /* do some logging */
        if ($debugging) {

            $headerArray = $response->getAllHeaders();

            $logger = $this->getLogger();

            $logger->debug(sprintf('Here are the ' . count($headerArray) . ' headers in the response for %s', $request));

            foreach($headerArray as $name => $value) {

                $logger->debug("<!--suppress HtmlPresentationalElement --><tt>$name: $value</tt>");
            }
        }
    }

    private function _assignEntityToResponse($body, ehough_shortstop_api_HttpResponse $response, $debugging)
    {
        if ($debugging) {

            $this->getLogger()->debug('Assigning (possibly empty) entity to response');
        }

        $entity = new ehough_shortstop_api_HttpEntity();
        $entity->setContent($body);
        $entity->setContentLength(strlen($body));

        $contentType = $response->getHeaderValue(ehough_shortstop_api_HttpResponse::HTTP_HEADER_CONTENT_TYPE);

        if ($contentType !== null) {

            $entity->setContentType($contentType);
        }

        $response->setEntity($entity);
    }

    private function _objectionsToTransportSelection(ehough_shortstop_api_HttpRequest $request)
    {
        /**
         * Fire the selection through filters to allow anyone to veto it.
         */
        $okToUseTransportEvent = new ehough_tickertape_GenericEvent($this, array(

                'ok'      => true,
                'request' => $request
            )
        );
        $this->_eventDispatcher->dispatch(ehough_shortstop_api_Events::TRANSPORT_SELECTED, $okToUseTransportEvent);

        return ! $okToUseTransportEvent->getArgument('ok');
    }

    private function _handleTransportException(Exception $e, ehough_shortstop_api_HttpRequest $request, ehough_chaingang_api_Context $context,
        $isDebugEnabled, ehough_epilog_psr_LoggerInterface $logger)
    {
        $this->_tearDown($request, true);

        /**
         * Fire an error event.
         */
        $transportFailureEvent = new ehough_tickertape_GenericEvent($this, array(

            'request'            => $request,
            'response'           => $context->containsKey('response') ? $context->get('response') : null,
            'exception'          => $e,
            'rethrow'            => false,
            'tryOtherTransports' => true
        ));
        $this->_eventDispatcher->dispatch(ehough_shortstop_api_Events::TRANSPORT_FAILURE, $transportFailureEvent);

        if ($transportFailureEvent->getArgument('rethrow')) {

            if ($isDebugEnabled) {

                $logger->error(sprintf('Caught exception when handling %s (%s). Will re-throw.', $request, $e->getMessage()));
            }

            throw new ehough_shortstop_api_exception_RuntimeException($e->getMessage());
        }

        if ($transportFailureEvent->getArgument('tryOtherTransports')) {

            if ($isDebugEnabled) {

                $logger->debug('Transport failed, but trying the next...');
            }

            return false;
        }

        if ($isDebugEnabled) {

            $logger->debug('Transport failed, and not trying any others...');
        }

        return true;
    }
}