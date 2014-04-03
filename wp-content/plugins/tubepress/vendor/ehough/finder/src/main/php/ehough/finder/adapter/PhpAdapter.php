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
 * PHP finder engine implementation.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ehough_finder_adapter_PhpAdapter extends ehough_finder_adapter_AbstractAdapter
{
    /**
     * {@inheritdoc}
     */
    public function searchInDirectory($dir)
    {
        if (version_compare(PHP_VERSION, '5.3') < 0) {

            $iterator = new RecursiveIteratorIterator(
                new ehough_finder_iterator_SkipDotsRecursiveDirectoryIterator($dir, $this->ignoreUnreadableDirs),
                RecursiveIteratorIterator::SELF_FIRST
            );

        } else {

            $flags = RecursiveDirectoryIterator::SKIP_DOTS;

            if ($this->followLinks) {
                $flags |= RecursiveDirectoryIterator::FOLLOW_SYMLINKS;
            }

            $iterator = new RecursiveIteratorIterator(
                new ehough_finder_iterator_RecursiveDirectoryIterator($dir, $flags, $this->ignoreUnreadableDirs),
                RecursiveIteratorIterator::SELF_FIRST
            );
        }

        if ($this->minDepth > 0 || $this->maxDepth < PHP_INT_MAX) {
            $iterator = new ehough_finder_iterator_DepthRangeFilterIterator($iterator, $this->minDepth, $this->maxDepth);
        }

        if ($this->mode) {
            $iterator = new ehough_finder_iterator_FileTypeFilterIterator($iterator, $this->mode);
        }

        if ($this->exclude) {
            $iterator = new ehough_finder_iterator_ExcludeDirectoryFilterIterator($iterator, $this->exclude);
        }

        if ($this->names || $this->notNames) {
            $iterator = new ehough_finder_iterator_FilenameFilterIterator($iterator, $this->names, $this->notNames);
        }

        if ($this->contains || $this->notContains) {
            $iterator = new ehough_finder_iterator_FilecontentFilterIterator($iterator, $this->contains, $this->notContains);
        }

        if ($this->sizes) {
            $iterator = new ehough_finder_iterator_SizeRangeFilterIterator($iterator, $this->sizes);
        }

        if ($this->dates) {
            $iterator = new ehough_finder_iterator_DateRangeFilterIterator($iterator, $this->dates);
        }

        if ($this->filters) {
            $iterator = new ehough_finder_iterator_CustomFilterIterator($iterator, $this->filters);
        }

        if ($this->sort) {
            $iteratorAggregate = new ehough_finder_iterator_SortableIterator($iterator, $this->sort);
            $iterator = $iteratorAggregate->getIterator();
        }

        if ($this->paths || $this->notPaths) {
            $iterator = new ehough_finder_iterator_PathFilterIterator($iterator, $this->paths, $this->notPaths);
        }

        return $iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'php';
    }

    /**
     * {@inheritdoc}
     */
    protected function canBeUsed()
    {
        return true;
    }
}
