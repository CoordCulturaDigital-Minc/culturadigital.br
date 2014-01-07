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
 * Used to format logging messages during the compilation.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_compiler_LoggingFormatter
{
    public function formatRemoveService(ehough_iconic_compiler_CompilerPassInterface $pass, $id, $reason)
    {
        return $this->format($pass, sprintf('Removed service "%s"; reason: %s', $id, $reason));
    }

    public function formatInlineService(ehough_iconic_compiler_CompilerPassInterface $pass, $id, $target)
    {
        return $this->format($pass, sprintf('Inlined service "%s" to "%s".', $id, $target));
    }

    public function formatUpdateReference(ehough_iconic_compiler_CompilerPassInterface $pass, $serviceId, $oldDestId, $newDestId)
    {
        return $this->format($pass, sprintf('Changed reference of service "%s" previously pointing to "%s" to "%s".', $serviceId, $oldDestId, $newDestId));
    }

    public function formatResolveInheritance(ehough_iconic_compiler_CompilerPassInterface $pass, $childId, $parentId)
    {
        return $this->format($pass, sprintf('Resolving inheritance for "%s" (parent: %s).', $childId, $parentId));
    }

    public function format(ehough_iconic_compiler_CompilerPassInterface $pass, $message)
    {
        return sprintf('%s: %s', get_class($pass), $message);
    }
}
