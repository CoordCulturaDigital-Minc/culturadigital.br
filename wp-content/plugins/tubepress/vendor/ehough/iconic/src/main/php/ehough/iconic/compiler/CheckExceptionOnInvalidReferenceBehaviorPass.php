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
 * Checks that all references are pointing to a valid service.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_compiler_CheckExceptionOnInvalidReferenceBehaviorPass implements ehough_iconic_compiler_CompilerPassInterface
{
    private $container;
    private $sourceId;

    public function process(ehough_iconic_ContainerBuilder $container)
    {
        $this->container = $container;

        foreach ($container->getDefinitions() as $id => $definition) {
            $this->sourceId = $id;
            $this->processDefinition($definition);
        }
    }

    private function processDefinition(ehough_iconic_Definition $definition)
    {
        $this->processReferences($definition->getArguments());
        $this->processReferences($definition->getMethodCalls());
        $this->processReferences($definition->getProperties());
    }

    private function processReferences(array $arguments)
    {
        foreach ($arguments as $argument) {
            if (is_array($argument)) {
                $this->processReferences($argument);
            } elseif ($argument instanceof ehough_iconic_Definition) {
                $this->processDefinition($argument);
            } elseif ($argument instanceof ehough_iconic_Reference && ehough_iconic_ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE === $argument->getInvalidBehavior()) {
                $destId = (string) $argument;

                if (!$this->container->has($destId)) {
                    throw new ehough_iconic_exception_ServiceNotFoundException($destId, $this->sourceId);
                }
            }
        }
    }
}
