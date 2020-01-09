<?php

namespace Mediadevs\StrictlyPHP\Issues\Mistyped\Functional;

use Mediadevs\StrictlyPHP\Issues\Severity;
use Mediadevs\StrictlyPHP\Issues\AbstractIssue;
use Mediadevs\StrictlyPHP\Issues\Contracts\IssueInterface;
use Mediadevs\StrictlyPHP\Issues\Contracts\MistypedInterface;
use Mediadevs\StrictlyPHP\Issues\Contracts\FunctionalInterface;

/**
 * Class MistypedParameterFunctional.
 *
 * @package Mediadevs\StrictlyPHP\Issues\Mistyped\Functional
 */
final class MistypedParameterFunctional extends AbstractIssue implements IssueInterface, MistypedInterface, FunctionalInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'mistyped-parameter-functional';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::WARNING;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE = 'Incorrect parameter type in the functional code!';
}