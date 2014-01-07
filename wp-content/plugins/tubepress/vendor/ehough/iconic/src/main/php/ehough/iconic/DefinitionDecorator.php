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
 * This definition decorates another definition.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * @api
 */
class ehough_iconic_DefinitionDecorator extends ehough_iconic_Definition
{
    private $parent;
    private $changes = array();

    /**
     * Constructor.
     *
     * @param string $parent The id of ehough_iconic_Definition instance to decorate.
     *
     * @api
     */
    public function __construct($parent)
    {
        parent::__construct();

        $this->parent = $parent;
    }

    /**
     * Returns the ehough_iconic_Definition being decorated.
     *
     * @return string
     *
     * @api
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Returns all changes tracked for the ehough_iconic_Definition object.
     *
     * @return array An array of changes for this ehough_iconic_Definition
     *
     * @api
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function setClass($class)
    {
        $this->changes['class'] = true;

        return parent::setClass($class);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function setFactoryClass($class)
    {
        $this->changes['factory_class'] = true;

        return parent::setFactoryClass($class);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function setFactoryMethod($method)
    {
        $this->changes['factory_method'] = true;

        return parent::setFactoryMethod($method);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function setFactoryService($service)
    {
        $this->changes['factory_service'] = true;

        return parent::setFactoryService($service);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function setConfigurator($callable)
    {
        $this->changes['configurator'] = true;

        return parent::setConfigurator($callable);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function setFile($file)
    {
        $this->changes['file'] = true;

        return parent::setFile($file);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function setPublic($boolean)
    {
        $this->changes['public'] = true;

        return parent::setPublic($boolean);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function setLazy($boolean)
    {
        $this->changes['lazy'] = true;

        return parent::setLazy($boolean);
    }

    /**
     * Gets an argument to pass to the service constructor/factory method.
     *
     * If replaceArgument() has been used to replace an argument, this method
     * will return the replacement value.
     *
     * @param integer $index
     *
     * @return mixed The argument value
     *
     * @throws ehough_iconic_exception_OutOfBoundsException When the argument does not exist
     *
     * @api
     */
    public function getArgument($index)
    {
        if (array_key_exists('index_'.$index, $this->arguments)) {
            return $this->arguments['index_'.$index];
        }

        $lastIndex = count(array_filter(array_keys($this->arguments), 'is_int')) - 1;

        if ($index < 0 || $index > $lastIndex) {
            throw new ehough_iconic_exception_OutOfBoundsException(sprintf('The index "%d" is not in the range [0, %d].', $index, $lastIndex));
        }

        return $this->arguments[$index];
    }

    /**
     * You should always use this method when overwriting existing arguments
     * of the parent definition.
     *
     * If you directly call setArguments() keep in mind that you must follow
     * certain conventions when you want to overwrite the arguments of the
     * parent definition, otherwise your arguments will only be appended.
     *
     * @param integer $index
     * @param mixed   $value
     *
     * @return ehough_iconic_DefinitionDecorator the current instance
     * @throws ehough_iconic_exception_InvalidArgumentException when $index isn't an integer
     *
     * @api
     */
    public function replaceArgument($index, $value)
    {
        if (!is_int($index)) {
            throw new ehough_iconic_exception_InvalidArgumentException('$index must be an integer.');
        }

        $this->arguments['index_'.$index] = $value;

        return $this;
    }
}
