<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface ehough_finder_adapter_AdapterInterface
{
    /**
     * @param Boolean $followLinks
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setFollowLinks($followLinks);

    /**
     * @param integer $mode
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setMode($mode);

    /**
     * @param array $exclude
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setExclude(array $exclude);

    /**
     * @param array $depths
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setDepths(array $depths);

    /**
     * @param array $names
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setNames(array $names);

    /**
     * @param array $notNames
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setNotNames(array $notNames);

    /**
     * @param array $contains
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setContains(array $contains);

    /**
     * @param array $notContains
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setNotContains(array $notContains);

    /**
     * @param array $sizes
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setSizes(array $sizes);

    /**
     * @param array $dates
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setDates(array $dates);

    /**
     * @param array $filters
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setFilters(array $filters);

    /**
     * @param callable|integer $sort
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setSort($sort);

    /**
     * @param array $paths
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setPath(array $paths);

    /**
     * @param array $notPaths
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function setNotPath(array $notPaths);

    /**
     * @param boolean $ignore
     *
     * @return ehough_finder_adapter_AdapterInterface Current instance
     */
    public function ignoreUnreadableDirs($ignore = true);

    /**
     * @param string $dir
     *
     * @return Iterator Result iterator
     */
    public function searchInDirectory($dir);

    /**
     * Tests adapter support for current platform.
     *
     * @return Boolean
     */
    public function isSupported();

    /**
     * Returns adapter name.
     *
     * @return string
     */
    public function getName();
}
