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
 * IntrospectableContainerInterface defines additional introspection functionality
 * for containers, allowing logic to be implemented based on a Container's state.
 *
 * @author Evan Villemez <evillemez@gmail.com>
 *
 */
interface ehough_iconic_IntrospectableContainerInterface extends ehough_iconic_ContainerInterface
{
    /**
     * Check for whether or not a service has been initialized.
     *
     * @param string $id
     *
     * @return Boolean true if the service has been initialized, false otherwise
     *
     */
    public function initialized($id);

}
