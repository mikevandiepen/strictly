<?php

namespace Mediadevs\StrictlyPHP\Issues\Mistyped\Functional;

use Mediadevs\StrictlyPHP\Issues\Severity;
use Mediadevs\StrictlyPHP\Issues\FunctionalInterface;
use Mediadevs\StrictlyPHP\Issues\Mistyped\MistypedInterface;

/**
 * Class MistypedPropertyFunctional.
 *
 * @package Mediadevs\StrictlyPHP\Issues\Mistyped\Functional
 */
final class MistypedPropertyFunctional implements MistypedInterface, FunctionalInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'mistyped-property-functional';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::WARNING;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE_COMPACT = 'Incorrect property type in the functional code!';
}