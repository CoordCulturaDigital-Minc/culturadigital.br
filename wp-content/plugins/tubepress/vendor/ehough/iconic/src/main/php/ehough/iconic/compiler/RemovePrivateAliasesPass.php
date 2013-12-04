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
 * Remove private aliases from the container. They were only used to establish
 * dependencies between services, and these dependencies have been resolved in
 * one of the previous passes.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_compiler_RemovePrivateAliasesPass implements ehough_iconic_compiler_CompilerPassInterface
{
    /**
     * Removes private aliases from the ContainerBuilder
     *
     * @param ehough_iconic_ContainerBuilder $container
     */
    public function process(ehough_iconic_ContainerBuilder $container)
    {
        $compiler = $container->getCompiler();
        $formatter = $compiler->getLoggingFormatter();

        foreach ($container->getAliases() as $id => $alias) {
            if ($alias->isPublic()) {
                continue;
            }

            $container->removeAlias($id);
            $compiler->addLogMessage($formatter->formatRemoveService($this, $id, 'private alias'));
        }
    }
}
