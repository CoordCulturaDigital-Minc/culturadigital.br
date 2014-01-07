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
 * {@inheritDoc}
 *
 * Noop proxy instantiator - simply produces the real service instead of a proxy instance.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class ehough_iconic_lazyproxy_instantiator_RealServiceInstantiator implements ehough_iconic_lazyproxy_instantiator_InstantiatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function instantiateProxy(ehough_iconic_ContainerInterface $container, ehough_iconic_Definition $definition, $id, $realInstantiator)
    {
        return call_user_func($realInstantiator);
    }
}
