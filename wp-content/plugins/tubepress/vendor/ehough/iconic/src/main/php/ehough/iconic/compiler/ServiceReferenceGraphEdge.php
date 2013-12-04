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
 * Represents an edge in your service graph.
 *
 * Value is typically a reference.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ehough_iconic_compiler_ServiceReferenceGraphEdge
{
    private $sourceNode;
    private $destNode;
    private $value;

    /**
     * Constructor.
     *
     * @param ehough_iconic_compiler_ServiceReferenceGraphNode $sourceNode
     * @param ehough_iconic_compiler_ServiceReferenceGraphNode $destNode
     * @param string                    $value
     */
    public function __construct(ehough_iconic_compiler_ServiceReferenceGraphNode $sourceNode, ehough_iconic_compiler_ServiceReferenceGraphNode $destNode, $value = null)
    {
        $this->sourceNode = $sourceNode;
        $this->destNode = $destNode;
        $this->value = $value;
    }

    /**
     * Returns the value of the edge
     *
     * @return ehough_iconic_compiler_ServiceReferenceGraphNode
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the source node
     *
     * @return ehough_iconic_compiler_ServiceReferenceGraphNode
     */
    public function getSourceNode()
    {
        return $this->sourceNode;
    }

    /**
     * Returns the destination node
     *
     * @return ehough_iconic_compiler_ServiceReferenceGraphNode
     */
    public function getDestNode()
    {
        return $this->destNode;
    }
}
