<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionLike;

use Mediadevs\StrictlyPHP\Parser\File\AbstractNode;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserInterface;
use Mediadevs\StrictlyPHP\Issues\Untyped\Docblock\UntypedReturnDocblock;
use Mediadevs\StrictlyPHP\Issues\Mistyped\Docblock\MistypedReturnDocblock;
use Mediadevs\StrictlyPHP\Issues\Untyped\Functional\UntypedReturnFunctional;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseReturnTrait;
use Mediadevs\StrictlyPHP\Issues\Mistyped\Functional\MistypedReturnFunctional;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseDocblockTrait;

/**
 * Class AnalyseReturn.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionNode
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
            $this->addIssue(new UntypedReturnFunctional());
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
        $this->setDocblockType($this->getReturnTypeFromDocblock($docblock));

        if (!$this->docblockTypeIsset()) {
            $this->addIssue(new UntypedReturnDocblock());
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
                $this->addIssue(new MistypedReturnFunctional());
                $this->addIssue(new MistypedReturnDocblock());
            }
        } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
            $this->addIssue(new UntypedReturnDocblock());
        } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
            $this->addIssue(new UntypedReturnFunctional());
        } else {
            $this->addIssue(new UntypedReturnFunctional());
            $this->addIssue(new UntypedReturnDocblock());
        }
    }
}