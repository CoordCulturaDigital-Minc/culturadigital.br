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

class ehough_coauthor_impl_v1_SessionCredentialsStorage implements ehough_coauthor_spi_v1_TemporaryCredentialsStorageInterface
{
    /**
     * @var string
     */
    private $_sessionVariableName;

    /**
     * @param bool   $startSession        Whether or not to start the session upon construction.
     * @param string $sessionVariableName The variable name to use within the _SESSION superglobal
     */
    public function __construct($startSession = true, $sessionVariableName = 'ehough_coauthor_credentials')
    {
        if ($startSession && session_id() == '') {

            session_start();
        }

        if (!isset($_SESSION[$sessionVariableName])) {

            $_SESSION[$sessionVariableName] = array();
        }

        $this->sessionVariableName = $sessionVariableName;
    }

    /**
     * @param string $id The credentials ID.
     *
     * @return ehough_coauthor_api_v1_Credentials The stored credentials, or null if not found.
     */
    public function retrieve($id)
    {
        if (!isset($_SESSION[$this->_sessionVariableName])
            || !is_array($_SESSION[$this->_sessionVariableName])
            || !isset($_SESSION[$this->_sessionVariableName][$id])) {

            return null;
        }

        return $_SESSION[$this->_sessionVariableName][$id];
    }

    /**
     * @param ehough_coauthor_api_v1_Credentials $credentials
     *
     * @return void
     */
    public function store(ehough_coauthor_api_v1_Credentials $credentials)
    {
        if (!isset($_SESSION[$this->_sessionVariableName])
            || !is_array($_SESSION[$this->_sessionVariableName])) {

            $_SESSION[$this->_sessionVariableName] = array();
        }

        $_SESSION[$this->_sessionVariableName][$credentials->getIdentifier()] = $credentials;
    }

    /**
     * Delete the temporary credentials with the given ID.
     *
     * @param string $id The credentials ID.
     *
     * @return void
     */
    public function clear($id)
    {
        if (isset($_SESSION[$this->_sessionVariableName]) && is_array($_SESSION[$this->_sessionVariableName])) {

            unset($_SESSION[$this->_sessionVariableName]);
        }
    }

    public function  __destruct()
    {
        session_write_close();
    }
}