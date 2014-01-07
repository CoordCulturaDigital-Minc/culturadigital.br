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
 * ContainerAwareInterface should be implemented by classes that depends on a Container.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
interface ehough_iconic_ContainerAwareInterface
{
    /**
     * Sets the Container.
     *
     * @param ehough_iconic_ContainerInterface $container A ehough_iconic_ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ehough_iconic_ContainerInterface $container = null);
}
