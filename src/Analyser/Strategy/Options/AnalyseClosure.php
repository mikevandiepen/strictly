<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy;

use Mediadevs\StrictlyPHP\Parser\File\FunctionNode;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseDocblockTrait;

/**
 * Class AnalyseClosure.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy
 */
final class AnalyseClosure extends AbstractAnalyser implements AnalyserInterface
{
    use AnalyseDocblockTrait;

    /**
     * AnalyseCallable constructor.
     *
     * @param FunctionNode $functionLike
     */
    public function __construct(FunctionNode $functionLike)
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

    }

    /**
     * Analysing only the docblock.
     *
     * @return void
     */
    public function onlyDocblock(): void
    {

    }

    /**
     * Analysing both the functional code as the docblock.
     *
     * @return void
     */
    public function bothFunctionalAndDocblock(): void
    {

    }
}