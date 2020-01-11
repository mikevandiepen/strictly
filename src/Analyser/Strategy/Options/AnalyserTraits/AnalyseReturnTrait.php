<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits;

use \PhpParser\Node;
use PhpParser\Node\Identifier;

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
     * @return null|Identifier|Node\Name|Node\NullableType|Node\UnionType
     */
    protected function getReturnType(Node $node)
    {
        return $node->getReturnType();
    }
}