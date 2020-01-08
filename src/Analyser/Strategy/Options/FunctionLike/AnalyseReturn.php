<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionLike;

use PhpParser\Node;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AbstractAnalyser;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserInterface;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseReturnTrait;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseDocblockTrait;

/**
 * Class AnalyseReturn.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy\FunctionLikeNode
 */
final class AnalyseReturn extends AbstractAnalyser implements AnalyserInterface
{
    use AnalyseReturnTrait;
    use AnalyseDocblockTrait;

    /**
     * AnalyseCallable constructor.
     *
     * @param \PhpParser\Node $node
     */
    public function __construct(Node $node)
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

    }

    /**
     * Analysing only the docblock.
     *
     * @return void
     */
    public function onlyDocblock(): void
    {

    }

    /**
     * Analysing both the functional code as the docblock.
     *
     * @return void
     */
    public function bothFunctionalAndDocblock(): void
    {
        $docblock = $this->getDocblockFromNode($this->node);

        $this->setFunctionalType($this->getReturnType($this->node));
        $this->setDocblockType($this->getReturnTypeFromDocblock($docblock));

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