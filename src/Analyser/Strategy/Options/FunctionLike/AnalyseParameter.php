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
     * The parameters from the functional code.
     *
     * @var array
     */
    private array $functionalParameters = array();

    /**
     * The parameters from the docblock.
     *
     * @var array
     */
    private array $docblockParameters = array();

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
        foreach ($this->getParametersFromNode($this->functional) as $parameter => $type) {
            // Binding types from the functional code and the docblock.
            $this->setFunctionalType($type);
            $this->setDocblockType($this->getParameterTypeFromDocblock($this->docblock, $parameter));

            if (!$this->functionalTypeIsset()) {
                $this->addIssue((new UntypedParameterFunctional())
                    ->setName($this->functional->name)
                    ->setLine($this->functional->getStartLine())
                    ->setType($this->getMissingTypeFromDocblock())
                    ->setParameter('temporary_parameter')
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
        foreach ($this->getParametersFromDocblock($this->docblock) as $parameter => $type) {
            // Binding the docblock type.
            $this->setDocblockType($type);
            $this->setFunctionalType($this->getParameterTypeFromNode($this->functional, $parameter));

            if (!$this->docblockTypeIsset()) {
                $this->addIssue((new UntypedParameterDocblock())
                    ->setName($this->functional->name)
                    ->setLine($this->functional->getStartLine())
                    ->setType($this->getMissingTypeFromNode())
                    ->setParameter('temporary_parameter')
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
        foreach ($this->getParametersFromNode($this->functional) as $parameter => $type) {
            // Binding types from the functional code and the docblock.
            $this->setFunctionalType($type);
            $this->setDocblockType($this->getParameterTypeFromDocblock($this->docblock, $parameter));

            if ($this->functionalTypeIsset() && $this->docblockTypeIsset()) {
                if (!$this->typesMatch()) {
                    $this->addIssue((new MistypedParameter())
                        ->setName($this->functional->name)
                        ->setLine($this->functional->getStartLine())
                        ->setType($this->getMissingTypeFromNode())
                        ->setParameter('temporary_parameter')
                    );
                }
            } elseif ($this->functionalTypeIsset() && !$this->docblockTypeIsset()) {
                $this->addIssue((new UntypedParameterDocblock())
                    ->setName($this->functional->name)
                    ->setLine($this->functional->getStartLine())
                    ->setType($this->getMissingTypeFromNode())
                    ->setParameter('temporary_parameter')
                );
            } elseif (!$this->functionalTypeIsset() && $this->docblockTypeIsset()) {
                $this->addIssue((new UntypedParameterFunctional())
                    ->setName($this->functional->name)
                    ->setLine($this->functional->getStartLine())
                    ->setType($this->getMissingTypeFromDocblock())
                    ->setParameter('temporary_parameter')
                );
            } else {
                $this->addIssue((new UntypedParameterFunctional())
                    ->setName($this->functional->name)
                    ->setLine($this->functional->getStartLine())
                    ->setType([] /** TODO: Create an analyser which analyses the body to assert the parameter type */)
                    ->setParameter('temporary_parameter')
                );
                $this->addIssue((new UntypedParameterDocblock())
                    ->setName($this->functional->name)
                    ->setLine($this->functional->getStartLine())
                    ->setType([] /** TODO: Create an analyser which analyses the body to assert the parameter type */)
                    ->setParameter('temporary_parameter')
                );
            }
        }
    }
}