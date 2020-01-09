<?php

namespace Mediadevs\Strictly\Analyser\Strategy\FunctionLike;

use Mediadevs\Strictly\Parser\File\AbstractNode;
use Mediadevs\Strictly\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\Strictly\Analyser\Strategy\AnalyserInterface;
use Mediadevs\Strictly\Issues\Untyped\Docblock\UntypedReturnDocblock;
use Mediadevs\Strictly\Issues\Mistyped\Docblock\MistypedReturnDocblock;
use Mediadevs\Strictly\Issues\Untyped\Functional\UntypedReturnFunctional;
use Mediadevs\Strictly\Analyser\Strategy\AnalyserTraits\AnalyseReturnTrait;
use Mediadevs\Strictly\Issues\Mistyped\Functional\MistypedReturnFunctional;
use Mediadevs\Strictly\Analyser\Strategy\AnalyserTraits\AnalyseDocblockTrait;

/**
 * Class AnalyseReturn.
 *
 * @package Mediadevs\Strictly\Analyser\Strategy\FunctionNode
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
        // Collecting the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();

        // Binding the functional type.
        $this->setFunctionalType($this->getReturnType($functional));

        if (!$this->functionalTypeIsset()) {
            $this->addIssue((new UntypedReturnFunctional())
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
        $this->setDocblockType($this->getReturnTypeFromDocblock($docblock));

        if (!$this->docblockTypeIsset()) {
            $this->addIssue((new UntypedReturnDocblock())
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
        $this->setFunctionalType($this->getReturnType($functional));
        $this->setDocblockType($this->getReturnTypeFromDocblock($docblock));

        if ($this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            if (!$this->typesMatch()) {
                $this->addIssue((new MistypedReturnFunctional())
                    ->setName($functional->name)
                    ->setLine($functional->getStartLine())
                );
                $this->addIssue((new MistypedReturnDocblock())
                    ->setName($functional->name)
                    ->setLine($functional->getStartLine())
                );
            }
        } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
            $this->addIssue((new UntypedReturnDocblock())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
            );
        } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            $this->addIssue((new UntypedReturnFunctional())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
            );
        } else {
            $this->addIssue((new UntypedReturnFunctional())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
            );
            $this->addIssue((new UntypedReturnDocblock())
                ->setName($functional->name)
                ->setLine($functional->getStartLine())
            );
        }
    }
}