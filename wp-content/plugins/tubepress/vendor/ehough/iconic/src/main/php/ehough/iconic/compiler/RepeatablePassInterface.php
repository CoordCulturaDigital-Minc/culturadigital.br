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
 * Interface that must be implemented by passes that are run as part of an
 * RepeatedPass.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface ehough_iconic_compiler_RepeatablePassInterface extends ehough_iconic_compiler_CompilerPassInterface
{
    /**
     * Sets the RepeatedPass interface.
     *
     * @param ehough_iconic_compiler_RepeatedPass $repeatedPass
     */
    public function setRepeatedPass(ehough_iconic_compiler_RepeatedPass $repeatedPass);
}
