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
 * ClosureLoader loads service definitions from a PHP closure.
 *
 * The Closure has access to the container as its first argument.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ehough_iconic_loader_ClosureLoader extends \Symfony\Component\Config\Loader\Loader
{
    private $container;

    /**
     * Constructor.
     *
     * @param \ehough_iconic_ContainerBuilder $container A ContainerBuilder instance
     */
    public function __construct(\ehough_iconic_ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * Loads a Closure.
     *
     * @param \Closure $closure The resource
     * @param string   $type    The resource type
     */
    public function load($closure, $type = null)
    {
        call_user_func($closure, $this->container);
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return $resource instanceof \Closure;
    }
}
