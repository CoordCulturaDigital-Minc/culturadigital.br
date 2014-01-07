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
 * Compiler Pass Configuration
 *
 * This class has a default configuration embedded.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * @api
 */
class ehough_iconic_compiler_PassConfig
{
    const TYPE_AFTER_REMOVING = 'afterRemoving';
    const TYPE_BEFORE_OPTIMIZATION = 'beforeOptimization';
    const TYPE_BEFORE_REMOVING = 'beforeRemoving';
    const TYPE_OPTIMIZE = 'optimization';
    const TYPE_REMOVE = 'removing';

    private $mergePass;
    private $afterRemovingPasses = array();
    private $beforeOptimizationPasses = array();
    private $beforeRemovingPasses = array();
    private $optimizationPasses;
    private $removingPasses;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->mergePass = new ehough_iconic_compiler_MergeExtensionConfigurationPass();

        $this->optimizationPasses = array(
            new ehough_iconic_compiler_ResolveDefinitionTemplatesPass(),
            new ehough_iconic_compiler_ResolveParameterPlaceHoldersPass(),
            new ehough_iconic_compiler_CheckDefinitionValidityPass(),
            new ehough_iconic_compiler_ResolveReferencesToAliasesPass(),
            new ehough_iconic_compiler_ResolveInvalidReferencesPass(),
            new ehough_iconic_compiler_AnalyzeServiceReferencesPass(true),
            new ehough_iconic_compiler_CheckCircularReferencesPass(),
            new ehough_iconic_compiler_CheckReferenceValidityPass(),
        );

        $this->removingPasses = array(
            new ehough_iconic_compiler_RemovePrivateAliasesPass(),
            new ehough_iconic_compiler_RemoveAbstractDefinitionsPass(),
            new ehough_iconic_compiler_ReplaceAliasByActualDefinitionPass(),
            new ehough_iconic_compiler_RepeatedPass(array(
                new ehough_iconic_compiler_AnalyzeServiceReferencesPass(),
                new ehough_iconic_compiler_InlineServiceDefinitionsPass(),
                new ehough_iconic_compiler_AnalyzeServiceReferencesPass(),
                new ehough_iconic_compiler_RemoveUnusedDefinitionsPass(),
            )),
            new ehough_iconic_compiler_CheckExceptionOnInvalidReferenceBehaviorPass(),
        );
    }

    /**
     * Returns all passes in order to be processed.
     *
     * @return array An array of all passes to process
     *
     * @api
     */
    public function getPasses()
    {
        return array_merge(
            array($this->mergePass),
            $this->beforeOptimizationPasses,
            $this->optimizationPasses,
            $this->beforeRemovingPasses,
            $this->removingPasses,
            $this->afterRemovingPasses
        );
    }

    /**
     * Adds a pass.
     *
     * @param ehough_iconic_compiler_CompilerPassInterface $pass A ehough_iconic_compiler_Compiler pass
     * @param string                $type The pass type
     *
     * @throws ehough_iconic_exception_InvalidArgumentException when a pass type doesn't exist
     *
     * @api
     */
    public function addPass(ehough_iconic_compiler_CompilerPassInterface $pass, $type = self::TYPE_BEFORE_OPTIMIZATION)
    {
        $property = $type.'Passes';
        if (!isset($this->$property)) {
            throw new ehough_iconic_exception_InvalidArgumentException(sprintf('Invalid type "%s".', $type));
        }

        $passes = &$this->$property;
        $passes[] = $pass;
    }

    /**
     * Gets all passes for the AfterRemoving pass.
     *
     * @return array An array of passes
     *
     * @api
     */
    public function getAfterRemovingPasses()
    {
        return $this->afterRemovingPasses;
    }

    /**
     * Gets all passes for the BeforeOptimization pass.
     *
     * @return array An array of passes
     *
     * @api
     */
    public function getBeforeOptimizationPasses()
    {
        return $this->beforeOptimizationPasses;
    }

    /**
     * Gets all passes for the BeforeRemoving pass.
     *
     * @return array An array of passes
     *
     * @api
     */
    public function getBeforeRemovingPasses()
    {
        return $this->beforeRemovingPasses;
    }

    /**
     * Gets all passes for the Optimization pass.
     *
     * @return array An array of passes
     *
     * @api
     */
    public function getOptimizationPasses()
    {
        return $this->optimizationPasses;
    }

    /**
     * Gets all passes for the Removing pass.
     *
     * @return array An array of passes
     *
     * @api
     */
    public function getRemovingPasses()
    {
        return $this->removingPasses;
    }

    /**
     * Gets all passes for the Merge pass.
     *
     * @return array An array of passes
     *
     * @api
     */
    public function getMergePass()
    {
        return $this->mergePass;
    }

    /**
     * Sets the Merge Pass.
     *
     * @param ehough_iconic_compiler_CompilerPassInterface $pass The merge pass
     *
     * @api
     */
    public function setMergePass(ehough_iconic_compiler_CompilerPassInterface $pass)
    {
        $this->mergePass = $pass;
    }

    /**
     * Sets the AfterRemoving passes.
     *
     * @param array $passes An array of passes
     *
     * @api
     */
    public function setAfterRemovingPasses(array $passes)
    {
        $this->afterRemovingPasses = $passes;
    }

    /**
     * Sets the BeforeOptimization passes.
     *
     * @param array $passes An array of passes
     *
     * @api
     */
    public function setBeforeOptimizationPasses(array $passes)
    {
        $this->beforeOptimizationPasses = $passes;
    }

    /**
     * Sets the BeforeRemoving passes.
     *
     * @param array $passes An array of passes
     *
     * @api
     */
    public function setBeforeRemovingPasses(array $passes)
    {
        $this->beforeRemovingPasses = $passes;
    }

    /**
     * Sets the Optimization passes.
     *
     * @param array $passes An array of passes
     *
     * @api
     */
    public function setOptimizationPasses(array $passes)
    {
        $this->optimizationPasses = $passes;
    }

    /**
     * Sets the Removing passes.
     *
     * @param array $passes An array of passes
     *
     * @api
     */
    public function setRemovingPasses(array $passes)
    {
        $this->removingPasses = $passes;
    }
}
