<?php
/*
 * This file is part of ehough/filesystem
 *
 * (c) Eric Hough <ehough.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

interface ehough_filesystem_FilesystemInterface
{
    /**
     * Get the absolute path of a temporary directory, preferably the system directory.
     *
     * @return string The absolute path of a temporary directory, preferably the system directory.
     */
    function getSystemTempDirectory();

    /**
     * Copies a file.
     *
     * This method only copies the file if the origin file is newer than the target file.
     *
     * By default, if the target already exists, it is not overridden.
     *
     * @param string  $originFile The original filename
     * @param string  $targetFile The target filename
     * @param boolean $override   Whether to override an existing file or not
     *
     * @throws ehough_filesystem_exception_IOException When copy fails
     */
    function copy($originFile, $targetFile, $override = false);

    /**
     * Creates a directory recursively.
     *
     * @param string|array|Traversable $dirs The directory path
     * @param integer                   $mode The directory mode
     *
     * @throws ehough_filesystem_exception_IOException On any directory creation failure
     */
    function mkdir($dirs, $mode = 0777);

    /**
     * Checks the existence of files or directories.
     *
     * @param string|array|Traversable $files A filename, an array of files, or a Traversable instance to check
     *
     * @return Boolean true if the file exists, false otherwise
     */
    function exists($files);

    /**
     * Sets access and modification time of file.
     *
     * @param string|array|Traversable $files A filename, an array of files, or a Traversable instance to create
     * @param integer                   $time  The touch time as a unix timestamp
     * @param integer                   $atime The access time as a unix timestamp
     *
     * @throws ehough_filesystem_exception_IOException When touch fails
     */
    function touch($files, $time = null, $atime = null);

    /**
     * Removes files or directories.
     *
     * @param string|array|Traversable $files A filename, an array of files, or a Traversable instance to remove
     *
     * @throws ehough_filesystem_exception_IOException When removal fails
     */
    function remove($files);

    /**
     * Change mode for an array of files or directories.
     *
     * @param string|array|Traversable $files     A filename, an array of files, or a Traversable instance to change mode
     * @param integer                   $mode      The new mode (octal)
     * @param integer                   $umask     The mode mask (octal)
     * @param Boolean                   $recursive Whether change the mod recursively or not
     *
     * @throws ehough_filesystem_exception_IOException When the change fail
     */
    function chmod($files, $mode, $umask = 0000, $recursive = false);

    /**
     * Change the owner of an array of files or directories
     *
     * @param string|array|Traversable $files     A filename, an array of files, or a Traversable instance to change owner
     * @param string                    $user      The new owner user name
     * @param Boolean                   $recursive Whether change the owner recursively or not
     *
     * @throws ehough_filesystem_exception_IOException When the change fail
     */
    function chown($files, $user, $recursive = false);

    /**
     * Change the group of an array of files or directories
     *
     * @param string|array|Traversable $files     A filename, an array of files, or a Traversable instance to change group
     * @param string                    $group     The group name
     * @param Boolean                   $recursive Whether change the group recursively or not
     *
     * @throws ehough_filesystem_exception_IOException When the change fail
     */
    function chgrp($files, $group, $recursive = false);

    /**
     * Renames a file.
     *
     * @param string $origin The origin filename
     * @param string $target The new filename
     *
     * @throws ehough_filesystem_exception_IOException When target file already exists
     * @throws ehough_filesystem_exception_IOException When origin cannot be renamed
     */
    function rename($origin, $target);

    /**
     * Creates a symbolic link or copy a directory.
     *
     * @param string  $originDir     The origin directory path
     * @param string  $targetDir     The symbolic link name
     * @param Boolean $copyOnWindows Whether to copy files if on Windows
     *
     * @throws ehough_filesystem_exception_IOException When symlink fails
     */
    function symlink($originDir, $targetDir, $copyOnWindows = false);

    /**
     * Given an existing path, convert it to a path relative to a given starting path
     *
     * @param string $endPath   Absolute path of target
     * @param string $startPath Absolute path where traversal begins
     *
     * @return string Path of target relative to starting path
     */
    function makePathRelative($endPath, $startPath);

    /**
     * Mirrors a directory to another.
     *
     * @param string       $originDir The origin directory
     * @param string       $targetDir The target directory
     * @param Traversable $iterator  A Traversable instance
     * @param array        $options   An array of boolean options
     *                               Valid options are:
     *                                 - $options['override'] Whether to override an existing file on copy or not (see copy())
     *                                 - $options['copy_on_windows'] Whether to copy files instead of links on Windows (see symlink())
     *                                 - $options['delete'] Whether to delete files that are not in the source directory (defaults to false)
     *
     * @throws ehough_filesystem_exception_IOException When file type is unknown
     */
    function mirror($originDir, $targetDir, Traversable $iterator = null, $options = array());

    /**
     * Returns whether the file path is an absolute path.
     *
     * @param string $file A file path
     *
     * @return Boolean
     */
    function isAbsolutePath($file);
}