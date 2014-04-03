<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

interface ehough_iconic_extension_PrependExtensionInterface
{
    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ehough_iconic_ContainerBuilder $container
     */
    public function prepend(ehough_iconic_ContainerBuilder $container);
}
