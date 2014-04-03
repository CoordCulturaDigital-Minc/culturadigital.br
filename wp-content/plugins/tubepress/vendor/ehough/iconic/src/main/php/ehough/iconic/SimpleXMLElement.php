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
 * SimpleXMLElement class.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ehough_iconic_SimpleXMLElement extends SimpleXMLElement
{
    /**
     * Converts an attribute as a php type.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getAttributeAsPhp($name)
    {
        return self::phpize($this[$name]);
    }

    /**
     * Returns arguments as valid php types.
     *
     * @param string  $name
     * @param Boolean $lowercase
     *
     * @return mixed
     */
    public function getArgumentsAsPhp($name, $lowercase = true)
    {
        $arguments = array();
        foreach ($this->$name as $arg) {
            if (isset($arg['name'])) {
                $arg['key'] = (string) $arg['name'];
            }
            $key = isset($arg['key']) ? (string) $arg['key'] : (!$arguments ? 0 : max(array_keys($arguments)) + 1);

            // parameter keys are case insensitive
            if ('parameter' == $name && $lowercase) {
                $key = strtolower($key);
            }

            // this is used by ehough_iconic_DefinitionDecorator to overwrite a specific
            // argument of the parent definition
            if (isset($arg['index'])) {
                $key = 'index_'.$arg['index'];
            }

            switch ($arg['type']) {
                case 'service':
                    $invalidBehavior = ehough_iconic_ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
                    if (isset($arg['on-invalid']) && 'ignore' == $arg['on-invalid']) {
                        $invalidBehavior = ehough_iconic_ContainerInterface::IGNORE_ON_INVALID_REFERENCE;
                    } elseif (isset($arg['on-invalid']) && 'null' == $arg['on-invalid']) {
                        $invalidBehavior = ehough_iconic_ContainerInterface::NULL_ON_INVALID_REFERENCE;
                    }

                    if (isset($arg['strict'])) {
                        $strict = self::phpize($arg['strict']);
                    } else {
                        $strict = true;
                    }

                    $arguments[$key] = new ehough_iconic_Reference((string) $arg['id'], $invalidBehavior, $strict);
                    break;
                case 'expression':
                    $ref             = new ReflectionClass('Symfony\Component\ExpressionLanguage\Expression');
                    $arguments[$key] = $ref->newInstance((string) $arg);
                    break;
                case 'collection':
                    $arguments[$key] = $arg->getArgumentsAsPhp($name, false);
                    break;
                case 'string':
                    $arguments[$key] = (string) $arg;
                    break;
                case 'constant':
                    $arguments[$key] = constant((string) $arg);
                    break;
                default:
                    $arguments[$key] = self::phpize($arg);
            }
        }

        return $arguments;
    }

    /**
     * Converts an xml value to a php type.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function phpize($value)
    {
        return \Symfony\Component\Config\Util\XmlUtils::phpize($value);
    }
}
