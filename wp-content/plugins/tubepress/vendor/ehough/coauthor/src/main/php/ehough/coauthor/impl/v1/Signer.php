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

class ehough_coauthor_impl_v1_Signer implements ehough_coauthor_spi_v1_SignerInterface
{
    /**
     * @param ehough_shortstop_api_HttpRequest   $request
     * @param ehough_coauthor_api_v1_Credentials $clientCredentials
     * @param ehough_curly_Url                   $callbackUrl
     *
     * @return void
     */
    public function signForTemporaryCredentialsRequest(ehough_shortstop_api_HttpRequest $request,
                                                ehough_coauthor_api_v1_Credentials $clientCredentials,
                                                ehough_curly_Url $callbackUrl)
    {
        $oAuthParams = $this->_getBaseOAuthParams($clientCredentials);

        $oAuthParams['oauth_callback'] = $callbackUrl->toString();

        $this->_sign($request, $oAuthParams, $clientCredentials);
    }

    /**
     * @param ehough_shortstop_api_HttpRequest   $request
     * @param ehough_coauthor_api_v1_Credentials $clientCredentials
     * @param ehough_coauthor_api_v1_Credentials $temporaryCredentials
     * @param string                             $verificationCode
     *
     * @return void
     */
    public function signForAccessTokenRequest(ehough_shortstop_api_HttpRequest $request,
                                       ehough_coauthor_api_v1_Credentials $clientCredentials,
                                       ehough_coauthor_api_v1_Credentials $temporaryCredentials,
                                       $verificationCode)
    {
        $oAuthParams = $this->_getBaseOAuthParams($clientCredentials);

        $oAuthParams['oauth_verifier'] = $verificationCode;

        $this->_sign($request, $oAuthParams, $clientCredentials, $temporaryCredentials);
    }

    /**
     * Sign the given HTTP request.
     *
     * @param ehough_shortstop_api_HttpRequest        $request
     * @param ehough_coauthor_api_v1_Credentials      $clientCredentials
     * @param ehough_coauthor_api_v1_Credentials|null $tokenCredentials
     *
     * @return void
     */
    public function sign(ehough_shortstop_api_HttpRequest $request,
                  ehough_coauthor_api_v1_Credentials $clientCredentials,
                  ehough_coauthor_api_v1_Credentials $tokenCredentials = null)
    {
        $oAuthParams = $this->_getBaseOAuthParams($clientCredentials);

        if ($tokenCredentials !== null) {

            $oAuthParams['oauth_token'] = $tokenCredentials->getIdentifier();
        }

        $this->_sign($request, $oAuthParams, $clientCredentials, $tokenCredentials);
    }

    private function _sign(ehough_shortstop_api_HttpRequest $httpRequest,
                           array $oauthParams,
                           ehough_coauthor_api_v1_Credentials $clientCredentials,
                           ehough_coauthor_api_v1_Credentials $tokenCredentials = null)
    {
        $oauthParams['oauth_signature'] = $this->_getSignature($httpRequest, $oauthParams, $clientCredentials, $tokenCredentials);

        $header    = 'OAuth ';
        $delimiter = '';

        foreach ($oauthParams as $key => $value) {

            $header .= $delimiter . $this->_urlEncode($key) . '="' . $this->_urlEncode($value) . '"';

            $delimiter = ', ';
        }

        $httpRequest->setHeader('Authorization', $header);
    }

    private function _getSignature(ehough_shortstop_api_HttpRequest $request,
                                   array $baseOAuthParams,
                                   ehough_coauthor_api_v1_Credentials $clientCredentials,
                                   ehough_coauthor_api_v1_Credentials $tokenCredentials = null)
    {
        $url                 = $request->getUrl();
        $existingQueryParams = $url->getQueryVariables();
        $signatureData       = array_merge($existingQueryParams, $baseOAuthParams);

        foreach ($signatureData as $key => $value) {

            $signatureData[$this->_urlEncode($key)] = $this->_urlEncode($value);
        }

        uksort($signatureData, 'strcmp');

        $baseUrl         = $url->getScheme() . '://' . $this->_getNormalizedAuthority($url) . $url->getPath();
        $baseStringParts = $this->_urlEncode(array(

            $request->getMethod(),
            $baseUrl,
            $this->_concatParams($signatureData)
        ));
        $baseString      = implode('&', $baseStringParts);
        $keyParts        = $this->_urlEncode(array(

            $clientCredentials->getSecret(),
            $tokenCredentials === null ? '' : $tokenCredentials->getSecret(),
        ));
        $signingKey      = implode('&', $keyParts);
        $signature       = base64_encode($this->_hash($baseString, $signingKey));

        return $signature;
    }

    private function _getBaseOAuthParams(ehough_coauthor_api_v1_Credentials $clientCredentials)
    {
        return array(

            'oauth_consumer_key'     => $clientCredentials->getIdentifier(),
            'oauth_nonce'            => md5(uniqid(mt_rand(), true)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => time(),
            'oauth_version'          => '1.0',
        );
    }

    /**
     * @param string $data      Data to hash.
     * @param string $key       Key.
     *
     * @return string
     */
    private function _hash($data, $key)
    {
        return hash_hmac('sha1', $data, $key, true);
    }

    private function _concatParams(array $params)
    {
        $toReturn  = '';
        $delimiter = '';

        foreach ($params as $key => $value) {

            $toReturn .= $delimiter . $key . '=' . $value;

            $delimiter = '&';
        }

        return $toReturn;
    }

    private function _getNormalizedAuthority(ehough_curly_Url $url)
    {
        $scheme = $url->getScheme();
        $port   = $url->getPort();

        if ($port != null && (($scheme === 'http' && $port != 80)
            || ($scheme === 'https' && $port != 465))) {

            return $url->getAuthority();
        }

        return $url->getHost();
    }

    /**
     * URL encode a parameter or array of parameters.
     *
     * @param array|string $input A parameter or set of parameters to encode.
     *
     * @return array|string The URL encoded parameter or array of parameters.
     */
    private function _urlEncode($input)
    {
        if (is_array($input)) {

            return array_map(array($this, '_urlEncode'), $input);

        } elseif (is_scalar($input)) {

            return str_replace(array('+', '%7E'), array(' ', '~'), rawurlencode($input));

        } else {

            return '';
        }
    }
}