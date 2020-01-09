<?php

namespace Mediadevs\StrictlyPHP\Issues\Untyped\Functional;

use Mediadevs\StrictlyPHP\Issues\Severity;
use Mediadevs\StrictlyPHP\Issues\FunctionalInterface;
use Mediadevs\StrictlyPHP\Issues\Untyped\UntypedInterface;

/**
 * Class UntypedPropertyFunctional.
 *
 * @package Mediadevs\StrictlyPHP\Issues\Untyped\Functional
 */
final class UntypedPropertyFunctional implements UntypedInterface, FunctionalInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'untyped-property-functional';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::WARNING;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE_COMPACT = 'Undeclared property type in the functional code!';
}