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

/**
 * OAuth 1.0 credentials, which may be a token or an instance of client credentials.
 */
class ehough_coauthor_api_v1_Credentials
{
    /**
     * @var string
     */
    private $_identifier;

    /**
     * @var string
     */
    private $_secret;

    /**
     * @param string $identifier
     * @param string $secret
     *
     * @throws InvalidArgumentException If a non-string ID or secret is passed to the
     *                                  constructor.
     */
    public function __construct($identifier, $secret)
    {
        if (!is_string($identifier) || !is_string($secret)) {

            throw new InvalidArgumentException('Credentials identifier and secret must both be strings.');
        }

        $this->_identifier = $identifier;
        $this->_secret     = $secret;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->_secret;
    }
}