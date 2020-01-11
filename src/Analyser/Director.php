<?php

namespace Mediadevs\Strictly\Analyser;

use Mediadevs\Strictly\Parser\File;
use Mediadevs\Strictly\Issues\IssueInterface;
use Mediadevs\Strictly\Parser\File\MethodNode;
use Mediadevs\Strictly\Parser\File\ClosureNode;
use Mediadevs\Strictly\Parser\File\PropertyNode;
use Mediadevs\Strictly\Parser\File\FunctionNode;
use Mediadevs\Strictly\Parser\File\MagicMethodNode;
use Mediadevs\Strictly\Parser\File\ArrowFunctionNode;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyseMethod;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyseClosure;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyseProperty;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyseFunction;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyseMagicMethod;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyseArrowFunction;

/**
 * Class Decorator.
 *
 * @package Mediadevs\Strictly\Analyser\Options\AnalyserHelpers
 */
final class Director
{
    /**
     * The subject file of the analysis.
     *
     * @var \Mediadevs\Strictly\Parser\File
     */
    private File $file;

    /**
     * All the issues in this file.
     *
     * @var IssueInterface[]
     */
    private array $issues = [];

    /**
     * Director constructor.
     *
     * @param \Mediadevs\Strictly\Parser\File $file
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
        // Whether ANY functional code or docblock can be analysed.
        $functional = (bool) in_array('functional', $filters);
        $docblock   = (bool) in_array('docblock', $filters);

        // Whether ANY return or parameter can be analysed.
        $parameter  = (bool) in_array('parameter', $filters);
        $return     = (bool) in_array('return', $filters);

        // Parameter functional or docblock scope.
        $parameterFunctional = (bool) ($functional && $parameter)
            ? (in_array('parameter-functional', $filters))
            : false;
        $parameterDocblock = (bool) ($docblock && $parameter)
            ? (in_array('parameter-docblock', $filters))
            : false;

        // Return functional or docblock scope.
        $returnFunctional = (bool) ($functional && $return)
            ? (in_array('return-functional', $filters))
            : false;
        $returnDocblock = (bool) ($docblock && $return)
            ? (in_array('parameter-docblock', $filters))
            : false;

        if (in_array('arrow-function', $filters)) {
            $arrowFunctionFunctional = (bool) ($functional)
                ? in_array('arrow-function-functional', $filters)
                : false;
            $arrowFunctionDocblock = (bool) ($docblock)
                ? in_array('arrow-function-docblock', $filters)
                : false;

            // Whether arrow-function parameter analysis is enabled.
            $arrowFunctionParameterFunctional = (bool) ($arrowFunctionFunctional && $parameterFunctional)
                ? in_array('arrow-function-parameter-functional', $filters)
                : false;
            $arrowFunctionParameterDocblock = (bool) ($arrowFunctionDocblock && $parameterDocblock)
                ? in_array('arrow-function-parameter-docblock', $filters)
                : false;

            // Whether arrow-function return analysis is enabled.
            $arrowFunctionReturnFunctional = (bool) ($arrowFunctionFunctional && $returnFunctional)
                ? in_array('arrow-function-return-functional', $filters)
                : false;
            $arrowFunctionReturnDocblock = (bool) ($arrowFunctionDocblock && $returnDocblock)
                ? in_array('arrow-function-return-docblock', $filters)
                : false;

            if (isset($this->file->arrowFunctionNode) && count($this->file->arrowFunctionNode) > 0) {
                foreach ($this->file->arrowFunctionNode as $arrowFunctionNode) {
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
        }

        if (in_array('closure', $filters)) {
            $closureFunctional = (bool) ($functional)
                ? in_array('closure-functional', $filters)
                : false;
            $closureDocblock = (bool) ($docblock)
                ? in_array('closure-docblock', $filters)
                : false;

            // Whether closure parameter analysis is enabled.
            $closureParameterFunctional = (bool) ($closureFunctional && $parameterFunctional)
                ? in_array('closure-parameter-functional', $filters)
                : false;
            $closureParameterDocblock = (bool) ($closureDocblock && $parameterDocblock)
                ? in_array('closure-parameter-docblock', $filters)
                : false;

            // Whether closure return analysis is enabled.
            $closureReturnFunctional = (bool) ($closureFunctional && $returnFunctional)
                ? in_array('closure-return-functional', $filters)
                : false;
            $closureReturnDocblock = (bool) ($closureDocblock && $returnDocblock)
                ? in_array('closure-return-docblock', $filters)
                : false;

            if (isset($this->file->closureNode) && count($this->file->closureNode) > 0) {
                foreach ($this->file->closureNode as $closureNode) {
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
        }

        if (in_array('function', $filters)) {
            $functionFunctional = (bool) ($functional)
                ? in_array('function-functional', $filters)
                : false;
            $functionDocblock = (bool) ($docblock)
                ? in_array('function-docblock', $filters)
                : false;

            // Whether function parameter analysis is enabled.
            $functionParameterFunctional = (bool) ($functionFunctional && $parameterFunctional)
                ? in_array('function-parameter-functional', $filters)
                : false;
            $functionParameterDocblock = (bool) ($functionDocblock && $parameterDocblock)
                ? in_array('function-parameter-docblock', $filters)
                : false;

            // Whether function return analysis is enabled.
            $functionReturnFunctional = (bool) ($functionFunctional && $returnFunctional)
                ? in_array('function-return-functional', $filters)
                : false;
            $functionReturnDocblock = (bool) ($functionDocblock && $returnDocblock)
                ? in_array('function-return-docblock', $filters)
                : false;

            if (isset($this->file->functionNode) && count($this->file->functionNode) > 0) {
                foreach ($this->file->functionNode as $functionNode) {
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
        }

        if (in_array('magic-method', $filters)) {
            $magicMethodFunctional = (bool) ($functional)
                ? in_array('magic-method-functional', $filters)
                : false;
            $magicMethodDocblock = (bool) ($docblock)
                ? in_array('magic-method-docblock', $filters)
                : false;

            // Whether magic method parameter analysis is enabled.
            $magicMethodParameterFunctional = (bool) ($magicMethodFunctional && $parameterFunctional)
                ? in_array('magic-method-parameter-functional', $filters)
                : false;
            $magicMethodParameterDocblock = (bool) ($magicMethodDocblock && $parameterDocblock)
                ? in_array('magic-method-parameter-docblock', $filters)
                : false;

            // Whether magic method return analysis is enabled.
            $magicMethodReturnFunctional = (bool) ($magicMethodFunctional && $returnFunctional)
                ? in_array('magic-method-return-functional', $filters)
                : false;
            $magicMethodReturnDocblock = (bool) ($magicMethodDocblock && $returnDocblock)
                ? in_array('magic-method-return-docblock', $filters)
                : false;

            if (isset($this->file->magicMethodNode) && count($this->file->magicMethodNode) > 0) {
                foreach ($this->file->magicMethodNode as $methodNode) {
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
        }

        if (in_array('method', $filters)) {
            $methodFunctional = (bool) ($functional)
                ? in_array('method-functional', $filters)
                : false;
            $methodDocblock = (bool) ($docblock)
                ? in_array('method-docblock', $filters)
                : false;

            // Whether method parameter analysis is enabled.
            $methodParameterFunctional = (bool) ($methodFunctional && $parameterFunctional)
                ? in_array('method-parameter-functional', $filters)
                : false;
            $methodParameterDocblock = (bool) ($methodDocblock && $parameterDocblock)
                ? in_array('method-parameter-docblock', $filters)
                : false;

            // Whether method return analysis is enabled.
            $methodReturnFunctional = (bool) ($methodFunctional && $returnFunctional)
                ? in_array('method-return-functional', $filters)
                : false;
            $methodReturnDocblock = (bool) ($methodDocblock && $returnDocblock)
                ? in_array('method-return-docblock', $filters)
                : false;

            if (isset($this->file->methodNode) && count($this->file->methodNode) > 0) {
                foreach ($this->file->methodNode as $methodNode) {
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
        }

        if (in_array('property', $filters)) {
            $propertyFunctional = (bool) ($functional)
                ? in_array('property-functional', $filters)
                : false;
            $propertyDocblock = (bool) ($docblock)
                ? in_array('property-docblock', $filters)
                : false;

            if (isset($this->file->propertyNodes) && count($this->file->propertyNodes) > 0) {
                foreach ($this->file->propertyNodes as $propertyNode) {
                    $this->analyseProperty(
                        $propertyNode,
                        $propertyFunctional,
                        $propertyDocblock
                    );
                }
            }
        }
    }

    /**
     * Returning all the issues which have been detected in this file analysis process.
     *
     * @return IssueInterface[]
     */
    public function getIssues(): array
    {
        return $this->issues;
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
     * @return void
     */
    private function analyseArrowFunction(
        ArrowFunctionNode $arrowFunctionNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): void
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

        foreach ($analyser->getIssues() as $issue) {
            $this->issues[] = $issue;
        }
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
     * @return void
     */
    private function analyseClosure(
        ClosureNode $closureNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): void
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

        foreach ($analyser->getIssues() as $issue) {
            $this->issues[] = $issue;
        }
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
     * @return void
     */
    private function analyseFunction(
        FunctionNode $functionNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): void
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

        foreach ($analyser->getIssues() as $issue) {
            $this->issues[] = $issue;
        }
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
     * @return void
     */
    private function analyseMagicMethod(
        MagicMethodNode $magicMethodNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): void
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

        foreach ($analyser->getIssues() as $issue) {
            $this->issues[] = $issue;
        }
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
     * @return void
     */
    private function analyseMethod(
        MethodNode $methodNode,
        bool $functional,
        bool $docblock,
        bool $parametersFunctional,
        bool $parametersDocblock,
        bool $returnFunctional,
        bool $returnDocblock
    ): void
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

        foreach ($analyser->getIssues() as $issue) {
            $this->issues[] = $issue;
        }
    }

    /**
     * The analyser for the property node.
     *
     * @param PropertyNode $propertyNode
     * @param bool         $functional
     * @param bool         $docblock
     *
     * @return void
     */
    private function analyseProperty(
        PropertyNode $propertyNode,
        bool $functional,
        bool $docblock
    ): void
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

        foreach ($analyser->getIssues() as $issue) {
            $this->issues[] = $issue;
        }
    }
}
