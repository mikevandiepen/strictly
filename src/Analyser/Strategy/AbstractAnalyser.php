<?php

namespace Mediadevs\Strictly\Analyser\Strategy;

use \PhpParser\Node;
use phpDocumentor\Reflection\DocBlock;
use Mediadevs\Strictly\Issues\IssueInterface;
use Mediadevs\Strictly\Parser\File\AbstractNode;

/**
 * Class AbstractAnalyser
 *
 * @package Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserOptions
 */
abstract class AbstractAnalyser
{
    /**
     * The functional code.
     *
     * @var \PhpParser\Node
     */
    protected Node $functional;

    /**
     * The docblock.
     *
     * @var \phpDocumentor\Reflection\DocBlock
     */
    protected Docblock $docblock;

    /**
     * The node which is subject for analysis.
     *
     * @var AbstractNode
     */
    protected AbstractNode $node;

    /**
     * The type which has been hinted in the functional code.
     *
     * @var array
     */
    protected array $functionalType = [];

    /**
     * The type which has been hinted in the docblock.
     *
     * @var array
     */
    protected array $docblockType = [];

    /**
     * An array with issues from the analysis process.
     *
     * @var IssueInterface[]
     */
    private array $issues = [];

    /**
     * AbstractAnalyser constructor.
     *
     * @param AbstractNode $node
     */
    public function __construct(AbstractNode $node)
    {
        // The node is an instance off AbstractNode.
        $this->node = $node;

        // Collecting the functional code and the docblock from the AbstractNode which has been passed as node.
        $this->functional = $node->getFunctionalCode();
        $this->docblock = $node->getDocblock();

    }

    /**
     * Adding an issue to the list of issues.
     *
     * @param IssueInterface $issue
     *
     * @return void
     */
    protected function addIssue(IssueInterface $issue): void
    {
        $this->issues[] = $issue;
    }

    /**
     * Collecting the issues from the child analyser.
     *
     * @return IssueInterface[]
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

    /**
     * Collecting the name of the current node.
     *
     * @return string
     */
    protected function getName(): string
    {
        return $this->node->getNode()->name->name;
    }

    /**
     * Collecting the line of the current node.
     *
     * @return string
     */
    protected function getLine(): string
    {
        return $this->node->getNode()->getStartLine();
    }

    /**
     * Setting the type(s) of the functional code, the type(s) will later be used for analysis.
     *
     * @param array $type
     *
     * @return void
     */
    protected function setFunctionalType(array $type): void
    {
        $this->functionalType = $type;
    }

    /**
     * Setting the type(s) of the docblock, the type(s) will later be used for analysis.
     *
     * @param array $type
     *
     * @return void
     */
    protected function setDocblockType(array $type): void
    {
        $this->docblockType = $type;
    }

    /**
     * Collecting the missing types from the docblock.
     *
     * @return string[]
     */
    protected function getMissingTypeFromDocblock(): array
    {
        return array_udiff($this->functionalType, $this->docblockType, function($functionalType, $docblockType) {
            if ($functionalType != $docblockType) {
                return $docblockType;
            }

            return [];
        });
    }

    /**
     * Collecting the missing types from the functional code.
     *
     * @return string[]
     */
    protected function getMissingTypeFromNode(): array
    {
        return array_udiff($this->docblockType, $this->functionalType, function($docblockType, $functionalType) {
            if ($docblockType != $functionalType) {
                return $functionalType;
            }

            return [];
        });
    }

    /**
     * Analysing whether the functional type isset.
     * The analysis validates whether the type isset and is not of value NULL.
     *
     * @return bool
     */
    protected function functionalTypeIsset(): bool
    {
        return (bool) (isset($this->functionalType) && $this->functionalType !== null) ? true : false;
    }

    /**
     * Analysing whether the functional type isset.
     * The analysis validates whether the type isset and is not of value NULL.
     *
     * @return bool
     */
    protected function docblockTypeIsset(): bool
    {
        return (bool) (isset($this->functionalType) && $this->functionalType !== null) ? true : false;
    }

    /**
     * Analysing whether the two given types match, the validation will also analyse whether the types are set.
     * If any of the types is of value NULL or not set false will be returned.
     *
     * @return bool
     */
    protected function typesMatch(): bool
    {
        if (!$this->functionalTypeIsset()) return false;
        if (!$this->docblockTypeIsset()) return false;

        if ($this->functionalType === $this->docblockType) {
            return true;
        }

        return false;
    }
}