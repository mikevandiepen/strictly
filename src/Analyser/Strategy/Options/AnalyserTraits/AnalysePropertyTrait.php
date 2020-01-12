<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits;

use PhpParser\Node;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserHelpers\AnalyseFunctionalHelper;

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
     * @param Node $node
     *
     * @return string[]
     */
    protected function getPropertyType(Node $node): array
    {
        $helper = new AnalyseFunctionalHelper();

        return $helper->getTypeFromNode($node);
    }
}