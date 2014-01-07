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
 * ehough_stash_Drivers contains various functions used to organize Driver classes that are available in the system.
 *
 * @package Stash
 * @author  Robert Hafner <tedivm@tedivm.com>
 */
class ehough_stash_Drivers
{
    /**
     * An array of possible cache storage data methods, with the driver class as the array value.
     *
     * @var array
     */
    protected static $drivers = array('Apc' => 'ehough_stash_driver_Apc',
                                       'BlackHole' => 'ehough_stash_driver_BlackHole',
                                       'Composite' => 'ehough_stash_driver_Composite',
                                       'Ephemeral' => 'ehough_stash_driver_Ephemeral',
                                       'FileSystem' => 'ehough_stash_driver_FileSystem',
                                       'Memcache' => 'ehough_stash_driver_Memcache',
                                       'Redis' => 'ehough_stash_driver_Redis',
                                       'SQLite' => 'ehough_stash_driver_Sqlite',
                                       'Xcache' => 'ehough_stash_driver_Xcache',
    );

    /**
     * Returns a list of build-in cache drivers that are also supported by this system.
     *
     * @return array Driver Name => Class Name
     */
    public static function getDrivers()
    {
        $availableDrivers = array();
        foreach (self::$drivers as $name => $class) {
            if (!class_exists($class)) {
                continue;
            }

            if (!in_array('ehough_stash_interfaces_DriverInterface', class_implements($class))) {
                continue;
            }

            if ($name == 'Composite') {
                $availableDrivers[$name] = $class;
            } else {
                if (call_user_func(array($class, 'isAvailable'))) {
                    $availableDrivers[$name] = $class;
                }
            }
        }

        return $availableDrivers;
    }

    public static function registerDriver($name, $class)
    {
        self::$drivers[$name] = $class;
    }

    public static function getDriverClass($name)
    {
        if (!isset(self::$drivers[$name])) {
            return false;
        }

        return self::$drivers[$name];
    }

}
