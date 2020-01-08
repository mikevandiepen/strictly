<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy;

use Mediadevs\StrictlyPHP\Parser\File\PropertyNode;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalyseDocblockTrait;
use Mediadevs\StrictlyPHP\Analyser\Strategy\AnalyserTraits\AnalysePropertyTrait;

/**
 * Class AnalyseProperty.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy
 */
final class AnalyseProperty extends AbstractAnalyser implements AnalyserInterface
{
    use AnalyseDocblockTrait;
    use AnalysePropertyTrait;

    /**
     * AnalyseCallable constructor.
     *
     * @param PropertyNode $property
     */
    public function __construct(PropertyNode $property)
    {
        parent::__construct($property);
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

    }
}