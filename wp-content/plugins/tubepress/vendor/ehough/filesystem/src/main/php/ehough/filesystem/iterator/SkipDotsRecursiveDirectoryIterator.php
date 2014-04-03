<?php
/*
 * This file is part of ehough/filesystem
 *
 * (c) Eric Hough <ehough.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Provides an interface for iterating recursively over filesystem directories.
 *
 * Manually skips '.' and '..' directories, since no existing method is
 * available in PHP 5.2.
 *
 * http://drupal.org/node/935036#comment-3766704
 */
class ehough_filesystem_iterator_SkipDotsRecursiveDirectoryIterator extends RecursiveDirectoryIterator
{
    /**
     * Constructs a SkipDotsRecursiveDirectoryIterator
     *
     * @param $path
     *   The path of the directory to be iterated over.
     */
    public function __construct($path)
    {
        parent::__construct($path);
    }

    public function next()
    {
        parent::next();

        while ($this->isDot()) {

            parent::next();
        }
    }
}