<?php
/*
 * This file is part of the ehough/stash package.
 *
 * (c) Eric D. Hough <eric@tubepress.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Cache\Pool generates Cache\Item objects.
 */
interface ehough_stash_PoolInterface
{
    /**
     * Returns objects which implement the Cache\Item interface.
     *
     * Provided key must be unique for each item in the cache. Implementing
     * Libraries are responsible for any encoding or escaping required by their
     * backends, but must be able to supply the original key if needed. Keys
     * should not contain the special characters listed:
     *  {}()/\@
     *
     * @param string $key
     * @return ehough_stash_ItemInterface
     */
    function getItem($key);

    /**
     * Returns a group of cache objects as an Iterator
     *
     * Bulk lookups can often by streamlined by backend cache systems. The
     * returned iterator will contain a Cache\Item for each key passed.
     *
     * @param array $keys
     * @return Iterator
     */
    function getItemIterator($keys);

    /**
     * Clears the cache pool of all items.
     *
     * @return bool
     */
    function clear();
}