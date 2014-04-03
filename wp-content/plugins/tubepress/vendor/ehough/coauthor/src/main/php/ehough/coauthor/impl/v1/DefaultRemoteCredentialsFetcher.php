<?php
/**
 * Copyright 2006 - 2013 Eric D. Hough (http://ehough.com)
 *
 * This file is part of coauthor (https://github.com/ehough/coauthor)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

class ehough_coauthor_impl_v1_DefaultRemoteCredentialsFetcher implements ehough_coauthor_spi_v1_RemoteCredentialsFetcherInterface
{
    /**
     * @var ehough_shortstop_api_HttpClientInterface
     */
    private $_httpClient;

    /**
     * @var ehough_coauthor_spi_v1_SignerInterface
     */
    private $_signer;

    public function __construct(ehough_shortstop_api_HttpClientInterface $httpClient,
                                ehough_coauthor_spi_v1_SignerInterface   $signer)
    {
        $this->_signer     = $signer;
        $this->_httpClient = $httpClient;
    }

    /**
     * Fetches a set of temporary credentials from the server.
     *
     * @param ehough_coauthor_api_v1_Credentials    $clientCredentials
     * @param ehough_coauthor_api_v1_AbstractServer $server
     * @param ehough_curly_Url                      $redirectUrl
     *
     * @return ehough_coauthor_api_v1_Credentials
     *
     * @throws ehough_coauthor_api_exception_RuntimeException If unable to retrieve the credentials for any reason.
     */
    public function fetchTemporaryCredentials(ehough_coauthor_api_v1_Credentials $clientCredentials,
                                       ehough_coauthor_api_v1_AbstractServer $server,
                                       ehough_curly_Url $redirectUrl)
    {
        /**
         * Build an HTTP request.
         */
        $httpRequest = new ehough_shortstop_api_HttpRequest(

            ehough_shortstop_api_HttpRequest::HTTP_METHOD_POST,
            $server->getTemporaryCredentialsEndpoint()
        );

        /**
         * Sign it using our client credentials.
         */
        $this->_signer->signForTemporaryCredentialsRequest($httpRequest, $clientCredentials, $redirectUrl);

        /**
         * Fetch the credentials.
         */
        return $this->_fetchCredentialsFromRemote($httpRequest, $server);
    }

    /**
     * Fetches a set of access credentials from the server.
     *
     * @param ehough_coauthor_api_v1_Credentials    $clientCredentials
     * @param ehough_coauthor_api_v1_AbstractServer $server
     * @param ehough_coauthor_api_v1_Credentials    $temporaryCredentials
     * @param string                                $verificationCode
     *
     * @return ehough_coauthor_api_v1_Credentials
     *
     * @throws ehough_coauthor_api_exception_RuntimeException If unable to retrieve the credentials for any reason.
     */
    public function fetchToken(ehough_coauthor_api_v1_Credentials $clientCredentials,
                        ehough_coauthor_api_v1_AbstractServer $server,
                        ehough_coauthor_api_v1_Credentials $temporaryCredentials,
                        $verificationCode)
    {
        /**
         * Build an HTTP request.
         */
        $httpRequest = new ehough_shortstop_api_HttpRequest(

            ehough_shortstop_api_HttpRequest::HTTP_METHOD_POST,
            $server->getTokensEndpoint()
        );

        /**
         * Sign it using our client *and* temporary credentials.
         */
        $this->_signer->signForAccessTokenRequest($httpRequest, $clientCredentials, $temporaryCredentials, $verificationCode);

        /**
         * Fetch the token.
         */
        return $this->_fetchCredentialsFromRemote($httpRequest, $server);
    }

    /**
     * @param ehough_shortstop_api_HttpRequest      $httpRequest
     * @param ehough_coauthor_api_v1_AbstractServer $server
     *
     * @return ehough_coauthor_api_v1_Credentials
     *
     * @throws ehough_coauthor_api_exception_RuntimeException
     */
    private function _fetchCredentialsFromRemote(ehough_shortstop_api_HttpRequest $httpRequest,
                                           ehough_coauthor_api_v1_AbstractServer $server)
    {
        /**
         * Get the response from the server.
         */
        $httpResponse = $this->_getHttpResponse($httpRequest, $server);

        parse_str($httpResponse->getEntity()->getContent(), $parsed);

        if (!isset($parsed['oauth_token']) || !isset($parsed['oauth_token_secret'])) {

            throw new ehough_coauthor_api_exception_RuntimeException('Malformed credentials response from ' . $server->getFriendlyName());
        }

        return new ehough_coauthor_api_v1_Credentials($parsed['oauth_token'], $parsed['oauth_token_secret']);
    }

    /**
     * @param ehough_shortstop_api_HttpRequest      $httpRequest
     * @param ehough_coauthor_api_v1_AbstractServer $server
     *
     * @return ehough_shortstop_api_HttpResponse
     *
     * @throws ehough_coauthor_api_exception_RuntimeException
     */
    private function _getHttpResponse(ehough_shortstop_api_HttpRequest $httpRequest,
                                      ehough_coauthor_api_v1_AbstractServer $server)
    {
        $server->onCredentialsOrTokenRequest($httpRequest);

        try {

            $httpResponse = $this->_httpClient->execute($httpRequest);

        } catch (ehough_shortstop_api_exception_RuntimeException $e) {

            throw new ehough_coauthor_api_exception_RuntimeException($e->getMessage());
        }

        $server->onAfterCredentialsOrTokenRequest($httpRequest, $httpResponse);

        return $httpResponse;
    }
}