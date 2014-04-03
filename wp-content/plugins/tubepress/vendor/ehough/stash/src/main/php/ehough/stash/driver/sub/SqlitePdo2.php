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
 * @package Stash
 * @author  Robert Hafner <tedivm@tedivm.com>
 */
class ehough_stash_driver_sub_SqlitePdo2 extends ehough_stash_driver_sub_SqlitePdo
{
    public static function isAvailable()
    {
        $drivers = class_exists('PDO', false) ? PDO::getAvailableDrivers() : array();

        return in_array('sqlite2', $drivers);
    }

    protected function buildDriver()
    {
        $db = new PDO('sqlite2:' . $this->path);

        return $db;
    }
}
