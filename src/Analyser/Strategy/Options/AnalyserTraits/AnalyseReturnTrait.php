<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits;

use \PhpParser\Node;

/**
 * Trait AnalyseReturnTrait.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\AnalyserTraits
 */
trait AnalyseReturnTrait
{
    /**
     * Collecting all the parameters from a "function like" node.
     *
     * @param \PhpParser\Node $node
     *
     * @return string|null
     */
    protected function getReturnType(Node $node): ?string
    {
        return $node->getReturnType();
    }
}