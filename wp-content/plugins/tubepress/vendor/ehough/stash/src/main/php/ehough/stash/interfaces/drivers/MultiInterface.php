<?php

/*
 * This file is part of the Stash package.
 *
 * (c) Robert Hafner <tedivm@tedivm.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

interface ehough_stash_interfaces_drivers_MultiInterface
{
    public function multiGet(array $keys);

    public function multiSet(array $data);
}
