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
 * ContainerAware trait.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
trait ContainerAwareTrait
{
    /**
     * @var ehough_iconic_ContainerInterface
     */
    protected $container;

    /**
     * Sets the Container associated with this Controller.
     *
     * @param ehough_iconic_ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ehough_iconic_ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
