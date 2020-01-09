<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy;

use Mediadevs\StrictlyPHP\Parser\File\ClosureNode;
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