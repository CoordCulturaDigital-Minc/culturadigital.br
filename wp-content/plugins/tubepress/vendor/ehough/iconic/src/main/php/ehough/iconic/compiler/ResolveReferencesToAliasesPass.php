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
 * Replaces all references to aliases with references to the actual service.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_compiler_ResolveReferencesToAliasesPass implements ehough_iconic_compiler_CompilerPassInterface
{
    private $container;

    /**
     * Processes the ContainerBuilder to replace references to aliases with actual service references.
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

            $definition->setArguments($this->processArguments($definition->getArguments()));
            $definition->setMethodCalls($this->processArguments($definition->getMethodCalls()));
            $definition->setProperties($this->processArguments($definition->getProperties()));
        }

        foreach ($container->getAliases() as $id => $alias) {
            $aliasId = (string) $alias;
            if ($aliasId !== $defId = $this->getDefinitionId($aliasId)) {
                $container->setAlias($id, new ehough_iconic_Alias($defId, $alias->isPublic()));
            }
        }
    }

    /**
     * Processes the arguments to replace aliases.
     *
     * @param array $arguments An array of ehough_iconic_References
     *
     * @return array An array of ehough_iconic_References
     */
    private function processArguments(array $arguments)
    {
        foreach ($arguments as $k => $argument) {
            if (is_array($argument)) {
                $arguments[$k] = $this->processArguments($argument);
            } elseif ($argument instanceof ehough_iconic_Reference) {
                $defId = $this->getDefinitionId($id = (string) $argument);

                if ($defId !== $id) {
                    $arguments[$k] = new ehough_iconic_Reference($defId, $argument->getInvalidBehavior(), $argument->isStrict());
                }
            }
        }

        return $arguments;
    }

    /**
     * Resolves an alias into a definition id.
     *
     * @param string $id The definition or alias id to resolve
     *
     * @return string The definition id with aliases resolved
     */
    private function getDefinitionId($id)
    {
        while ($this->container->hasAlias($id)) {
            $id = (string) $this->container->getAlias($id);
        }

        return $id;
    }
}
