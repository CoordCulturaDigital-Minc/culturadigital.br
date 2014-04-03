<?php

/*
 * This file is part of the ehough/finder package.
 *
 * (c) Eric D. Hough <eric@tubepress.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ehough_finder_FinderFactory implements ehough_finder_FinderFactoryInterface
{
    /**
     * @return ehough_finder_FinderInterface
     */
    public function createFinder()
    {
        return new ehough_finder_Finder();
    }
}
