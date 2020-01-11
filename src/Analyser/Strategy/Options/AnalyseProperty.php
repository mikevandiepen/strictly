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
        // Collecting the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();

        // Binding the functional type.
        $this->setFunctionalType($this->getPropertyType($functional));

        if (!$this->functionalTypeIsset()) {
            $this->addIssue((new UntypedPropertyFunctional())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
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
        // Collecting the functional code and the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();
        $docblock   = $this->node->getDocblock();

        // Binding the docblock type.
        $this->setDocblockType($this->getPropertyTypeFromDocblock($docblock));

        if (!$this->docblockTypeIsset()) {
            $this->addIssue((new UntypedPropertyDocblock())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
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
        // Collecting the functional code and the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();
        $docblock   = $this->node->getDocblock();

        // Binding types from the functional code and the docblock.
        $this->setFunctionalType($this->getPropertyType($functional));
        $this->setDocblockType($this->getPropertyTypeFromDocblock($docblock));

        if ($this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            if (!$this->typesMatch()) {
                $this->addIssue((new MistypedProperty())
                    ->setName($functional->name)
                    ->setLine($functional->getStartLine())
                );
            }
        } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
            $this->addIssue((new UntypedPropertyDocblock())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
            );
        } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            $this->addIssue((new UntypedPropertyFunctional())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
            );
        } else {
            $this->addIssue((new UntypedPropertyFunctional())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
            );
            $this->addIssue((new UntypedPropertyDocblock())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
            );
        }
    }
}