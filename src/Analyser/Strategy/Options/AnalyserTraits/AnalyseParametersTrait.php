<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits;

use PhpParser\Node;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserHelpers\AnalyseFunctionalHelper;

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
     * @param Node $node
     *
     * @return Node\Param[]
     */
    protected function getParametersFromNode(Node $node): array
    {
        $helper = new AnalyseFunctionalHelper();

        $parameters = [];

        foreach ($node->getParams() as $parameter) {
            $parameters += [$this->getParameterName($parameter) => $helper->getTypeFromNode($parameter)];
        }

        return $parameters;
    }

    protected function getParameterTypeFromNode(Node $node, string $parameter): array
    {
        $helper = new AnalyseFunctionalHelper();

        foreach ($node->getParams() as $nodeParameter) {
            if ($this->getParameterName($nodeParameter) !== $parameter) {
                continue;
            }

            return $helper->getTypeFromNode($nodeParameter);
        }

        return [];
    }

    /**
     * Extracting the name from the node.
     * When the parameter name is not defined this string will be returned.
     *
     * @param Node $parameter
     *
     * @return string
     */
    private function getParameterName(Node $parameter): string
    {
        if (!$parameter->var instanceof Node\Expr\Error) {
            if ($parameter->var instanceof Node\Expr\Variable) {
                return $parameter->var->name;
            }
        }

        return 'undefined';
    }
}
