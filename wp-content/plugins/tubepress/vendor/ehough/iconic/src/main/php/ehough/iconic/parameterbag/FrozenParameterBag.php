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
 * Holds read-only parameters.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class ehough_iconic_parameterbag_FrozenParameterBag extends ehough_iconic_parameterbag_ParameterBag
{
    /**
     * Constructor.
     *
     * For performance reasons, the constructor assumes that
     * all keys are already lowercased.
     *
     * This is always the case when used internally.
     *
     * @param array $parameters An array of parameters
     *
     * @api
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
        $this->resolved = true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function clear()
    {
        throw new ehough_iconic_exception_LogicException('Impossible to call clear() on a frozen ehough_iconic_parameterbag_ParameterBag.');
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function add(array $parameters)
    {
        throw new ehough_iconic_exception_LogicException('Impossible to call add() on a frozen ehough_iconic_parameterbag_ParameterBag.');
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function set($name, $value)
    {
        throw new ehough_iconic_exception_LogicException('Impossible to call set() on a frozen ehough_iconic_parameterbag_ParameterBag.');
    }
}
