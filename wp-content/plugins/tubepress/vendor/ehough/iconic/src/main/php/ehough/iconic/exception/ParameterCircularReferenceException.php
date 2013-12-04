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
 * This exception is thrown when a circular reference in a parameter is detected.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ehough_iconic_exception_ParameterCircularReferenceException extends ehough_iconic_exception_RuntimeException
{
    private $parameters;

    public function __construct($parameters, Exception $previous = null)
    {
        if (version_compare(PHP_VERSION, '5.3') < 0) {

            parent::__construct(sprintf('Circular reference detected for parameter "%s" ("%s" > "%s").', $parameters[0], implode('" > "', $parameters), $parameters[0]), 0);

        } else {

            parent::__construct(sprintf('Circular reference detected for parameter "%s" ("%s" > "%s").', $parameters[0], implode('" > "', $parameters), $parameters[0]), 0, $previous);
        }

        $this->parameters = $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}
