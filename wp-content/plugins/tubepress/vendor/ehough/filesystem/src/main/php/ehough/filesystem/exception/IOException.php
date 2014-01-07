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
 * Exception class thrown when a filesystem operation failure happens
 *
 * @author Romain Neutron <imprec@gmail.com>
 * @author Christian Gärtner <christiangaertner.film@googlemail.com>
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class ehough_filesystem_exception_IOException extends RuntimeException implements ehough_filesystem_exception_IOExceptionInterface
{
    private $path;

    public function __construct($message, $code = 0, Exception $previous = null, $path = null)
    {
        $this->path = $path;

        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {

            parent::__construct($message, $code, $previous);

        } else {

            parent::__construct($message, $code);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }
}
