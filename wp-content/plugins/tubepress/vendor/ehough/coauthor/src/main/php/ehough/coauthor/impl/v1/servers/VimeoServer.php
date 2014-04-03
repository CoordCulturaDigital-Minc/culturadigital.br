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

class ehough_coauthor_impl_v1_servers_VimeoServer extends ehough_coauthor_api_v1_AbstractServer
{
    public function getFriendlyName()
    {
        return 'Vimeo';
    }

    /**
     * @return ehough_curly_Url
     */
    protected function getTemporaryCredentialsUrl()
    {
        return 'https://vimeo.com/oauth/request_token';
    }

    /**
     * @return ehough_curly_Url
     */
    protected function getAuthorizationUrl()
    {
        return 'https://vimeo.com/oauth/authorize';
    }

    /**
     * @return ehough_curly_Url
     */
    protected function getTokensUrl()
    {
        return 'https://vimeo.com/oauth/access_token';
    }
}