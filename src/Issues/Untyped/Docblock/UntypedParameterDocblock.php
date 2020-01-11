<?php

namespace Mediadevs\Strictly\Issues\Untyped\Docblock;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\IssueInterface;

/**
 * Class UntypedParameterDocblock.
 *
 * @package Mediadevs\Strictly\Issues\Untyped\Docblock
 */
final class UntypedParameterDocblock extends AbstractUntypedDocblockIssue implements IssueInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'untyped-parameter';

    /** @var string The location of the issue, either docblock or functional. */
    public const LOCATION = 'docblock';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with when the print flag is "abstract". */
    public const ABSTRACT_MESSAGE = 'Undeclared parameter type';

    /** @var string The message which the user will be prompted with when the print flag is "simple". */
    public const SIMPLE_MESSAGE = 'The docblock of "%s" has an undeclared parameter type at line:%d, the parameter type should be "%s"';
}