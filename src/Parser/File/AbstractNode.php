<?php

namespace Mediadevs\Strictly\Parser\File;

use PhpParser\Node;
use phpDocumentor\Reflection\Docblock;
use phpDocumentor\Reflection\DocblockFactory;

/**
 * Class AbstractNode.
 *
 * @package Mediadevs\Strictly\FileAnalyser\Nodes
 */
abstract class AbstractNode
{
    /**
     * The node which will be subject to preparation.
     *
     * @var Node
     */
    protected Node $node;

    /**
     * AbstractNode constructor.
     *
     * @param Node $node
     */
    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    /**
     * Collecting the node of the child.
     *
     * @return Node
     */
    public function getNode(): Node
    {
        return $this->node;
    }

    /**
     * Collecting the functional code of the node.
     *
     * @return Node
     */
    public function getFunctionalCode(): Node
    {
        return $this->node;
    }

    /**
     * Collecting the docblock of the node.
     *
     * @return Docblock
     */
    public function getDocblock(): Docblock
    {
        $docblock = $this->node->getDocComment() !== null ? $this->node->getDocComment()->getText() : '/** */';

        return DocBlockFactory::createInstance()->create($docblock);
    }
}
