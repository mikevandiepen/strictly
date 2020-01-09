<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy;

use Mediadevs\StrictlyPHP\Parser\File\ClosureNode;
use Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionLike\AnalyseReturn;
use Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionLike\AnalyseParameter;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseDocblockTrait;
use Mediadevs\StrictlyPHP\Analyser\Strategy\Options\AnalyserTraits\FunctionLikeTrait;

/**
 * Class AnalyseClosure.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy
 */
final class AnalyseClosure extends AbstractAnalyser implements AnalyserInterface
{
    use FunctionLikeTrait;
    use AnalyseDocblockTrait;

    /**
     * AnalyseCallable constructor.
     *
     * @param ClosureNode $functionLike
     */
    public function __construct(ClosureNode $functionLike)
    {
        parent::__construct($functionLike);
    }

    /**
     * Analysing only the functional code.
     *
     * @return void
     */
    public function onlyFunctional(): void
    {
        $analyseParameter   = new AnalyseParameter($this->node);
        $analyseReturn      = new AnalyseReturn($this->node);

        // Analysing parameters.
        if ($this->parametersFunctional) {
            $analyseParameter->onlyFunctional();
        }

        // Analysing return.
        if ($this->returnFunctional) {
            $analyseReturn->onlyFunctional();
        }

        // Handling issue inheritance.
        foreach ($analyseParameter->getIssues() as $parameterIssue) $this->addIssue($parameterIssue);
        foreach ($analyseReturn->getIssues() as $returnIssue)       $this->addIssue($returnIssue);
    }

    /**
     * Analysing only the docblock.
     *
     * @return void
     */
    public function onlyDocblock(): void
    {
        $analyseParameter   = new AnalyseParameter($this->node);
        $analyseReturn      = new AnalyseReturn($this->node);

        // Analysing parameters.
        if ($this->parametersDocblock) {
            $analyseParameter->onlyDocblock();
        }

        // Analysing return.
        if ($this->returnDocblock) {
            $analyseReturn->onlyDocblock();
        }

        // Handling issue inheritance.
        foreach ($analyseParameter->getIssues() as $parameterIssue) $this->addIssue($parameterIssue);
        foreach ($analyseReturn->getIssues() as $returnIssue)       $this->addIssue($returnIssue);
    }

    /**
     * Analysing both the functional code as the docblock.
     *
     * @return void
     */
    public function bothFunctionalAndDocblock(): void
    {
        $analyseParameter   = new AnalyseParameter($this->node);
        $analyseReturn      = new AnalyseReturn($this->node);

        // Analysing parameters.
        if ($this->parametersFunctional && $this->parametersDocblock) {
            $analyseParameter->bothFunctionalAndDocblock();
        }

        if ($this->parametersFunctional && !$this->parametersDocblock) {
            $analyseParameter->onlyFunctional();
        }

        if (!$this->parametersFunctional && $this->parametersDocblock) {
            $analyseParameter->onlyDocblock();
        }

        // Analysing return.
        if ($this->returnFunctional && $this->returnDocblock) {
            $analyseReturn->bothFunctionalAndDocblock();
        }

        if ($this->returnFunctional && !$this->returnDocblock) {
            $analyseReturn->onlyFunctional();
        }

        if (!$this->returnFunctional && $this->returnDocblock) {
            $analyseReturn->onlyDocblock();
        }

        // Handling issue inheritance.
        foreach ($analyseParameter->getIssues() as $parameterIssue) $this->addIssue($parameterIssue);
        foreach ($analyseReturn->getIssues() as $returnIssue)       $this->addIssue($returnIssue);
    }
}