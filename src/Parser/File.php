<?php

namespace Mediadevs\StrictlyPHP\Parser;

use PhpParser\Node;
use PhpParser\ParserFactory;
use \Symfony\Component\Finder\SplFileInfo;
use Mediadevs\StrictlyPHP\Parser\File\MethodNode;
use Mediadevs\StrictlyPHP\Parser\File\PropertyNode;
use Mediadevs\StrictlyPHP\Parser\File\FunctionNode;
use Mediadevs\StrictlyPHP\Parser\File\MagicMethodNode;
use Mediadevs\StrictlyPHP\Parser\File\ArrowFunctionNode;

/**
 * Class File.
 *
 * @package Mediadevs\StrictlyPHP\FileAnalyser
 */
final class File
{
    /** @var array All the names of the magic methods. */
    private const MAGIC_METHODS = array(
        '__construct',
        '__destruct',
        '__call',
        '__callStatic',
        '__get',
        '__set',
        '__isset',
        '__unset',
        '__sleep',
        '__wakeup',
        '__toString',
        '__invoke',
        '__set_state',
        '__clone',
        '__debugInfo'
    );

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
     * This property stores all arrow function nodes for this file.
     *
     * @var ArrowFunctionNode[]
     */
    public array $arrowFunctionNode;

    /**
     * This property stores all function nodes for this file.
     *
     * @var FunctionNode[]
     */
    public array $functionNode;

    /**
     * This property stores all magic method nodes for this file.
     *
     * @var MagicMethodNode[]
     */
    public array $magicMethodNode;

    /**
     * This property stores all method nodes for this file.
     *
     * @var MethodNode[]
     */
    public array $methodNode;


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
            if ($this->isArrowFunction($node)) {
                $this->arrowFunctionNode[] = new ArrowFunctionNode($node);
            }

            if ($this->isFunction($node)) {
                $this->functionNode[] = new FunctionNode($node);
            }

            if ($this->isMagicMethod($node)) {
                $this->magicMethodNode[] = new MagicMethodNode($node);
            }

            if ($this->isMethod($node)) {
                $this->methodNode[] = new MethodNode($node);
            }
        }

        if ($this->isPropertyGroup($node)) {
            $this->parseProperties($node);
        }

        if ($this->isProperty($node)) {
            $this->propertyNodes[] = new PropertyNode($node);
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
     * Whether the node is an instance of functionLike.
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
     * Whether the node is an instance of function.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isFunction(Node $node): bool
    {
        return (bool) ($node instanceof Node\Stmt\Function_);
    }

    /**
     * Whether the node is an arrow function.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isArrowFunction(Node $node): bool
    {
        return (bool) false; // TODO: Not implemented yet.
    }

    /**
     * Whether the node is an instance of method.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isMethod(Node $node): bool
    {
        return (bool) ($node instanceof Node\Stmt\ClassMethod);
    }

    /**
     * Whether the node is an instance of (magic) method.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isMagicMethod(Node $node): bool
    {
        return (bool) ($this->isMethod($node) && in_array($node->name->name, self::MAGIC_METHODS));
    }

    /**
     * Whether the node is an instance of property.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isPropertyGroup(Node $node): bool
    {
        return (bool) ($node instanceof Node\Stmt\Property);
    }

    /**
     * Whether the node is an instance of property.
     *
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isProperty(Node $node): bool
    {
        return (bool) ($node instanceof Node\Stmt\PropertyProperty);
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
     * Parsing the properties from the property group.
     *
     * @param \PhpParser\Node $node
     *
     * @return void
     */
    private function parseProperties(Node $node): void
    {
        foreach ($node->props as $properties) {
            $this->analyseNode($properties);
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
