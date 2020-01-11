<?php

namespace Mediadevs\Strictly\Issues\Mistyped;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\AbstractIssue;
use Mediadevs\Strictly\Issues\Contracts\IssueInterface;
use Mediadevs\Strictly\Issues\Contracts\MistypedInterface;

/**
 * Class MistypedParameter.
 *
 * @package Mediadevs\Strictly\Issues\Mistyped
 */
final class MistypedParameter extends AbstractIssue implements IssueInterface, MistypedInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'mistyped-parameter';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with when the print flag is "abstract". */
    public const ABSTRACT_MESSAGE = 'Incorrect parameter type in either the docblock or functional code';

    /** @var string The message which the user will be prompted with when the print flag is "simple". */
    public const SIMPLE_MESSAGE = 'Parameter "%s" of "%s" has a missing type at line:%d, the parameter type should be "%s"';
}