<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


class ehough_iconic_lazyproxy_instantiator_IconicInstantiator
{
    /**
     * @var ehough_iconic_Definition
     */
    private $_definition;

    /**
     * @var string
     */
    private $_id;

    /**
     * @var ehough_iconic_ContainerBuilder
     */
    private $_container;

    public function __construct(ehough_iconic_Definition $definition, $id, ehough_iconic_ContainerBuilder $container)
    {
        $this->_definition = $definition;
        $this->_id         = $id;
        $this->_container  = $container;
    }

    public function create()
    {
        return $this->_container->createService($this->_definition, $this->_id, false);
    }
}
