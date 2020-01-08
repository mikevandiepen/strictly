<?php

namespace Mediadevs\StrictlyPHP\Parser;

use PhpParser\Node;
use \Symfony\Component\Finder\SplFileInfo;
use Mediadevs\StrictlyPHP\Parser\File\PropertyNode;
use Mediadevs\StrictlyPHP\Parser\File\FunctionLikeNode;

/**
 * Class File.
 *
 * @package Mediadevs\StrictlyPHP\FileAnalyser
 */
final class File
{
    /**
     * The name of this file.
     *
     * @var string
     */
    public string $fileName;

    /**
     * The size of this file.
     *
     * @var int
     */
    public int $fileSize;

    /**
     * This property stores all function like nodes for this file.
     *
     * @var FunctionLikeNode[]
     */
    public array $functionLikeNodes;


    /**
     * This property stores all property nodes for this file.
     *
     * @var PropertyNode[]
     */
    public array $propertyNodes;

    /**
     * Handling the file and sorting the nodes into the correct node group.
     *
     * @param \Symfony\Component\Finder\SplFileInfo $file
     *
     * @return void
     */
    public function handleFile(SplFileInfo $file): void
    {
        $this->fileName = $file->getFilename();
        $this->fileSize = $file->getSize();

        $parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);
        $nodes = $parser->parse($file->getContents());

        // Iterating through all the nodes and validating whether the analyser exists.
        foreach ($nodes as $node) {
            $this->analyseNode($node);
        }
    }

    /**
     * Analysing the node and storing the node in the right node-group.
     *
     * @param \PhpParser\Node $node
     *
     * @return void
     */
    private function analyseNode(Node $node): void
    {
        if ($this->isAssign($node)) {
            $this->analyseNode($node->expr);
        }

        if ($this->isClass($node)) {
            $this->parseSubNodes($node);
        }

        if ($this->isCallable($node)) {
            $this->parseNodeArguments($node);
        }

        if ($this->isExpression($node)) {
            $this->analyseNode($node->expr);
        }

        if ($this->isFunctionLike($node)) {
            $this->functionLikeNodes[] = $node;
        }

        if ($this->isProperty($node)) {
            $this->propertyNodes[] = $node;
        }

        $this->parseSubNodes($node);
    }

    /**
     * Whether the node is an instance of assign.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isAssign(Node $node): bool
    {
        return (bool) ($node instanceof Node\Expr\Assign);
    }

    /**
     * Whether the node is an instance of Class.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isClass(Node $node): bool
    {
        return (bool) ($node instanceof Node\Stmt\Class_);
    }

    /**
     * Whether the node falls in the callable group.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isCallable(Node $node): bool
    {
        $functionCall   = ($node instanceof Node\Expr\FuncCall);
        $methodCall     = ($node instanceof Node\Expr\MethodCall);
        $staticCall     = ($node instanceof Node\Expr\StaticCall);

        return (bool) ($functionCall || $methodCall || $staticCall);
    }

    /**
     * Whether the node is an instance of expression.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isExpression(Node $node): bool
    {
        return (bool) ($node instanceof Node\Stmt\Expression);
    }


    /**
     * Whether the node is an instance of FunctionLikeNode.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isFunctionLike(Node $node): bool
    {
        return (bool) ($node instanceof Node\FunctionLike);
    }

    /**
     * Whether the node falls in the property group.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isProperty(Node $node): bool
    {
        $property           = ($node instanceof Node\Stmt\Property);
        $propertyProperty   = ($node instanceof Node\Stmt\PropertyProperty);

        return (bool) ($property || $propertyProperty);
    }

    /**
     * Parsing the sub nodes of the parent node.
     *
     * @param \PhpParser\Node $node
     *
     * @return void
     */
    private function parseSubNodes(Node $node): void
    {
        if (isset($node->stmts) && count($node->stmts) > 0) {
            foreach ($node->stmts as $subNode) {
                // Running a recursive call for the sub node.
                $this->analyseNode($subNode);
            }
        }
    }

    /**
     * Parsing the arguments of the parent node.
     *
     * @param \PhpParser\Node $node
     *
     * @return void
     */
    private function parseNodeArguments(Node $node): void
    {
        foreach ($node->args as $arg) {
            $this->analyseNode($arg->value);
        }
    }
}
