<?php

namespace Mediadevs\Strictly\Parser\File;

use PhpParser\Node;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;

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
     * @var \PhpParser\Node
     */
    protected Node $node;

    /**
     * AbstractNode constructor.
     *
     * @param \PhpParser\Node $node
     */
    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    /**
     * Collecting the node of the child.
     *
     * @return \PhpParser\Node
     */
    public function getNode(): Node
    {
        return $this->node;
    }

    /**
     * Collecting the functional code of the node.
     *
     * @return \PhpParser\Node
     */
    public function getFunctionalCode(): Node
    {
        return $this->node;
    }

    /**
     * Collecting the docblock of the node.
     *
     * @return \phpDocumentor\Reflection\DocBlock
     */
    public function getDocblock(): DocBlock
    {
        $docblock = $this->node->getDocComment() !== null ? $this->node->getDocComment()->getText() : '/** */';

        return DocBlockFactory::createInstance()->create($docblock);
    }
}
