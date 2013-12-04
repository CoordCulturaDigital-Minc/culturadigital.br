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
 * A simple implementation of ContainerAwareInterface.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
abstract class ehough_iconic_ContainerAware implements ehough_iconic_ContainerAwareInterface
{
    /**
     * @var ehough_iconic_ContainerInterface
     *
     * @api
     */
    protected $container;

    /**
     * Sets the Container associated with this Controller.
     *
     * @param ehough_iconic_ContainerInterface $container A ehough_iconic_ContainerInterface instance
     *
     * @api
     */
    public function setContainer(ehough_iconic_ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
