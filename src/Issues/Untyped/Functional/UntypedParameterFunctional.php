<?php

namespace Mediadevs\Strictly\Issues\Untyped\Functional;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\AbstractIssue;
use Mediadevs\Strictly\Issues\Contracts\IssueInterface;
use Mediadevs\Strictly\Issues\Contracts\UntypedInterface;
use Mediadevs\Strictly\Issues\Contracts\FunctionalInterface;

/**
 * Class UntypedParameterFunctional.
 *
 * @package Mediadevs\Strictly\Issues\Untyped\Functional
 */
final class UntypedParameterFunctional extends AbstractIssue implements IssueInterface, UntypedInterface, FunctionalInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'untyped-parameter-functional';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::WARNING;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE = 'Undeclared parameter type in the functional code!';
}