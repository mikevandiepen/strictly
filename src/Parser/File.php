<?php

namespace Mediadevs\Strictly\Parser;

use PhpParser\Node;
use PhpParser\ParserFactory;
use \Symfony\Component\Finder\SplFileInfo;
use Mediadevs\Strictly\Parser\File\MethodNode;
use Mediadevs\Strictly\Parser\File\ClosureNode;
use Mediadevs\Strictly\Parser\File\PropertyNode;
use Mediadevs\Strictly\Parser\File\FunctionNode;
use Mediadevs\Strictly\Parser\File\MagicMethodNode;
use Mediadevs\Strictly\Parser\File\ArrowFunctionNode;

/**
 * Class File.
 *
 * @package Mediadevs\Strictly\FileAnalyser
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
     * This property stores all closure nodes for this file.
     *
     * @var ClosureNode[]
     */
    public array $closureNode;

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
     * File constructor.
     * Handling the file and sorting the nodes into the correct node group.
     *
     * @param SplFileInfo $file
     */
    public function __construct(SplFileInfo $file)
    {
        $this->fileName = $file->getFilename();
        $this->fileSize = $file->getSize();

        $parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
        $nodes = $parser->parse($file->getContents());

        // Iterating through all the nodes and validating whether the analyser exists.
        foreach ($nodes as $node) {
            $this->analyseNode($node);
        }
    }

    /**
     * Analysing the node and storing the node in the right node-group.
     *
     * @param Node $node
     *
     * @return void
     */
    private function analyseNode(Node $node): void
    {
        if ($this->isAssign($node)) {
            // Recursive call.
            $this->analyseNode($node->expr);
        }

        if ($this->isClass($node)) {
            // Recursive call.
            $this->parseSubNodes($node);
        }

        if ($this->isCallable($node)) {
            // Recursive call.
            $this->parseNodeArguments($node);
        }

        if ($this->isExpression($node)) {
            // Recursive call.
            $this->analyseNode($node->expr);
        }

        if ($this->isFunctionLike($node)) {
            if ($this->isArrowFunction($node)) {
                $this->arrowFunctionNode[] = new ArrowFunctionNode($node);
            }

            if ($this->isClosure($node)) {
                $this->closureNode[] = new ClosureNode($node);
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
            // Recursive call.
            $this->parseProperties($node);
        }

        if ($this->isProperty($node)) {
            $this->propertyNodes[] = new PropertyNode($node);
        }

        // Recursive call.
        $this->parseSubNodes($node);
    }

    /**
     * Whether the node is an instance of assign.
     *
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
     *
     * @return bool
     */
    private function isArrowFunction(Node $node): bool
    {
        return (bool) false; // TODO: Not implemented yet.
    }

    /**
     * Whether the node is an instance of closure.
     *
     * @param Node $node
     *
     * @return bool
     */
    private function isClosure(Node $node): bool
    {
        return (bool) ($node instanceof Node\Expr\Closure);
    }

    /**
     * Whether the node is an instance of method.
     *
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
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
     * @param Node $node
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
