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
 * Extends the \RecursiveDirectoryIterator to support relative paths
 *
 * @author Victor Berchet <victor@suumit.com>
 */
class ehough_finder_iterator_SkipDotsRecursiveDirectoryIterator extends RecursiveDirectoryIterator
{
    private $_ignoreUnreadableDirs;

    public function __construct($path, $ignoreUnreadableDirs = false)
    {
        if (!$ignoreUnreadableDirs && (!is_dir($path) || !is_readable($path))) {

            throw new ehough_finder_exception_AccessDeniedException("Could not open directory at $path");
        }

        $this->_ignoreUnreadableDirs = $ignoreUnreadableDirs;

        parent::__construct($path);
    }

    /**
     * Return an instance of SplFileInfo with support for relative paths
     *
     * @return SplFileInfo File information
     */
    public function current()
    {
        return new ehough_finder_SplFileInfo(parent::current()->getPathname(), $this->getSubPath(), $this->getSubPathname());
    }

    /**
     * @return mixed object
     *
     * @throws (ehough_finder_exception_AccessDeniedException
     */
    public function getChildren()
    {
        try {
            return parent::getChildren();
        } catch (UnexpectedValueException $e) {
            if ($this->_ignoreUnreadableDirs) {
                // If directory is unreadable and finder is set to ignore it, a fake empty content is returned.
                return new RecursiveArrayIterator(array());
            } else {
                throw new ehough_finder_exception_AccessDeniedException($e->getMessage(), $e->getCode());
            }
        }
    }

    public function next()
    {
        parent::next();

        while ($this->isDot()) {

            parent::next();
        }
    }
}
