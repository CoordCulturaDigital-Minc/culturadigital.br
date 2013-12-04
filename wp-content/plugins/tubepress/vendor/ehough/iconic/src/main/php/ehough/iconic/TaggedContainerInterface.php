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
 * TaggedContainerInterface is the interface implemented when a container knows how to deals with tags.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
interface ehough_iconic_TaggedContainerInterface extends ehough_iconic_ContainerInterface
{
    /**
     * Returns service ids for a given tag.
     *
     * @param string $name The tag name
     *
     * @return array An array of tags
     *
     * @api
     */
    public function findTaggedServiceIds($name);
}
