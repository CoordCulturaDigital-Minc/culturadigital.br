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
 * This class is used to remove circular dependencies between individual passes.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * @api
 */
class ehough_iconic_compiler_Compiler
{
    private $passConfig;
    private $log = array();
    private $loggingFormatter;
    private $serviceReferenceGraph;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->passConfig = new ehough_iconic_compiler_PassConfig();
        $this->serviceReferenceGraph = new ehough_iconic_compiler_ServiceReferenceGraph();
        $this->loggingFormatter = new ehough_iconic_compiler_LoggingFormatter();
    }

    /**
     * Returns the ehough_iconic_compiler_PassConfig.
     *
     * @return ehough_iconic_compiler_PassConfig The ehough_iconic_compiler_PassConfig instance
     *
     * @api
     */
    public function getPassConfig()
    {
        return $this->passConfig;
    }

    /**
     * Returns the ehough_iconic_compiler_ServiceReferenceGraph.
     *
     * @return ehough_iconic_compiler_ServiceReferenceGraph The ehough_iconic_compiler_ServiceReferenceGraph instance
     *
     * @api
     */
    public function getServiceReferenceGraph()
    {
        return $this->serviceReferenceGraph;
    }

    /**
     * Returns the logging formatter which can be used by compilation passes.
     *
     * @return ehough_iconic_compiler_LoggingFormatter
     */
    public function getLoggingFormatter()
    {
        return $this->loggingFormatter;
    }

    /**
     * Adds a pass to the ehough_iconic_compiler_PassConfig.
     *
     * @param ehough_iconic_compiler_CompilerPassInterface $pass A compiler pass
     * @param string                $type The type of the pass
     *
     * @api
     */
    public function addPass(ehough_iconic_compiler_CompilerPassInterface $pass, $type = ehough_iconic_compiler_PassConfig::TYPE_BEFORE_OPTIMIZATION)
    {
        $this->passConfig->addPass($pass, $type);
    }

    /**
     * Adds a log message.
     *
     * @param string $string The log message
     */
    public function addLogMessage($string)
    {
        $this->log[] = $string;
    }

    /**
     * Returns the log.
     *
     * @return array Log array
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Run the ehough_iconic_compiler_Compiler and process all Passes.
     *
     * @param ehough_iconic_ContainerBuilder $container
     *
     * @api
     */
    public function compile(ehough_iconic_ContainerBuilder $container)
    {
        foreach ($this->passConfig->getPasses() as $pass) {
            $pass->process($container);
        }
    }
}
