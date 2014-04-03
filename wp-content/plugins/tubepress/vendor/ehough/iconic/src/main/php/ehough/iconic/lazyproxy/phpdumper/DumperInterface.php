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
 * Lazy proxy dumper capable of generating the instantiation logic php code for proxied services.
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
interface ehough_iconic_lazyproxy_phpdumper_DumperInterface
{
    /**
     * Inspects whether the given definitions should produce proxy instantiation logic in the dumped container.
     *
     * @param ehough_iconic_Definition $definition
     *
     * @return bool
     */
    public function isProxyCandidate(ehough_iconic_Definition $definition);

    /**
     * Generates the code to be used to instantiate a proxy in the dumped factory code.
     *
     * @param ehough_iconic_Definition $definition
     * @param string     $id         service identifier
     *
     * @return string
     */
    public function getProxyFactoryCode(ehough_iconic_Definition $definition, $id);

    /**
     * Generates the code for the lazy proxy.
     *
     * @param ehough_iconic_Definition $definition
     *
     * @return string
     */
    public function getProxyCode(ehough_iconic_Definition $definition);
}
