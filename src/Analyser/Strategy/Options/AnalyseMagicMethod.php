<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options;

use Mediadevs\Strictly\Parser\File\MagicMethodNode;
use Mediadevs\Strictly\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\Strictly\Analyser\Strategy\AnalyserInterface;
use Mediadevs\Strictly\Analyser\Strategy\Options\FunctionLike\AnalyseReturn;
use Mediadevs\Strictly\Analyser\Strategy\Options\FunctionLike\AnalyseParameter;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits\FunctionLikeTrait;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits\AnalyseDocblockTrait;

/**
 * Class AnalyseMagicMethod.
 *
 * @package Mediadevs\Strictly\Analyser\Strategy\Options
 */
final class AnalyseMagicMethod extends AbstractAnalyser implements AnalyserInterface
{
    use FunctionLikeTrait;
    use AnalyseDocblockTrait;

    /**
     * AnalyseCallable constructor.
     *
     * @param MagicMethodNode $functionLike
     */
    public function __construct(MagicMethodNode $functionLike)
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