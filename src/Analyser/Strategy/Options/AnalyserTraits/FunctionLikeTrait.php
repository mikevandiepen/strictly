<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits;

/**
 * Trait FunctionLikeTrait.
 *
 * @package Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits
 */
trait FunctionLikeTrait
{
    /**
     * Whether the parameters of the functional code may be analysed.
     *
     * @var bool
     */
    protected bool $parametersFunctional;

    /**
     * Whether the parameters of the docblock may be analysed.
     *
     * @var bool
     */
    protected bool $parametersDocblock;

    /**
     * Whether the return of the functional code may be analysed.
     *
     * @var bool
     */
    protected bool $returnFunctional;

    /**
     * Whether the return of the docblock may be analysed.
     *
     * @var bool
     */
    protected bool $returnDocblock;

    /**
     * Whether the parameters of the functional code may be analysed.
     *
     * @param bool $parametersFunctional
     *
     * @return void
     */
    public function analyseParametersFunctional(bool $parametersFunctional): void
    {
        $this->parametersFunctional = $parametersFunctional;
    }

    /**
     * Whether the return of the docblock may be analysed.
     *
     * @param bool $parametersDocblock
     *
     * @return void
     */
    public function analyseParametersDocblock(bool $parametersDocblock): void
    {
        $this->parametersDocblock = $parametersDocblock;
    }

    /**
     * Whether the return of the functional code may be analysed.
     *
     * @param bool $returnFunctional
     *
     * @return void
     */
    public function analyseReturnFunctional(bool $returnFunctional): void
    {
        $this->returnFunctional = $returnFunctional;
    }

    /**
     * Whether the return of the docblock may be analysed.
     *
     * @param bool $returnDocblock
     *
     * @return void
     */
    public function analyseReturnDocblock(bool $returnDocblock): void
    {
        $this->returnDocblock = $returnDocblock;
    }
}