<?php

namespace Mediadevs\Strictly\Issues\Mistyped;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\AbstractIssue;
use Mediadevs\Strictly\Issues\Contracts\IssueInterface;
use Mediadevs\Strictly\Issues\Contracts\MistypedInterface;

/**
 * Class MistypedProperty.
 *
 * @package Mediadevs\Strictly\Issues\Mistyped
 */
final class MistypedProperty extends AbstractIssue implements IssueInterface, MistypedInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'mistyped-property';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE = 'Incorrect property type in the docblock!';
}