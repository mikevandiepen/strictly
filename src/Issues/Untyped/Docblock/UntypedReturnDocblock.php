<?php

namespace Mediadevs\Strictly\Issues\Untyped\Docblock;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\AbstractIssue;
use Mediadevs\Strictly\Issues\Contracts\IssueInterface;
use Mediadevs\Strictly\Issues\Contracts\UntypedInterface;
use Mediadevs\Strictly\Issues\Contracts\DocblockInterface;

/**
 * Class UntypedReturnDocblock.
 *
 * @package Mediadevs\Strictly\Issues\Untyped\Docblock
 */
final class UntypedReturnDocblock extends AbstractIssue implements IssueInterface, UntypedInterface, DocblockInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'untyped-return-docblock';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE = 'Undeclared return type in the docblock!';
}