<?php

namespace Mediadevs\StrictlyPHP\Analyser;

use Mediadevs\StrictlyPHP\Parser\File;
use Mediadevs\StrictlyPHP\Parser\File\MethodNode;
use Mediadevs\StrictlyPHP\Parser\File\ClosureNode;
use Mediadevs\StrictlyPHP\Parser\File\PropertyNode;
use Mediadevs\StrictlyPHP\Parser\File\FunctionNode;
use Mediadevs\StrictlyPHP\Parser\File\MagicMethodNode;
use Mediadevs\StrictlyPHP\Parser\File\ArrowFunctionNode;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseMethod;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseClosure;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseProperty;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseFunction;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseMagicMethod;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseFunctionLike;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseArrowFunction;

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

        foreach ($file->functionNode as $functionNode) {
            if (isset($filters['arrow-function'])) {
                $arrowFunctionFunctional = (bool) ($functional) ? isset($filters['arrow-function-functional']) : false;
                $arrowFunctionDocblock   = (bool) ($docblock) ? isset($filters['arrow-function-docblock']) : false;

                $this->analyseArrowFunction($functionNode, $arrowFunctionFunctional, $arrowFunctionDocblock);
            }
        }

        foreach ($file->closureNode as $closureNode) {
            if (isset($filters['arrow-function'])) {
                $closureFunctional  = (bool) ($functional) ? isset($filters['closure-functional']) : false;
                $closureDocblock    = (bool) ($docblock) ? isset($filters['closure-docblock']) : false;

                $this->analyseArrowFunction($closureNode, $closureFunctional, $closureDocblock);
            }
        }

        foreach ($file->functionNode as $functionNode) {
            if (isset($filters['function'])) {
                $functionFunctional = (bool) ($functional) ? isset($filters['arrow-function-functional']) : false;
                $functionDocblock   = (bool) ($docblock) ? isset($filters['arrow-function-docblock']) : false;

                $this->analyseFunction($functionNode, $functionFunctional, $functionDocblock);
            }
        }

        foreach ($file->magicMethodNode as $methodNode) {
            if (isset($filters['magic-method'])) {
                $magicMethodFunctional = (bool) ($functional) ? isset($filters['magic-method-functional']) : false;
                $magicMethodDocblock   = (bool) ($docblock) ? isset($filters['magic-method-docblock']) : false;

                $this->analyseMagicMethod($methodNode, $magicMethodFunctional, $magicMethodDocblock);
            }
        }

        foreach ($file->methodNode as $methodNode) {
            if (isset($filters['method'])) {
                $methodFunctional   = (bool) ($functional) ? isset($filters['method-functional']) : false;
                $methodDocblock     = (bool) ($docblock) ? isset($filters['method-docblock']) : false;

                $this->analyseMethod($methodNode, $methodFunctional, $methodDocblock);
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
     * The analyser for the arrow function node.
     *
     * @param ArrowFunctionNode $arrowFunctionNode
     * @param bool              $functional
     * @param bool              $docblock
     *
     * @return void
     */
    private function analyseArrowFunction(ArrowFunctionNode $arrowFunctionNode, bool $functional, bool $docblock): void
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseArrowFunction($arrowFunctionNode);

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
     * The analyser for the closure node.
     *
     * @param ClosureNode $closureNode
     * @param bool       $functional
     * @param bool       $docblock
     *
     * @return void
     */
    private function analyseClosure(ClosureNode $closureNode, bool $functional, bool $docblock): void
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseClosure($closureNode);

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
     * The analyser for the function node.
     *
     * @param FunctionNode $functionNode
     * @param bool         $functional
     * @param bool         $docblock
     *
     * @return void
     */
    private function analyseFunction(FunctionNode $functionNode, bool $functional, bool $docblock): void
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseFunction($functionNode);

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
     * The analyser for the magic node.
     *
     * @param MagicMethodNode $magicMethodNode
     * @param bool            $functional
     * @param bool            $docblock
     *
     * @return void
     */
    private function analyseMagicMethod(MagicMethodNode $magicMethodNode, bool $functional, bool $docblock): void
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseMagicMethod($magicMethodNode);

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
     * The analyser for the method node.
     *
     * @param MethodNode $methodNode
     * @param bool       $functional
     * @param bool       $docblock
     *
     * @return void
     */
    private function analyseMethod(MethodNode $methodNode, bool $functional, bool $docblock): void
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseMethod($methodNode);

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
