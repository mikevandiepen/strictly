<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionLike;

use PhpParser\Node;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserInterface;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseDocblockTrait;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseParametersTrait;

/**
 * Class AnalyseParameter.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionLikeNode
 */
final class AnalyseParameter extends AbstractAnalyser implements AnalyserInterface
{
    use AnalyseDocblockTrait;
    use AnalyseParametersTrait;

    /**
     * AnalyseCallable constructor.
     *
     * @param \PhpParser\Node $node
     */
    public function __construct(Node $node)
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