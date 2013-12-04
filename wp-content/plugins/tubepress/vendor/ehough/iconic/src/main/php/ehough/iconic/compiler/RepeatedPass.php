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
 * A pass that might be run repeatedly.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_compiler_RepeatedPass implements ehough_iconic_compiler_CompilerPassInterface
{
    /**
     * @var Boolean
     */
    private $repeat = false;

    /**
     * @var ehough_iconic_compiler_RepeatablePassInterface[]
     */
    private $passes;

    /**
     * Constructor.
     *
     * @param ehough_iconic_compiler_RepeatablePassInterface[] $passes An array of RepeatablePassInterface objects
     *
     * @throws ehough_iconic_exception_InvalidArgumentException when the passes don't implement RepeatablePassInterface
     */
    public function __construct(array $passes)
    {
        foreach ($passes as $pass) {
            if (!$pass instanceof ehough_iconic_compiler_RepeatablePassInterface) {
                throw new ehough_iconic_exception_InvalidArgumentException('$passes must be an array of RepeatablePassInterface.');
            }

            $pass->setRepeatedPass($this);
        }

        $this->passes = $passes;
    }

    /**
     * Process the repeatable passes that run more than once.
     *
     * @param ehough_iconic_ContainerBuilder $container
     */
    public function process(ehough_iconic_ContainerBuilder $container)
    {
        $this->repeat = false;
        foreach ($this->passes as $pass) {
            $pass->process($container);
        }

        if ($this->repeat) {
            $this->process($container);
        }
    }

    /**
     * Sets if the pass should repeat
     */
    public function setRepeat()
    {
        $this->repeat = true;
    }

    /**
     * Returns the passes
     *
     * @return ehough_iconic_compiler_RepeatablePassInterface[] An array of ehough_iconic_compiler_RepeatablePassInterface objects
     */
    public function getPasses()
    {
        return $this->passes;
    }
}
