<?php

namespace Mediadevs\Strictly\Issues\Mistyped;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\IssueInterface;

/**
 * Class MistypedParameter.
 *
 * @package Mediadevs\Strictly\Issues\Mistyped
 */
final class MistypedParameter extends AbstractMistypedIssue implements IssueInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'mistyped-parameter';

    /** @var string The location of the issue, either docblock, functional or (docblock-and-functional). */
    public const LOCATION = 'docblock-and-functional';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with when the print flag is "abstract". */
    public const ABSTRACT_MESSAGE = 'Incorrect parameter type';

    /** @var string The message which the user will be prompted with when the print flag is "simple". */
    public const SIMPLE_MESSAGE = 'Parameter "%s" of "%s" has a missing type at line:%d, the parameter type should be "%s"';
}