<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits;

use PhpParser\Node;

/**
 * Trait AnalysePropertyTrait.
 *
 * @package Mediadevs\Strictly\Analyser\Options\AnalyserTraits
 */
trait AnalysePropertyTrait
{
    /**
     * Collecting the property type based upon the node.
     *
     * @param \PhpParser\Node $node
     *
     * @return string|null
     */
    protected function getPropertyType(Node $node): ?string
    {
        return $node->getType();
    }
}