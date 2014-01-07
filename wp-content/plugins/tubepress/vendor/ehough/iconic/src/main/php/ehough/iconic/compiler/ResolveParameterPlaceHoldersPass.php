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
 * Resolves all parameter placeholders "%somevalue%" to their real values.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_compiler_ResolveParameterPlaceHoldersPass implements ehough_iconic_compiler_CompilerPassInterface
{
    /**
     * Processes the ContainerBuilder to resolve parameter placeholders.
     *
     * @param ehough_iconic_ContainerBuilder $container
     *
     * @throws ehough_iconic_exception_ParameterNotFoundException
     */
    public function process(ehough_iconic_ContainerBuilder $container)
    {
        $parameterBag = $container->getParameterBag();

        foreach ($container->getDefinitions() as $id => $definition) {
            try {
                $definition->setClass($parameterBag->resolveValue($definition->getClass()));
                $definition->setFile($parameterBag->resolveValue($definition->getFile()));
                $definition->setArguments($parameterBag->resolveValue($definition->getArguments()));

                $calls = array();
                foreach ($definition->getMethodCalls() as $name => $arguments) {
                    $calls[$parameterBag->resolveValue($name)] = $parameterBag->resolveValue($arguments);
                }
                $definition->setMethodCalls($calls);

                $definition->setProperties($parameterBag->resolveValue($definition->getProperties()));
            } catch (ehough_iconic_exception_ParameterNotFoundException $e) {
                $e->setSourceId($id);

                throw $e;
            }
        }

        $aliases = array();
        foreach ($container->getAliases() as $name => $target) {
            $aliases[$parameterBag->resolveValue($name)] = $parameterBag->resolveValue($target);
        }
        $container->setAliases($aliases);

        $parameterBag->resolve();
    }
}
