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
 * Lazy proxy instantiator, capable of instantiating a proxy given a container, the
 * service definitions and a callback that produces the real service instance.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
interface ehough_iconic_lazyproxy_instantiator_InstantiatorInterface
{
    /**
     * Instantiates a proxy object.
     *
     * @param ehough_iconic_ContainerInterface $container        the container from which the service is being requested
     * @param ehough_iconic_Definition         $definition       the definition of the requested service
     * @param string             $id               identifier of the requested service
     * @param callable           $realInstantiator zero-argument callback that is capable of producing the real
     *                                             service instance
     *
     * @return object
     */
    public function instantiateProxy(ehough_iconic_ContainerInterface $container, ehough_iconic_Definition $definition, $id, $realInstantiator);
}
