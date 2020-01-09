<?php

namespace Mediadevs\Strictly\Issues\Mistyped\Functional;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\AbstractIssue;
use Mediadevs\Strictly\Issues\Contracts\IssueInterface;
use Mediadevs\Strictly\Issues\Contracts\MistypedInterface;
use Mediadevs\Strictly\Issues\Contracts\FunctionalInterface;

/**
 * Class MistypedPropertyFunctional.
 *
 * @package Mediadevs\Strictly\Issues\Mistyped\Functional
 */
final class MistypedPropertyFunctional extends AbstractIssue implements IssueInterface, MistypedInterface, FunctionalInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'mistyped-property-functional';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::WARNING;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE = 'Incorrect property type in the functional code!';
}