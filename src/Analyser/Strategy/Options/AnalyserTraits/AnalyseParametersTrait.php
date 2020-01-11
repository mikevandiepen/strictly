<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits;

use PhpParser\Node;

/**
 * Trait AnalyseParametersTrait.
 *
 * @package Mediadevs\Strictly\Analyser\Options\AnalyserTraits
 */
trait AnalyseParametersTrait
{
    /**
     * Collecting all the parameters from a "function like" node.
     *
     * @param \PhpParser\Node $node
     *
     * @return \PhpParser\Node\Param[]
     */
    protected function getParameters(Node $node): array
    {
        return $node->getParams();
    }

    /**
     * Collecting the parameter type based upon the given node.
     *
     * @param mixed $node
     *
     * @return string|null
     */
    protected function getParameterType(Node\Param $node): ?string
    {
        return $node->getType() ? $node->getType() : null;
    }
}
