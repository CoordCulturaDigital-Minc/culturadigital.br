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
 * HTTP request method uses Curl extension to retrieve the url.
 *
 * Requires the Curl extension to be installed.
 */
class ehough_shortstop_impl_exec_command_CurlCommand extends ehough_shortstop_impl_exec_command_AbstractHttpExecutionCommand
{
    /** @var ehough_epilog_Logger */
    private $_logger;

    private $_handle;

    public function __construct(ehough_shortstop_spi_HttpMessageParser $messageParser, ehough_tickertape_EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($messageParser, $eventDispatcher);

        $this->_logger = ehough_epilog_LoggerFactory::getLogger('cURL Transport');
    }

    /**
     * Determines whether or not this transport is available on the system.
     *
     * @return bool True if this transport is available on the system. False otherwise.
     */
    public final function isAvailable()
    {
        $isDebugEnabled = $this->_logger->isHandling(ehough_epilog_Logger::DEBUG);

        if (! function_exists('curl_init')) {

            if ($isDebugEnabled) {

                $this->_logger->debug('curl_init() does not exist');
            }

            return false;
        }

        if (! function_exists('curl_exec')) {

            if ($isDebugEnabled) {

                $this->_logger->debug('curl_exec() does not exist');
            }

            return false;
        }

        return true;
    }

    /**
     * Determines if this transport can handle the given request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The request to handle.
     *
     * @return bool True if this transport can handle the given request. False otherwise.
     */
    public function canHandle(ehough_shortstop_api_HttpRequest $request)
    {
        return true;
    }

    /**
     * Perform optional setup to handle a new HTTP request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The HTTP request to handle.
     *
     * @return void
     */
    protected function prepareToHandleNewRequest(ehough_shortstop_api_HttpRequest $request)
    {
        $isDebugEnabled = $this->_logger->isHandling(ehough_epilog_Logger::DEBUG);

        if ($isDebugEnabled) {

            $this->_logger->debug('Initializing cURL');
        }

        $this->_handle = curl_init($request->getUrl()->toString());
        $this->_setCurlOptions($request);

        if ($isDebugEnabled) {

            $this->_logger->debug('cURL initialized');
        }
    }

    /**
     * Perform handling of the given request.
     *
     * @param ehough_shortstop_api_HttpRequest $request The HTTP request.
     *
     * @throws ehough_shortstop_api_exception_RuntimeException If something goes wrong.
     *
     * @return string The raw response for this request. May be empty or null.
     */
    protected function handleRequest(ehough_shortstop_api_HttpRequest $request)
    {
        $isDebugging = $this->_logger->isHandling(ehough_epilog_Logger::DEBUG);

        if ($isDebugging) {

            $this->_logger->debug('Calling curl_exec()');
        }

        $response = curl_exec($this->_handle);

        if ($response === false) {

            if ($curlError = curl_error($this->_handle)) {

                throw new ehough_shortstop_api_exception_RuntimeException($curlError);
            }

            throw new ehough_shortstop_api_exception_RuntimeException('cURL failed');
        }

        if ($isDebugging) {

            $this->_logger->debug('cURL returned a valid response');
        }

        return $response;
    }

    /**
     * Get the name of this transport.
     *
     * @return string The name of this transport.
     */
    protected function getTransportName()
    {
        return 'cURL';
    }

    /**
     * Get the response code.
     *
     * @return int the HTTP response code.
     */
    protected function getResponseCode()
    {
        return curl_getinfo($this->_handle, CURLINFO_HTTP_CODE);
    }

    /**
     * Perform optional tear down after handling a request.
     *
     * @return void
     */
    protected function tearDown()
    {
        if ($this->_logger->isHandling(ehough_epilog_Logger::DEBUG)) {

            $this->_logger->debug('Closing cURL');
        }

        if (isset($this->_handle)) {

            curl_close($this->_handle);
            unset($this->_handle);
        }
    }

    private function _setCurlOptions(ehough_shortstop_api_HttpRequest $request)
    {
        curl_setopt_array($this->_handle, array(

            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_HEADER         => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_0,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_URL            => $request->getUrl()->toString(),
            CURLOPT_USERAGENT      => $request->getHeaderValue(ehough_shortstop_api_HttpRequest::HTTP_HEADER_USER_AGENT),

        ));

        $this->_setCurlOptionsFollowLocation();
        $this->_setCurlOptionsBody($request);
        $this->_setCurlOptionsHeaders($request);
    }

    private function _setCurlOptionsFollowLocation()
    {
        // The option doesn't work with safe mode or when open_basedir is set.
        // Disable HEAD when making HEAD requests.
        if (!ini_get('safe_mode') && !ini_get('open_basedir')) {

            curl_setopt($this->_handle, CURLOPT_FOLLOWLOCATION, true);
        }
    }

    private function _setCurlOptionsBody(ehough_shortstop_api_HttpRequest $request)
    {
        $body = $request->getEntity() === null ? null : $request->getEntity()->getContent();

        switch ($request->getMethod()) {

            case ehough_shortstop_api_HttpRequest::HTTP_METHOD_POST:

                curl_setopt($this->_handle, CURLOPT_POST, true);
                curl_setopt($this->_handle, CURLOPT_POSTFIELDS, $body);

                break;

            case ehough_shortstop_api_HttpRequest::HTTP_METHOD_PUT:

                curl_setopt($this->_handle, CURLOPT_CUSTOMREQUEST, ehough_shortstop_api_HttpRequest::HTTP_METHOD_PUT);
                curl_setopt($this->_handle, CURLOPT_POSTFIELDS, $body);

                break;
        }
    }

    private function _setCurlOptionsHeaders(ehough_shortstop_api_HttpRequest $request)
    {
        // cURL expects full header strings in each element
        $newHeaders = array();
        $headers    = $request->getAllHeaders();

        foreach ($headers as $name => $value) {

            $newHeaders[] = "{$name}: $value";
        }

        curl_setopt($this->_handle, CURLOPT_HTTPHEADER, $newHeaders);
    }

    /**
     * @return ehough_epilog_psr_LoggerInterface
     */
    protected function getLogger()
    {
        return $this->_logger;
    }
}