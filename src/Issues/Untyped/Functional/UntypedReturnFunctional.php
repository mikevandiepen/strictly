<?php

namespace Mediadevs\Strictly\Issues\Untyped\Functional;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\IssueInterface;

/**
 * Class UntypedReturnFunctional.
 *
 * @package Mediadevs\Strictly\Issues\Untyped\Functional
 */
final class UntypedReturnFunctional extends AbstractUntypedFunctionalIssue implements IssueInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'untyped-return';

    /** @var string The location of the issue, either docblock or functional. */
    public const LOCATION = 'functional';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::WARNING;

    /** @var string The message which the user will be prompted with when the print flag is "abstract". */
    public const ABSTRACT_MESSAGE = 'Undeclared return type';

    /** @var string The message which the user will be prompted with when the print flag is "simple". */
    public const SIMPLE_MESSAGE = '"%s" has an undeclared return type at line:%d, the return type should be "%s"';
}