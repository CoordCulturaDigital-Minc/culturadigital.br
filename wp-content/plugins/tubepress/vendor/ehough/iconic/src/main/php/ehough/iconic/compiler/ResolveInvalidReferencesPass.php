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
 * Emulates the invalid behavior if the reference is not found within the
 * container.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_compiler_ResolveInvalidReferencesPass implements ehough_iconic_compiler_CompilerPassInterface
{
    private $container;

    /**
     * Process the ContainerBuilder to resolve invalid references.
     *
     * @param ehough_iconic_ContainerBuilder $container
     */
    public function process(ehough_iconic_ContainerBuilder $container)
    {
        $this->container = $container;
        foreach ($container->getDefinitions() as $definition) {
            if ($definition->isSynthetic() || $definition->isAbstract()) {
                continue;
            }

            $definition->setArguments(
                $this->processArguments($definition->getArguments())
            );

            $calls = array();
            foreach ($definition->getMethodCalls() as $call) {
                try {
                    $calls[] = array($call[0], $this->processArguments($call[1], true));
                } catch (ehough_iconic_exception_RuntimeException $ignore) {
                    // this call is simply removed
                }
            }
            $definition->setMethodCalls($calls);

            $properties = array();
            foreach ($definition->getProperties() as $name => $value) {
                try {
                    $value = $this->processArguments(array($value), true);
                    $properties[$name] = reset($value);
                } catch (ehough_iconic_exception_RuntimeException $ignore) {
                    // ignore property
                }
            }
            $definition->setProperties($properties);
        }
    }

    /**
     * Processes arguments to determine invalid references.
     *
     * @param array   $arguments    An array of ehough_iconic_Reference objects
     * @param Boolean $inMethodCall
     *
     * @return array
     *
     * @throws ehough_iconic_exception_RuntimeException When the config is invalid
     */
    private function processArguments(array $arguments, $inMethodCall = false)
    {
        foreach ($arguments as $k => $argument) {
            if (is_array($argument)) {
                $arguments[$k] = $this->processArguments($argument, $inMethodCall);
            } elseif ($argument instanceof ehough_iconic_Reference) {
                $id = (string) $argument;

                $invalidBehavior = $argument->getInvalidBehavior();
                $exists = $this->container->has($id);

                // resolve invalid behavior
                if (!$exists && ehough_iconic_ContainerInterface::NULL_ON_INVALID_REFERENCE === $invalidBehavior) {
                    $arguments[$k] = null;
                } elseif (!$exists && ehough_iconic_ContainerInterface::IGNORE_ON_INVALID_REFERENCE === $invalidBehavior) {
                    if ($inMethodCall) {
                        throw new ehough_iconic_exception_RuntimeException('Method shouldn\'t be called.');
                    }

                    $arguments[$k] = null;
                }
            }
        }

        return $arguments;
    }
}
