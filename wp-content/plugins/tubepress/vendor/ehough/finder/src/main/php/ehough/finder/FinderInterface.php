<?php

/*
 * This file is part of the ehough/finder package.
 *
 * (c) Eric D. Hough <eric@tubepress.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * ehough_finder_Finder allows to build rules to find files and directories.
 *
 * It is a thin wrapper around several specialized iterator classes.
 *
 * All rules may be invoked several times.
 *
 * All methods return the current ehough_finder_Finder object to allow easy chaining:
 *
 * $finder = ehough_finder_Finder::create()->files()->name('*.php')->in(dirname(__FILE__));
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
interface ehough_finder_FinderInterface extends IteratorAggregate, Countable
{
    /**
     * Restricts the matching to directories only.
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @api
     */
    function directories();

    /**
     * Restricts the matching to files only.
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @api
     */
    function files();

    /**
     * Adds tests for the directory depth.
     *
     * Usage:
     *
     *   $finder->depth('> 1') // the ehough_finder_Finder will start matching at level 1.
     *   $finder->depth('< 3') // the ehough_finder_Finder will descend at most 3 levels of directories below the starting point.
     *
     * @param int $level The depth level expression
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_DepthRangeFilterIterator
     * @see ehough_finder_comparator_NumberComparator
     *
     * @api
     */
    function depth($level);

    /**
     * Adds tests for file dates (last modified).
     *
     * The date must be something that strtotime() is able to parse:
     *
     *   $finder->date('since yesterday');
     *   $finder->date('until 2 days ago');
     *   $finder->date('> now - 2 hours');
     *   $finder->date('>= 2005-10-15');
     *
     * @param string $date A date rage string
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see strtotime
     * @see ehough_finder_iterator_DateRangeFilterIterator
     * @see ehough_finder_comparator_DateComparator
     *
     * @api
     */
    function date($date);

    /**
     * Adds rules that files must match.
     *
     * You can use patterns (delimited with / sign), globs or simple strings.
     *
     * $finder->name('*.php')
     * $finder->name('/\.php$/') // same as above
     * $finder->name('test.php')
     *
     * @param string $pattern A pattern (a regexp, a glob, or a string)
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_FilenameFilterIterator
     *
     * @api
     */
    function name($pattern);

    /**
     * Adds rules that files must not match.
     *
     * @param string $pattern A pattern (a regexp, a glob, or a string)
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_FilenameFilterIterator
     *
     * @api
     */
    function notName($pattern);

    /**
     * Adds tests that file contents must match.
     *
     * Strings or PCRE patterns can be used:
     *
     * $finder->contains('Lorem ipsum')
     * $finder->contains('/Lorem ipsum/i')
     *
     * @param string $pattern A pattern (string or regexp)
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_FilecontentFilterIterator
     */
    function contains($pattern);

    /**
     * Adds tests that file contents must not match.
     *
     * Strings or PCRE patterns can be used:
     *
     * $finder->notContains('Lorem ipsum')
     * $finder->notContains('/Lorem ipsum/i')
     *
     * @param string $pattern A pattern (string or regexp)
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_FilecontentFilterIterator
     */
    function notContains($pattern);

    /**
     * Adds rules that filenames must match.
     *
     * You can use patterns (delimited with / sign) or simple strings.
     *
     * $finder->path('some/special/dir')
     * $finder->path('/some\/special\/dir/') // same as above
     *
     * Use only / as dirname separator.
     *
     * @param string $pattern A pattern (a regexp or a string)
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_FilenameFilterIterator
     */
    function path($pattern);

    /**
     * Adds rules that filenames must not match.
     *
     * You can use patterns (delimited with / sign) or simple strings.
     *
     * $finder->notPath('some/special/dir')
     * $finder->notPath('/some\/special\/dir/') // same as above
     *
     * Use only / as dirname separator.
     *
     * @param string $pattern A pattern (a regexp or a string)
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_FilenameFilterIterator
     */
    function notPath($pattern);

    /**
     * Adds tests for file sizes.
     *
     * $finder->size('> 10K');
     * $finder->size('<= 1Ki');
     * $finder->size(4);
     *
     * @param string $size A size range string
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_SizeRangeFilterIterator
     * @see ehough_finder_comparator_NumberComparator
     *
     * @api
     */
    function size($size);

    /**
     * Excludes directories.
     *
     * @param string|array $dirs A directory path or an array of directories
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_ExcludeDirectoryFilterIterator
     *
     * @api
     */
    function exclude($dirs);

    /**
     * Excludes "hidden" directories and files (starting with a dot).
     *
     * @param Boolean $ignoreDotFiles Whether to exclude "hidden" files or not
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_ExcludeDirectoryFilterIterator
     *
     * @api
     */
    function ignoreDotFiles($ignoreDotFiles);

    /**
     * Forces the finder to ignore version control directories.
     *
     * @param Boolean $ignoreVCS Whether to exclude VCS files or not
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_ExcludeDirectoryFilterIterator
     *
     * @api
     */
    function ignoreVCS($ignoreVCS);

    /**
     * Adds VCS patterns.
     *
     * @see ignoreVCS
     *
     * @param string|string[] $pattern VCS patterns to ignore
     */
    public static function addVCSPattern($pattern);

    /**
     * Sorts files and directories by an anonymous function.
     *
     * The anonymous function receives two SplFileInfo instances to compare.
     *
     * This can be slow as all the matching files and directories must be retrieved for comparison.
     *
     * @param callable $closure An anonymous function
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_SortableIterator
     *
     * @api
     */
    function sort($closure);

    /**
     * Sorts files and directories by name.
     *
     * This can be slow as all the matching files and directories must be retrieved for comparison.
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_SortableIterator
     *
     * @api
     */
    function sortByName();

    /**
     * Sorts files and directories by type (directories before files), then by name.
     *
     * This can be slow as all the matching files and directories must be retrieved for comparison.
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_SortableIterator
     *
     * @api
     */
    function sortByType();

    /**
     * Sorts files and directories by the last accessed time.
     *
     * This is the time that the file was last accessed, read or written to.
     *
     * This can be slow as all the matching files and directories must be retrieved for comparison.
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_SortableIterator
     *
     * @api
     */
    function sortByAccessedTime();

    /**
     * Sorts files and directories by the last inode changed time.
     *
     * This is the time that the inode information was last modified (permissions, owner, group or other metadata).
     *
     * On Windows, since inode is not available, changed time is actually the file creation time.
     *
     * This can be slow as all the matching files and directories must be retrieved for comparison.
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_SortableIterator
     *
     * @api
     */
    function sortByChangedTime();

    /**
     * Sorts files and directories by the last modified time.
     *
     * This is the last time the actual contents of the file were last modified.
     *
     * This can be slow as all the matching files and directories must be retrieved for comparison.
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_SortableIterator
     *
     * @api
     */
    function sortByModifiedTime();

    /**
     * Filters the iterator with an anonymous function.
     *
     * The anonymous function receives a SplFileInfo and must return false
     * to remove files.
     *
     * @param callable $closure An anonymous function
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @see ehough_finder_iterator_CustomFilterIterator
     *
     * @api
     */
    function filter($closure);

    /**
     * Forces the following of symlinks.
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @api
     */
    function followLinks();

    /**
     * Searches files and directories which match defined rules.
     *
     * @param string|array $dirs A directory path or an array of directories
     *
     * @return ehough_finder_Finder The current ehough_finder_Finder instance
     *
     * @throws InvalidArgumentException if one of the directories does not exist
     *
     * @api
     */
    function in($dirs);

    /**
     * Appends an existing set of files/directories to the finder.
     *
     * The set can be another ehough_finder_Finder, an Iterator, an IteratorAggregate, or even a plain array.
     *
     * @param mixed $iterator
     *
     * @return ehough_finder_Finder The finder
     *
     * @throws InvalidArgumentException When the given argument is not iterable.
     */
    function append($iterator);
}
