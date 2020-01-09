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
use Mediadevs\StrictlyPHP\Issues\Contracts\IssueInterface;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseClosure;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseProperty;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseFunction;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseMagicMethod;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyseArrowFunction;

/**
 * Class Decorator.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Options\Helpers
 */
final class Director
{
    /**
     * The subject file of the analysis.
     *
     * @var \Mediadevs\StrictlyPHP\Parser\File
     */
    private File $file;

    /**
     * Director constructor.
     *
     * @param \Mediadevs\StrictlyPHP\Parser\File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Asserting based upon the filters what the analysis strategy will be.
     *
     * @param array $filters
     *
     * @return void
     */
    public function direct(array $filters): void
    {
        $functional             = (bool) isset($filters['functional']);
        $docblock               = (bool) isset($filters['docblock']);
        $parameterFunctional    = (bool) ($functional)  ? (isset($filters['parameter-functional'])) : false;
        $parameterDocblock      = (bool) ($docblock)    ? (isset($filters['parameter-docblock']))   : false;
        $returnFunctional       = (bool) ($functional)  ? (isset($filters['return-functional']))    : false;
        $returnDocblock         = (bool) ($docblock)    ? (isset($filters['parameter-docblock']))   : false;

        foreach ($this->file->arrowFunctionNode as $arrowFunctionNode) {
            if (isset($filters['arrow-function'])) {
                $arrowFunctionFunctional = (bool) ($functional) ? isset($filters['arrow-function-functional']) : false;
                $arrowFunctionDocblock   = (bool) ($docblock) ? isset($filters['arrow-function-docblock']) : false;

                // Whether arrow-function parameter analysis is enabled.
                $arrowFunctionParameterFunctional = (bool) ($arrowFunctionFunctional && $parameterFunctional)
                    ? isset($filters['arrow-function-parameter-functional'])
                    : false;
                $arrowFunctionParameterDocblock = (bool) ($arrowFunctionDocblock && $parameterDocblock)
                    ? isset($filters['arrow-function-parameter-docblock'])
                    : false;

                // Whether arrow-function return analysis is enabled.
                $arrowFunctionReturnFunctional = (bool) ($arrowFunctionFunctional && $returnFunctional)
                    ? isset($filters['arrow-function-return-functional'])
                    : false;
                $arrowFunctionReturnDocblock = (bool) ($arrowFunctionDocblock && $returnDocblock)
                    ? isset($filters['arrow-function-return-docblock'])
                    : false;

                $this->analyseArrowFunction(
                    $arrowFunctionNode,
                    $arrowFunctionFunctional,
                    $arrowFunctionDocblock,
                    $arrowFunctionParameterFunctional,
                    $arrowFunctionParameterDocblock,
                    $arrowFunctionReturnFunctional,
                    $arrowFunctionReturnDocblock
                );
            }
        }

        foreach ($this->file->closureNode as $closureNode) {
            if (isset($filters['closure'])) {
                $closureFunctional  = (bool) ($functional) ? isset($filters['closure-functional']) : false;
                $closureDocblock    = (bool) ($docblock) ? isset($filters['closure-docblock']) : false;

                // Whether closure parameter analysis is enabled.
                $closureParameterFunctional = (bool) ($closureFunctional && $parameterFunctional)
                    ? isset($filters['closure-parameter-functional'])
                    : false;
                $closureParameterDocblock = (bool) ($closureDocblock && $parameterDocblock)
                    ? isset($filters['closure-parameter-docblock'])
                    : false;

                // Whether closure return analysis is enabled.
                $closureReturnFunctional = (bool) ($closureFunctional && $returnFunctional)
                    ? isset($filters['closure-return-functional'])
                    : false;
                $closureReturnDocblock = (bool) ($closureDocblock && $returnDocblock)
                    ? isset($filters['closure-return-docblock'])
                    : false;

                $this->analyseClosure(
                    $closureNode,
                    $closureFunctional,
                    $closureDocblock,
                    $closureParameterFunctional,
                    $closureParameterDocblock,
                    $closureReturnFunctional,
                    $closureReturnDocblock
                );
            }
        }

        foreach ($this->file->functionNode as $functionNode) {
            if (isset($filters['function'])) {
                $functionFunctional = (bool) ($functional) ? isset($filters['function-functional']) : false;
                $functionDocblock   = (bool) ($docblock) ? isset($filters['function-docblock']) : false;

                // Whether function parameter analysis is enabled.
                $functionParameterFunctional = (bool) ($functionFunctional && $parameterFunctional)
                    ? isset($filters['function-parameter-functional'])
                    : false;
                $functionParameterDocblock = (bool) ($functionDocblock && $parameterDocblock)
                    ? isset($filters['function-parameter-docblock'])
                    : false;

                // Whether function return analysis is enabled.
                $functionReturnFunctional = (bool) ($functionFunctional && $returnFunctional)
                    ? isset($filters['function-return-functional'])
                    : false;
                $functionReturnDocblock = (bool) ($functionDocblock && $returnDocblock)
                    ? isset($filters['function-return-docblock'])
                    : false;

                $this->analyseFunction(
                    $functionNode,
                    $functionFunctional,
                    $functionDocblock,
                    $functionParameterFunctional,
                    $functionParameterDocblock,
                    $functionReturnFunctional,
                    $functionReturnDocblock
                );
            }
        }

        foreach ($this->file->magicMethodNode as $methodNode) {
            if (isset($filters['magic-method'])) {
                $magicMethodFunctional = (bool) ($functional) ? isset($filters['magic-method-functional']) : false;
                $magicMethodDocblock   = (bool) ($docblock) ? isset($filters['magic-method-docblock']) : false;

                // Whether magic method parameter analysis is enabled.
                $magicMethodParameterFunctional = (bool) ($magicMethodFunctional && $parameterFunctional)
                    ? isset($filters['magic-method-parameter-functional'])
                    : false;
                $magicMethodParameterDocblock = (bool) ($magicMethodDocblock && $parameterDocblock)
                    ? isset($filters['magic-method-parameter-docblock'])
                    : false;

                // Whether magic method return analysis is enabled.
                $magicMethodReturnFunctional = (bool) ($magicMethodFunctional && $returnFunctional)
                    ? isset($filters['magic-method-return-functional'])
                    : false;
                $magicMethodReturnDocblock = (bool) ($magicMethodDocblock && $returnDocblock)
                    ? isset($filters['magic-method-return-docblock'])
                    : false;

                $this->analyseMagicMethod(
                    $methodNode,
                    $magicMethodFunctional,
                    $magicMethodDocblock,
                    $magicMethodParameterFunctional,
                    $magicMethodParameterDocblock,
                    $magicMethodReturnFunctional,
                    $magicMethodReturnDocblock
                );
            }
        }

        foreach ($this->file->methodNode as $methodNode) {
            if (isset($filters['method'])) {
                $methodFunctional   = (bool) ($functional) ? isset($filters['method-functional']) : false;
                $methodDocblock     = (bool) ($docblock) ? isset($filters['method-docblock']) : false;

                // Whether method parameter analysis is enabled.
                $methodParameterFunctional = (bool) ($methodFunctional && $parameterFunctional)
                    ? isset($filters['method-parameter-functional'])
                    : false;
                $methodParameterDocblock = (bool) ($methodDocblock && $parameterDocblock)
                    ? isset($filters['method-parameter-docblock'])
                    : false;

                // Whether method return analysis is enabled.
                $methodReturnFunctional = (bool) ($methodFunctional && $returnFunctional)
                    ? isset($filters['-method-return-functional'])
                    : false;
                $methodReturnDocblock = (bool) ($methodDocblock && $returnDocblock)
                    ? isset($filters['method-return-docblock'])
                    : false;

                $this->analyseMethod(
                    $methodNode,
                    $methodFunctional,
                    $methodDocblock,
                    $methodParameterFunctional,
                    $methodParameterDocblock,
                    $methodReturnFunctional,
                    $methodReturnDocblock
                );
            }
        }

        foreach ($this->file->propertyNodes as $propertyNode) {
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
     * @param bool              $parametersFunctional
     * @param bool              $parametersDocblock
     * @param bool              $returnFunctional
     * @param bool              $returnDocblock
     *
     * @return IssueInterface[]
     */
    private function analyseArrowFunction(
        ArrowFunctionNode $arrowFunctionNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): array
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseArrowFunction($arrowFunctionNode);
        $analyser->analyseParametersFunctional($parametersFunctional);
        $analyser->analyseParametersDocblock($parametersDocblock);
        $analyser->analyseReturnFunctional($returnFunctional);
        $analyser->analyseReturnDocblock($returnDocblock);

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

        return $analyser->getIssues();
    }

    /**
     * The analyser for the closure node.
     *
     * @param ClosureNode $closureNode
     * @param bool        $functional
     * @param bool        $docblock
     * @param bool        $parametersFunctional
     * @param bool        $parametersDocblock
     * @param bool        $returnFunctional
     * @param bool        $returnDocblock
     *
     * @return IssueInterface[]
     */
    private function analyseClosure(
        ClosureNode $closureNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): array
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseClosure($closureNode);
        $analyser->analyseParametersFunctional($parametersFunctional);
        $analyser->analyseParametersDocblock($parametersDocblock);
        $analyser->analyseReturnFunctional($returnFunctional);
        $analyser->analyseReturnDocblock($returnDocblock);

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

        return $analyser->getIssues();
    }

    /**
     * The analyser for the function node.
     *
     * @param FunctionNode $functionNode
     * @param bool         $functional
     * @param bool         $docblock
     * @param bool         $parametersFunctional
     * @param bool         $parametersDocblock
     * @param bool         $returnFunctional
     * @param bool         $returnDocblock
     *
     * @return IssueInterface[]
     */
    private function analyseFunction(
        FunctionNode $functionNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): array
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseFunction($functionNode);
        $analyser->analyseParametersFunctional($parametersFunctional);
        $analyser->analyseParametersDocblock($parametersDocblock);
        $analyser->analyseReturnFunctional($returnFunctional);
        $analyser->analyseReturnDocblock($returnDocblock);

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

        return $analyser->getIssues();
    }

    /**
     * The analyser for the magic node.
     *
     * @param MagicMethodNode $magicMethodNode
     * @param bool            $functional
     * @param bool            $docblock
     * @param bool            $parametersFunctional
     * @param bool            $parametersDocblock
     * @param bool            $returnFunctional
     * @param bool            $returnDocblock
     *
     * @return IssueInterface[]
     */
    private function analyseMagicMethod(
        MagicMethodNode $magicMethodNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): array
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseMagicMethod($magicMethodNode);
        $analyser->analyseParametersFunctional($parametersFunctional);
        $analyser->analyseParametersDocblock($parametersDocblock);
        $analyser->analyseReturnFunctional($returnFunctional);
        $analyser->analyseReturnDocblock($returnDocblock);

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

        return $analyser->getIssues();
    }

    /**
     * The analyser for the method node.
     *
     * @param MethodNode $methodNode
     * @param bool       $functional
     * @param bool       $docblock
     * @param bool       $parametersFunctional
     * @param bool       $parametersDocblock
     * @param bool       $returnFunctional
     * @param bool       $returnDocblock
     *
     * @return IssueInterface[]
     */
    private function analyseMethod(
        MethodNode $methodNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): array
    {
        // The analyser class for this strategy.
        $analyser = new AnalyseMethod($methodNode);
        $analyser->analyseParametersFunctional($parametersFunctional);
        $analyser->analyseParametersDocblock($parametersDocblock);
        $analyser->analyseReturnFunctional($returnFunctional);
        $analyser->analyseReturnDocblock($returnDocblock);

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

        return $analyser->getIssues();
    }

    /**
     * The analyser for the property node.
     *
     * @param PropertyNode $propertyNode
     * @param bool         $functional
     * @param bool         $docblock
     *
     * @return IssueInterface[]
     */
    private function analyseProperty(
        PropertyNode $propertyNode,
        bool $functional,
        bool $docblock
    ): array
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

        return $analyser->getIssues();
    }
}
