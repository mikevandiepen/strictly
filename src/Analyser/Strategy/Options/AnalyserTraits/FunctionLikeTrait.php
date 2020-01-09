<?php

namespace Mediadevs\StrictlyPHP\Analyser\Strategy\Options\AnalyserTraits;

/**
 * Trait FunctionLikeTrait.
 *
 * @package Mediadevs\StrictlyPHP\Analyser\Strategy\Options\AnalyserTraits
 */
trait FunctionLikeTrait
{
    /**
     * The more in depth filters which belong to each analyser in the FunctionLike group.
     *
     * @var array
     */
    protected array $filters;

    /**
     * Setting the extra filters for the FunctionLike group.
     *
     * @param array $filters
     *
     * @return void
     */
    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }
}