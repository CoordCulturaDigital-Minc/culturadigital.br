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
 * Null dumper, negates any proxy code generation for any given service definition.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class ehough_iconic_lazyproxy_phpdumper_NullDumper implements ehough_iconic_lazyproxy_phpdumper_DumperInterface
{
    /**
     * {@inheritDoc}
     */
    public function isProxyCandidate(ehough_iconic_Definition $definition)
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getProxyFactoryCode(ehough_iconic_Definition $definition, $id)
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getProxyCode(ehough_iconic_Definition $definition)
    {
        return '';
    }
}
