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
 * Provides useful features shared by many extensions.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class ehough_iconic_extension_Extension implements ehough_iconic_extension_ExtensionInterface, ehough_iconic_extension_ConfigurationExtensionInterface
{
    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return false;
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     */
    public function getNamespace()
    {
        return 'http://example.org/schema/dic/'.$this->getAlias();
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * This convention is to remove the "Extension" postfix from the class
     * name and then lowercase and underscore the result. So:
     *
     *     AcmeHelloExtension
     *
     * becomes
     *
     *     acme_hello
     *
     * This can be overridden in a sub-class to specify the alias manually.
     *
     * @return string The alias
     *
     * @throws ehough_iconic_exception_BadMethodCallException When the extension name does not follow conventions
     */
    public function getAlias()
    {
        $className = get_class($this);
        if (substr($className, -9) != 'Extension') {
            throw new ehough_iconic_exception_BadMethodCallException('This extension does not follow the naming convention; you must overwrite the getAlias() method.');
        }
        $classBaseName = substr(strrchr($className, '\\'), 1, -9);

        return ehough_iconic_Container::underscore($classBaseName);
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(array $config, ehough_iconic_ContainerBuilder $container)
    {
        $reflected = new ReflectionClass($this);
        $namespace = $reflected->getNamespaceName();

        $class = $namespace.'\\Configuration';
        if (class_exists($class)) {
            $r = new ReflectionClass($class);
            $ref = new ReflectionClass('\Symfony\Component\Config\Resource\FileResource');
            $container->addResource($ref->newInstanceArgs(array($r->getFileName())));

            if (!method_exists($class, '__construct')) {
                $configuration = new $class();

                return $configuration;
            }
        }

        return null;
    }

    final protected function processConfiguration($configuration, array $configs)
    {
        $ref = new ReflectionClass('\Symfony\Component\Config\Definition\Processor');

        $processor = $ref->newInstance();

        return $processor->processConfiguration($configuration, $configs);
    }

    /**
     * @param ehough_iconic_ContainerBuilder $container
     * @param array            $config
     *
     * @return Boolean Whether the configuration is enabled
     *
     * @throws ehough_iconic_exception_InvalidArgumentException When the config is not enableable
     */
    protected function isConfigEnabled(ehough_iconic_ContainerBuilder $container, array $config)
    {
        if (!array_key_exists('enabled', $config)) {
            throw new ehough_iconic_exception_InvalidArgumentException("The config array has no 'enabled' key.");
        }

        return (Boolean) $container->getParameterBag()->resolveValue($config['enabled']);
    }
}
