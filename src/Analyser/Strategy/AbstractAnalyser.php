<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy;

use PhpParser\Node;
use Mediadevs\StrictlyPHP\Parser\File\AbstractNode;

/**
 * Class AbstractAnalyser
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy\Options\AnalyserOptions
 */
abstract class AbstractAnalyser
{
    /**
     * The node which is subject for analysis.
     *
     * @var AbstractNode
     */
    protected AbstractNode $node;

    /**
     * The type which has been hinted in the functional code.
     *
     * @var string|null
     */
    protected ?string $functionalType;

    /**
     * The type which has been hinted in the docblock.
     *
     * @var string|null
     */
    protected ?string $docblockType;

    /**
     * AbstractAnalyser constructor.
     *
     * @param AbstractNode|\PhpParser\Node $node
     */
    public function __construct($node)
    {
        $this->node = $node;
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
     * Setting the type of the functional code, this type will later be used for analysis.
     *
     * @param string|null $type
     *
     * @return void
     */
    protected function setFunctionalType(?string $type): void
    {
        $this->functionalType = $type;
    }

    /**
     * Setting the type of the docblock, this type will later be used for analysis.
     *
     * @param string|null $type
     *
     * @return void
     */
    protected function setDocblockType(?string $type): void
    {
        $this->docblockType = $type;
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