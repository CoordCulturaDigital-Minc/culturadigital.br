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
 * Dumper is the abstract class for all built-in dumpers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
abstract class ehough_iconic_dumper_Dumper implements ehough_iconic_dumper_DumperInterface
{
    protected $container;

    /**
     * Constructor.
     *
     * @param ehough_iconic_ContainerBuilder $container The service container to dump
     *
     * @api
     */
    public function __construct(ehough_iconic_ContainerBuilder $container)
    {
        $this->container = $container;
    }
}
