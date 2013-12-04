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
 * IniFileLoader loads parameters from INI files.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ehough_iconic_loader_IniFileLoader extends ehough_iconic_loader_FileLoader
{
    /**
     * Loads a resource.
     *
     * @param mixed  $file The resource
     * @param string $type The resource type
     *
     * @throws ehough_iconic_exception_InvalidArgumentException When ini file is not valid
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        $this->container->addResource(new \Symfony\Component\Config\Resource\FileResource($path));

        $result = parse_ini_file($path, true);
        if (false === $result || array() === $result) {
            throw new ehough_iconic_exception_InvalidArgumentException(sprintf('The "%s" file is not valid.', $file));
        }

        if (isset($result['parameters']) && is_array($result['parameters'])) {
            foreach ($result['parameters'] as $key => $value) {
                $this->container->setParameter($key, $value);
            }
        }
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'ini' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
