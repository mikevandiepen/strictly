<?php

namespace Mediadevs\StrictlyPHP\Analyser;

use Mediadevs\StrictlyPHP\Parser\File;
use Mediadevs\StrictlyPHP\Parser\File\PropertyNode;
use Mediadevs\StrictlyPHP\Parser\File\FunctionNode;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseProperty;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseFunctionLike;

/**
 * Class Decorator.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Options\Helpers
 */
final class Director
{
    /**
     * Asserting based upon the filters what the analysis strategy will be.
     *
     * @param \Mediadevs\StrictlyPHP\Parser\File $file
     * @param array                              $filters
     *
     * @return void
     */
    public function direct(File $file, array $filters): void
    {
        $functional = (bool) isset($filters['functional']);
        $docblock   = (bool) isset($filters['docblock']);

        foreach ($file->functionLikeNodes as $functionLikeNode) {
            if (isset($filters['function-like'])) {
                $functionLikeFunctional = (bool) ($functional) ? isset($filters['function-like-functional']) : false;
                $functionLikeDocblock   = (bool) ($docblock) ? isset($filters['function-like-docblock']) : false;

                $this->analyseFunctionLike($functionLikeNode, $functionLikeFunctional, $functionLikeDocblock);
            }
        }

        foreach ($file->propertyNodes as $propertyNode) {
            if (isset($filters['property'])) {
                $propertyFunctional = (bool) ($functional) ? isset($filters['property-functional']) : false;
                $propertyDocblock   = (bool) ($docblock) ? isset($filters['property-docblock']) : false;

                $this->analyseProperty($propertyNode, $propertyFunctional, $propertyDocblock);
            }
        }
    }

    /**
     * The analyser for the callable node.
     *
     * @param FunctionNode $functionLikeNode
     * @param bool         $functional
     * @param bool         $docblock
     *
     * @return void
     */
    private function analyseFunctionLike(FunctionNode $functionLikeNode, bool $functional, bool $docblock): void
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseFunctionLike($functionLikeNode);

        // Analysing both the functional code and the docblock.
        if ($functional && $docblock) {
            $analyser->bothFunctionalAndDocblock();
        }

        // Analysing only the functional code and not the docblock.
        if ($functional && !$docblock) {
            $analyser->onlyFunctional();
        }

        // Analysing only the docblock and not the functional code.
        if (!$functional && $docblock) {
            $analyser->onlyDocblock();
        }
    }

    /**
     * The analyser for the property node.
     *
     * @param \Mediadevs\StrictlyPHP\Parser\File\PropertyNode $propertyNode
     * @param bool                                            $functional
     * @param bool                                            $docblock
     *
     * @return void
     */
    private function analyseProperty(PropertyNode $propertyNode, bool $functional, bool $docblock): void
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseProperty($propertyNode);

        // Analysing both the functional code and the docblock.
        if ($functional && $docblock) {
            $analyser->bothFunctionalAndDocblock();
        }

        // Analysing only the functional code and not the docblock.
        if ($functional && !$docblock) {
            $analyser->onlyFunctional();
        }

        // Analysing only the docblock and not the functional code.
        if (!$functional && $docblock) {
            $analyser->onlyDocblock();
        }
    }
}
