<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\FunctionLike;

use Mediadevs\Strictly\Parser\File\AbstractNode;
use Mediadevs\Strictly\Issues\Mistyped\MistypedReturn;
use Mediadevs\Strictly\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\Strictly\Analyser\Strategy\AnalyserInterface;
use Mediadevs\Strictly\Issues\Untyped\Docblock\UntypedReturnDocblock;
use Mediadevs\Strictly\Issues\Untyped\Functional\UntypedReturnFunctional;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits\AnalyseReturnTrait;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits\AnalyseDocblockTrait;

/**
 * Class AnalyseReturn.
 *
 * @package Mediadevs\Strictly\Analyser\Strategy\Options\FunctionNode
 */
final class AnalyseReturn extends AbstractAnalyser implements AnalyserInterface
{
    use AnalyseReturnTrait;
    use AnalyseDocblockTrait;

    /**
     * AnalyseCallable constructor.
     *
     * @param AbstractNode $node
     */
    public function __construct(AbstractNode $node)
    {
        parent::__construct($node);
    }

    /**
     * Analysing only the functional code.
     *
     * @return void
     */
    public function onlyFunctional(): void
    {
        // Binding types from the functional code and the docblock.
        $this->setFunctionalType($this->getReturnType($this->functional));
        $this->setDocblockType($this->getReturnTypeFromDocblock($this->docblock));

        if (!$this->functionalTypeIsset()) {
            $this->addIssue((new UntypedReturnFunctional())
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
        $this->setFunctionalType($this->getReturnType($this->functional));
        $this->setDocblockType($this->getReturnTypeFromDocblock($this->docblock));

        if (!$this->docblockTypeIsset()) {
            $this->addIssue((new UntypedReturnDocblock())
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
        $this->setFunctionalType($this->getReturnType($this->functional));
        $this->setDocblockType($this->getReturnTypeFromDocblock($this->docblock));

        if ($this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            if (!$this->typesMatch()) {
                $this->addIssue((new MistypedReturn())
                    ->setName($this->functional->name)
                    ->setLine($this->functional->getStartLine())
                    ->setType($this->getReturnType($this->functional))
                );
            }
        } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
            $this->addIssue((new UntypedReturnDocblock())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType($this->getMissingTypeFromNode())
            );
        } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            $this->addIssue((new UntypedReturnFunctional())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType($this->getMissingTypeFromDocblock())
            );
        } else {
            $this->addIssue((new UntypedReturnFunctional())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType([]/** TODO: Create an analyser which analyses the body to assert the return type */)
            );
            $this->addIssue((new UntypedReturnDocblock())
                ->setName($this->functional->name)
                ->setLine($this->functional->getStartLine())
                ->setType([]/** TODO: Create an analyser which analyses the body to assert the return type */)
            );
        }
    }
}