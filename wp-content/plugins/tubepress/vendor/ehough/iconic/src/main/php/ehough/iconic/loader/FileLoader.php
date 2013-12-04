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
 * FileLoader is the abstract class used by all built-in loaders that are file based.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class ehough_iconic_loader_FileLoader extends \Symfony\Component\Config\Loader\FileLoader
{
    protected $container;

    /**
     * Constructor.
     *
     * @param ehough_iconic_ContainerBuilder $container A ContainerBuilder instance
     * @param \Symfony\Component\Config\FileLocatorInterface      $locator   A FileLocator instance
     */
    public function __construct(\ehough_iconic_ContainerBuilder $container, \Symfony\Component\Config\FileLocatorInterface $locator)
    {
        $this->container = $container;

        parent::__construct($locator);
    }
}
