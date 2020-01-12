<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits;

use PhpParser\Node;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserHelpers\AnalyseFunctionalHelper;

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
     * @param Node $node
     *
     * @return string[]
     */
    protected function getReturnType(Node $node): array
    {
        $helper = new AnalyseFunctionalHelper();

        return $helper->getTypeFromNode($node->getReturnType());
    }
}