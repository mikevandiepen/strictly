<?php

namespace Mediadevs\Strictly\Coverage\Format;

/**
 * Interface CoverageInterface.
 *
 * @package Mediadevs\Strictly\Coverage\Format
 */
interface CoverageInterface
{
    /**
     * Generating the coverage based upon the data which has been assigned in the
     * constructor of the main coverage class.
     *
     * @return void
     */
    public function generate(): void;
}
