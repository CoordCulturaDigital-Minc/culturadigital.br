<?php
/**
 * Copyright 2006 - 2013 Eric D. Hough (http://ehough.com)
 *
 * This file is part of chaingang (https://github.com/ehough/chaingang)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

interface ehough_coauthor_api_v1_ClientInterface
{
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
    function commenceNewAuthorization(ehough_coauthor_api_v1_AbstractServer $server,
                                      $redirectUrl,
                                      ehough_coauthor_api_v1_Credentials $clientCredentials);

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
    function fetchTokenCredentials(ehough_coauthor_api_v1_AbstractServer $server,
                                   $incomingCredentialsId,
                                   $incomingVerificationCode,
                                   ehough_coauthor_api_v1_Credentials $clientCredentials);

    /**
     * @param ehough_shortstop_api_HttpRequest   $request
     * @param ehough_coauthor_api_v1_Credentials $clientCredentials
     * @param ehough_coauthor_api_v1_Credentials $tokenCredentials
     *
     * @return void
     *
     * @throws ehough_coauthor_api_exception_RuntimeException If anything fails.
     */
    function signRequest(ehough_shortstop_api_HttpRequest $request,
                         ehough_coauthor_api_v1_Credentials $clientCredentials,
                         ehough_coauthor_api_v1_Credentials $tokenCredentials = null);
}