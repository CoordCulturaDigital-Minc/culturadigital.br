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

interface ehough_coauthor_spi_v1_SignerInterface
{
    /**
     * Sign the given HTTP request.
     *
     * @param ehough_shortstop_api_HttpRequest        $request
     * @param ehough_coauthor_api_v1_Credentials      $clientCredentials
     * @param ehough_coauthor_api_v1_Credentials|null $tokenCredentials
     *
     * @return void
     */
    function sign(ehough_shortstop_api_HttpRequest $request,
                  ehough_coauthor_api_v1_Credentials $clientCredentials,
                  ehough_coauthor_api_v1_Credentials $tokenCredentials = null);

    /**
     * @param ehough_shortstop_api_HttpRequest   $request
     * @param ehough_coauthor_api_v1_Credentials $clientCredentials
     * @param ehough_curly_Url                   $callbackUrl
     *
     * @return void
     */
    function signForTemporaryCredentialsRequest(ehough_shortstop_api_HttpRequest $request,
                                                ehough_coauthor_api_v1_Credentials $clientCredentials,
                                                ehough_curly_Url $callbackUrl);

    /**
     * @param ehough_shortstop_api_HttpRequest   $request
     * @param ehough_coauthor_api_v1_Credentials $clientCredentials
     * @param ehough_coauthor_api_v1_Credentials $temporaryCredentials
     * @param string                             $verificationCode
     *
     * @return void
     */
    function signForAccessTokenRequest(ehough_shortstop_api_HttpRequest $request,
                                       ehough_coauthor_api_v1_Credentials $clientCredentials,
                                       ehough_coauthor_api_v1_Credentials $temporaryCredentials,
                                       $verificationCode);


}