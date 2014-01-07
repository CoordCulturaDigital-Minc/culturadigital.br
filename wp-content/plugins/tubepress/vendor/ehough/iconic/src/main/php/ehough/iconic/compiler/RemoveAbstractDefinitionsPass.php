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
 * Removes abstract ehough_iconic_Definitions
 *
 */
class ehough_iconic_compiler_RemoveAbstractDefinitionsPass implements ehough_iconic_compiler_CompilerPassInterface
{
    /**
     * Removes abstract definitions from the ContainerBuilder
     *
     * @param ehough_iconic_ContainerBuilder $container
     */
    public function process(ehough_iconic_ContainerBuilder $container)
    {
        $compiler = $container->getCompiler();
        $formatter = $compiler->getLoggingFormatter();

        foreach ($container->getDefinitions() as $id => $definition) {
            if ($definition->isAbstract()) {
                $container->removeDefinition($id);
                $compiler->addLogMessage($formatter->formatRemoveService($this, $id, 'abstract'));
            }
        }
    }
}
