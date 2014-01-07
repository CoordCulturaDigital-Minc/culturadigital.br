<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ehough
 * Date: 8/24/13
 * Time: 9:14 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class ehough_coauthor_api_v1_AbstractServer
{
    /**
     * @return ehough_curly_Url The URL to this server's temporary credentials endpoint.
     */
    public function getTemporaryCredentialsEndpoint()
    {
        return $this->_toUrl($this->getTemporaryCredentialsUrl(), 'temporary credentials');
    }

    /**
     * @return ehough_curly_Url The URL to this server's authorization endpoint.
     */
    public function getAuthorizationEndpoint()
    {
        return $this->_toUrl($this->getAuthorizationUrl(), 'authorization');
    }

    /**
     * @return ehough_curly_Url The URL to this server's token endpoint.
     */
    public function getTokensEndpoint()
    {
        return $this->_toUrl($this->getTokensUrl(), 'tokens');
    }



    /**
     * @param ehough_shortstop_api_HttpRequest $httpRequest The HTTP request that coauthor is about to fire off
     *                                                      for a temporary credentials or token request.
     */
    public function onCredentialsOrTokenRequest(ehough_shortstop_api_HttpRequest $httpRequest)
    {
        //override point
    }

    /**
     * @param ehough_shortstop_api_HttpRequest  $request  The HTTP request that we just sent.
     * @param ehough_shortstop_api_HttpResponse $response The HTTP response for the temporary credentials or token response.
     *
     * @throws ehough_coauthor_api_exception_RuntimeException If a non-200 status code is returned.
     */
    public function onAfterCredentialsOrTokenRequest(ehough_shortstop_api_HttpRequest $request,
                                                     ehough_shortstop_api_HttpResponse $response)
    {
        switch ($response->getStatusCode()) {

            case 200:

                $this->onSuccessfulCredentialsOrTokenRequest($request, $response);
                break;

            default:

                throw new ehough_coauthor_api_exception_RuntimeException($this->getErrorMessageFromBadHttpResponse($response));
        }
    }

    /**
     * @param ehough_shortstop_api_HttpRequest  $request  The HTTP request that we just sent.
     * @param ehough_shortstop_api_HttpResponse $response The successful HTTP response for the temporary credentials or token request.
     */
    public function onSuccessfulCredentialsOrTokenRequest(ehough_shortstop_api_HttpRequest $request,
                                                          ehough_shortstop_api_HttpResponse $response)
    {
        //override point
    }

    /**
     * @param ehough_shortstop_api_HttpResponse $response The HTTP response for the temporary credentials or token request that failed.
     *
     * @return string An error message based on the HTTP response.
     */
    public function getErrorMessageFromBadHttpResponse(ehough_shortstop_api_HttpResponse $response)
    {
        //override point
        return $this->getFriendlyName() . ' responded with an HTTP ' . $response->getStatusCode();
    }




    /**
     * @return string a User-friendly name for this server. e.g. "YouTube" or "Vimeo".
     */
    public abstract function getFriendlyName();

    /**
     * @return ehough_curly_Url
     */
    protected abstract function getTemporaryCredentialsUrl();

    /**
     * @return ehough_curly_Url
     */
    protected abstract function getAuthorizationUrl();

    /**
     * @return ehough_curly_Url
     */
    protected abstract function getTokensUrl();




    private function _toUrl($candidate, $name)
    {
        if (is_string($candidate)) {

            return new ehough_curly_Url($candidate);
        }

        if ($candidate instanceof ehough_curly_Url) {

            return $candidate;
        }

        throw new InvalidArgumentException('Server supplied invalid URL for ' . $name);
    }
}