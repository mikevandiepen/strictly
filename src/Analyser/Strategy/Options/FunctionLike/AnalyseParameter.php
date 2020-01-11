<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\FunctionLike;

use Mediadevs\Strictly\Parser\File\AbstractNode;
use Mediadevs\Strictly\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\Strictly\Analyser\Strategy\AnalyserInterface;
use Mediadevs\Strictly\Issues\Untyped\Docblock\UntypedParameterDocblock;
use Mediadevs\Strictly\Issues\Mistyped\Docblock\MistypedParameterDocblock;
use Mediadevs\Strictly\Issues\Untyped\Functional\UntypedParameterFunctional;
use Mediadevs\Strictly\Issues\Mistyped\Functional\MistypedParameterFunctional;
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
        // Collecting the docblock from the AbstractNode which has been passed as node.
        $functional = $this->node->getFunctionalCode();

        foreach ($functional->getParams() as $functionalParameter) {
            // Binding the functional type.
            $this->setFunctionalType($this->getParameterType($functionalParameter->type));

            if (!$this->functionalTypeIsset()) {
                $this->addIssue((new UntypedParameterFunctional())
                    ->setName($functional->name)
                    ->setLine($functional->getStartLine())
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
            $this->setDocblockType($docblockParameter->type);

            if (!$this->docblockTypeIsset()) {
                $this->addIssue((new UntypedParameterDocblock())
                    ->setName($functional->name)
                    ->setLine($functional->getStartLine())
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
            $this->setFunctionalType($this->getParameterType($functionalParameter->type));
            $this->setDocblockType($this->getParameterTypeFromDocblock($docblock, $functionalParameter->var));

            if ($this->functionalTypeIsset() && $this->docblockTypeIsset()) {
                if (!$this->typesMatch()) {
                    $this->addIssue((new MistypedParameterFunctional())
                        ->setName($functional->name)
                        ->setLine($functionalParameter->getStartLine())
                    );

                    $this->addIssue((new MistypedParameterDocblock())
                        ->setName($functional->name)
                        ->setLine($functionalParameter->getStartLine())
                    );
                }
            } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
                $this->addIssue((new UntypedParameterDocblock())
                    ->setName($functional->name)
                    ->setLine($functionalParameter->getStartLine())
                );
            } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
                $this->addIssue((new UntypedParameterFunctional())
                    ->setName($functional->name)
                    ->setLine($functionalParameter->getStartLine())
                );
            } else {
                $this->addIssue((new UntypedParameterFunctional())
                    ->setName($functional->name)
                    ->setLine($functionalParameter->getStartLine()));
                $this->addIssue((new UntypedParameterDocblock())
                    ->setName($functional->name)
                    ->setLine($functionalParameter->getStartLine())
                );
            }
        }
    }
}