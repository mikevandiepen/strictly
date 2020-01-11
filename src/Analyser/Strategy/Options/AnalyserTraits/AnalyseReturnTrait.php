<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits;

use \PhpParser\Node;

/**
 * Trait AnalyseReturnTrait.
 *
 * @package Mediadevs\Strictly\Analyser\Options\AnalyserTraits
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