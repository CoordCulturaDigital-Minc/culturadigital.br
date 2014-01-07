<?php

/*
 * This file is part of the Stash package.
 *
 * (c) Robert Hafner <tedivm@tedivm.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 *
 * @package Stash
 * @author  Robert Hafner <tedivm@tedivm.com>
 */
interface ehough_stash_interfaces_PoolInterface
{
    public function getItem();

    public function getItemIterator($keys);

    public function flush();

    public function purge();

    public function setDriver(ehough_stash_interfaces_DriverInterface $driver);

    public function getDriver();

    public function setLogger($logger);
}
