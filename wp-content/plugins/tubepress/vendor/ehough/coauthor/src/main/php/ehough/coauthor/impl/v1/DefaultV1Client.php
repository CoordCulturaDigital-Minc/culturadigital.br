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

class ehough_coauthor_impl_v1_DefaultV1Client implements ehough_coauthor_api_v1_ClientInterface
{
    /**
     * @var bool Are we in test mode?
     */
    private $_inTestMode = false;

    /**
     * @var ehough_coauthor_spi_v1_TemporaryCredentialsStorageInterface
     */
    private $_credentialsStorage;

    /**
     * @var ehough_coauthor_spi_v1_RemoteCredentialsFetcherInterface
     */
    private $_credentialsFetcher;

    /**
     * @var ehough_coauthor_spi_v1_SignerInterface
     */
    private $_signer;

    public function __construct(ehough_coauthor_spi_v1_TemporaryCredentialsStorageInterface $credentialsStorage,
                                ehough_coauthor_spi_v1_RemoteCredentialsFetcherInterface $credentialsFetcher,
                                ehough_coauthor_spi_v1_SignerInterface $signer)
    {
        $this->_credentialsStorage = $credentialsStorage;
        $this->_credentialsFetcher = $credentialsFetcher;
        $this->_signer             = $signer;
    }

    /**
     * Commence authorization by fetching a set of temporary credentials.
     *
     * WARNING. Invoking this function will cause a redirect to the $redirectUrl parameter. Thus, script
     * execution will come to a complete halt from the caller's perspective.
     *
     * http://tools.ietf.org/html/rfc5849#section-2.1
     *
     * @param ehough_coauthor_api_v1_AbstractServer $server
     * @param string|ehough_curly_Url               $redirectUrl
     * @param ehough_coauthor_api_v1_Credentials    $clientCredentials
     *
     * @return void
     *
     * @throws ehough_coauthor_api_exception_RuntimeException If anything fails.
     */
    public function commenceNewAuthorization(ehough_coauthor_api_v1_AbstractServer $server,
                                             $redirectUrl,
                                             ehough_coauthor_api_v1_Credentials $clientCredentials)
    {
        if (!($redirectUrl instanceof ehough_curly_Url)) {

            $redirectUrl = new ehough_curly_Url($redirectUrl);
        }

        /**
         * Grab the temporary credentials from the server.
         *
         * http://tools.ietf.org/html/rfc5849#section-2.1
         */
        $temporaryCredentials = $this->_credentialsFetcher->fetchTemporaryCredentials($clientCredentials, $server, $redirectUrl);

        /**
         * Store them for later.
         */
        $this->_credentialsStorage->store($temporaryCredentials);

        /**
         * Prepare the authorization URL for redirection.
         *
         * http://tools.ietf.org/html/rfc5849#section-2.2
         */
        $authUrl = new ehough_curly_Url($server->getAuthorizationEndpoint()->toString());
        $authUrl->setQueryVariable('oauth_token', $temporaryCredentials->getIdentifier());

        /**
         * Redirect, if we're not testing.
         */
        if (!$this->_inTestMode) {

            header("$authUrl", true, 302);
            exit();
        }
    }

    /**
     * Fetch a new access token from the server.
     *
     * http://tools.ietf.org/html/rfc5849#section-2.3
     *
     * @param ehough_coauthor_api_v1_AbstractServer $server
     * @param string                                $incomingCredentialsId
     * @param string                                $incomingVerificationCode
     * @param ehough_coauthor_api_v1_Credentials    $clientCredentials
     *
     * @return ehough_coauthor_api_v1_Credentials
     *
     * @throws ehough_coauthor_api_exception_RuntimeException If anything fails.
     */
    public function fetchTokenCredentials(ehough_coauthor_api_v1_AbstractServer $server,
                                          $incomingCredentialsId,
                                          $incomingVerificationCode,
                                          ehough_coauthor_api_v1_Credentials $clientCredentials)
    {
        /**
         * Fetch the temporary credentials, which we should have collected from earlier.
         */
        $temporaryCredentials = $this->_fetchStoredTemporaryCredentials($server, $incomingCredentialsId);

        return $this->_credentialsFetcher->fetchToken($clientCredentials, $server, $temporaryCredentials, $incomingVerificationCode);
    }

    /**
     * @param ehough_shortstop_api_HttpRequest   $request
     * @param ehough_coauthor_api_v1_Credentials $clientCredentials
     * @param ehough_coauthor_api_v1_Credentials $tokenCredentials
     *
     * @return void
     *
     * @throws ehough_coauthor_api_exception_RuntimeException If anything fails.
     */
    public function signRequest(ehough_shortstop_api_HttpRequest $request,
                                ehough_coauthor_api_v1_Credentials $clientCredentials,
                                ehough_coauthor_api_v1_Credentials $tokenCredentials = null)
    {
        $this->_signer->sign($request, $clientCredentials, $tokenCredentials);
    }

    /**
     * This function should not be used outside of testing!
     */
    public function ___setTestMode()
    {
        $this->_inTestMode = true;
    }

    private function _fetchStoredTemporaryCredentials(ehough_coauthor_api_v1_AbstractServer $server,
                                                      $temporaryCredentialsId)
    {
        $temporaryCredentials = $this->_credentialsStorage->retrieve($temporaryCredentialsId);

        if (!$temporaryCredentials) {

            throw new ehough_coauthor_api_exception_RuntimeException('No temporary credentials found. Unable to fetch access tokens for ' . $server->getFriendlyName());
        }

        return $temporaryCredentials;
    }
}