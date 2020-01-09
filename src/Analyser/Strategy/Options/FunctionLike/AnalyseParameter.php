<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionLike;

use PhpParser\Node;
use Mediadevs\StrictlyPHP\Parser\File\AbstractNode;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserInterface;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseDocblockTrait;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseParametersTrait;

/**
 * Class AnalyseParameter.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionNode
 */
final class AnalyseParameter extends AbstractAnalyser implements AnalyserInterface
{
    use AnalyseDocblockTrait;
    use AnalyseParametersTrait;

    /**
     * AnalyseCallable constructor.
     *
     * @param AbstractNode $node
     */
    public function __construct(AbstractNode $node)
    {
        parent::__construct($node);
    }

    /**
     * Analysing only the functional code.
     *
     * @return void
     */
    public function onlyFunctional(): void
    {
        // Collecting the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();

        foreach ($functional->getParams() as $functionalParameter) {
            // Binding the functional type.
            $this->setFunctionalType($this->getParameterType($functionalParameter->type));

            if (!$this->functionalTypeIsset()) {
                // TODO: (No functional type is not set).
            }
        }
    }

    /**
     * Analysing only the docblock.
     *
     * @return void
     */
    public function onlyDocblock(): void
    {
        // Collecting the docblock from the AbstractNode which has been passed as node.
        $docblock = $this->node->getDocblock();

        foreach ($this->getParametersFromDocblock($docblock) as $docblockParameter) {
            // Binding the docblock type.
            $this->setDocblockType($docblockParameter->type);

            if (!$this->docblockTypeIsset()) {
                // TODO: (No docblock type is not set).
            }
        }
    }

    /**
     * Analysing both the functional code as the docblock.
     *
     * @return void
     */
    public function bothFunctionalAndDocblock(): void
    {
        // Collecting the functional code and the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();
        $docblock = $this->node->getDocblock();

        foreach ($this->getParameters($functional) as $functionalParameter) {
            // Binding types from the functional code and the docblock.
            $this->setFunctionalType($this->getParameterType($functionalParameter->type));
            $this->setDocblockType($this->getParameterTypeFromDocblock($docblock, $functionalParameter->var));

            if ($this->functionalTypeIsset() && $this->docblockTypeIsset()) {
                if ($this->typesMatch()) {
                    // TODO: (Types do not match).
                }
            } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
                // TODO: (No docblock type is not set).
            } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
                // TODO: (No functional type is not set).
            } else {
                // TODO: (No types are set).
            }
        }
    }
}