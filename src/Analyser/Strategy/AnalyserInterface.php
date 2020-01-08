<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy;

use PhpParser\Node;

/**
 * Interface AnalyserInterface.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Options\Strategy
 */
interface AnalyserInterface
{
    /**
     * Analysing only the functional code.
     *
     * @return void
     */
    public function onlyFunctional(): void;

    /**
     * Analysing only the docblock.
     *
     * @return void
     */
    public function onlyDocblock(): void;

    /**
     * Analysing both the functional code as the docblock.
     *
     * @return void
     */
    public function bothFunctionalAndDocblock(): void;
}