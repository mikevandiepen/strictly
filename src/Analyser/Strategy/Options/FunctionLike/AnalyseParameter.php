<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\FunctionLike;

use Mediadevs\Strictly\Parser\File\AbstractNode;
use Mediadevs\Strictly\Issues\Mistyped\MistypedParameter;
use Mediadevs\Strictly\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\Strictly\Analyser\Strategy\AnalyserInterface;
use Mediadevs\Strictly\Issues\Untyped\Docblock\UntypedParameterDocblock;
use Mediadevs\Strictly\Issues\Untyped\Functional\UntypedParameterFunctional;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits\AnalyseDocblockTrait;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits\AnalyseParametersTrait;

/**
 * Class AnalyseParameter.
 *
 * @package Mediadevs\Strictly\Analyser\Strategy\Options\FunctionNode
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
        // Collecting the functional code and the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();
        $docblock   = $this->node->getDocblock();

        foreach ($functional->getParams() as $functionalParameter) {
            // Binding the functional type.
            $this->setFunctionalType($this->getParameterType($functionalParameter));

            if (!$this->functionalTypeIsset()) {
                $this->addIssue((new UntypedParameterFunctional())
                    ->setName($functional->name)
                    ->setLine($functional->getStartLine())
                    ->setType('temporary_type')
                );
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
        // Collecting the functional code and the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();
        $docblock   = $this->node->getDocblock();

        foreach ($this->getParametersFromDocblock($docblock) as $docblockParameter) {
            // Binding the docblock type.
            $this->setDocblockType($docblockParameter);

            if (!$this->docblockTypeIsset()) {
                $this->addIssue((new UntypedParameterDocblock())
                    ->setName($functional->name)
                    ->setLine($functional->getStartLine())
                    ->setType('temporary_type')
                );
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
            $this->setFunctionalType($this->getParameterType($functionalParameter));
            $this->setDocblockType($this->getParameterTypeFromDocblock($docblock, $functionalParameter->var->name));

            if ($this->functionalTypeIsset() && $this->docblockTypeIsset()) {
                if (!$this->typesMatch()) {
                    $this->addIssue((new MistypedParameter())
                        ->setName($functional->name)
                        ->setLine($functionalParameter->getStartLine())
                        ->setType('temporary_type')
                    );
                }
            } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
                $this->addIssue((new UntypedParameterDocblock())
                    ->setName($functional->name)
                    ->setLine($functionalParameter->getStartLine())
                    ->setType('temporary_type')
                );
            } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
                $this->addIssue((new UntypedParameterFunctional())
                    ->setName($functional->name)
                    ->setLine($functionalParameter->getStartLine())
                    ->setType('temporary_type')
                );
            } else {
                $this->addIssue((new UntypedParameterFunctional())
                    ->setName($functional->name)
                    ->setLine($functionalParameter->getStartLine())
                    ->setType('temporary_type')
                );
                $this->addIssue((new UntypedParameterDocblock())
                    ->setName($functional->name)
                    ->setLine($functionalParameter->getStartLine())
                    ->setType('temporary_type')
                );
            }
        }
    }
}