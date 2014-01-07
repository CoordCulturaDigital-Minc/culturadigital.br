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

interface ehough_coauthor_spi_v1_RemoteCredentialsFetcherInterface
{
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
    function fetchTemporaryCredentials(ehough_coauthor_api_v1_Credentials $clientCredentials,
                                       ehough_coauthor_api_v1_AbstractServer $server,
                                       ehough_curly_Url $redirectUrl);

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
    function fetchToken(ehough_coauthor_api_v1_Credentials $clientCredentials,
                        ehough_coauthor_api_v1_AbstractServer $server,
                        ehough_coauthor_api_v1_Credentials $temporaryCredentials,
                        $verificationCode);
}