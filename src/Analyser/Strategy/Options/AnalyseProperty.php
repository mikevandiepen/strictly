<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options;

use Mediadevs\Strictly\Parser\File\PropertyNode;
use Mediadevs\Strictly\Issues\Mistyped\MistypedProperty;
use Mediadevs\Strictly\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\Strictly\Analyser\Strategy\AnalyserInterface;
use Mediadevs\Strictly\Issues\Untyped\Docblock\UntypedPropertyDocblock;
use Mediadevs\Strictly\Issues\Untyped\Functional\UntypedPropertyFunctional;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits\AnalysePropertyTrait;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits\AnalyseDocblockTrait;

/**
 * Class AnalyseProperty.
 *
 * @package Mediadevs\Strictly\Analyser\Strategy\Options
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
        // Binding types from the functional code and the docblock.
        $this->setFunctionalType($this->getPropertyType($this->functional));
        $this->setDocblockType($this->getPropertyTypeFromDocblock($this->docblock));

        if (!$this->functionalTypeIsset()) {
            $this->addIssue((new UntypedPropertyFunctional())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType($this->getMissingTypeFromDocblock())
            );
        }
    }

    /**
     * Analysing only the docblock.
     *
     * @return void
     */
    public function onlyDocblock(): void
    {
        // Binding types from the functional code and the docblock.
        $this->setFunctionalType($this->getPropertyType($this->functional));
        $this->setDocblockType($this->getPropertyTypeFromDocblock($this->docblock));

        if (!$this->docblockTypeIsset()) {
            $this->addIssue((new UntypedPropertyDocblock())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType($this->getMissingTypeFromNode())
            );
        }
    }

    /**
     * Analysing both the functional code as the docblock.
     *
     * @return void
     */
    public function bothFunctionalAndDocblock(): void
    {
        // Binding types from the functional code and the docblock.
        $this->setFunctionalType($this->getPropertyType($this->functional));
        $this->setDocblockType($this->getPropertyTypeFromDocblock($this->docblock));

        if ($this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            if (!$this->typesMatch()) {
                $this->addIssue((new MistypedProperty())
                    ->setName($this->functional->name)
                    ->setLine($this->functional->getStartLine())
                    ->setType($this->getMissingTypeFromNode())
                );
            }
        } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
            $this->addIssue((new UntypedPropertyDocblock())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType($this->getMissingTypeFromNode())
            );
        } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            $this->addIssue((new UntypedPropertyFunctional())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType($this->getMissingTypeFromDocblock())
            );
        } else {
            $this->addIssue((new UntypedPropertyFunctional())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType([]/** TODO: Create an analyser which analyses the usage of the property to assert a type */)
            );
            $this->addIssue((new UntypedPropertyDocblock())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType([]/** TODO: Create an analyser which analyses the usage of the property to assert a type */)
            );
        }
    }
}