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

interface ehough_coauthor_spi_v1_TemporaryCredentialsStorageInterface
{
    /**
     * @param string $id The credentials ID.
     *
     * @return ehough_coauthor_api_v1_Credentials The stored credentials, or null if not found.
     */
    function retrieve($id);

    /**
     * @param ehough_coauthor_api_v1_Credentials  $credentials
     * 
     * @return void
     */
    function store(ehough_coauthor_api_v1_Credentials $credentials);

    /**
     * Delete the temporary credentials with the given ID.
     *
     * @param string $id The credentials ID.
     *
     * @return void
     */
    function clear($id);
}