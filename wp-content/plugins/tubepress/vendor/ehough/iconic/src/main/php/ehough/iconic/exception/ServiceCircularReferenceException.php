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
 * This exception is thrown when a circular reference is detected.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_exception_ServiceCircularReferenceException extends ehough_iconic_exception_RuntimeException
{
    private $serviceId;
    private $path;

    public function __construct($serviceId, array $path, Exception $previous = null)
    {
        if (version_compare(PHP_VERSION, '5.3') < 0) {

            parent::__construct(sprintf('Circular reference detected for service "%s", path: "%s".', $serviceId, implode(' -> ', $path)), 0);

        } else {

            parent::__construct(sprintf('Circular reference detected for service "%s", path: "%s".', $serviceId, implode(' -> ', $path)), 0, $previous);
        }

        $this->serviceId = $serviceId;
        $this->path = $path;
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    public function getPath()
    {
        return $this->path;
    }
}
