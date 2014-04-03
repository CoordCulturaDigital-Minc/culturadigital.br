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
 * StashSqlite is a wrapper around one or more SQLite databases stored on the local system. While not as quick at at
 * reading as the StashFilesystem driver this class is significantly better when it comes to clearing multiple keys
 * at once.
 *
 * @package Stash
 * @author  Robert Hafner <tedivm@tedivm.com>
 */
class ehough_stash_driver_Sqlite implements ehough_stash_interfaces_DriverInterface
{
    protected $defaultOptions = array('filePermissions' => 0660,
                                      'dirPermissions' => 0770,
                                      'busyTimeout' => 500,
                                      'nesting' => 0,
                                      'subdriver' => 'PDO'
    );

    protected $filePerms;
    protected $dirPerms;
    protected $busyTimeout;
    protected $cachePath;
    protected $driverClass;
    protected $nesting;
    protected $subDrivers;

    protected $disabled = false;

    /**
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $options = array_merge($this->defaultOptions, $options);

        $cachePath = isset($options['path']) ? $options['path'] : ehough_stash_Utilities::getBaseDirectory($this);
        $this->cachePath = rtrim($cachePath, '\\/') . '/';

        $this->checkFileSystemPermissions();

        $extension = isset($options['extension']) ? strtolower($options['extension']) : 'any';
        $version = isset($options['version']) ? $options['version'] : 'any';

        $subdrivers = array();
        if (ehough_stash_driver_sub_SqlitePdo::isAvailable()) {
            $subdrivers['pdo'] = 'ehough_stash_driver_sub_SqlitePdo';
        }
        if (ehough_stash_driver_sub_Sqlite::isAvailable()) {
            $subdrivers['sqlite'] = 'ehough_stash_driver_sub_Sqlite';
        }
        if (ehough_stash_driver_sub_SqlitePdo2::isAvailable()) {
            $subdrivers['pdo2'] = 'ehough_stash_driver_sub_SqlitePdo2';
        }

        if ($extension == 'pdo' && $version != '2' && isset($subdrivers['pdo'])) {
            $driver = $subdrivers['pdo'];
        } elseif ($extension == 'sqlite' && isset($subdrivers['sqlite'])) {
            $driver = $subdrivers['sqlite'];
        } elseif ($extension == 'pdo' && $version != '3' && isset($subdrivers['pdo2'])) {
            $driver = $subdrivers['pdo2'];
        } elseif (count($subdrivers) > 0 && $extension == 'any') {
            $driver = reset($subdrivers);
        } else {
            throw new ehough_stash_exception_RuntimeException('No sqlite extension available.');
        }

        $this->driverClass = $driver;
        $this->filePerms = $options['filePermissions'];
        $this->dirPerms = $options['dirPermissions'];
        $this->busyTimeout = $options['busyTimeout'];
        $this->nesting = $options['nesting'];

        $this->checkStatus();
    }

    /**
     * @param  array $key
     * @return array
     */
    public function getData($key)
    {
        if (!($sqlDriver = $this->getSqliteDriver($key))) {
            return false;
        }

        $sqlKey = $this->makeSqlKey($key);

        if (!($data = $sqlDriver->get($sqlKey))) {
            return false;
        }

        $data['data'] = ehough_stash_Utilities::decode($data['data'], $data['encoding']);

        return $data;
    }

    /**
     * @param  array $key
     * @param  array $data
     * @param  int   $expiration
     * @return bool
     */
    public function storeData($key, $data, $expiration)
    {
        if (!($sqlDriver = $this->getSqliteDriver($key))) {
            return false;
        }

        $storeData = array('data' => ehough_stash_Utilities::encode($data),
                           'expiration' => $expiration,
                           'encoding' => ehough_stash_Utilities::encoding($data)
        );

        return $sqlDriver->set($this->makeSqlKey($key), $storeData, $expiration);
    }

    /**
     *
     * @param  null|array $key
     * @return bool
     */
    public function clear($key = null)
    {
        if (!($databases = $this->getCacheList())) {
            return true;
        }

        if (!is_null($key)) {
            $sqlKey = $this->makeSqlKey($key);
        }

        foreach ($databases as $database) {
            if (!($driver = $this->getSqliteDriver($database, true))) {
                continue;
            }

            isset($sqlKey) ? $driver->clear($sqlKey) : $driver->clear();
            $driver->__destruct();
            unset($driver);
        }
        $this->subDrivers = array();

        return true;
    }

    /**
     *
     * @return bool
     */
    public function purge()
    {
        if (!($databases = $this->getCacheList())) {
            return true;
        }

        foreach ($databases as $database) {
            if ($driver = $this->getSqliteDriver($database, true)) {
                $driver->purge();
            }
        }

        return true;
    }

    /**
     *
     * @param  null|array                     $key
     * @param  bool                           $name = false
     * @return ehough_stash_driver_sub_Sqlite
     */
    protected function getSqliteDriver($key, $name = false)
    {
        if ($name) {
            if (!is_scalar($key)) {
                return false;
            }

            $file = $key;

        } else {
            if (!is_array($key)) {
                return false;
            }

            $key = ehough_stash_Utilities::normalizeKeys($key);

            $nestingLevel = $this->nesting;
            $fileName = 'cache_';
            for ($i = 1; $i < $nestingLevel; $i++) {
                $fileName .= $key[$i - 1] . '_';
            }

            $file = $this->cachePath . rtrim($fileName, '_') . '.sqlite';
        }

        if (isset($this->subDrivers[$file])) {
            return $this->subDrivers[$file];
        }

        $driverClass = $this->driverClass;

        if(is_null($driverClass))

            return false;

        $driver = new $driverClass($file, $this->dirPerms, $this->filePerms, $this->busyTimeout);

        $this->subDrivers[$file] = $driver;

        return $driver;
    }

    /**
     * Destroys the sub-drivers when this driver is unset -- required for Windows compatibility.
     *
     */
    public function __destruct()
    {
        if ($this->subDrivers) {
            foreach ($this->subDrivers as &$driver) {
                $driver->__destruct();
                unset($driver);
            }
        }
    }

    /**
     *
     * @return array|false
     */
    protected function getCacheList()
    {
        $filePath = $this->cachePath;
        $caches = array();
        $databases = glob($filePath . '*.sqlite');
        foreach ($databases as $database) {
            $caches[] = $database;
        }

        return count($caches) > 0 ? $caches : false;
    }

    /**
     * Checks to see whether the requisite permissions are available on the specified path.
     *
     */
    protected function checkFileSystemPermissions()
    {
        if (!isset($this->cachePath)) {
            throw new ehough_stash_exception_RuntimeException('Cache path was not set correctly.');
        }

        if (file_exists($this->cachePath) && !is_dir($this->cachePath)) {
            throw new ehough_stash_exception_InvalidArgumentException('Cache path is not a directory.');
        }

        if (!is_dir($this->cachePath) && !@mkdir( $this->cachePath, $this->dirPermissions, true )) {
            throw new ehough_stash_exception_InvalidArgumentException('Failed to create cache path.');
        }

        if (!is_writable($this->cachePath)) {
            throw new ehough_stash_exception_InvalidArgumentException('Cache path is not writable.');
        }
    }

    /**
     * Checks availability of the specified subdriver.
     *
     * @return bool
     */
    protected function checkStatus()
    {
        if (!self::isAvailable()) {
            throw new ehough_stash_exception_RuntimeException('No Sqlite extension is available.');
        }

        $driver = $this->getSqliteDriver(array('_none'));

        if (!$driver) {
            throw new ehough_stash_exception_RuntimeException('No Sqlite driver could be loaded.');
        }

        $driver->checkFileSystemPermissions();
    }

    /**
     * Returns whether the driver is able to run in the current environment or not. Any system checks- such as making
     * sure any required extensions are missing- should be done here.
     *
     * @return bool
     */
    public static function isAvailable()
    {
        return (ehough_stash_driver_sub_SqlitePdo::isAvailable()) || (ehough_stash_driver_sub_Sqlite::isAvailable()) || (ehough_stash_driver_sub_SqlitePdo2::isAvailable());
    }

    /**
     * This function takes an array of strings and turns it into the sqlKey. It does this by iterating through the
     * array, running the string through sqlite_escape_string() and then combining that string to the keystring with a
     * delimiter.
     *
     * @param  array  $key
     * @return string
     */
    public static function makeSqlKey($key)
    {
        $key = ehough_stash_Utilities::normalizeKeys($key, 'base64_encode');
        $path = '';
        foreach ($key as $rawPathPiece) {
            $path .= $rawPathPiece . ':::';
        }

        return $path;
    }
}
