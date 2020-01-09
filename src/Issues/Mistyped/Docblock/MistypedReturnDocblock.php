<?php

namespace Mediadevs\Strictly\Issues\Mistyped\Docblock;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\AbstractIssue;
use Mediadevs\Strictly\Issues\Contracts\IssueInterface;
use Mediadevs\Strictly\Issues\Contracts\MistypedInterface;
use Mediadevs\Strictly\Issues\Contracts\DocblockInterface;

/**
 * Class MistypedReturnDocblock.
 *
 * @package Mediadevs\Strictly\Issues\Mistyped\Docblock
 */
final class MistypedReturnDocblock extends AbstractIssue implements IssueInterface, MistypedInterface, DocblockInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'mistyped-return-docblock';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE = 'Incorrect return type in the docblock!';
}