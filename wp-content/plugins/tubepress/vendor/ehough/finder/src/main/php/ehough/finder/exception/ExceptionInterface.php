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
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface ehough_finder_exception_ExceptionInterface
{
    /**
     * @return ehough_finder_adapter_AdapterInterface
     */
    public function getAdapter();
}
