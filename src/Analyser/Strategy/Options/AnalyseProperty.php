<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy;

use Mediadevs\StrictlyPHP\Parser\File\PropertyNode;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseDocblockTrait;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalysePropertyTrait;

/**
 * Class AnalyseProperty.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy
 */
final class AnalyseProperty extends AbstractAnalyser implements AnalyserInterface
{
    use AnalyseDocblockTrait;
    use AnalysePropertyTrait;

    /**
     * AnalyseCallable constructor.
     *
     * @param PropertyNode $property
     */
    public function __construct(PropertyNode $property)
    {
        parent::__construct($property);
    }

    /**
     * Analysing only the functional code.
     *
     * @return void
     */
    public function onlyFunctional(): void
    {
        // Collecting the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();

        // Binding the functional type.
        $this->setFunctionalType($this->getPropertyType($functional));

        if (!$this->functionalTypeIsset()) {
            // TODO: (No functional type is not set).
        }
    }

    /**
     * Analysing only the docblock.
     *
     * @return void
     */
    public function onlyDocblock(): void
    {
        // Collecting the docblock from the AbstractNode which has been passed as node.
        $docblock = $this->node->getDocblock();

        // Binding the docblock type.
        $this->setDocblockType($this->getPropertyTypeFromDocblock($docblock));

        if (!$this->docblockTypeIsset()) {
            // TODO: (No docblock type is not set).
        }
    }

    /**
     * Analysing both the functional code as the docblock.
     *
     * @return void
     */
    public function bothFunctionalAndDocblock(): void
    {
        // Collecting the functional code and the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();
        $docblock   = $this->node->getDocblock();

        // Binding types from the functional code and the docblock.
        $this->setFunctionalType($this->getPropertyType($functional));
        $this->setDocblockType($this->getPropertyTypeFromDocblock($docblock));

        if ($this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            if (!$this->typesMatch()) {
                // TODO: (Types do not match).
            }
        } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
            // TODO: (No docblock type is not set).
        } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            // TODO: (No functional type is not set).
        } else {
            // TODO: (No types are set).
        }
    }
}