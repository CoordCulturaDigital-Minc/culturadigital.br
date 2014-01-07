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
 * HTTP request method uses fopen function to retrieve the url.
 *
 * Does not allow for $context support,
 * but should still be okay, to write the headers, before getting the response. Also requires that
 * 'allow_url_fopen' to be enabled.
 *
 */
class ehough_shortstop_impl_exec_command_FopenCommand extends ehough_shortstop_impl_exec_command_AbstractHttpExecutionCommand
{
    private static $_fopen_readonly = 'r';

    private static $_meta_wrapper      = 'wrapper_data';
    private static $_meta_wrapper_info = 'headers';

    private $_handle;

    /** @var ehough_epilog_Logger */
    private $_logger;

    public function __construct(ehough_shortstop_spi_HttpMessageParser $messageParser, ehough_tickertape_EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($messageParser, $eventDispatcher);

        $this->_logger = ehough_epilog_LoggerFactory::getLogger('fOpen Transport');
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

            $this->_logger->debug('Calling fopen()...');
        }

        $this->_handle = @fopen($request->getUrl()->toString(), self::$_fopen_readonly);

        if ($this->_handle === false) {

            throw new ehough_shortstop_api_exception_RuntimeException(sprintf('Could not open handle for fopen() to %s', $request->getUrl()));
        }

        if ($isDebugging) {

            $this->_logger->debug('Successfully opened stream');
        }

        stream_set_timeout($this->_handle, 15);

        $rawContent = '';

        while (! feof($this->_handle)) {

            $rawContent .= fread($this->_handle, 4096);
        }

        if ($isDebugging) {

            $this->_logger->debug('Done reading stream');
        }

        if (function_exists('stream_get_meta_data')) {

            if ($isDebugging) {

                $this->_logger->debug('Asking for stream metadata');
            }

            $meta = stream_get_meta_data($this->_handle);

            $rawHeaders = $meta[self::$_meta_wrapper];

            if (isset($meta[self::$_meta_wrapper][self::$_meta_wrapper_info])) {

                $rawHeaders = $meta[self::$_meta_wrapper][self::$_meta_wrapper_info];
            }

        } else {

            if ($isDebugging) {

                $this->_logger->debug('stream_get_meta_data() does not exist');
            }

            //$http_response_header is a PHP reserved variable which is set in the current-scope when using the HTTP Wrapper
            //see http://php.oregonstate.edu/manual/en/reserved.variables.httpresponseheader.php
            $rawHeaders = $http_response_header;
        }

        return implode("\r\n", $rawHeaders) . "\r\n\r\n" . $rawContent;
    }

    /**
     * Get the name of this transport.
     *
     * @return string The name of this transport.
     */
    protected function getTransportName()
    {
        return 'fopen()';
    }

    /**
     * Get the response code.
     *
     * @return int the HTTP response code.
     */
    protected function getResponseCode()
    {
        /* fopen will bail on any non-200 code */
        return 200;
    }

    /**
     * Perform optional tear down after handling a request.
     *
     * @return void
     */
    protected function tearDown()
    {
        @fclose($this->_handle);
        unset($this->_handle);
    }

    /**
     * Determines whether or not this transport is available on the system.
     *
     * @return bool True if this transport is available on the system. False otherwise.
     */
    public function isAvailable()
    {
        $isDebugging = $this->_logger->isHandling(ehough_epilog_Logger::DEBUG);

        if (! function_exists('fopen')) {

            if ($isDebugging) {

                $this->_logger->debug('fopen() does not exist');
            }

            return false;
        }

        if (function_exists('ini_get') && ini_get('allow_url_fopen') != true) {

            if ($isDebugging) {

                $this->_logger->debug('allow_url_fopen is set to false');
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
        return $request->getMethod() === ehough_shortstop_api_HttpRequest::HTTP_METHOD_GET;
    }

    /**
     * @return ehough_epilog_psr_LoggerInterface
     */
    protected function getLogger()
    {
        return $this->_logger;
    }
}