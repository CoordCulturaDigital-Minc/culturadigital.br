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
 * Represents a node in your service graph.
 *
 * Value is typically a definition, or an alias.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_compiler_ServiceReferenceGraphNode
{
    private $id;
    private $inEdges = array();
    private $outEdges = array();
    private $value;

    /**
     * Constructor.
     *
     * @param string $id    The node identifier
     * @param mixed  $value The node value
     */
    public function __construct($id, $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    /**
     * Adds an in edge to this node.
     *
     * @param ehough_iconic_compiler_ServiceReferenceGraphEdge $edge
     */
    public function addInEdge(ehough_iconic_compiler_ServiceReferenceGraphEdge $edge)
    {
        $this->inEdges[] = $edge;
    }

    /**
     * Adds an out edge to this node.
     *
     * @param ehough_iconic_compiler_ServiceReferenceGraphEdge $edge
     */
    public function addOutEdge(ehough_iconic_compiler_ServiceReferenceGraphEdge $edge)
    {
        $this->outEdges[] = $edge;
    }

    /**
     * Checks if the value of this node is an ehough_iconic_Alias.
     *
     * @return Boolean True if the value is an ehough_iconic_Alias instance
     */
    public function isAlias()
    {
        return $this->value instanceof ehough_iconic_Alias;
    }

    /**
     * Checks if the value of this node is a ehough_iconic_Definition.
     *
     * @return Boolean True if the value is a ehough_iconic_Definition instance
     */
    public function isDefinition()
    {
        return $this->value instanceof ehough_iconic_Definition;
    }

    /**
     * Returns the identifier.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the in edges.
     *
     * @return array The in ehough_iconic_compiler_ServiceReferenceGraphEdge array
     */
    public function getInEdges()
    {
        return $this->inEdges;
    }

    /**
     * Returns the out edges.
     *
     * @return array The out ehough_iconic_compiler_ServiceReferenceGraphEdge array
     */
    public function getOutEdges()
    {
        return $this->outEdges;
    }

    /**
     * Returns the value of this Node
     *
     * @return mixed The value
     */
    public function getValue()
    {
        return $this->value;
    }
}
