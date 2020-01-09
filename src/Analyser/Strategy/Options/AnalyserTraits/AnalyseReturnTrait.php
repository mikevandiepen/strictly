<?php

namespace Mediadevs\Strictly\Analyser\Strategy\AnalyserTraits;

use \PhpParser\Node;

/**
 * Trait AnalyseReturnTrait.
 *
 * @package Mediadevs\Strictly\Analyser\AnalyserTraits
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