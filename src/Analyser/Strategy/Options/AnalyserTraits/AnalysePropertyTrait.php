<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits;

use PhpParser\Node;

/**
 * Trait AnalysePropertyTrait.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\AnalyserTraits
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